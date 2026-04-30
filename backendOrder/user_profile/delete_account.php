<?php
/**
 * User App - Delete Account
 * POST: /user_profile/delete_account.php
 * Headers: Authorization: Bearer <token>
 * Body: password (confirm with current password)
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

    $password = filterRequest("password");
    if (empty($password)) {
        sendResponse(STATUS_FAIL, null, "Password is required to confirm account deletion");
    }

    // Verify password
    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE id = ?");
    $stmt->execute([$customer['customer_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!password_verify($password, $user['password_hash'])) {
        sendResponse(STATUS_FAIL, null, "Incorrect password");
    }

    $conn->beginTransaction();
    try {
        // Deactivate account (soft delete)
        $conn->prepare("UPDATE users SET is_active = 0, updated_at = NOW() WHERE id = ?")
             ->execute([$customer['customer_id']]);

        // Kill all sessions
        $conn->prepare("DELETE FROM admin_sessions WHERE user_id = ?")
             ->execute([$customer['customer_id']]);

        $conn->commit();

        sendResponse(STATUS_SUCCESS, null, "Account deactivated successfully");

    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }

} catch (PDOException $e) {
    error_log("Delete account error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to delete account");
}
?>
