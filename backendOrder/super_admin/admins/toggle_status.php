<?php
/**
 * Super Admin - Toggle Admin Status
 * POST: /super_admin/admins/toggle_status.php
 * Headers: Authorization: Bearer <token>
 * Body: admin_id
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
    
    if (empty($adminId)) {
        sendResponse(STATUS_FAIL, null, "Admin ID is required");
    }
    
    if ($adminId === $user['user_id']) {
        sendResponse(STATUS_FAIL, null, "Cannot toggle your own account status");
    }
    
    $checkStmt = $conn->prepare("SELECT id, name, is_active FROM users WHERE id = ? AND role = ?");
    $checkStmt->execute([$adminId, ROLE_ADMIN]);
    $admin = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$admin) {
        sendResponse(STATUS_FAIL, null, "Admin not found");
    }
    
    $newStatus = $admin['is_active'] ? 0 : 1;
    
    $updateStmt = $conn->prepare("UPDATE users SET is_active = ?, updated_at = NOW() WHERE id = ?");
    $updateStmt->execute([$newStatus, $adminId]);
    
    // If deactivating, kill their sessions
    if ($newStatus === 0) {
        $conn->prepare("DELETE FROM admin_sessions WHERE user_id = ?")->execute([$adminId]);
    }
    
    $statusText = $newStatus ? 'activated' : 'deactivated';
    logActivity($conn, $user['user_id'], $statusText, 'admin', $adminId,
        ucfirst($statusText) . " admin: {$admin['name']}");
    
    sendResponse(STATUS_SUCCESS, [
        'admin_id' => $adminId,
        'is_active' => (bool)$newStatus
    ], "Admin {$statusText} successfully");
    
} catch (PDOException $e) {
    error_log("Toggle admin status error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to toggle admin status");
}
?>
