<?php
/**
 * Super Admin - Reactivate Vendor
 * POST: /super_admin/vendors/reactivate.php
 * Headers: Authorization: Bearer <token>
 * Body: vendor_id
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
    
    if (empty($vendorId)) {
        sendResponse(STATUS_FAIL, null, "Vendor ID is required");
    }
    
    $stmt = $conn->prepare("SELECT id, name, status, user_id FROM vendors WHERE id = ?");
    $stmt->execute([$vendorId]);
    $vendor = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$vendor) {
        sendResponse(STATUS_FAIL, null, "Vendor not found");
    }
    
    if ($vendor['status'] === VENDOR_APPROVED) {
        sendResponse(STATUS_FAIL, null, "Vendor is already active");
    }
    
    $conn->beginTransaction();
    
    try {
        $conn->prepare("UPDATE vendors SET status = ?, updated_at = NOW() WHERE id = ?")
             ->execute([VENDOR_APPROVED, $vendorId]);
        
        // Reactivate user account
        $conn->prepare("UPDATE users SET is_active = 1, updated_at = NOW() WHERE id = ?")
             ->execute([$vendor['user_id']]);
        
        $conn->commit();
        
        logActivity($conn, $user['user_id'], 'reactivate', 'vendor', $vendorId,
            "Reactivated vendor: {$vendor['name']}");
        
        sendResponse(STATUS_SUCCESS, [
            'vendor_id' => $vendorId,
            'status' => VENDOR_APPROVED
        ], "Vendor reactivated successfully");
        
    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }
    
} catch (PDOException $e) {
    error_log("Reactivate vendor error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to reactivate vendor");
}
?>
