<?php
/**
 * Super Admin - Get Admin By ID
 * POST: /super_admin/admins/get_by_id.php
 * Headers: Authorization: Bearer <token>
 * Body: admin_id
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
    $user = requireRole($conn, [ROLE_SUPER_ADMIN]);
    
    $adminId = filterRequest("admin_id") ?? (isset($_GET['admin_id']) ? $_GET['admin_id'] : null);
    
    if (empty($adminId)) {
        sendResponse(STATUS_FAIL, null, "Admin ID is required");
    }
    
    // Get admin info
    $stmt = $conn->prepare("
        SELECT id, name, email, phone, is_active, created_at, updated_at 
        FROM users WHERE id = ? AND role = ?
    ");
    $stmt->execute([$adminId, ROLE_ADMIN]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$admin) {
        sendResponse(STATUS_FAIL, null, "Admin not found");
    }
    
    $admin['is_active'] = (bool)$admin['is_active'];
    
    // Active Sessions
    $sessionsStmt = $conn->prepare("
        SELECT ip_address, user_agent, created_at, expires_at
        FROM admin_sessions 
        WHERE user_id = ?
        ORDER BY created_at DESC
        LIMIT 10
    ");
    $sessionsStmt->execute([$adminId]);
    $sessions = $sessionsStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format dates for display
    foreach ($sessions as &$session) {
        $session['created_at'] = formatDateTime($session['created_at']);
        $session['expires_at'] = formatDateTime($session['expires_at']);
        $session['is_expired'] = (strtotime($session['expires_at']) < time());
    }
    
    // Get metrics (just basic for admins if any apply)
    $loginCountStmt = $conn->prepare("SELECT COUNT(*) as count FROM admin_sessions WHERE user_id = ?");
    $loginCountStmt->execute([$adminId]);
    $totalLogins = (int)$loginCountStmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    sendResponse(STATUS_SUCCESS, [
        'admin' => $admin,
        'stats' => [
            'total_recorded_logins' => $totalLogins,
        ],
        'sessions' => $sessions
    ], "Admin details retrieved");
    
} catch (PDOException $e) {
    error_log("Get admin by ID error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve admin details");
}
?>
