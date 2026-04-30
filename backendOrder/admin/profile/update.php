<?php
/**
 * Admin - Update Own Profile
 * POST: /admin/profile/update.php
 * Headers: Authorization: Bearer <token>
 * Body: name?, phone?
 * 
 * Admin can update their own name and phone. Email changes require super_admin.
 */

include "../../config/database.php";
include "../../config/constants.php";
include "../../core/functions.php";
include "../../core/middleware.php";

if (!isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    $user = requireRole($conn, [ROLE_SUPER_ADMIN, ROLE_ADMIN]);
    
    $name = filterRequest("name");
    $phone = filterRequest("phone");
    
    $fields = [];
    $params = [];
    
    if (!empty($name)) {
        $fields[] = "name = ?";
        $params[] = $name;
    }
    
    if (!empty($phone)) {
        // Check uniqueness
        $checkStmt = $conn->prepare("SELECT id FROM users WHERE phone = ? AND id != ?");
        $checkStmt->execute([$phone, $user['user_id']]);
        if ($checkStmt->fetch()) {
            sendResponse(STATUS_FAIL, null, "Phone number already in use");
        }
        $fields[] = "phone = ?";
        $params[] = $phone;
    }
    
    if (empty($fields)) {
        sendResponse(STATUS_SUCCESS, null, "No changes provided");
    }
    
    $fields[] = "updated_at = NOW()";
    $params[] = $user['user_id'];
    
    $updateStmt = $conn->prepare("UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?");
    $updateStmt->execute($params);
    
    // Fetch updated profile
    $selectStmt = $conn->prepare("SELECT id, name, email, phone, role, updated_at FROM users WHERE id = ?");
    $selectStmt->execute([$user['user_id']]);
    $updated = $selectStmt->fetch(PDO::FETCH_ASSOC);
    
    logActivity($conn, $user['user_id'], 'update_profile', 'user', $user['user_id'], "Updated own profile");
    
    sendResponse(STATUS_SUCCESS, [
        'profile' => [
            'id' => $updated['id'],
            'name' => $updated['name'],
            'email' => $updated['email'],
            'phone' => $updated['phone'],
            'role' => $updated['role'],
            'updated_at' => formatDateTime($updated['updated_at'])
        ]
    ], "Profile updated successfully");
    
} catch (PDOException $e) {
    error_log("Update profile error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to update profile");
}
?>
