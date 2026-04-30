<?php
/**
 * Admin - Get All Delivery Zones
 * GET: /admin/delivery_zones/get_all.php
 * Headers: Authorization: Bearer <token>
 */

include "../../config/database.php";
include "../../config/constants.php";
include "../../core/functions.php";
include "../../core/middleware.php";

if (!isRequestMethod('GET') && !isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    $user = requireRole($conn, [ROLE_SUPER_ADMIN, ROLE_ADMIN]);
    
    $stmt = $conn->prepare("SELECT * FROM delivery_zones ORDER BY min_distance_km ASC");
    $stmt->execute();
    $zones = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    sendResponse(STATUS_SUCCESS, [
        'delivery_zones' => $zones,
        'total' => count($zones)
    ], "Delivery zones retrieved");
    
} catch (PDOException $e) {
    error_log("Get delivery zones error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve delivery zones");
}
?>
