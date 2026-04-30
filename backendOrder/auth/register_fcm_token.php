<?php
/**
 * Register FCM Token (Admin/Vendor/Super Admin)
 * POST: /auth/register_fcm_token.php
 * Headers: Authorization: Bearer <token>
 * Body: fcm_token, device_type? (android|ios|web)
 * 
 * Stores or updates the device FCM token for push notifications.
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";
include "../core/middleware.php";

handleCorsHeaders();

if (!isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    // Works for any authenticated role (admin, vendor, super_admin)
    $user = verifyAuth($conn);
    
    $fcmToken = filterRequest("fcm_token");
    $deviceType = filterRequest("device_type");
    
    if (empty($fcmToken)) {
        sendResponse(STATUS_FAIL, null, "FCM token is required");
    }
    
    if (empty($deviceType) || !in_array($deviceType, ['android', 'ios', 'web'])) {
        $deviceType = 'android';
    }
    
    // Upsert: insert or update if token already exists for this user
    $stmt = $conn->prepare("
        INSERT INTO fcm_tokens (user_id, token, device_type, updated_at)
        VALUES (?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE
            device_type = VALUES(device_type),
            updated_at = NOW()
    ");
    $stmt->execute([$user['user_id'], $fcmToken, $deviceType]);
    
    sendResponse(STATUS_SUCCESS, null, "FCM token registered");
    
} catch (PDOException $e) {
    error_log("Register FCM token error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to register FCM token");
}
?>
