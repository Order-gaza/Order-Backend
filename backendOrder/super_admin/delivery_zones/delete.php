<?php
/**
 * Super Admin - Delete Delivery Zone
 * POST: /super_admin/delivery_zones/delete.php
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
    $user = requireRole($conn, [ROLE_SUPER_ADMIN]);
    
    $zoneId = filterRequest("zone_id");
    
    if (empty($zoneId)) {
        sendResponse(STATUS_FAIL, null, "Zone ID is required");
    }
    
    $infoStmt = $conn->prepare("SELECT id, name FROM delivery_zones WHERE id = ?");
    $infoStmt->execute([$zoneId]);
    $zone = $infoStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$zone) {
        sendResponse(STATUS_FAIL, null, "Delivery zone not found");
    }
    
    $deleteStmt = $conn->prepare("DELETE FROM delivery_zones WHERE id = ?");
    $deleteStmt->execute([$zoneId]);
    
    if ($deleteStmt->rowCount() > 0) {
        logActivity($conn, $user['user_id'], 'delete', 'delivery_zone', $zoneId,
            "SA deleted delivery zone: {$zone['name']}");
        sendResponse(STATUS_SUCCESS, null, "Delivery zone deleted successfully");
    } else {
        sendResponse(STATUS_FAIL, null, "Failed to delete delivery zone");
    }
    
} catch (PDOException $e) {
    error_log("SA delete delivery zone error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to delete delivery zone");
}
?>
