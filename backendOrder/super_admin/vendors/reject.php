<?php
/**
 * Super Admin - Reject Vendor
 * POST: /super_admin/vendors/reject.php
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
    
    $stmt = $conn->prepare("SELECT id, name, status FROM vendors WHERE id = ?");
    $stmt->execute([$vendorId]);
    $vendor = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$vendor) {
        sendResponse(STATUS_FAIL, null, "Vendor not found");
    }
    
    if ($vendor['status'] === VENDOR_REJECTED) {
        sendResponse(STATUS_FAIL, null, "Vendor is already rejected");
    }
    
    $conn->prepare("UPDATE vendors SET status = ?, updated_at = NOW() WHERE id = ?")
         ->execute([VENDOR_REJECTED, $vendorId]);
    
    $description = "Rejected vendor: {$vendor['name']}";
    if (!empty($reason)) {
        $description .= " - Reason: $reason";
    }
    
    logActivity($conn, $user['user_id'], 'reject', 'vendor', $vendorId, $description);
    
    sendResponse(STATUS_SUCCESS, [
        'vendor_id' => $vendorId,
        'status' => VENDOR_REJECTED
    ], "Vendor rejected successfully");
    
} catch (PDOException $e) {
    error_log("Reject vendor error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to reject vendor");
}
?>
