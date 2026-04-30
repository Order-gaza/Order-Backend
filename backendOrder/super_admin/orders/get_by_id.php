<?php
/**
 * Super Admin - Get Order By ID
 * GET: /super_admin/orders/get_by_id.php?id=<uuid>
 * Headers: Authorization: Bearer <token>
 * 
 * Uses view_order_detail DB view for full order details + revenue breakdown.
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
    $user = requireRole($conn, [ROLE_SUPER_ADMIN]);
    
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
    
    // Get order items — use snapshot item_name; join vendor via order_vendor
    $itemsStmt = $conn->prepare("
        SELECT
            oi.id,
            oi.menu_item_id,
            COALESCE(oi.item_name, mi.name, 'Unknown Item') AS item_name,
            mi.image_url,
            oi.quantity,
            oi.unit_price,
            oi.line_total,
            oi.special_instructions,
            v.name AS vendor_name
        FROM order_items oi
        LEFT JOIN order_vendors ov ON oi.order_vendor_id = ov.id
        LEFT JOIN menu_items mi   ON oi.menu_item_id    = mi.id
        LEFT JOIN vendors v       ON ov.vendor_id       = v.id
        WHERE ov.order_id = ?
    ");
    $itemsStmt->execute([$orderId]);
    $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get status history
    $historyStmt = $conn->prepare("
        SELECT from_status AS old_status, to_status AS new_status, created_at AS changed_at 
        FROM order_status_history 
        WHERE order_id = ? 
        ORDER BY created_at DESC
    ");
    $historyStmt->execute([$orderId]);
    $history = $historyStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get order vendors (multi-vendor breakdown) with commission info
    $vendorsStmt = $conn->prepare("
        SELECT 
            ov.vendor_id, v.name AS vendor_name, v.phone AS vendor_phone,
            ov.subtotal, ov.status, ov.created_at,
            ov.vendor_net_amount,
            ov.vendor_delivery_fee,
            v.commission_rate,
            ROUND(ov.subtotal * v.commission_rate / 100, 2) AS commission_amount
        FROM order_vendors ov
        LEFT JOIN vendors v ON ov.vendor_id = v.id
        WHERE ov.order_id = ?
    ");
    $vendorsStmt->execute([$orderId]);
    $vendors = $vendorsStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Build SA revenue breakdown for this order
    $orderData = $conn->prepare("SELECT subtotal, delivery_fee, service_fee, total_amount FROM orders WHERE id = ?");
    $orderData->execute([$orderId]);
    $od = $orderData->fetch(PDO::FETCH_ASSOC);
    
    $totalCommission = 0;
    foreach ($vendors as &$v) {
        $v['subtotal'] = (float)$v['subtotal'];
        $v['vendor_net_amount'] = (float)$v['vendor_net_amount'];
        $v['vendor_delivery_fee'] = (float)$v['vendor_delivery_fee'];
        $v['commission_rate'] = (float)$v['commission_rate'];
        $v['commission_amount'] = (float)$v['commission_amount'];
        $totalCommission += $v['commission_amount'];
    }
    
    $serviceFee = (float)($od['service_fee'] ?? 2);
    $deliveryFee = (float)($od['delivery_fee'] ?? 0);
    $totalAmount = (float)($od['total_amount'] ?? 0);
    $subtotal = (float)($od['subtotal'] ?? 0);
    $totalVendorPayout = round($subtotal - $totalCommission, 2);
    
    $revenueBreakdown = [
        'total_amount' => $totalAmount,
        'subtotal' => $subtotal,
        'delivery_fee' => $deliveryFee,
        'service_fee' => $serviceFee,
        'total_commission' => round($totalCommission, 2),
        'sa_earnings' => round($serviceFee + $totalCommission, 2),
        'admin_earnings' => $deliveryFee,
        'total_vendor_payout' => $totalVendorPayout,
        'user_pays' => $totalAmount,
        'admin_collects' => $totalAmount,
        'admin_pays_vendors' => $totalVendorPayout,
        'admin_keeps' => $deliveryFee,
        'admin_holds_for_sa' => round($serviceFee + $totalCommission, 2)
    ];
    
    sendResponse(STATUS_SUCCESS, [
        'order' => $order,
        'items' => $items,
        'vendors' => $vendors,
        'status_history' => $history,
        'revenue_breakdown' => $revenueBreakdown
    ], "Order retrieved successfully");
    
} catch (PDOException $e) {
    error_log("Get order error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve order");
}
?>
