<?php
/**
 * Super Admin - Bulk Mark Earnings as Collected
 * POST: /super_admin/earnings/mark_bulk_collected.php
 * Headers: Authorization: Bearer <token>
 * Body: earning_ids (JSON array) OR vendor_id (mark all for vendor)
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
    
    $earningIds = filterRequest("earning_ids");
    $vendorId = filterRequest("vendor_id");
    
    if (empty($earningIds) && empty($vendorId)) {
        sendResponse(STATUS_FAIL, null, "Either earning_ids (JSON array) or vendor_id is required");
    }
    
    $conn->beginTransaction();
    
    try {
        $count = 0;
        
        if (!empty($vendorId)) {
            // Mark all uncollected earnings for a vendor
            $stmt = $conn->prepare("
                UPDATE platform_earnings 
                SET is_collected = 1, collected_at = NOW() 
                WHERE vendor_id = ? AND is_collected = 0
            ");
            $stmt->execute([$vendorId]);
            $count = $stmt->rowCount();
            
            logActivity($conn, $user['user_id'], 'bulk_collect', 'platform_earnings', $vendorId,
                "Bulk collected $count earnings for vendor");
        } else {
            // Mark specific earning IDs
            $ids = json_decode($earningIds, true);
            if (!is_array($ids) || empty($ids)) {
                sendResponse(STATUS_FAIL, null, "Invalid earning_ids format. Must be a JSON array.");
            }
            
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $conn->prepare("
                UPDATE platform_earnings 
                SET is_collected = 1, collected_at = NOW() 
                WHERE id IN ($placeholders) AND is_collected = 0
            ");
            $stmt->execute($ids);
            $count = $stmt->rowCount();
            
            logActivity($conn, $user['user_id'], 'bulk_collect', 'platform_earnings', null,
                "Bulk collected $count earnings");
        }
        
        $conn->commit();
        
        sendResponse(STATUS_SUCCESS, [
            'collected_count' => $count
        ], "$count earnings marked as collected");
        
    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }
    
} catch (PDOException $e) {
    error_log("Bulk mark collected error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to bulk mark earnings");
}
?>
