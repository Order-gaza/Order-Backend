<?php
/**
 * Order User App - Logout
 * POST: /user_auth/logout.php
 * Headers: Authorization: Bearer <token>
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";
include "../core/middleware.php";

handleCorsHeaders();

if (!isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    $customer = verifyCustomerAuth($conn);
    
    $token = extractToken();
    if ($token) {
        deleteSession($conn, $token);
    }
    
    sendResponse(STATUS_SUCCESS, null, "Logged out successfully");

} catch (PDOException $e) {
    error_log("Logout error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Logout failed");
}
?>
