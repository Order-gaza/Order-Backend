<?php
/**
 * Order User App - Verify Reset OTP
 * POST: /user_auth/verify_reset_otp.php
 * Body: email, otp
 * 
 * Step 2 of reset password flow:
 * forgot_password → verify_reset_otp → reset_password
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";

handleCorsHeaders();

if (!isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$email = filterRequest("email");
$otp = filterRequest("otp");

$errors = validateRequired(['email', 'otp']);
if (!empty($errors)) {
    sendResponse(STATUS_FAIL, null, implode(", ", $errors));
}

if (!isValidEmail($email)) {
    sendResponse(STATUS_FAIL, null, "Invalid email format");
}

if (!preg_match('/^\d{6}$/', $otp)) {
    sendResponse(STATUS_FAIL, null, "Invalid OTP format. Must be 6 digits.");
}

$db = new Database();
$conn = $db->connect();

try {
    $stmt = $conn->prepare("
        SELECT id, expires_at
        FROM email_verifications
        WHERE email = ? AND otp = ? AND type = 'reset_password' AND verified = 0 AND expires_at > NOW()
        ORDER BY created_at DESC
        LIMIT 1
    ");
    $stmt->execute([$email, $otp]);
    $verification = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$verification) {
        // Check specific failure reasons
        $checkStmt = $conn->prepare("
            SELECT id, verified, expires_at FROM email_verifications 
            WHERE email = ? AND otp = ? AND type = 'reset_password'
            ORDER BY created_at DESC LIMIT 1
        ");
        $checkStmt->execute([$email, $otp]);
        $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            if ($existing['verified'] == 1) {
                sendResponse(STATUS_FAIL, null, "This code has already been used.");
            }
            if (new \DateTime($existing['expires_at']) <= new \DateTime('now', new \DateTimeZone('Asia/Gaza'))) {
                sendResponse(STATUS_FAIL, null, "Verification code has expired. Please request a new one.");
            }
        }
        sendResponse(STATUS_FAIL, null, "Invalid verification code.");
    }

    // Mark as verified
    $conn->prepare("UPDATE email_verifications SET verified = 1 WHERE id = ?")
         ->execute([$verification['id']]);

    // Return the OTP as the reset token (same pattern as Naseej)
    sendResponse(STATUS_SUCCESS, [
        'email' => $email,
        'reset_token' => $otp,
        'expires_at' => $verification['expires_at']
    ], "Verification successful");

} catch (PDOException $e) {
    error_log("Verify reset OTP error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Verification failed");
}
?>
