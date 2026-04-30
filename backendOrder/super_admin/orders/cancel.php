<?php
/**
 * Super Admin - Cancel Order
 * POST: /super_admin/orders/cancel.php
 * Headers: Authorization: Bearer <token>
 * Body: order_id, reason? (optional)
 */

include "../../config/database.php";
include "../../config/constants.php";
include "../../core/functions.php";
include "../../core/middleware.php";

if (!isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    $user = requireRole($conn, [ROLE_SUPER_ADMIN]);
    
    $orderId = filterRequest("order_id");
    $reason = filterRequest("reason");
    
    if (empty($orderId)) {
        sendResponse(STATUS_FAIL, null, "Order ID is required");
    }
    
    $stmt = $conn->prepare("SELECT id, order_number, status FROM orders WHERE id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) {
        sendResponse(STATUS_FAIL, null, "Order not found");
    }
    
    if ($order['status'] === ORDER_CANCELLED) {
        sendResponse(STATUS_FAIL, null, "Order is already cancelled");
    }
    
    if ($order['status'] === ORDER_DELIVERED) {
        sendResponse(STATUS_FAIL, null, "Cannot cancel a delivered order");
    }
    
    $conn->beginTransaction();
    
    try {
        // Update order status (triggers trg_log_order_status_change automatically)
        $updateStmt = $conn->prepare("UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?");
        $updateStmt->execute([ORDER_CANCELLED, $orderId]);
        
        // Also update any order_vendors entries
        $conn->prepare("UPDATE order_vendors SET status = ? WHERE order_id = ?")
             ->execute([ORDER_CANCELLED, $orderId]);
        
        $conn->commit();
        
        $description = "Cancelled order: {$order['order_number']}";
        if (!empty($reason)) $description .= " - Reason: $reason";
        
        logActivity($conn, $user['user_id'], 'cancel', 'order', $orderId, $description);
        
        sendResponse(STATUS_SUCCESS, [
            'order_id' => $orderId,
            'order_number' => $order['order_number'],
            'status' => ORDER_CANCELLED
        ], "Order cancelled successfully");
        
    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }
    
} catch (PDOException $e) {
    error_log("Cancel order error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to cancel order");
}
?>
