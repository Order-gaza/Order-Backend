<?php
/**
 * Order User App - Verify Email
 * POST: /user_auth/verify_email.php
 * Body: email, otp
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
    sendResponse(STATUS_FAIL, null, "Invalid OTP format");
}

$db = new Database();
$conn = $db->connect();

try {
    $stmt = $conn->prepare("
        SELECT id, expires_at 
        FROM email_verifications 
        WHERE email = ? AND otp = ? AND type = 'signup' AND verified = 0 AND expires_at > NOW()
        ORDER BY created_at DESC 
        LIMIT 1
    ");
    $stmt->execute([$email, $otp]);
    $verification = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$verification) {
        sendResponse(STATUS_FAIL, null, "Invalid or expired verification code");
    }

    $conn->beginTransaction();
    try {
        // Mark OTP as used
        $conn->prepare("UPDATE email_verifications SET verified = 1 WHERE id = ?")
             ->execute([$verification['id']]);

        // Mark user email as verified
        $conn->prepare("UPDATE users SET email_verified_at = NOW(), updated_at = NOW() WHERE email = ? AND role = ?")
             ->execute([$email, ROLE_CUSTOMER]);

        $conn->commit();
        sendResponse(STATUS_SUCCESS, null, "Email verified successfully");

    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }

} catch (PDOException $e) {
    error_log("Email verification error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Email verification failed");
}
?>
