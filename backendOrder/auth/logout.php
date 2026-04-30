<?php
/**
 * Order Admin - Logout Endpoint
 * POST: /auth/logout.php
 * Headers: Authorization: Bearer <token>
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";
include "../core/middleware.php";

// Only allow POST requests
if (!isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

// Create database connection
$db = new Database();
$conn = $db->connect();

try {
    // Verify authentication and get user data
    $user = verifyAuth($conn);
    
    // Get token from header or POST data
    $token = extractToken();
    
    if (!$token) {
        sendResponse(STATUS_FAIL, null, "Token is required");
    }
    
    // Delete the session
    deleteSession($conn, $token);
    
    // Log activity
    logActivity(
        $conn, 
        $user['user_id'], 
        'logout', 
        'user', 
        $user['user_id'], 
        ucfirst($user['role']) . ' logged out'
    );
    
    sendResponse(STATUS_SUCCESS, null, "Logged out successfully");
    
} catch (PDOException $e) {
    error_log("Logout error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "An error occurred during logout");
}
?>
