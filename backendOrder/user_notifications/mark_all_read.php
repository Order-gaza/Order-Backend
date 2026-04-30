<?php
/**
 * User App - Mark All Notifications as Read
 * POST: /user_notifications/mark_all_read.php
 * Headers: Authorization: Bearer <token>
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

    $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0");
    $stmt->execute([$customer['customer_id']]);

    sendResponse(STATUS_SUCCESS, ['marked' => $stmt->rowCount()], "All notifications marked as read");

} catch (PDOException $e) {
    error_log("Mark all read error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to mark all as read");
}
?>
