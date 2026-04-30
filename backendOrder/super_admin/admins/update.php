<?php
/**
 * Super Admin - Update Admin
 * POST: /super_admin/admins/update.php
 * Headers: Authorization: Bearer <token>
 * Body: admin_id, name?, phone?, email?
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
    $user = requireRole($conn, [ROLE_SUPER_ADMIN]);
    
    $adminId = filterRequest("admin_id");
    $name = filterRequest("name");
    $phone = filterRequest("phone");
    $email = filterRequest("email");
    
    if (empty($adminId)) {
        sendResponse(STATUS_FAIL, null, "Admin ID is required");
    }
    
    // Check admin exists
    $checkStmt = $conn->prepare("SELECT id, name, email FROM users WHERE id = ? AND role = ?");
    $checkStmt->execute([$adminId, ROLE_ADMIN]);
    $current = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$current) {
        sendResponse(STATUS_FAIL, null, "Admin not found");
    }
    
    // Build dynamic update
    $fields = [];
    $params = [];
    
    if (!empty($name)) {
        $fields[] = "name = ?";
        $params[] = $name;
    }
    
    if (!empty($phone)) {
        // Check phone uniqueness
        $phoneCheck = $conn->prepare("SELECT id FROM users WHERE phone = ? AND id != ?");
        $phoneCheck->execute([$phone, $adminId]);
        if ($phoneCheck->fetch()) {
            sendResponse(STATUS_FAIL, null, "Phone number already in use");
        }
        $fields[] = "phone = ?";
        $params[] = $phone;
    }
    
    if (!empty($email)) {
        if (!isValidEmail($email)) {
            sendResponse(STATUS_FAIL, null, "Invalid email format");
        }
        $emailCheck = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $emailCheck->execute([$email, $adminId]);
        if ($emailCheck->fetch()) {
            sendResponse(STATUS_FAIL, null, "Email already in use");
        }
        $fields[] = "email = ?";
        $params[] = $email;
    }
    
    if (empty($fields)) {
        sendResponse(STATUS_SUCCESS, null, "No changes provided");
    }
    
    $fields[] = "updated_at = NOW()";
    $params[] = $adminId;
    
    $updateStmt = $conn->prepare("UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?");
    $updateStmt->execute($params);
    
    // Fetch updated record
    $selectStmt = $conn->prepare("SELECT id, name, email, phone, role, is_active, updated_at FROM users WHERE id = ?");
    $selectStmt->execute([$adminId]);
    $updated = $selectStmt->fetch(PDO::FETCH_ASSOC);
    
    logActivity($conn, $user['user_id'], 'update', 'admin', $adminId, "Updated admin: {$updated['name']}");
    
    sendResponse(STATUS_SUCCESS, [
        'admin' => [
            'id' => $updated['id'],
            'name' => $updated['name'],
            'email' => $updated['email'],
            'phone' => $updated['phone'],
            'role' => $updated['role'],
            'is_active' => (bool)$updated['is_active'],
            'updated_at' => formatDateTime($updated['updated_at'])
        ]
    ], "Admin updated successfully");
    
} catch (PDOException $e) {
    error_log("Update admin error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "An error occurred while updating admin");
}
?>
