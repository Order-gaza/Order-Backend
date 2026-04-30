<?php
/**
 * Vendor - Get Order By ID
 * GET: /vendor/orders/get_by_id.php?id=<order_uuid>
 * Headers: Authorization: Bearer <token>
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
    $user = requireRole($conn, [ROLE_VENDOR]);
    
    $vendorStmt = $conn->prepare("SELECT id FROM vendors WHERE user_id = ?");
    $vendorStmt->execute([$user['user_id']]);
    $vendor = $vendorStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$vendor) {
        sendResponse(STATUS_FAIL, null, "Vendor profile not found");
    }
    
    $orderId = isset($_GET['id']) ? trim($_GET['id']) : filterRequest("id");
    if (empty($orderId)) {
        sendResponse(STATUS_FAIL, null, "Order ID is required");
    }
    
    // Verify vendor has access to this order
    $accessCheck = $conn->prepare("SELECT vendor_id FROM order_vendors WHERE order_id = ? AND vendor_id = ?");
    $accessCheck->execute([$orderId, $vendor['id']]);
    if (!$accessCheck->fetch()) {
        sendResponse(STATUS_FAIL, null, "Order not found");
    }
    
    // Get order details — return the VENDOR's own status as 'status'
    // so the UI shows correct action buttons for this vendor,
    // regardless of other vendors' progress on the same order.
    $orderStmt = $conn->prepare("
        SELECT o.id, o.order_number, o.customer_id, o.delivery_address,
            o.delivery_phone, o.subtotal, o.delivery_fee, o.service_fee,
            o.total_amount, o.payment_method, o.payment_status,
            o.customer_notes, o.created_at, o.updated_at,
            ov.status AS status,
            o.status AS order_status,
            ov.subtotal AS vendor_subtotal,
            u.name AS customer_name, u.phone AS customer_phone
        FROM orders o
        JOIN users u ON o.customer_id = u.id
        JOIN order_vendors ov ON ov.order_id = o.id AND ov.vendor_id = ?
        WHERE o.id = ?
    ");
    $orderStmt->execute([$vendor['id'], $orderId]);
    $order = $orderStmt->fetch(PDO::FETCH_ASSOC);
    
    // Get only this vendor's items (join through order_vendors)
    $itemsStmt = $conn->prepare("
        SELECT oi.id, oi.menu_item_id, oi.quantity, oi.unit_price, 
               oi.line_total, oi.special_instructions,
               mi.name AS item_name, mi.image_url
        FROM order_items oi
        JOIN order_vendors ov ON oi.order_vendor_id = ov.id
        JOIN menu_items mi ON oi.menu_item_id = mi.id
        WHERE ov.order_id = ? AND ov.vendor_id = ?
    ");
    $itemsStmt->execute([$orderId, $vendor['id']]);
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

    // Get order vendors (multi-vendor breakdown)
    $vendorsStmt = $conn->prepare("
        SELECT 
            ov.vendor_id, v.name AS vendor_name, v.phone AS vendor_phone,
            ov.subtotal, ov.status, ov.created_at
        FROM order_vendors ov
        LEFT JOIN vendors v ON ov.vendor_id = v.id
        WHERE ov.order_id = ?
    ");
    $vendorsStmt->execute([$orderId]);
    $orderVendors = $vendorsStmt->fetchAll(PDO::FETCH_ASSOC);
    
    sendResponse(STATUS_SUCCESS, [
        'order' => $order,
        'items' => $items,
        'vendors' => $orderVendors,
        'status_history' => $history
    ], "Order retrieved");
    
} catch (PDOException $e) {
    error_log("Vendor get order error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve order");
}
?>
