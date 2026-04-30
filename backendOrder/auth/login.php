<?php
/**
 * Order Admin - Login Endpoint
 * POST: /auth/login.php
 * Body: email, password
 * 
 * Unified login for super_admin, admin, and vendor roles.
 * Returns user data + role so Flutter routes to the correct dashboard.
 * Rejects customer role (customers use the User App).
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";
include "../core/middleware.php";

// Only allow POST requests
if (!isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

// Get and validate input
$email = filterRequest("email");
$password = filterRequest("password");

// Validate required fields
$errors = validateRequired(['email', 'password']);
if (!empty($errors)) {
    sendResponse(STATUS_FAIL, null, implode(", ", $errors));
}

// Validate email format
if (!isValidEmail($email)) {
    sendResponse(STATUS_FAIL, null, "Invalid email format");
}

// Create database connection
$db = new Database();
$conn = $db->connect();

try {
    // Find user by email
    $stmt = $conn->prepare("
        SELECT 
            id,
            email,
            password_hash,
            name,
            phone,
            role,
            zone_coverage,
            is_active,
            email_verified_at,
            updated_at
        FROM users 
        WHERE email = ?
    ");
    
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Check if user exists
    if (!$user) {
        sendResponse(STATUS_FAIL, null, "Invalid email or password");
    }
    
    // Only allow management roles (not customers)
    if ($user['role'] === ROLE_CUSTOMER) {
        sendResponse(STATUS_FAIL, null, "Invalid email or password");
    }
    
    // Check if account is active
    if (!$user['is_active']) {
        sendResponse(STATUS_FAIL, null, "Account is deactivated. Please contact administrator.");
    }
    
    // Verify password
    if (!password_verify($password, $user['password_hash'])) {
        sendResponse(STATUS_FAIL, null, "Invalid email or password");
    }
    
    // Create session token
    $token = createSession($conn, $user['id']);
    
    // Clean expired sessions
    cleanExpiredSessions($conn);
    
    // Prepare response data
    $responseData = [
        'user' => [
            'id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'phone' => $user['phone'],
            'role' => $user['role'],
            'zone_coverage' => $user['zone_coverage'],
            'last_login' => formatDateTime($user['updated_at'])
        ],
        'token' => $token
    ];
    
    // If vendor, fetch vendor profile
    if ($user['role'] === ROLE_VENDOR) {
        $vendorStmt = $conn->prepare("
            SELECT 
                id AS vendor_id,
                name AS vendor_name,
                type,
                status,
                rating,
                total_ratings,
                logo_url,
                commission_rate
            FROM vendors 
            WHERE user_id = ?
        ");
        $vendorStmt->execute([$user['id']]);
        $vendorProfile = $vendorStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($vendorProfile) {
            // Check if vendor is approved
            if ($vendorProfile['status'] !== VENDOR_APPROVED) {
                sendResponse(STATUS_FAIL, null, "Your vendor account is " . $vendorProfile['status'] . ". Please contact administrator.");
            }
            $responseData['vendor'] = $vendorProfile;
        }
    }
    
    // Log activity
    logActivity($conn, $user['id'], 'login', 'user', $user['id'], ucfirst($user['role']) . ' logged in');
    
    sendResponse(STATUS_SUCCESS, $responseData, "Login successful");
    
} catch (PDOException $e) {
    error_log("Login error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "An error occurred. Please try again.");
}
?>
