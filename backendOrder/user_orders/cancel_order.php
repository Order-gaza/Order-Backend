<?php
/**
 * User App - Cancel Order
 * POST: /user_orders/cancel_order.php
 * Headers: Authorization: Bearer <token>
 * Body: order_id, reason?
 * 
 * Only allowed when status is 'pending'
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

    $orderId = filterRequest("order_id");
    $reason = filterRequest("reason");

    if (empty($orderId)) {
        sendResponse(STATUS_FAIL, null, "Order ID is required");
    }

    $orderStmt = $conn->prepare("SELECT id, status FROM orders WHERE id = ? AND customer_id = ?");
    $orderStmt->execute([$orderId, $customer['customer_id']]);
    $order = $orderStmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        sendResponse(STATUS_FAIL, null, "Order not found");
    }

    // Customers can only cancel pending orders
    if ($order['status'] !== ORDER_PENDING) {
        sendResponse(STATUS_FAIL, null, "Order can only be cancelled while in 'pending' status. Current: " . $order['status']);
    }

    $conn->beginTransaction();
    try {
        $conn->prepare("UPDATE orders SET status = ?, cancellation_reason = ?, updated_at = NOW() WHERE id = ?")
             ->execute([ORDER_CANCELLED, $reason ?: 'Cancelled by customer', $orderId]);

        $conn->commit();

        // Get order number for notifications
        $numStmt = $conn->prepare("SELECT order_number FROM orders WHERE id = ?");
        $numStmt->execute([$orderId]);
        $orderNumber = $numStmt->fetchColumn();

        // ── Send Push Notifications ──
        $notifData = [
            'order_id' => $orderId,
            'order_number' => $orderNumber,
            'status' => ORDER_CANCELLED,
            'reason' => $reason ?: 'Cancelled by customer'
        ];

        // Notify admins
        notifyAllAdmins($conn,
            'Customer Cancelled #' . $orderNumber,
            'Customer cancelled order #' . $orderNumber,
            'order_status', $notifData);

        // Notify vendors
        notifyOrderVendors($conn, $orderId,
            'Order Cancelled #' . $orderNumber,
            'Customer cancelled this order.',
            'order_status', $notifData);

        sendResponse(STATUS_SUCCESS, ['order_id' => $orderId, 'status' => ORDER_CANCELLED], "Order cancelled");

    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }

} catch (PDOException $e) {
    error_log("Cancel order error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to cancel order");
}
?>
