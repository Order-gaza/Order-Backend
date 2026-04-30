<?php
/**
 * Order Admin - Forgot Password Endpoint
 * POST: /auth/forgot_password.php
 * Body: email
 * 
 * Generates a 6-digit OTP and sends it via email.
 * Does not reveal whether the email exists (security best practice).
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

// Validate required fields
if (empty($email)) {
    sendResponse(STATUS_FAIL, null, "Email is required");
}

// Validate email format
if (!isValidEmail($email)) {
    sendResponse(STATUS_FAIL, null, "Invalid email format");
}

$db = new Database();
$conn = $db->connect();

try {
    // Check if user exists, is active, and has a management role
    $stmt = $conn->prepare("
        SELECT id, name, role, is_active, email_verified_at
        FROM users
        WHERE email = ?
    ");

    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // Don't reveal that email doesn't exist (security)
        sendResponse(
            STATUS_SUCCESS, 
            ['email' => $email], 
            "If the email exists, a reset code will be sent"
        );
    }

    // Only allow management roles
    if ($user['role'] === ROLE_CUSTOMER) {
        sendResponse(
            STATUS_SUCCESS, 
            ['email' => $email], 
            "If the email exists, a reset code will be sent"
        );
    }

    if (!$user['is_active']) {
        sendResponse(STATUS_FAIL, null, "Account is deactivated. Please contact administrator.");
    }

    // Generate OTP (6-digit code)
    $otp = sprintf("%06d", mt_rand(100000, 999999));
    $expiresAt = date('Y-m-d H:i:s', strtotime('+30 minutes'));

    // Store verification record
    $stmt = $conn->prepare("
        INSERT INTO email_verifications
        (email, otp, type, expires_at, verified)
        VALUES (?, ?, 'reset_password', ?, 0)
    ");

    $stmt->execute([$email, $otp, $expiresAt]);

    // Send OTP via email
    $emailSent = sendOTPEmail($email, $user['name'], $otp, 'reset_password');
    
    if (!$emailSent) {
        error_log("Failed to send reset OTP email to: $email");
    }

    $responseData = [
        'email' => $email,
        'expires_at' => $expiresAt,
        'email_sent' => $emailSent
    ];

    sendResponse(STATUS_SUCCESS, $responseData, "Reset code sent to your email");

} catch (PDOException $e) {
    error_log("Forgot password error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to process reset request");
}
?>
