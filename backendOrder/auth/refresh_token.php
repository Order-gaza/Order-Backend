<?php
/**
 * Order Admin - Refresh Token Endpoint
 * POST: /auth/refresh_token.php
 * Headers: Authorization: Bearer <token>
 * 
 * Extends the current session by creating a new token.
 * Useful for keeping users logged in without re-authentication.
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
    // Verify current authentication
    $user = verifyAuth($conn);
    
    // Get current token
    $currentToken = extractToken();
    
    if (!$currentToken) {
        sendResponse(STATUS_FAIL, null, "Token is required");
    }
    
    // Begin transaction
    $conn->beginTransaction();
    
    // Delete old session
    deleteSession($conn, $currentToken);
    
    // Create new session
    $newToken = createSession($conn, $user['user_id']);
    
    // Commit transaction
    $conn->commit();
    
    // Get user details
    $stmt = $conn->prepare("
        SELECT 
            id,
            email,
            name,
            phone,
            role
        FROM users 
        WHERE id = ?
    ");
    
    $stmt->execute([$user['user_id']]);
    $userDetails = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Prepare response
    $responseData = [
        'user' => [
            'id' => $userDetails['id'],
            'email' => $userDetails['email'],
            'name' => $userDetails['name'],
            'phone' => $userDetails['phone'],
            'role' => $userDetails['role']
        ],
        'token' => $newToken
    ];
    
    // Log activity
    logActivity(
        $conn, 
        $user['user_id'], 
        'refresh_token', 
        'user', 
        $user['user_id'], 
        'Token refreshed'
    );
    
    sendResponse(STATUS_SUCCESS, $responseData, "Token refreshed successfully");
    
} catch (PDOException $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    error_log("Token refresh error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Token refresh failed");
}
?>
