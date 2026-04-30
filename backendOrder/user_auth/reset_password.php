<?php
/**
 * Order User App - Reset Password
 * POST: /user_auth/reset_password.php
 * Body: email, reset_token, new_password, confirm_password
 * 
 * Step 3 of reset password flow (after verify_reset_otp)
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";

handleCorsHeaders();

if (!isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$email = filterRequest("email");
$resetToken = filterRequest("reset_token");
$newPassword = filterRequest("new_password");
$confirmPassword = filterRequest("confirm_password");

$errors = validateRequired(['email', 'reset_token', 'new_password', 'confirm_password']);
if (!empty($errors)) {
    sendResponse(STATUS_FAIL, null, implode(", ", $errors));
}

if (!isValidEmail($email)) {
    sendResponse(STATUS_FAIL, null, "Invalid email format");
}

if (!preg_match('/^\d{6}$/', $resetToken)) {
    sendResponse(STATUS_FAIL, null, "Invalid reset token format.");
}

if (strlen($newPassword) < 6) {
    sendResponse(STATUS_FAIL, null, "Password must be at least 6 characters");
}

if ($newPassword !== $confirmPassword) {
    sendResponse(STATUS_FAIL, null, "Passwords do not match");
}

$db = new Database();
$conn = $db->connect();

try {
    // Find verified reset token
    $stmt = $conn->prepare("
        SELECT id FROM email_verifications
        WHERE email = ? AND otp = ? AND type = 'reset_password' AND verified = 1 AND expires_at > NOW()
        ORDER BY created_at DESC LIMIT 1
    ");
    $stmt->execute([$email, $resetToken]);
    $tokenRecord = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tokenRecord) {
        sendResponse(STATUS_FAIL, null, "Invalid or expired reset token. Please restart the reset process.");
    }

    $conn->beginTransaction();
    try {
        // Update password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $conn->prepare("UPDATE users SET password_hash = ?, updated_at = NOW() WHERE email = ? AND role = ?")
             ->execute([$hashedPassword, $email, ROLE_CUSTOMER]);

        // Invalidate all reset tokens for this email
        $conn->prepare("UPDATE email_verifications SET verified = 0 WHERE email = ? AND type = 'reset_password'")
             ->execute([$email]);

        // Kill all existing sessions (force re-login)
        $userStmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND role = ?");
        $userStmt->execute([$email, ROLE_CUSTOMER]);
        $user = $userStmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $conn->prepare("DELETE FROM admin_sessions WHERE user_id = ?")->execute([$user['id']]);
        }

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
