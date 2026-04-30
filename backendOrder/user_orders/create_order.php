<?php
/**
 * User App - Create Order (Checkout)
 * POST: /user_orders/create_order.php
 * Headers: Authorization: Bearer <token>
 * Body: address_id, payment_method, notes?
 * 
 * Creates order from current cart items. Clears cart after.
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";
include "../core/middleware.php";
include "../core/notification_helper.php";

handleCorsHeaders();

if (!isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    $customer = verifyCustomerAuth($conn);

    $addressId = filterRequest("address_id");
    $paymentMethod = filterRequest("payment_method");
    $notes = filterRequest("notes");

    if (empty($addressId) || empty($paymentMethod)) {
        sendResponse(STATUS_FAIL, null, "Address and payment method are required");
    }

    // Verify address
    $addrStmt = $conn->prepare("SELECT address_line, city, neighborhood, latitude, longitude, phone FROM customer_addresses WHERE id = ? AND user_id = ?");
    $addrStmt->execute([$addressId, $customer['customer_id']]);
    $address = $addrStmt->fetch(PDO::FETCH_ASSOC);
    if (!$address) {
        sendResponse(STATUS_FAIL, null, "Address not found");
    }

    // City-to-zone mapping
    $northCities = ['مدينة غزة'];
    $centralCities = ['مدينة دير البلح', 'مخيم النصيرات', 'مخيم البريج', 'مخيم المغازي'];
    $southCities = ['مدينة خانيونس'];
    $customerCity = trim($address['city'] ?? '');
    if (in_array($customerCity, $centralCities)) { $customerZone = 'central'; }
    elseif (in_array($customerCity, $southCities)) { $customerZone = 'south'; }
    else { $customerZone = 'north'; }

    // Get cart items
    $cartStmt = $conn->prepare("
        SELECT ci.*, mi.name, mi.base_price, mi.vendor_id, mi.is_available
        FROM cart_items ci
        JOIN menu_items mi ON ci.menu_item_id = mi.id
        WHERE ci.user_id = ?
    ");
    $cartStmt->execute([$customer['customer_id']]);
    $cartItems = $cartStmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($cartItems)) {
        sendResponse(STATUS_FAIL, null, "Cart is empty");
    }

    // Check all items still available
    foreach ($cartItems as $ci) {
        if (!$ci['is_available']) {
            sendResponse(STATUS_FAIL, null, "Item '{$ci['name']}' is no longer available");
        }
    }

    // Check all vendors are currently open
    $vendorIds = array_unique(array_column($cartItems, 'vendor_id'));
    $placeholders = implode(',', array_fill(0, count($vendorIds), '?'));
    $vendorCheckStmt = $conn->prepare("
        SELECT id, name, is_open, opening_time, closing_time, delivery_zone,
            CASE
                WHEN is_open = 0 THEN 0
                WHEN opening_time IS NULL OR closing_time IS NULL THEN 1
                WHEN opening_time < closing_time THEN
                    IF(CURTIME() >= opening_time AND CURTIME() < closing_time, 1, 0)
                ELSE
                    IF(CURTIME() >= opening_time OR CURTIME() < closing_time, 1, 0)
            END AS is_currently_open
        FROM vendors WHERE id IN ($placeholders)
    ");
    $vendorCheckStmt->execute($vendorIds);
    $vendorStatuses = $vendorCheckStmt->fetchAll(PDO::FETCH_ASSOC);
    $vendorZoneMap = [];
    foreach ($vendorStatuses as $vs) {
        if (!$vs['is_currently_open']) {
            sendResponse(STATUS_FAIL, null, "Vendor '{$vs['name']}' is currently closed. Please remove their items and try again.");
        }
        $vendorZoneMap[$vs['id']] = $vs['delivery_zone'] ?? 'north';
    }

    // Calculate subtotal and group by vendor
    $subtotal = 0;
    $vendorItems = [];
    foreach ($cartItems as $ci) {
        $subtotal += (float)$ci['base_price'] * (int)$ci['quantity'];
        $vid = $ci['vendor_id'];
        if (!isset($vendorItems[$vid])) {
            $vendorItems[$vid] = [];
        }
        $vendorItems[$vid][] = $ci;
    }
    $vendorCount = count($vendorItems);

    // Calculate zone-based delivery fee (highest fee among all vendor→customer routes)
    $deliveryFee = 0;
    $activeVendorZone = $customerZone;
    $zoneFeeStmt = $conn->prepare("SELECT fee FROM delivery_zone_pricing WHERE from_zone = ? AND to_zone = ?");
    foreach ($vendorZoneMap as $vid => $vZone) {
        $zoneFeeStmt->execute([$vZone, $customerZone]);
        $feeRow = $zoneFeeStmt->fetch(PDO::FETCH_ASSOC);
        $zoneFee = $feeRow ? (float)$feeRow['fee'] : 10.0;
        if ($zoneFee > $deliveryFee) {
            $deliveryFee = $zoneFee;
            $activeVendorZone = $vZone;
        }
    }
    // Multi-vendor surcharge: 5₪ for each additional vendor beyond the first
    $multiVendorSurcharge = max(0, ($vendorCount - 1)) * 5.0;

    // Get fee config for service fee (formerly platform fee)
    $feeStmt = $conn->prepare("SELECT active_model, flat_fee_amount, percentage_value FROM fee_config LIMIT 1");
    $feeStmt->execute();
    $feeConfig = $feeStmt->fetch(PDO::FETCH_ASSOC);
    $feeModel = $feeConfig ? $feeConfig['active_model'] : 'flat_per_order';
    
    $serviceFee = 0;
    if ($feeConfig) {
        if ($feeConfig['active_model'] === 'flat_per_order') {
            $serviceFee = (float)$feeConfig['flat_fee_amount'];
        } else {
            $serviceFee = round($subtotal * (float)$feeConfig['percentage_value'] / 100, 2);
        }
    }
    $platformFee = 0; // Keeping column zeroed out in DB

    $totalAmount = $subtotal + $deliveryFee + $multiVendorSurcharge + $serviceFee;

    $conn->beginTransaction();
    try {
        $orderId = generateUUID();

        // Create order (order_number is auto-generated by DB trigger trg_generate_order_number)
        $orderStmt = $conn->prepare("
            INSERT INTO orders 
            (id, order_number, customer_id, status, subtotal, delivery_fee, multi_vendor_surcharge, service_fee, total_amount, 
             platform_fee, fee_model_used, payment_method, payment_status, 
             delivery_address, delivery_latitude, delivery_longitude, delivery_phone,
             delivery_city, delivery_neighborhood, delivery_zone_from, delivery_zone_to,
             customer_notes, created_at, updated_at)
            VALUES (?, '', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        $orderStmt->execute([
            $orderId, $customer['customer_id'],
            ORDER_PENDING, $subtotal, $deliveryFee, $multiVendorSurcharge, $serviceFee, $totalAmount,
            $platformFee, $feeModel,
            $paymentMethod, PAYMENT_PENDING,
            $address['address_line'], $address['latitude'], $address['longitude'],
            $address['phone'],
            $address['city'] ?? null, $address['neighborhood'] ?? null,
            $activeVendorZone, $customerZone,
            $notes ?: null
        ]);

        // Create order_vendors and order_items entries
        $vendorStmt = $conn->prepare("
            INSERT INTO order_vendors (id, order_id, vendor_id, subtotal, vendor_delivery_fee, vendor_net_amount, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())
        ");
        
        $itemStmt = $conn->prepare("
            INSERT INTO order_items (id, order_vendor_id, menu_item_id, item_name, quantity, unit_price, line_total, special_instructions, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");

        // Pre-fetch vendor commission rates
        $commStmt = $conn->prepare("SELECT id, commission_rate FROM vendors WHERE id = ?");

        $vendorIndex = 0;
        foreach ($vendorItems as $vid => $vItems) {
            $vSubtotal = 0;
            foreach ($vItems as $vci) {
                 $vSubtotal += (float)$vci['base_price'] * (int)$vci['quantity'];
            }
            // Calculate per-vendor delivery fee: spread total evenly, first vendor gets the bulk
            $vDeliveryFee = ($vendorIndex === 0) ? $deliveryFee : 0;
            
            // Get vendor's commission rate and calculate net amount
            $commStmt->execute([$vid]);
            $vendorInfo = $commStmt->fetch(PDO::FETCH_ASSOC);
            $commissionRate = $vendorInfo ? (float)$vendorInfo['commission_rate'] : 12.0;
            $commission = round($vSubtotal * $commissionRate / 100, 2);
            $vendorNetAmount = round($vSubtotal - $commission, 2);
            
            $orderVendorId = generateUUID();
            $vendorStmt->execute([$orderVendorId, $orderId, $vid, $vSubtotal, $vDeliveryFee, $vendorNetAmount]);

            foreach ($vItems as $vci) {
                $lineTotal = (float)$vci['base_price'] * (int)$vci['quantity'];
                $itemStmt->execute([
                    generateUUID(), $orderVendorId, $vci['menu_item_id'], $vci['name'],
                    $vci['quantity'], $vci['base_price'], $lineTotal,
                    $vci['notes']
                ]);
            }
            $vendorIndex++;
        }

        // Clear cart
        $conn->prepare("DELETE FROM cart_items WHERE user_id = ?")->execute([$customer['customer_id']]);

        $conn->commit();

        // Fetch the trigger-generated order_number
        $numStmt = $conn->prepare("SELECT order_number FROM orders WHERE id = ?");
        $numStmt->execute([$orderId]);
        $orderNumber = $numStmt->fetchColumn();

        // ── Send Push Notifications ──
        $notifData = [
            'order_id' => $orderId,
            'order_number' => $orderNumber,
            'total_amount' => strval($totalAmount),
            'status' => ORDER_PENDING
        ];

        // Notify all admins (delivery company)
        notifyAllAdmins($conn,
            'New Order #' . $orderNumber,
            'New order placed — ₪' . number_format($totalAmount, 2),
            'new_order', $notifData);

        // Notify all super admins
        notifyAllSuperAdmins($conn,
            'New Order #' . $orderNumber,
            'New order placed — ₪' . number_format($totalAmount, 2),
            'new_order', $notifData);

        // Notify each vendor involved
        notifyOrderVendors($conn, $orderId,
            'New Order Received',
            'Order #' . $orderNumber . ' — ₪' . number_format($totalAmount, 2),
            'new_order', $notifData);

        sendResponse(STATUS_SUCCESS, [
            'order_id' => $orderId,
            'order_number' => $orderNumber,
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'multi_vendor_surcharge' => $multiVendorSurcharge,
            'service_fee' => $serviceFee,
            'total_amount' => $totalAmount,
            'vendor_count' => $vendorCount,
            'status' => ORDER_PENDING,
            'payment_method' => $paymentMethod
        ], "Order placed successfully");

    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }

} catch (PDOException $e) {
    error_log("Create order error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to place order");
}
?>
