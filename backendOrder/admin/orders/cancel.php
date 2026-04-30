<?php
/**
 * Admin - Cancel Order
 * POST: /admin/orders/cancel.php
 * Headers: Authorization: Bearer <token>
 * Body: order_id, reason? (optional)
 * 
 * Admin can cancel orders that haven't been delivered yet.
 */

include "../../config/database.php";
include "../../config/constants.php";
include "../../core/functions.php";
include "../../core/middleware.php";
include "../../core/notification_helper.php";

if (!isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    $user = requireRole($conn, [ROLE_SUPER_ADMIN, ROLE_ADMIN]);
    
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
        // Update order status (triggers trg_log_order_status_change)
        $conn->prepare("UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?")
             ->execute([ORDER_CANCELLED, $orderId]);
        
        // Cancel all order_vendors entries
        $conn->prepare("UPDATE order_vendors SET status = ? WHERE order_id = ?")
             ->execute([ORDER_CANCELLED, $orderId]);
        
        $conn->commit();
        
        $description = "Cancelled order {$order['order_number']}";
        if (!empty($reason)) $description .= " - Reason: $reason";
        
        logActivity($conn, $user['user_id'], 'cancel', 'order', $orderId, $description);
        
        // ── Send Push Notifications ──
        $reasonText = !empty($reason) ? " Reason: $reason" : '';
        $notifData = [
            'order_id' => $orderId,
            'order_number' => $order['order_number'],
            'status' => ORDER_CANCELLED,
            'reason' => $reason ?: ''
        ];

        // Notify customer
        $customerId = getOrderCustomerId($conn, $orderId);
        if ($customerId) {
            notifyUser($conn, $customerId,
                'Order Cancelled',
                'Your order #' . $order['order_number'] . ' has been cancelled.' . $reasonText,
                'order_status', $notifData);
        }

        // Notify vendors
        notifyOrderVendors($conn, $orderId,
            'Order Cancelled #' . $order['order_number'],
            'This order has been cancelled.' . $reasonText,
            'order_status', $notifData);

        // Notify super admins
        notifyAllSuperAdmins($conn,
            'Order Cancelled #' . $order['order_number'],
            'Cancelled by admin.' . $reasonText,
            'order_status', $notifData);

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
    error_log("Admin cancel order error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to cancel order");
}
?>
