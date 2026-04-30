<?php
/**
 * User App - Mark Notification as Read
 * POST: /user_notifications/mark_read.php
 * Headers: Authorization: Bearer <token>
 * Body: notification_id
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
    $customer = verifyCustomerAuth($conn);

    $notifId = filterRequest("notification_id");
    if (empty($notifId)) {
        sendResponse(STATUS_FAIL, null, "Notification ID is required");
    }

    $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
    $stmt->execute([$notifId, $customer['customer_id']]);

    if ($stmt->rowCount() === 0) {
        sendResponse(STATUS_FAIL, null, "Notification not found");
    }

    sendResponse(STATUS_SUCCESS, null, "Notification marked as read");

} catch (PDOException $e) {
    error_log("Mark read error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to mark as read");
}
?>
