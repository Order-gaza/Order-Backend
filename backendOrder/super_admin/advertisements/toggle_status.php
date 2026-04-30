<?php
/**
 * Super Admin - Toggle Advertisement Status
 * POST: /super_admin/advertisements/toggle_status.php
 * Headers: Authorization: Bearer <token>
 * Body: ad_id
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
    
    $adId = filterRequest("ad_id");
    
    if (empty($adId)) {
        sendResponse(STATUS_FAIL, null, "Advertisement ID is required");
    }
    
    $stmt = $conn->prepare("SELECT id, title, is_active FROM advertisements WHERE id = ?");
    $stmt->execute([$adId]);
    $ad = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$ad) {
        sendResponse(STATUS_FAIL, null, "Advertisement not found");
    }
    
    $newStatus = $ad['is_active'] ? 0 : 1;
    $conn->prepare("UPDATE advertisements SET is_active = ? WHERE id = ?")
         ->execute([$newStatus, $adId]);
    
    $statusText = $newStatus ? 'activated' : 'deactivated';
    logActivity($conn, $user['user_id'], $statusText, 'advertisement', $adId,
        ucfirst($statusText) . " advertisement: {$ad['title']}");
    
    sendResponse(STATUS_SUCCESS, [
        'ad_id' => $adId,
        'is_active' => (bool)$newStatus
    ], "Advertisement {$statusText} successfully");
    
} catch (PDOException $e) {
    error_log("Toggle ad status error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to toggle advertisement status");
}
?>
