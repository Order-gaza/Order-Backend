<?php
/**
 * Admin - Confirm Order
 * POST: /admin/orders/confirm.php
 * Headers: Authorization: Bearer <token>
 * Body: order_id
 * 
 * Workflow: pending → admin_confirmed → sent_to_vendor
 * Admin reviews the order and confirms it, then it's sent to the vendor(s).
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
    
    if ($order['status'] !== ORDER_PENDING) {
        sendResponse(STATUS_FAIL, null, "Order can only be confirmed from 'pending' status. Current: " . $order['status']);
    }
    
    $conn->beginTransaction();
    
    try {
        // Step 1: Mark as admin_confirmed
        $conn->prepare("UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?")
             ->execute([ORDER_ADMIN_CONFIRMED, $orderId]);
        
        // Step 2: Immediately send to vendor(s)
        $conn->prepare("UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?")
             ->execute([ORDER_SENT_TO_VENDOR, $orderId]);
        
        // Update order_vendors status
        $conn->prepare("UPDATE order_vendors SET status = ? WHERE order_id = ?")
             ->execute([ORDER_SENT_TO_VENDOR, $orderId]);
        
        $conn->commit();
        
        logActivity($conn, $user['user_id'], 'confirm', 'order', $orderId,
            "Confirmed and sent order {$order['order_number']} to vendor(s)");
        
        // ── Send Push Notifications ──
        $notifData = [
            'order_id' => $orderId,
            'order_number' => $order['order_number'],
            'status' => ORDER_SENT_TO_VENDOR
        ];

        // Notify customer
        $customerId = getOrderCustomerId($conn, $orderId);
        if ($customerId) {
            notifyUser($conn, $customerId,
                'Order Confirmed!',
                'Your order #' . $order['order_number'] . ' has been confirmed and sent to the vendor.',
                'order_status', $notifData);
        }

        // Notify each vendor
        notifyOrderVendors($conn, $orderId,
            'New Order to Prepare',
            'Order #' . $order['order_number'] . ' — Please accept and start preparing.',
            'new_order', $notifData);

        // Notify super admins
        notifyAllSuperAdmins($conn,
            'Order #' . $order['order_number'] . ' Confirmed',
            'Confirmed by admin and sent to vendor(s)',
            'order_status', $notifData);

        sendResponse(STATUS_SUCCESS, [
            'order_id' => $orderId,
            'order_number' => $order['order_number'],
            'status' => ORDER_SENT_TO_VENDOR
        ], "Order confirmed and sent to vendor(s)");
        
    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }
    
} catch (PDOException $e) {
    error_log("Confirm order error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to confirm order");
}
?>
