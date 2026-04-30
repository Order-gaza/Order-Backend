<?php
/**
 * Vendor - Get Recent Orders
 * GET: /vendor/dashboard/get_recent_orders.php?limit=10
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
    
    $limit = isset($_GET['limit']) ? max(1, min(50, intval($_GET['limit']))) : 10;
    
    $stmt = $conn->prepare("
        SELECT 
            o.id AS order_id, o.order_number, o.status AS order_status,
            o.total_amount, o.created_at AS order_created_at,
            ov.subtotal AS vendor_subtotal, ov.status AS vendor_status,
            u.name AS customer_name, u.phone AS customer_phone
        FROM order_vendors ov
        JOIN orders o ON ov.order_id = o.id
        JOIN users u ON o.customer_id = u.id
        WHERE ov.vendor_id = ?
        ORDER BY o.created_at DESC
        LIMIT ?
    ");
    $stmt->execute([$vendor['id'], $limit]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    sendResponse(STATUS_SUCCESS, [
        'orders' => $orders,
        'total' => count($orders)
    ], "Recent orders retrieved");
    
} catch (PDOException $e) {
    error_log("Vendor recent orders error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve recent orders");
}
?>
