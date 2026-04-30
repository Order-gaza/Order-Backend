<?php
/**
 * Vendor - Get Pending Orders (Vendor's Work Queue)
 * GET: /vendor/orders/get_pending.php
 * Headers: Authorization: Bearer <token>
 * 
 * Returns orders with status 'sent_to_vendor' — waiting for vendor acceptance.
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
    
    $stmt = $conn->prepare("
        SELECT o.id, o.order_number, ov.status AS status,
            o.status AS order_status,
            o.total_amount, o.created_at,
            ov.subtotal AS vendor_subtotal,
            u.name AS customer_name, u.phone AS customer_phone,
            o.customer_notes
        FROM order_vendors ov
        JOIN orders o ON ov.order_id = o.id
        JOIN users u ON o.customer_id = u.id
        WHERE ov.vendor_id = ? AND ov.status = ?
        ORDER BY o.created_at ASC
    ");
    $stmt->execute([$vendor['id'], ORDER_SENT_TO_VENDOR]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    sendResponse(STATUS_SUCCESS, [
        'orders' => $orders,
        'total' => count($orders)
    ], "Pending orders retrieved");
    
} catch (PDOException $e) {
    error_log("Vendor pending orders error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve pending orders");
}
?>
