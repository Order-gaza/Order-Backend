<?php
/**
 * Order Admin - Change Password Endpoint
 * POST: /auth/change_password.php
 * Headers: Authorization: Bearer <token>
 * Body: current_password, new_password, confirm_password
 * 
 * Allows authenticated user to change their password.
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";
include "../core/middleware.php";

// Only allow POST requests
if (!isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

// Create database connection
$db = new Database();
$conn = $db->connect();

try {
    // Verify authentication
    $user = verifyAuth($conn);
    
    // Get and validate input
    $currentPassword = filterRequest("current_password");
    $newPassword = filterRequest("new_password");
    $confirmPassword = filterRequest("confirm_password");
    
    // Validate required fields
    $errors = validateRequired(['current_password', 'new_password', 'confirm_password']);
    if (!empty($errors)) {
        sendResponse(STATUS_FAIL, null, implode(", ", $errors));
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
    
    // Get current password hash from database
    $stmt = $conn->prepare("
        SELECT password_hash 
        FROM users 
        WHERE id = ?
    ");
    $stmt->execute([$user['user_id']]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$userData) {
        sendResponse(STATUS_FAIL, null, "User not found");
    }
    
    // Verify current password
    if (!password_verify($currentPassword, $userData['password_hash'])) {
        sendResponse(STATUS_FAIL, null, "Current password is incorrect");
    }
    
    // Hash new password
    $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
    
    // Update password
    $updateStmt = $conn->prepare("
        UPDATE users 
        SET password_hash = ?, updated_at = NOW() 
        WHERE id = ?
    ");
    $updateStmt->execute([$newPasswordHash, $user['user_id']]);
    
    if ($updateStmt->rowCount() > 0) {
        // Log activity
        logActivity(
            $conn, 
            $user['user_id'], 
            'change_password', 
            'user', 
            $user['user_id'], 
            'Password changed'
        );
        
        sendResponse(STATUS_SUCCESS, null, "Password changed successfully");
    } else {
        sendResponse(STATUS_FAIL, null, "Failed to update password");
    }
    
} catch (PDOException $e) {
    error_log("Change password error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "An error occurred while changing password");
}
?>
