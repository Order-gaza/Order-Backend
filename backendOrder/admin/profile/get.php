<?php
/**
 * Admin - Get Own Profile
 * GET: /admin/profile/get.php
 * Headers: Authorization: Bearer <token>
 */

include "../../config/database.php";
include "../../config/constants.php";
include "../../core/functions.php";
include "../../core/middleware.php";

if (!isRequestMethod('GET') && !isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    $user = requireRole($conn, [ROLE_SUPER_ADMIN, ROLE_ADMIN]);
    
    $stmt = $conn->prepare("
        SELECT id, name, email, phone, role, is_active, email_verified_at, created_at, updated_at
        FROM users 
        WHERE id = ?
    ");
    $stmt->execute([$user['user_id']]);
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$profile) {
        sendResponse(STATUS_FAIL, null, "Profile not found");
    }
    
    sendResponse(STATUS_SUCCESS, [
        'profile' => [
            'id' => $profile['id'],
            'name' => $profile['name'],
            'email' => $profile['email'],
            'phone' => $profile['phone'],
            'role' => $profile['role'],
            'is_active' => (bool)$profile['is_active'],
            'email_verified' => !empty($profile['email_verified_at']),
            'created_at' => formatDateTime($profile['created_at']),
            'updated_at' => formatDateTime($profile['updated_at'])
        ]
    ], "Profile retrieved");
    
} catch (PDOException $e) {
    error_log("Get profile error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve profile");
}
?>
