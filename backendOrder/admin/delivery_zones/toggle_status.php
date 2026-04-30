<?php
/**
 * Admin - Toggle Delivery Zone Status
 * POST: /admin/delivery_zones/toggle_status.php
 * Headers: Authorization: Bearer <token>
 * Body: zone_id
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
    $user = requireRole($conn, [ROLE_SUPER_ADMIN, ROLE_ADMIN]);
    
    $zoneId = filterRequest("zone_id");
    
    if (empty($zoneId)) {
        sendResponse(STATUS_FAIL, null, "Zone ID is required");
    }
    
    $stmt = $conn->prepare("SELECT id, name, is_active FROM delivery_zones WHERE id = ?");
    $stmt->execute([$zoneId]);
    $zone = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$zone) {
        sendResponse(STATUS_FAIL, null, "Delivery zone not found");
    }
    
    $newStatus = $zone['is_active'] ? 0 : 1;
    $conn->prepare("UPDATE delivery_zones SET is_active = ? WHERE id = ?")
         ->execute([$newStatus, $zoneId]);
    
    $statusText = $newStatus ? 'activated' : 'deactivated';
    logActivity($conn, $user['user_id'], $statusText, 'delivery_zone', $zoneId,
        ucfirst($statusText) . " delivery zone: {$zone['name']}");
    
    sendResponse(STATUS_SUCCESS, [
        'zone_id' => $zoneId,
        'is_active' => (bool)$newStatus
    ], "Delivery zone {$statusText} successfully");
    
} catch (PDOException $e) {
    error_log("Toggle delivery zone error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to toggle delivery zone status");
}
?>
