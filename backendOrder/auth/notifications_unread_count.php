<?php
/**
 * Admin/Vendor/SA - Get Unread Notification Count
 * GET: /auth/notifications_unread_count.php
 * Headers: Authorization: Bearer <token>
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";
include "../core/middleware.php";

handleCorsHeaders();

if (!isRequestMethod('GET') && !isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    $user = verifyAuth($conn);

    $stmt = $conn->prepare("SELECT COUNT(*) as unread FROM notifications WHERE user_id = ? AND is_read = 0");
    $stmt->execute([$user['user_id']]);
    $count = $stmt->fetch(PDO::FETCH_ASSOC);

    sendResponse(STATUS_SUCCESS, ['unread_count' => (int)$count['unread']], "Unread count");

} catch (PDOException $e) {
    error_log("Unread count error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to get count");
}
?>
