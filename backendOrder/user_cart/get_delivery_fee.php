<?php
/**
 * User App - Get Delivery Fee (Zone-Based)
 * GET: /user_cart/get_delivery_fee.php?address_id=<uuid>
 * Headers: Authorization: Bearer <token>
 * 
 * Calculates delivery fee based on the zone of the customer's address
 * and the zone(s) of vendor(s) in the cart.
 * 
 * Zones: north (Gaza City), central (Deir al-Balah / Nuseirat / Bureij / Maghazi),
 *        south (Khan Younis)
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";
include "../core/middleware.php";

handleCorsHeaders();

if (!isRequestMethod('GET') && !isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

// City-to-zone mapping
function getZoneForCity($city) {
    $northCities = ['مدينة غزة'];
    $centralCities = ['مدينة دير البلح', 'مخيم النصيرات', 'مخيم البريج', 'مخيم المغازي'];
    $southCities = ['مدينة خانيونس'];

    $city = trim($city);
    if (in_array($city, $northCities)) return 'north';
    if (in_array($city, $centralCities)) return 'central';
    if (in_array($city, $southCities)) return 'south';
    return 'north'; // fallback
}

try {
    $customer = verifyCustomerAuth($conn);

    $addressId = isset($_GET['address_id']) ? trim($_GET['address_id']) : filterRequest("address_id");
    if (empty($addressId)) {
        sendResponse(STATUS_FAIL, null, "Address ID is required");
    }

    // Get customer address with city
    $addrStmt = $conn->prepare("SELECT id, city, latitude, longitude FROM customer_addresses WHERE id = ? AND user_id = ?");
    $addrStmt->execute([$addressId, $customer['customer_id']]);
    $address = $addrStmt->fetch(PDO::FETCH_ASSOC);

    if (!$address) {
        sendResponse(STATUS_FAIL, null, "Address not found");
    }

    // Determine customer's zone
    $customerZone = getZoneForCity($address['city'] ?? '');

    // Get distinct vendor zones from cart
    $vendorStmt = $conn->prepare("
        SELECT DISTINCT v.delivery_zone 
        FROM cart_items ci 
        JOIN menu_items mi ON ci.menu_item_id = mi.id 
        JOIN vendors v ON mi.vendor_id = v.id
        WHERE ci.user_id = ?
    ");
    $vendorStmt->execute([$customer['customer_id']]);
    $vendorZones = $vendorStmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($vendorZones)) {
        $vendorZones = [$customerZone]; // fallback if empty cart
    }

    // Count distinct vendors in cart for multi-vendor surcharge
    $vendorCountStmt = $conn->prepare("
        SELECT COUNT(DISTINCT mi.vendor_id) as vendor_count
        FROM cart_items ci
        JOIN menu_items mi ON ci.menu_item_id = mi.id
        WHERE ci.user_id = ?
    ");
    $vendorCountStmt->execute([$customer['customer_id']]);
    $vendorCount = (int)$vendorCountStmt->fetch(PDO::FETCH_ASSOC)['vendor_count'];
    if ($vendorCount < 1) $vendorCount = 1;

    // Look up highest fee among all vendor→customer routes
    $maxFee = 0;
    $activeVendorZone = $vendorZones[0];
    
    foreach ($vendorZones as $vZone) {
        $feeStmt = $conn->prepare("SELECT fee FROM delivery_zone_pricing WHERE from_zone = ? AND to_zone = ?");
        $feeStmt->execute([$vZone, $customerZone]);
        $feeRow = $feeStmt->fetch(PDO::FETCH_ASSOC);
        $fee = $feeRow ? (float)$feeRow['fee'] : 10.0;
        
        if ($fee > $maxFee) {
            $maxFee = $fee;
            $activeVendorZone = $vZone;
        }
    }

    $deliveryFee = $maxFee;
    // Get subtotal for percentage calculation if needed
    $subStmt = $conn->prepare("
        SELECT SUM(ci.quantity * mi.base_price) as subtotal
        FROM cart_items ci
        JOIN menu_items mi ON ci.menu_item_id = mi.id
        WHERE ci.user_id = ?
    ");
    $subStmt->execute([$customer['customer_id']]);
    $subtotal = (float)$subStmt->fetchColumn();

    // Get fee config
    $feeStmt = $conn->prepare("SELECT active_model, flat_fee_amount, percentage_value FROM fee_config LIMIT 1");
    $feeStmt->execute();
    $feeConfig = $feeStmt->fetch(PDO::FETCH_ASSOC);
    
    $serviceFee = 0;
    if ($feeConfig) {
        if ($feeConfig['active_model'] === 'flat_per_order') {
            $serviceFee = (float)$feeConfig['flat_fee_amount'];
        } else {
            $serviceFee = round($subtotal * (float)$feeConfig['percentage_value'] / 100, 2);
        }
    }

    // Multi-vendor surcharge: 5₪ for each additional vendor beyond the first
    $multiVendorSurcharge = max(0, ($vendorCount - 1)) * 5.0;

    sendResponse(STATUS_SUCCESS, [
        'delivery_fee' => $deliveryFee,
        'multi_vendor_surcharge' => $multiVendorSurcharge,
        'service_fee' => $serviceFee,
        'vendor_count' => $vendorCount,
        'zone_from' => $activeVendorZone,
        'zone_to' => $customerZone,
        'address_id' => $addressId
    ], "Delivery fee calculated");

} catch (PDOException $e) {
    error_log("Get delivery fee error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to calculate delivery fee");
}
?>

