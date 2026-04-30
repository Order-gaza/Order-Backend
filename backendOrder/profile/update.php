<?php
/**
 * Profile - Update Current User Profile
 * POST: /profile/update.php
 * Headers: Authorization: Bearer <token>
 * Body: name, phone
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";
include "../core/middleware.php";

if (!isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    $user = verifyAuth($conn);
    
    $name = filterRequest("name");
    $phone = filterRequest("phone");
    
    if (empty($name)) {
        sendResponse(STATUS_FAIL, null, "Name is required");
    }
    
    $stmt = $conn->prepare("
        UPDATE users SET name = ?, phone = ?, updated_at = NOW() WHERE id = ?
    ");
    $stmt->execute([$name, $phone, $user['user_id']]);
    
    sendResponse(STATUS_SUCCESS, [
        'name' => $name,
        'phone' => $phone
    ], "Profile updated successfully");
    
} catch (PDOException $e) {
    error_log("Update profile error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to update profile");
}
?>
