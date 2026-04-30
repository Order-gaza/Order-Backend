<?php
/**
 * Super Admin - Mark Earning as Collected
 * POST: /super_admin/earnings/mark_collected.php
 * Headers: Authorization: Bearer <token>
 * Body: earning_id
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
    
    $earningId = filterRequest("earning_id");
    
    if (empty($earningId)) {
        sendResponse(STATUS_FAIL, null, "Earning ID is required");
    }
    
    $stmt = $conn->prepare("SELECT id, is_collected, platform_fee, vendor_id FROM platform_earnings WHERE id = ?");
    $stmt->execute([$earningId]);
    $earning = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$earning) {
        sendResponse(STATUS_FAIL, null, "Earning record not found");
    }
    
    if ($earning['is_collected']) {
        sendResponse(STATUS_FAIL, null, "Earning is already marked as collected");
    }
    
    $conn->prepare("UPDATE platform_earnings SET is_collected = 1, collected_at = NOW() WHERE id = ?")
         ->execute([$earningId]);
    
    logActivity($conn, $user['user_id'], 'collect_earning', 'platform_earnings', $earningId,
        "Collected earning: " . $earning['platform_fee'] . " NIS");
    
    sendResponse(STATUS_SUCCESS, [
        'earning_id' => $earningId,
        'is_collected' => true
    ], "Earning marked as collected");
    
} catch (PDOException $e) {
    error_log("Mark collected error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to mark earning as collected");
}
?>
