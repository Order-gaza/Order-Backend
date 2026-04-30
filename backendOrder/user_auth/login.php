<?php
/**
 * Order User App - Login
 * POST: /user_auth/login.php
 * Body: email, password
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";
include "../core/middleware.php";

handleCorsHeaders();

if (!isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$email = filterRequest("email");
$password = filterRequest("password");

if (empty($email) || empty($password)) {
    sendResponse(STATUS_FAIL, null, "Email and password are required");
}

if (!isValidEmail($email)) {
    sendResponse(STATUS_FAIL, null, "Invalid email format");
}

$db = new Database();
$conn = $db->connect();

try {
    $stmt = $conn->prepare("
        SELECT id, name, email, phone, password_hash, role, is_active, email_verified_at, created_at
        FROM users 
        WHERE email = ? AND role = ?
    ");
    $stmt->execute([$email, ROLE_CUSTOMER]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$customer) {
        sendResponse(STATUS_FAIL, null, "Invalid email or password");
    }

    if (empty($customer['email_verified_at'])) {
        sendResponse(STATUS_FAIL, null, "Please verify your email before logging in");
    }

    if (!$customer['is_active']) {
        sendResponse(STATUS_FAIL, null, "Account is deactivated. Please contact support.");
    }

    if (!password_verify($password, $customer['password_hash'])) {
        sendResponse(STATUS_FAIL, null, "Invalid email or password");
    }

    // Create session with user-specific expiry (7 days)
    $session = createUserSession($conn, $customer['id']);

    // Clean expired sessions
    $conn->prepare("DELETE FROM admin_sessions WHERE user_id = ? AND expires_at < NOW()")
         ->execute([$customer['id']]);

    // Get profile image
    $profileImage = null;
    $profileStmt = $conn->prepare("SELECT profile_image_url FROM customer_profiles WHERE user_id = ?");
    $profileStmt->execute([$customer['id']]);
    $profile = $profileStmt->fetch(PDO::FETCH_ASSOC);
    if ($profile && !empty($profile['profile_image_url'])) {
        $profileImage = $profile['profile_image_url'];
    }

    sendResponse(STATUS_SUCCESS, [
        'user' => [
            'id' => $customer['id'],
            'name' => $customer['name'],
            'email' => $customer['email'],
            'phone' => $customer['phone'],
            'profile_image' => $profileImage,
            'created_at' => formatDateTime($customer['created_at'])
        ],
        'token' => $session['token'],
        'expires_at' => $session['expires_at']
    ], "Login successful");

} catch (PDOException $e) {
    error_log("Login error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Login failed. Please try again.");
}
?>
