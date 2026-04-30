<?php
/**
 * Admin - Get Order Status History
 * GET: /admin/orders/get_status_history.php?order_id=<uuid>
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
    $user = requireRole($conn, [ROLE_SUPER_ADMIN, ROLE_ADMIN]);
    
    $orderId = isset($_GET['order_id']) ? trim($_GET['order_id']) : filterRequest("order_id");
    
    if (empty($orderId)) {
        sendResponse(STATUS_FAIL, null, "Order ID is required");
    }
    
    // Verify order exists
    $orderStmt = $conn->prepare("SELECT id, order_number, status FROM orders WHERE id = ?");
    $orderStmt->execute([$orderId]);
    $order = $orderStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) {
        sendResponse(STATUS_FAIL, null, "Order not found");
    }
    
    $stmt = $conn->prepare("
        SELECT from_status AS old_status, to_status AS new_status, created_at AS changed_at 
        FROM order_status_history 
        WHERE order_id = ? 
        ORDER BY created_at ASC
    ");
    $stmt->execute([$orderId]);
    $history = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    sendResponse(STATUS_SUCCESS, [
        'order_number' => $order['order_number'],
        'current_status' => $order['status'],
        'history' => $history
    ], "Status history retrieved");
    
} catch (PDOException $e) {
    error_log("Order status history error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve status history");
}
?>
