<?php
/**
 * Admin - Add Delivery Zone
 * POST: /admin/delivery_zones/add.php
 * Headers: Authorization: Bearer <token>
 * Body: name, min_distance_km, max_distance_km, base_fee, nearby_extra_fee, far_extra_fee
 *
 * Also accepts legacy field names: min_distance, max_distance
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
    
    $name           = filterRequest("name");
    $minDistRaw      = filterRequest("min_distance_km");
    $minDistance      = ($minDistRaw !== null && $minDistRaw !== '') ? $minDistRaw : filterRequest("min_distance");
    $maxDistRaw      = filterRequest("max_distance_km");
    $maxDistance      = ($maxDistRaw !== null && $maxDistRaw !== '') ? $maxDistRaw : filterRequest("max_distance");
    $baseFee        = filterRequest("base_fee");
    $nearbyExtraFee = filterRequest("nearby_extra_fee");
    $farExtraFee    = filterRequest("far_extra_fee");
    $estDeliveryTime = filterRequest("estimated_delivery_time");
    
    // Validate required
    if (empty($name)) {
        sendResponse(STATUS_FAIL, null, "Zone name is required");
    }
    if ($minDistance === null || $minDistance === '' || $maxDistance === null || $maxDistance === '') {
        sendResponse(STATUS_FAIL, null, "Min and max distance are required");
    }
    if ($baseFee === null || $baseFee === '') {
        sendResponse(STATUS_FAIL, null, "Base fee is required");
    }
    
    $minDist = floatval($minDistance);
    $maxDist = floatval($maxDistance);
    
    if ($maxDist <= $minDist) {
        sendResponse(STATUS_FAIL, null, "Max distance must be greater than min distance");
    }
    
    $insertStmt = $conn->prepare("
        INSERT INTO delivery_zones 
        (name, min_distance_km, max_distance_km, base_fee, nearby_extra_fee, far_extra_fee, estimated_delivery_time, is_active, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, 1, NOW())
    ");
    
    $insertStmt->execute([
        $name,
        $minDist,
        $maxDist,
        floatval($baseFee),
        floatval($nearbyExtraFee ?: 0),
        floatval($farExtraFee ?: 0),
        $estDeliveryTime
    ]);
    
    // Get newly created zone
    $selectStmt = $conn->prepare("SELECT * FROM delivery_zones WHERE name = ? ORDER BY created_at DESC LIMIT 1");
    $selectStmt->execute([$name]);
    $zone = $selectStmt->fetch(PDO::FETCH_ASSOC);
    
    logActivity($conn, $user['user_id'], 'create', 'delivery_zone', $zone['id'], "Created delivery zone: $name");
    
    sendResponse(STATUS_SUCCESS, ['delivery_zone' => $zone], "Delivery zone created successfully");
    
} catch (PDOException $e) {
    error_log("Add delivery zone error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to create delivery zone");
}
?>
