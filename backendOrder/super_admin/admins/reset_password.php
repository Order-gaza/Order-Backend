<?php
/**
 * Super Admin - Reset Admin Password
 * POST: /super_admin/admins/reset_password.php
 * Headers: Authorization: Bearer <token>
 * Body: admin_id, new_password
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
    $newPassword = filterRequest("new_password");
    
    $errors = validateRequired(['admin_id', 'new_password']);
    if (!empty($errors)) {
        sendResponse(STATUS_FAIL, null, implode(", ", $errors));
    }
    
    if (strlen($newPassword) < 6) {
        sendResponse(STATUS_FAIL, null, "Password must be at least 6 characters");
    }
    
    $checkStmt = $conn->prepare("SELECT id, name FROM users WHERE id = ? AND role = ?");
    $checkStmt->execute([$adminId, ROLE_ADMIN]);
    $admin = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$admin) {
        sendResponse(STATUS_FAIL, null, "Admin not found");
    }
    
    $hash = password_hash($newPassword, PASSWORD_DEFAULT);
    $conn->prepare("UPDATE users SET password_hash = ?, updated_at = NOW() WHERE id = ?")->execute([$hash, $adminId]);
    
    // Invalidate all their sessions (force re-login)
    $conn->prepare("DELETE FROM admin_sessions WHERE user_id = ?")->execute([$adminId]);
    
    logActivity($conn, $user['user_id'], 'reset_password', 'admin', $adminId,
        "Reset password for admin: {$admin['name']}");
    
    sendResponse(STATUS_SUCCESS, null, "Admin password reset successfully");
    
} catch (PDOException $e) {
    error_log("Reset admin password error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to reset admin password");
}
?>
