<?php
/**
 * Order Admin - Verify OTP Endpoint
 * POST: /auth/verify_otp.php
 * Body: email, otp, type (signup/reset_password)
 * 
 * Verifies a 6-digit OTP code.
 * For signup: marks user email as verified.
 * For reset_password: marks OTP as verified (allows reset_password.php to proceed).
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
$otp = filterRequest("otp");
$type = filterRequest("type") ?: 'signup';

// Validate required fields
$errors = validateRequired(['email', 'otp']);
if (!empty($errors)) {
    sendResponse(STATUS_FAIL, null, implode(", ", $errors));
}

// Validate email format
if (!isValidEmail($email)) {
    sendResponse(STATUS_FAIL, null, "Invalid email format");
}

// Validate OTP format (must be 6 digits)
if (!preg_match('/^\d{6}$/', $otp)) {
    sendResponse(STATUS_FAIL, null, "Invalid OTP format. Must be 6 digits.");
}

// Validate type
if (!in_array($type, ['signup', 'reset_password'])) {
    sendResponse(STATUS_FAIL, null, "Invalid verification type");
}

$db = new Database();
$conn = $db->connect();

try {
    // Find valid verification record
    $stmt = $conn->prepare("
        SELECT id, expires_at 
        FROM email_verifications 
        WHERE email = ? 
        AND otp = ? 
        AND type = ? 
        AND verified = 0 
        AND expires_at > NOW()
        ORDER BY created_at DESC 
        LIMIT 1
    ");
    
    $stmt->execute([$email, $otp, $type]);
    $verification = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$verification) {
        sendResponse(STATUS_FAIL, null, "Invalid or expired verification code");
    }

    // Begin transaction
    $conn->beginTransaction();

    try {
        // Mark verification as used
        $updateStmt = $conn->prepare("
            UPDATE email_verifications 
            SET verified = 1 
            WHERE id = ?
        ");
        $updateStmt->execute([$verification['id']]);

        // If signup type, verify user email
        if ($type === 'signup') {
            $userStmt = $conn->prepare("
                UPDATE users 
                SET email_verified_at = NOW(), updated_at = NOW() 
                WHERE email = ?
            ");
            $userStmt->execute([$email]);
        }

        // Commit transaction
        $conn->commit();

        $responseData = [
            'email' => $email,
            'type' => $type,
            'verified' => true
        ];

        $message = ($type === 'signup') 
            ? "Email verified successfully" 
            : "OTP verified. You can now reset your password.";

        sendResponse(STATUS_SUCCESS, $responseData, $message);

    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }

} catch (PDOException $e) {
    error_log("OTP verification error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Verification failed");
}
?>
