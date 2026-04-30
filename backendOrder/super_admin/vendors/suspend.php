<?php
/**
 * Super Admin - Suspend Vendor
 * POST: /super_admin/vendors/suspend.php
 * Headers: Authorization: Bearer <token>
 * Body: vendor_id, reason? (optional)
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
    
    $vendorId = filterRequest("vendor_id");
    $reason = filterRequest("reason");
    
    if (empty($vendorId)) {
        sendResponse(STATUS_FAIL, null, "Vendor ID is required");
    }
    
    $stmt = $conn->prepare("SELECT id, name, status, user_id FROM vendors WHERE id = ?");
    $stmt->execute([$vendorId]);
    $vendor = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$vendor) {
        sendResponse(STATUS_FAIL, null, "Vendor not found");
    }
    
    if ($vendor['status'] === VENDOR_SUSPENDED) {
        sendResponse(STATUS_FAIL, null, "Vendor is already suspended");
    }
    
    $conn->beginTransaction();
    
    try {
        $conn->prepare("UPDATE vendors SET status = ?, updated_at = NOW() WHERE id = ?")
             ->execute([VENDOR_SUSPENDED, $vendorId]);
        
        // Deactivate user account and kill sessions
        $conn->prepare("UPDATE users SET is_active = 0, updated_at = NOW() WHERE id = ?")
             ->execute([$vendor['user_id']]);
        $conn->prepare("DELETE FROM admin_sessions WHERE user_id = ?")
             ->execute([$vendor['user_id']]);
        
        $conn->commit();
        
        $description = "Suspended vendor: {$vendor['name']}";
        if (!empty($reason)) $description .= " - Reason: $reason";
        
        logActivity($conn, $user['user_id'], 'suspend', 'vendor', $vendorId, $description);
        
        sendResponse(STATUS_SUCCESS, [
            'vendor_id' => $vendorId,
            'status' => VENDOR_SUSPENDED
        ], "Vendor suspended successfully");
        
    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }
    
} catch (PDOException $e) {
    error_log("Suspend vendor error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to suspend vendor");
}
?>
