<?php
/**
 * Admin - Get Order By ID
 * GET: /admin/orders/get_by_id.php?id=<uuid>
 * Headers: Authorization: Bearer <token>
 * 
 * Includes financial receipt showing: collect from user, pay to vendor,
 * admin keeps (delivery), hold for SA (service fee + commission).
 */

include "../../config/database.php";
include "../../config/constants.php";
include "../../core/functions.php";
include "../../core/middleware.php";

if (!isRequestMethod('GET') && !isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    $user = requireRole($conn, [ROLE_SUPER_ADMIN, ROLE_ADMIN]);
    
    $orderId = isset($_GET['id']) ? trim($_GET['id']) : filterRequest("id");
    
    if (empty($orderId)) {
        sendResponse(STATUS_FAIL, null, "Order ID is required");
    }
    
    // Fetch order with LEFT JOINs — view_order_detail uses INNER JOINs which
    // silently excludes orders whose vendor records are missing or incomplete.
    $stmt = $conn->prepare("
        SELECT
            o.id AS order_id, o.order_number, o.status AS order_status,
            o.branch_region, o.delivery_address, o.delivery_latitude, o.delivery_longitude,
            o.delivery_city, o.delivery_neighborhood,
            o.delivery_zone_from, o.delivery_zone_to,
            o.subtotal, o.delivery_fee, o.multi_vendor_surcharge, o.service_fee,
            o.total_amount, o.platform_fee, o.fee_model_used,
            o.payment_method, o.payment_status, o.customer_notes,
            o.cancellation_reason, o.confirmed_at, o.delivered_at, o.created_at,
            u.id   AS customer_id,
            u.name AS customer_name,
            u.phone AS customer_phone,
            u.address AS customer_saved_address,
            ov.id  AS order_vendor_id,
            ov.vendor_id,
            ov.subtotal       AS vendor_subtotal,
            ov.vendor_notes,
            ov.vendor_delivery_fee,
            ov.vendor_net_amount,
            ov.status         AS vendor_status,
            ov.accepted_at,
            ov.ready_at,
            v.name   AS vendor_name,
            v.phone  AS vendor_phone,
            v.address AS vendor_address,
            v.type   AS vendor_type
        FROM orders o
        JOIN  users u  ON o.customer_id = u.id
        LEFT JOIN order_vendors ov ON ov.order_id = o.id
        LEFT JOIN vendors v        ON ov.vendor_id = v.id
        WHERE o.id = ?
        LIMIT 1
    ");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) {
        sendResponse(STATUS_FAIL, null, "Order not found");
    }
    
    // Get order items — use snapshot item_name to avoid null if menu item deleted
    $itemsStmt = $conn->prepare("
        SELECT 
            oi.id, oi.menu_item_id,
            COALESCE(oi.item_name, mi.name, 'Unknown Item') AS item_name,
            mi.image_url,
            oi.quantity, oi.unit_price, oi.line_total, oi.special_instructions,
            v.name AS vendor_name, v.id AS vendor_id
        FROM order_items oi
        LEFT JOIN order_vendors ov ON oi.order_vendor_id = ov.id
        LEFT JOIN menu_items mi ON oi.menu_item_id = mi.id
        LEFT JOIN vendors v ON ov.vendor_id = v.id
        WHERE ov.order_id = ?
    ");
    $itemsStmt->execute([$orderId]);
    $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get order vendors (multi-vendor breakdown) with payout info
    $vendorsStmt = $conn->prepare("
        SELECT 
            ov.vendor_id, v.name AS vendor_name, v.phone AS vendor_phone,
            ov.subtotal, ov.vendor_net_amount, ov.vendor_delivery_fee,
            ov.status, ov.created_at,
            v.commission_rate,
            ROUND(ov.subtotal * v.commission_rate / 100, 2) AS commission_amount
        FROM order_vendors ov
        LEFT JOIN vendors v ON ov.vendor_id = v.id
        WHERE ov.order_id = ?
    ");
    $vendorsStmt->execute([$orderId]);
    $vendors = $vendorsStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get status history
    $historyStmt = $conn->prepare("
        SELECT from_status AS old_status, to_status AS new_status, created_at AS changed_at 
        FROM order_status_history 
        WHERE order_id = ? 
        ORDER BY created_at DESC
    ");
    $historyStmt->execute([$orderId]);
    $history = $historyStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Build financial receipt for Admin (delivery company)
    $orderData = $conn->prepare("SELECT subtotal, delivery_fee, multi_vendor_surcharge, service_fee, total_amount FROM orders WHERE id = ?");
    $orderData->execute([$orderId]);
    $od = $orderData->fetch(PDO::FETCH_ASSOC);
    
    $totalAmount = (float)($od['total_amount'] ?? 0);
    $subtotal = (float)($od['subtotal'] ?? 0);
    $deliveryFee = (float)($od['delivery_fee'] ?? 0);
    $multiVendorSurcharge = (float)($od['multi_vendor_surcharge'] ?? 0);
    $serviceFee = (float)($od['service_fee'] ?? 2);
    
    $totalCommission = 0;
    $totalVendorPayout = 0;
    foreach ($vendors as &$v) {
        $v['subtotal'] = (float)$v['subtotal'];
        $v['vendor_net_amount'] = (float)$v['vendor_net_amount'];
        $v['commission_amount'] = (float)$v['commission_amount'];
        $v['commission_rate'] = (float)$v['commission_rate'];
        $totalCommission += $v['commission_amount'];
        $totalVendorPayout += $v['vendor_net_amount'];
    }
    
    $saShare = round($serviceFee + $totalCommission, 2);
    $adminKeeps = round($deliveryFee + $multiVendorSurcharge, 2);
    
    $financialReceipt = [
        'collect_from_user' => $totalAmount,
        'pay_to_vendors' => round($totalVendorPayout, 2),
        'admin_keeps' => $adminKeeps,
        'delivery_fee' => $deliveryFee,
        'multi_vendor_surcharge' => $multiVendorSurcharge,
        'hold_for_sa' => $saShare,
        'service_fee' => $serviceFee,
        'total_commission' => round($totalCommission, 2),
        'remaining_with_admin' => round($adminKeeps + $saShare, 2)
    ];
    
    sendResponse(STATUS_SUCCESS, [
        'order' => $order,
        'items' => $items,
        'vendors' => $vendors,
        'status_history' => $history,
        'financial_receipt' => $financialReceipt
    ], "Order retrieved successfully");
    
} catch (PDOException $e) {
    error_log("Admin get order error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve order");
}
?>
