<?php
/**
 * Order User App - Signup
 * POST: /user_auth/signup.php
 * Body: name, email, phone, password, confirm_password
 * 
 * Creates customer in unified users table with ROLE_CUSTOMER.
 * Sends OTP email for verification.
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";
include "../core/email_service.php";

handleCorsHeaders();

if (!isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$name = filterRequest("name");
$email = filterRequest("email");
$phone = filterRequest("phone");
$password = filterRequest("password");
$confirmPassword = filterRequest("confirm_password");

// Validate
$errors = [];
if (empty($name)) $errors[] = "Name is required";
if (empty($email)) $errors[] = "Email is required";
elseif (!isValidEmail($email)) $errors[] = "Invalid email format";
if (empty($phone)) $errors[] = "Phone number is required";
elseif (!isValidPhone($phone)) $errors[] = "Invalid phone number format";
if (empty($password)) $errors[] = "Password is required";
elseif (strlen($password) < 6) $errors[] = "Password must be at least 6 characters";
if (empty($confirmPassword)) $errors[] = "Confirm password is required";
elseif ($password !== $confirmPassword) $errors[] = "Passwords do not match";

if (!empty($errors)) {
    sendResponse(STATUS_FAIL, null, implode(", ", $errors));
}

$db = new Database();
$conn = $db->connect();

try {
    // Check duplicate email
    $checkStmt = $conn->prepare("SELECT id, email_verified_at FROM users WHERE email = ?");
    $checkStmt->execute([$email]);
    $existingUser = $checkStmt->fetch();
    if ($existingUser) {
        if (empty($existingUser['email_verified_at'])) {
            // Delete unverified user so they can re-register
            $conn->prepare("DELETE FROM email_verifications WHERE email = ?")->execute([$email]);
            $conn->prepare("DELETE FROM cart_items WHERE user_id = ?")->execute([$existingUser['id']]);
            $conn->prepare("DELETE FROM admin_sessions WHERE user_id = ?")->execute([$existingUser['id']]);
            $conn->prepare("DELETE FROM users WHERE id = ?")->execute([$existingUser['id']]);
        } else {
            sendResponse(STATUS_FAIL, null, "Email already registered");
        }
    }

    // Check duplicate phone
    $checkStmt = $conn->prepare("SELECT id, email_verified_at FROM users WHERE phone = ?");
    $checkStmt->execute([$phone]);
    $existingPhone = $checkStmt->fetch();
    if ($existingPhone) {
        if (empty($existingPhone['email_verified_at'])) {
            // Delete unverified user so they can re-register
            $conn->prepare("DELETE FROM email_verifications WHERE email = (SELECT email FROM users WHERE id = ? LIMIT 1)")->execute([$existingPhone['id']]);
            $conn->prepare("DELETE FROM cart_items WHERE user_id = ?")->execute([$existingPhone['id']]);
            $conn->prepare("DELETE FROM admin_sessions WHERE user_id = ?")->execute([$existingPhone['id']]);
            $conn->prepare("DELETE FROM users WHERE id = ?")->execute([$existingPhone['id']]);
        } else {
            sendResponse(STATUS_FAIL, null, "Phone number already registered");
        }
    }

    $conn->beginTransaction();
    try {
        $hashedPassword = hashPassword($password);
        $userId = generateUUID();

        // Insert into unified users table as customer
        $insertStmt = $conn->prepare("
            INSERT INTO users (id, name, email, phone, password_hash, role, is_active, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, 1, NOW(), NOW())
        ");
        $insertStmt->execute([$userId, $name, $email, $phone, $hashedPassword, ROLE_CUSTOMER]);

        // Generate OTP
        $otp = sprintf("%06d", mt_rand(100000, 999999));

        // Use MySQL NOW() so expires_at matches the session timezone (Asia/Gaza)
        $otpStmt = $conn->prepare("
            INSERT INTO email_verifications (email, otp, type, expires_at, verified)
            VALUES (?, ?, 'signup', NOW() + INTERVAL 30 MINUTE, 0)
        ");
        $otpStmt->execute([$email, $otp]);

        // Fetch the actual expires_at value from DB for the response
        $expiresStmt = $conn->prepare("SELECT expires_at FROM email_verifications WHERE email = ? AND type = 'signup' ORDER BY created_at DESC LIMIT 1");
        $expiresStmt->execute([$email]);
        $expiresAt = $expiresStmt->fetchColumn();

        $conn->commit();

        // Send OTP email
        $emailSent = sendOTPEmail($email, $name, $otp, 'signup');
        if (!$emailSent) {
            error_log("Failed to send OTP email to: $email");
        }

        $responseData = [
            'user_id' => $userId,
            'email' => $email,
            'name' => $name,
            'expires_at' => $expiresAt,
            'email_sent' => $emailSent
        ];

        // Dev mode: include OTP
        if (defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE === true) {
            $responseData['otp'] = $otp;
        }

        sendResponse(STATUS_SUCCESS, $responseData, "Registration successful. Verification code sent to your email.");

    } catch (Exception $e) {
        $conn->rollBack();
        error_log("Signup transaction error: " . $e->getMessage());
        sendResponse(STATUS_ERROR, null, "Registration failed. Please try again.");
    }

} catch (PDOException $e) {
    error_log("Signup error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Registration failed. Please try again.");
}
?>
