<?php
/**
 * Super Admin - Approve Vendor
 * POST: /super_admin/vendors/approve.php
 * Headers: Authorization: Bearer <token>
 * Body: vendor_id, commission_rate? (optional, defaults to 12.00)
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
    $commissionRate = filterRequest("commission_rate");
    
    if (empty($vendorId)) {
        sendResponse(STATUS_FAIL, null, "Vendor ID is required");
    }
    
    // Get vendor
    $stmt = $conn->prepare("SELECT id, name, status, user_id FROM vendors WHERE id = ?");
    $stmt->execute([$vendorId]);
    $vendor = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$vendor) {
        sendResponse(STATUS_FAIL, null, "Vendor not found");
    }
    
    if ($vendor['status'] === VENDOR_APPROVED) {
        sendResponse(STATUS_FAIL, null, "Vendor is already approved");
    }
    
    $conn->beginTransaction();
    
    try {
        // Update vendor status
        $updateFields = "status = ?";
        $updateParams = [VENDOR_APPROVED];
        
        if (!empty($commissionRate) && is_numeric($commissionRate)) {
            $updateFields .= ", commission_rate = ?";
            $updateParams[] = floatval($commissionRate);
        }
        
        $updateFields .= ", updated_at = NOW()";
        $updateParams[] = $vendorId;
        
        $updateStmt = $conn->prepare("UPDATE vendors SET $updateFields WHERE id = ?");
        $updateStmt->execute($updateParams);
        
        // Activate the vendor's user account
        $conn->prepare("UPDATE users SET is_active = 1, updated_at = NOW() WHERE id = ?")
             ->execute([$vendor['user_id']]);
        
        $conn->commit();
        
        logActivity($conn, $user['user_id'], 'approve', 'vendor', $vendorId,
            "Approved vendor: {$vendor['name']}");
        
        sendResponse(STATUS_SUCCESS, [
            'vendor_id' => $vendorId,
            'status' => VENDOR_APPROVED
        ], "Vendor approved successfully");
        
    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }
    
} catch (PDOException $e) {
    error_log("Approve vendor error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to approve vendor");
}
?>
