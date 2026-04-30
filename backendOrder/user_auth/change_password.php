<?php
/**
 * Order User App - Change Password
 * POST: /user_auth/change_password.php
 * Headers: Authorization: Bearer <token>
 * Body: current_password, new_password, confirm_password
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
    
    $currentPassword = filterRequest("current_password");
    $newPassword = filterRequest("new_password");
    $confirmPassword = filterRequest("confirm_password");
    
    $errors = validateRequired(['current_password', 'new_password', 'confirm_password']);
    if (!empty($errors)) {
        sendResponse(STATUS_FAIL, null, implode(", ", $errors));
    }
    
    if (strlen($newPassword) < 6) {
        sendResponse(STATUS_FAIL, null, "New password must be at least 6 characters");
    }
    
    if ($newPassword !== $confirmPassword) {
        sendResponse(STATUS_FAIL, null, "Passwords do not match");
    }
    
    // Verify current password
    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE id = ?");
    $stmt->execute([$customer['customer_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!password_verify($currentPassword, $user['password_hash'])) {
        sendResponse(STATUS_FAIL, null, "Current password is incorrect");
    }
    
    // Update password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $conn->prepare("UPDATE users SET password_hash = ?, updated_at = NOW() WHERE id = ?")
         ->execute([$hashedPassword, $customer['customer_id']]);
    
    sendResponse(STATUS_SUCCESS, null, "Password changed successfully");

} catch (PDOException $e) {
    error_log("Change password error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to change password");
}
?>
