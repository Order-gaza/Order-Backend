<?php
/**
 * Order User App - Resend OTP
 * POST: /user_auth/resend_otp.php
 * Body: email, type (signup|reset_password)
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
$type = filterRequest("type");

if (empty($email)) {
    sendResponse(STATUS_FAIL, null, "Email is required");
}

if (!isValidEmail($email)) {
    sendResponse(STATUS_FAIL, null, "Invalid email format");
}

if (empty($type) || !in_array($type, ['signup', 'reset_password'])) {
    $type = 'signup'; // Default
}

$db = new Database();
$conn = $db->connect();

try {
    $stmt = $conn->prepare("SELECT id, name FROM users WHERE email = ? AND role = ?");
    $stmt->execute([$email, ROLE_CUSTOMER]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$customer) {
        sendResponse(STATUS_FAIL, null, "Email not found");
    }

    // Generate new OTP
    $otp = sprintf("%06d", mt_rand(100000, 999999));

    // Use MySQL NOW() so expires_at matches the session timezone (Asia/Gaza)
    $conn->prepare("
        INSERT INTO email_verifications (email, otp, type, expires_at, verified)
        VALUES (?, ?, ?, NOW() + INTERVAL 30 MINUTE, 0)
    ")->execute([$email, $otp, $type]);

    // Fetch the actual expires_at value from DB for the response
    $expiresStmt = $conn->prepare("SELECT expires_at FROM email_verifications WHERE email = ? AND type = ? ORDER BY created_at DESC LIMIT 1");
    $expiresStmt->execute([$email, $type]);
    $expiresAt = $expiresStmt->fetchColumn();

    $emailSent = sendOTPEmail($email, $customer['name'], $otp, $type);
    if (!$emailSent) {
        error_log("Failed to resend OTP to: $email");
    }

    $responseData = [
        'email' => $email,
        'expires_at' => $expiresAt,
        'email_sent' => $emailSent
    ];

    if (defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE === true) {
        $responseData['otp'] = $otp;
    }

    sendResponse(STATUS_SUCCESS, $responseData, "Verification code resent");

} catch (PDOException $e) {
    error_log("Resend OTP error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to resend code");
}
?>
