<?php
/**
 * Notification Helper
 * Unified system for creating in-app notifications + sending FCM push
 * 
 * Every notification:
 *   1. Inserts a record into the `notifications` table (in-app)
 *   2. Sends an FCM push notification to the user's device(s)
 */

include_once __DIR__ . '/firebase_helper.php';

/**
 * Send notification to a specific user (DB record + FCM push)
 * 
 * @param PDO    $conn      Database connection
 * @param string $userId    User UUID
 * @param string $title     Notification title
 * @param string $body      Notification body
 * @param string $type      Notification type (order_status, new_order, etc.)
 * @param array  $data      Extra data (order_id, status, etc.)
 * @param bool   $sendPush  Whether to also send FCM push
 */
function notifyUser($conn, $userId, $title, $body, $type = 'general', $data = [], $sendPush = true) {
    try {
        // 1. Insert in-app notification
        $stmt = $conn->prepare("
            INSERT INTO notifications (user_id, title, body, type, data, is_read, created_at)
            VALUES (?, ?, ?, ?, ?, 0, NOW())
        ");
        $stmt->execute([
            $userId,
            $title,
            $body,
            $type,
            !empty($data) ? json_encode($data) : null
        ]);
        
        // 2. Send FCM push
        if ($sendPush) {
            $pushData = array_merge($data, [
                'type' => $type,
                'title' => $title,
                'body' => $body,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ]);
            sendPushToUser($conn, $userId, $title, $body, $pushData);
        }
        
    } catch (PDOException $e) {
        error_log("notifyUser error: " . $e->getMessage());
    }
}

/**
 * Send notification to a vendor by vendor_id (not user_id)
 */
function notifyVendorByVendorId($conn, $vendorId, $title, $body, $type = 'general', $data = []) {
    try {
        $stmt = $conn->prepare("SELECT user_id FROM vendors WHERE id = ?");
        $stmt->execute([$vendorId]);
        $vendor = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($vendor) {
            notifyUser($conn, $vendor['user_id'], $title, $body, $type, $data);
        }
    } catch (PDOException $e) {
        error_log("notifyVendorByVendorId error: " . $e->getMessage());
    }
}

/**
 * Send notification to ALL admins (role = 'admin')
 */
function notifyAllAdmins($conn, $title, $body, $type = 'general', $data = []) {
    try {
        $stmt = $conn->prepare("SELECT id FROM users WHERE role = ? AND is_active = 1");
        $stmt->execute([ROLE_ADMIN]);
        $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($admins as $admin) {
            notifyUser($conn, $admin['id'], $title, $body, $type, $data);
        }
    } catch (PDOException $e) {
        error_log("notifyAllAdmins error: " . $e->getMessage());
    }
}

/**
 * Send notification to ALL super_admins
 */
function notifyAllSuperAdmins($conn, $title, $body, $type = 'general', $data = []) {
    try {
        $stmt = $conn->prepare("SELECT id FROM users WHERE role = ? AND is_active = 1");
        $stmt->execute([ROLE_SUPER_ADMIN]);
        $superAdmins = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($superAdmins as $sa) {
            notifyUser($conn, $sa['id'], $title, $body, $type, $data);
        }
    } catch (PDOException $e) {
        error_log("notifyAllSuperAdmins error: " . $e->getMessage());
    }
}

/**
 * Send notification to ALL vendors involved in an order
 */
function notifyOrderVendors($conn, $orderId, $title, $body, $type = 'general', $data = []) {
    try {
        $stmt = $conn->prepare("
            SELECT DISTINCT v.user_id 
            FROM order_vendors ov
            JOIN vendors v ON ov.vendor_id = v.id
            WHERE ov.order_id = ?
        ");
        $stmt->execute([$orderId]);
        $vendors = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($vendors as $vendor) {
            notifyUser($conn, $vendor['user_id'], $title, $body, $type, $data);
        }
    } catch (PDOException $e) {
        error_log("notifyOrderVendors error: " . $e->getMessage());
    }
}

/**
 * Get the customer_id (user_id) for an order
 */
function getOrderCustomerId($conn, $orderId) {
    try {
        $stmt = $conn->prepare("SELECT customer_id FROM orders WHERE id = ?");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        return $order ? $order['customer_id'] : null;
    } catch (PDOException $e) {
        error_log("getOrderCustomerId error: " . $e->getMessage());
        return null;
    }
}
?>
