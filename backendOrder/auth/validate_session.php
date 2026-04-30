<?php
/**
 * Order Admin - Validate Session Endpoint
 * POST/GET: /auth/validate_session.php
 * Headers: Authorization: Bearer <token>
 * 
 * Returns user profile data if session is valid.
 * Used for checking authentication state on app startup.
 * If vendor role, also returns vendor profile.
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";
include "../core/middleware.php";

// Allow both GET and POST methods
if (!isRequestMethod('GET') && !isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

// Create database connection
$db = new Database();
$conn = $db->connect();

try {
    // Verify authentication and get user data
    $user = verifyAuth($conn);
    
    // Get full user details
    $stmt = $conn->prepare("
        SELECT 
            id,
            email,
            name,
            phone,
            role,
            is_active,
            email_verified_at,
            created_at,
            updated_at
        FROM users 
        WHERE id = ? AND is_active = 1
    ");
    
    $stmt->execute([$user['user_id']]);
    $userDetails = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$userDetails) {
        sendResponse(STATUS_FAIL, null, "User not found or inactive");
    }
    
    // Get session info
    $token = extractToken();
    
    $sessionInfo = null;
    if ($token) {
        $sessionStmt = $conn->prepare("
            SELECT 
                created_at,
                expires_at,
                ip_address
            FROM admin_sessions 
            WHERE token = ?
        ");
        $sessionStmt->execute([$token]);
        $sessionInfo = $sessionStmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Prepare response
    $responseData = [
        'user' => [
            'id' => $userDetails['id'],
            'email' => $userDetails['email'],
            'name' => $userDetails['name'],
            'phone' => $userDetails['phone'],
            'role' => $userDetails['role'],
            'email_verified' => !empty($userDetails['email_verified_at']),
            'last_login' => formatDateTime($userDetails['updated_at']),
            'created_at' => formatDateTime($userDetails['created_at'])
        ],
        'session' => [
            'valid' => true,
            'created_at' => $sessionInfo ? formatDateTime($sessionInfo['created_at']) : null,
            'expires_at' => $sessionInfo ? formatDateTime($sessionInfo['expires_at']) : null,
            'ip_address' => $sessionInfo['ip_address'] ?? null
        ]
    ];
    
    // If vendor, fetch vendor profile
    if ($userDetails['role'] === ROLE_VENDOR) {
        $vendorStmt = $conn->prepare("
            SELECT 
                id AS vendor_id,
                name AS vendor_name,
                type,
                status,
                description,
                logo_url,
                cover_url,
                phone AS vendor_phone,
                address,
                opening_time,
                closing_time,
                rating,
                total_ratings,
                commission_rate
            FROM vendors 
            WHERE user_id = ?
        ");
        $vendorStmt->execute([$user['user_id']]);
        $vendorProfile = $vendorStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($vendorProfile) {
            $responseData['vendor'] = $vendorProfile;
        }
    }
    
    sendResponse(STATUS_SUCCESS, $responseData, "Session is valid");
    
} catch (PDOException $e) {
    error_log("Session validation error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Session validation failed");
}
?>
