<?php
/**
 * Super Admin - Delete Admin
 * POST: /super_admin/admins/delete.php
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
    
    // Prevent self-deletion
    if ($adminId === $user['user_id']) {
        sendResponse(STATUS_FAIL, null, "Cannot delete your own account");
    }
    
    // Get admin info
    $infoStmt = $conn->prepare("SELECT name, email, role FROM users WHERE id = ? AND role = ?");
    $infoStmt->execute([$adminId, ROLE_ADMIN]);
    $adminInfo = $infoStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$adminInfo) {
        sendResponse(STATUS_FAIL, null, "Admin not found");
    }
    
    // Delete sessions first (FK cascade should handle this, but be explicit)
    $conn->prepare("DELETE FROM admin_sessions WHERE user_id = ?")->execute([$adminId]);
    
    // Delete admin
    $deleteStmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role = ?");
    $deleteStmt->execute([$adminId, ROLE_ADMIN]);
    
    if ($deleteStmt->rowCount() > 0) {
        logActivity($conn, $user['user_id'], 'delete', 'admin', $adminId,
            "Deleted admin: {$adminInfo['name']} ({$adminInfo['email']})");
        
        sendResponse(STATUS_SUCCESS, null, "Admin deleted successfully");
    } else {
        sendResponse(STATUS_FAIL, null, "Failed to delete admin");
    }
    
} catch (PDOException $e) {
    error_log("Delete admin error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "An error occurred while deleting admin");
}
?>
