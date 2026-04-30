<?php
/**
 * Vendor - Toggle Store Open/Closed
 * POST: /vendor/profile/toggle_open.php
 * Headers: Authorization: Bearer <token>
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
    $user = requireRole($conn, [ROLE_VENDOR]);
    
    $stmt = $conn->prepare("SELECT id, name, is_open, status FROM vendors WHERE user_id = ?");
    $stmt->execute([$user['user_id']]);
    $vendor = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$vendor) {
        sendResponse(STATUS_FAIL, null, "Vendor profile not found");
    }
    
    // Only approved vendors can toggle open status
    if ($vendor['status'] !== 'approved') {
        sendResponse(STATUS_FAIL, null, "Your store must be approved before you can open it");
    }
    
    $newStatus = $vendor['is_open'] ? 0 : 1;
    $conn->prepare("UPDATE vendors SET is_open = ?, updated_at = NOW() WHERE id = ?")
         ->execute([$newStatus, $vendor['id']]);
    
    $statusText = $newStatus ? 'opened' : 'closed';
    logActivity($conn, $user['user_id'], $statusText, 'vendor', $vendor['id'],
        ucfirst($statusText) . " store: {$vendor['name']}");
    
    sendResponse(STATUS_SUCCESS, [
        'is_open' => (bool)$newStatus
    ], "Store {$statusText} successfully");
    
} catch (PDOException $e) {
    error_log("Toggle open error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to toggle store status");
}
?>
