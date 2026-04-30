<?php
/**
 * Admin - Mark Order as Delivered
 * POST: /admin/orders/deliver.php
 * Headers: Authorization: Bearer <token>
 * Body: order_id
 * 
 * Marks order as delivered. Triggers trg_create_earning_on_delivery
 * which auto-creates platform_earnings records.
 * Workflow: out_for_delivery → delivered
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
    
    if (empty($orderId)) {
        sendResponse(STATUS_FAIL, null, "Order ID is required");
    }
    
    $stmt = $conn->prepare("SELECT id, order_number, status FROM orders WHERE id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) {
        sendResponse(STATUS_FAIL, null, "Order not found");
    }
    
    if ($order['status'] !== ORDER_OUT_FOR_DELIVERY) {
        sendResponse(STATUS_FAIL, null, "Order can only be delivered from 'out_for_delivery' status. Current: " . $order['status']);
    }
    
    $conn->beginTransaction();
    
    try {
        // Update order status — triggers:
        // 1. trg_log_order_status_change (logs to order_status_history)
        // 2. trg_create_earning_on_delivery (creates platform_earnings records)
        $conn->prepare("UPDATE orders SET status = ?, delivered_at = NOW(), updated_at = NOW() WHERE id = ?")
             ->execute([ORDER_DELIVERED, $orderId]);
        
        // Update order_vendors
        $conn->prepare("UPDATE order_vendors SET status = ? WHERE order_id = ?")
             ->execute([ORDER_DELIVERED, $orderId]);
        
        // Update payment status to collected (cash on delivery)
        $conn->prepare("UPDATE orders SET payment_status = 'collected', updated_at = NOW() WHERE id = ? AND payment_method = 'cash_on_delivery'")
             ->execute([$orderId]);
        
        $conn->commit();
        
        logActivity($conn, $user['user_id'], 'deliver', 'order', $orderId,
            "Marked order {$order['order_number']} as delivered");
        
        // ── Send Push Notifications ──
        $notifData = [
            'order_id' => $orderId,
            'order_number' => $order['order_number'],
            'status' => ORDER_DELIVERED
        ];

        // Notify customer
        $customerId = getOrderCustomerId($conn, $orderId);
        if ($customerId) {
            notifyUser($conn, $customerId,
                'Order Delivered!',
                'Your order #' . $order['order_number'] . ' has been delivered. Enjoy and rate your experience!',
                'order_status', $notifData);
        }

        // Notify super admins
        notifyAllSuperAdmins($conn,
            'Order Delivered #' . $order['order_number'],
            'Order has been successfully delivered.',
            'order_status', $notifData);

        sendResponse(STATUS_SUCCESS, [
            'order_id' => $orderId,
            'order_number' => $order['order_number'],
            'status' => ORDER_DELIVERED
        ], "Order marked as delivered");
        
    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }
    
} catch (PDOException $e) {
    error_log("Deliver order error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to mark order as delivered: " . $e->getMessage());
} catch (Exception $e) {
    error_log("Deliver order general error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to mark order as delivered: " . $e->getMessage());
}
?>
