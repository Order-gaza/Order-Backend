<?php
/**
 * Order Admin - Resend OTP Endpoint
 * POST: /auth/resend_otp.php
 * Body: email, type (signup/reset_password)
 * 
 * Generates a new 6-digit OTP and sends it via email.
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";
include "../core/email_service.php";

// Only allow POST requests
if (!isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

// Get and validate input
$email = filterRequest("email");
$type = filterRequest("type") ?: 'signup';

// Validate required fields
if (empty($email)) {
    sendResponse(STATUS_FAIL, null, "Email is required");
}

// Validate email format
if (!isValidEmail($email)) {
    sendResponse(STATUS_FAIL, null, "Invalid email format");
}

// Validate type
if (!in_array($type, ['signup', 'reset_password'])) {
    sendResponse(STATUS_FAIL, null, "Invalid verification type");
}

$db = new Database();
$conn = $db->connect();

try {
    // Check if user exists
    $userStmt = $conn->prepare("SELECT id, name FROM users WHERE email = ?");
    $userStmt->execute([$email]);
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        sendResponse(STATUS_FAIL, null, "Email not found");
    }

    // Generate new OTP (6-digit code)
    $otp = sprintf("%06d", mt_rand(100000, 999999));
    $expiresAt = date('Y-m-d H:i:s', strtotime('+30 minutes'));

    // Insert new verification record
    $stmt = $conn->prepare("
        INSERT INTO email_verifications 
        (email, otp, type, expires_at, verified) 
        VALUES (?, ?, ?, ?, 0)
    ");
    
    $stmt->execute([$email, $otp, $type, $expiresAt]);

    // Send OTP via email
    $emailSent = sendOTPEmail($email, $user['name'], $otp, $type);
    
    if (!$emailSent) {
        error_log("Failed to resend OTP email to: $email");
    }

    $responseData = [
        'email' => $email,
        'type' => $type,
        'expires_at' => $expiresAt,
        'email_sent' => $emailSent
    ];

    sendResponse(STATUS_SUCCESS, $responseData, "Verification code sent successfully");

} catch (PDOException $e) {
    error_log("Resend OTP error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to send verification code");
}
?>
