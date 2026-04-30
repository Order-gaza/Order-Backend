<?php
/**
 * Order User App - Forgot Password
 * POST: /user_auth/forgot_password.php
 * Body: email
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";
include "../core/email_service.php";

handleCorsHeaders();

if (!isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$email = filterRequest("email");

if (empty($email)) {
    sendResponse(STATUS_FAIL, null, "Email is required");
}

if (!isValidEmail($email)) {
    sendResponse(STATUS_FAIL, null, "Invalid email format");
}

$db = new Database();
$conn = $db->connect();

try {
    $stmt = $conn->prepare("
        SELECT id, name, is_active, email_verified_at 
        FROM users WHERE email = ? AND role = ?
    ");
    $stmt->execute([$email, ROLE_CUSTOMER]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$customer) {
        // Don't reveal that email doesn't exist
        sendResponse(STATUS_SUCCESS, ['email' => $email], "If the email exists, a reset code will be sent");
    }

    if (!$customer['is_active']) {
        sendResponse(STATUS_FAIL, null, "Account is deactivated. Please contact support.");
    }

    if (empty($customer['email_verified_at'])) {
        sendResponse(STATUS_FAIL, null, "Please verify your email first");
    }

    // Generate OTP
    $otp = sprintf("%06d", mt_rand(100000, 999999));

    // Use MySQL NOW() so expires_at matches the session timezone (Asia/Gaza)
    $conn->prepare("
        INSERT INTO email_verifications (email, otp, type, expires_at, verified)
        VALUES (?, ?, 'reset_password', NOW() + INTERVAL 30 MINUTE, 0)
    ")->execute([$email, $otp]);

    // Fetch the actual expires_at value from DB for the response
    $expiresStmt = $conn->prepare("SELECT expires_at FROM email_verifications WHERE email = ? AND type = 'reset_password' ORDER BY created_at DESC LIMIT 1");
    $expiresStmt->execute([$email]);
    $expiresAt = $expiresStmt->fetchColumn();

    $emailSent = sendOTPEmail($email, $customer['name'], $otp, 'reset_password');
    if (!$emailSent) {
        error_log("Failed to send reset OTP to: $email");
    }

    $responseData = [
        'email' => $email,
        'expires_at' => $expiresAt,
        'email_sent' => $emailSent
    ];

    if (defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE === true) {
        $responseData['otp'] = $otp;
    }

    sendResponse(STATUS_SUCCESS, $responseData, "Reset code sent to your email");

} catch (PDOException $e) {
    error_log("Forgot password error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to process reset request");
}
?>
