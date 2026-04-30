<?php
/**
 * Order Admin - Reset Password Endpoint
 * POST: /auth/reset_password.php
 * Body: email, reset_token, new_password, confirm_password
 * 
 * Sets a new password after OTP has been verified.
 * Flow: forgot_password → verify_otp → reset_password
 * The reset_token is the same 6-digit OTP that was verified in verify_otp.
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";

// Only allow POST requests
if (!isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

// Get and validate input
$email = filterRequest("email");
$resetToken = filterRequest("reset_token");
$newPassword = filterRequest("new_password");
$confirmPassword = filterRequest("confirm_password");

// Validate required fields
$errors = validateRequired(['email', 'reset_token', 'new_password', 'confirm_password']);
if (!empty($errors)) {
    sendResponse(STATUS_FAIL, null, implode(", ", $errors));
}

// Validate email format
if (!isValidEmail($email)) {
    sendResponse(STATUS_FAIL, null, "Invalid email format");
}

// Validate reset token format (must be 6 digits)
if (!preg_match('/^\d{6}$/', $resetToken)) {
    sendResponse(STATUS_FAIL, null, "Invalid reset token format. Must be 6 digits.");
}

// Validate password length
if (strlen($newPassword) < 6) {
    sendResponse(STATUS_FAIL, null, "Password must be at least 6 characters");
}

// Validate password confirmation
if ($newPassword !== $confirmPassword) {
    sendResponse(STATUS_FAIL, null, "Passwords do not match");
}

$db = new Database();
$conn = $db->connect();

try {
    // Find verified reset token
    $stmt = $conn->prepare("
        SELECT id, otp, type, verified, expires_at
        FROM email_verifications
        WHERE email = ? 
        AND otp = ? 
        AND type = 'reset_password'
        AND verified = 1 
        AND expires_at > NOW()
        ORDER BY created_at DESC
        LIMIT 1
    ");
    $stmt->execute([$email, $resetToken]);
    $tokenRecord = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$tokenRecord) {
        // Check different failure scenarios for better error messages
        $checkStmt = $conn->prepare("
            SELECT id, type, verified, expires_at
            FROM email_verifications 
            WHERE email = ? AND otp = ?
            ORDER BY created_at DESC 
            LIMIT 1
        ");
        $checkStmt->execute([$email, $resetToken]);
        $record = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$record) {
            sendResponse(STATUS_FAIL, null, "Invalid reset token. The code doesn't match our records.");
        }
        
        if ($record['type'] !== 'reset_password') {
            sendResponse(STATUS_FAIL, null, "Invalid token type.");
        }
        
        if ($record['verified'] != 1) {
            sendResponse(STATUS_FAIL, null, "Token not verified. Please verify the OTP first.");
        }
        
        if ($record['expires_at'] <= date('Y-m-d H:i:s')) {
            sendResponse(STATUS_FAIL, null, "Reset token has expired. Please request a new password reset.");
        }
        
        sendResponse(STATUS_FAIL, null, "Token validation failed.");
    }
    
    // Begin transaction
    $conn->beginTransaction();
    
    try {
        // Hash new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Update user password
        $updateStmt = $conn->prepare("
            UPDATE users
            SET password_hash = ?, updated_at = NOW()
            WHERE email = ?
        ");
        $updateStmt->execute([$hashedPassword, $email]);
        
        // Invalidate used reset token
        $invalidateStmt = $conn->prepare("
            UPDATE email_verifications
            SET verified = 0
            WHERE id = ?
        ");
        $invalidateStmt->execute([$tokenRecord['id']]);
        
        // Invalidate all other reset tokens for this email
        $invalidateAllStmt = $conn->prepare("
            UPDATE email_verifications
            SET verified = 0
            WHERE email = ? AND type = 'reset_password'
        ");
        $invalidateAllStmt->execute([$email]);
        
        // Commit transaction
        $conn->commit();
        
        sendResponse(STATUS_SUCCESS, ['email' => $email], "Password reset successfully");
        
    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }
    
} catch (PDOException $e) {
    error_log("Reset password error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Password reset failed");
}
?>
