<?php
/**
 * Profile - Change Password
 * POST: /profile/change_password.php
 * Headers: Authorization: Bearer <token>
 * Body: current_password, new_password, new_password_confirmation
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";
include "../core/middleware.php";

if (!isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    $user = verifyAuth($conn);
    
    $currentPassword = filterRequest("current_password");
    $newPassword = filterRequest("new_password");
    $confirmPassword = filterRequest("new_password_confirmation");
    
    // Validate required fields
    if (empty($currentPassword)) {
        sendResponse(STATUS_FAIL, null, "Current password is required");
    }
    if (empty($newPassword)) {
        sendResponse(STATUS_FAIL, null, "New password is required");
    }
    if (empty($confirmPassword)) {
        sendResponse(STATUS_FAIL, null, "Password confirmation is required");
    }
    
    // Validate new password length
    if (strlen($newPassword) < 6) {
        sendResponse(STATUS_FAIL, null, "New password must be at least 6 characters");
    }
    
    // Validate password confirmation
    if ($newPassword !== $confirmPassword) {
        sendResponse(STATUS_FAIL, null, "New password and confirmation do not match");
    }
    
    // Check if new password is same as current
    if ($currentPassword === $newPassword) {
        sendResponse(STATUS_FAIL, null, "New password must be different from current password");
    }
    
    // Get current password hash
    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE id = ?");
    $stmt->execute([$user['user_id']]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$userData) {
        sendResponse(STATUS_FAIL, null, "User not found");
    }
    
    // Verify current password
    if (!password_verify($currentPassword, $userData['password_hash'])) {
        sendResponse(STATUS_FAIL, null, "Current password is incorrect");
    }
    
    // Hash and update new password
    $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
    
    $updateStmt = $conn->prepare("
        UPDATE users SET password_hash = ?, updated_at = NOW() WHERE id = ?
    ");
    $updateStmt->execute([$newHash, $user['user_id']]);
    
    if ($updateStmt->rowCount() > 0) {
        logActivity($conn, $user['user_id'], 'change_password', 'user', 
            $user['user_id'], 'Password changed via profile');
        
        sendResponse(STATUS_SUCCESS, null, "Password changed successfully");
    } else {
        sendResponse(STATUS_FAIL, null, "Failed to update password");
    }
    
} catch (PDOException $e) {
    error_log("Change password error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "An error occurred while changing password");
}
?>
