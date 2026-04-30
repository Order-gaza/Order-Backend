<?php
/**
 * Admin - Get Delivery Zone By ID
 * GET: /admin/delivery_zones/get_by_id.php?id=<uuid>
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
    
    $zoneId = isset($_GET['id']) ? trim($_GET['id']) : filterRequest("id");
    
    if (empty($zoneId)) {
        sendResponse(STATUS_FAIL, null, "Delivery zone ID is required");
    }
    
    $stmt = $conn->prepare("SELECT * FROM delivery_zones WHERE id = ?");
    $stmt->execute([$zoneId]);
    $zone = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$zone) {
        sendResponse(STATUS_FAIL, null, "Delivery zone not found");
    }
    
    sendResponse(STATUS_SUCCESS, ['delivery_zone' => $zone], "Delivery zone retrieved");
    
} catch (PDOException $e) {
    error_log("Get delivery zone error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve delivery zone");
}
?>
