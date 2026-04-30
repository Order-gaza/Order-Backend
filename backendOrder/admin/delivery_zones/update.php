<?php
/**
 * Admin - Update Delivery Zone
 * POST: /admin/delivery_zones/update.php
 * Headers: Authorization: Bearer <token>
 * Body: zone_id, name?, min_distance_km?, max_distance_km?, base_fee?, nearby_extra_fee?, far_extra_fee?
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
    
    $zoneId = filterRequest("zone_id");
    
    if (empty($zoneId)) {
        sendResponse(STATUS_FAIL, null, "Zone ID is required");
    }
    
    $checkStmt = $conn->prepare("SELECT id FROM delivery_zones WHERE id = ?");
    $checkStmt->execute([$zoneId]);
    if (!$checkStmt->fetch()) {
        sendResponse(STATUS_FAIL, null, "Delivery zone not found");
    }
    
    $fields = [];
    $params = [];
    
    $name           = filterRequest("name");
    $minDistRaw      = filterRequest("min_distance_km");
    $minDistance      = ($minDistRaw !== null && $minDistRaw !== '') ? $minDistRaw : filterRequest("min_distance");
    $maxDistRaw      = filterRequest("max_distance_km");
    $maxDistance      = ($maxDistRaw !== null && $maxDistRaw !== '') ? $maxDistRaw : filterRequest("max_distance");
    $baseFee        = filterRequest("base_fee");
    $nearbyExtraFee = filterRequest("nearby_extra_fee");
    $farExtraFee    = filterRequest("far_extra_fee");
    $estDeliveryTime = filterRequest("estimated_delivery_time");
    
    if (!empty($name))                                     { $fields[] = "name = ?";             $params[] = $name; }
    if ($minDistance !== null && $minDistance !== '')        { $fields[] = "min_distance_km = ?";  $params[] = floatval($minDistance); }
    if ($maxDistance !== null && $maxDistance !== '')        { $fields[] = "max_distance_km = ?";  $params[] = floatval($maxDistance); }
    if ($baseFee !== null && $baseFee !== '')               { $fields[] = "base_fee = ?";         $params[] = floatval($baseFee); }
    if ($nearbyExtraFee !== null && $nearbyExtraFee !== '') { $fields[] = "nearby_extra_fee = ?"; $params[] = floatval($nearbyExtraFee); }
    if ($farExtraFee !== null && $farExtraFee !== '')       { $fields[] = "far_extra_fee = ?";    $params[] = floatval($farExtraFee); }
    if ($estDeliveryTime !== null && $estDeliveryTime !== '') { $fields[] = "estimated_delivery_time = ?"; $params[] = $estDeliveryTime; }
    
    if (empty($fields)) {
        sendResponse(STATUS_SUCCESS, null, "No changes provided");
    }
    
    // Validate distance logic if both provided
    if ($minDistance !== null && $minDistance !== '' && $maxDistance !== null && $maxDistance !== '') {
        if (floatval($maxDistance) <= floatval($minDistance)) {
            sendResponse(STATUS_FAIL, null, "Max distance must be greater than min distance");
        }
    }
    
    $params[] = $zoneId;
    
    $updateStmt = $conn->prepare("UPDATE delivery_zones SET " . implode(', ', $fields) . " WHERE id = ?");
    $updateStmt->execute($params);
    
    $selectStmt = $conn->prepare("SELECT * FROM delivery_zones WHERE id = ?");
    $selectStmt->execute([$zoneId]);
    $updated = $selectStmt->fetch(PDO::FETCH_ASSOC);
    
    logActivity($conn, $user['user_id'], 'update', 'delivery_zone', $zoneId, "Updated delivery zone: {$updated['name']}");
    
    sendResponse(STATUS_SUCCESS, ['delivery_zone' => $updated], "Delivery zone updated successfully");
    
} catch (PDOException $e) {
    error_log("Update delivery zone error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to update delivery zone");
}
?>
