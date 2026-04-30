<?php
/**
 * Order User App - Validate Session
 * POST/GET: /user_auth/validate_session.php
 * Headers: Authorization: Bearer <token>
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";
include "../core/middleware.php";

handleCorsHeaders();

if (!isRequestMethod('GET') && !isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    $customer = verifyCustomerAuth($conn);
    
    // Get full customer details
    $stmt = $conn->prepare("
        SELECT id, name, email, phone, is_active, email_verified_at, created_at, updated_at
        FROM users WHERE id = ? AND is_active = 1
    ");
    $stmt->execute([$customer['customer_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        sendResponse(STATUS_FAIL, null, "Customer not found or inactive");
    }

    // Get profile image
    $profileImage = null;
    $profileStmt = $conn->prepare("SELECT profile_image_url FROM customer_profiles WHERE user_id = ?");
    $profileStmt->execute([$customer['customer_id']]);
    $profile = $profileStmt->fetch(PDO::FETCH_ASSOC);
    if ($profile && !empty($profile['profile_image_url'])) {
        $profileImage = $profile['profile_image_url'];
    }

    // Get session info
    $token = extractToken();
    $sessionInfo = null;
    if ($token) {
        $sessionStmt = $conn->prepare("SELECT created_at, expires_at, ip_address FROM admin_sessions WHERE token = ?");
        $sessionStmt->execute([$token]);
        $sessionInfo = $sessionStmt->fetch(PDO::FETCH_ASSOC);
    }

    sendResponse(STATUS_SUCCESS, [
        'user' => [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'phone' => $user['phone'],
            'profile_image' => $profileImage,
            'email_verified' => !empty($user['email_verified_at']),
            'is_active' => (bool)$user['is_active'],
            'created_at' => formatDateTime($user['created_at']),
            'updated_at' => formatDateTime($user['updated_at'])
        ],
        'session' => [
            'valid' => true,
            'created_at' => $sessionInfo ? formatDateTime($sessionInfo['created_at']) : null,
            'expires_at' => $sessionInfo ? formatDateTime($sessionInfo['expires_at']) : null,
            'ip_address' => $sessionInfo['ip_address'] ?? null
        ],
        'user_id' => $user['id'],
        'user_email' => $user['email']
    ], "Session is valid");

} catch (PDOException $e) {
    error_log("Session validation error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Session validation failed");
}
?>
