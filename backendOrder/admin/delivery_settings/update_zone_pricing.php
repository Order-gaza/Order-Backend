<?php
/**
 * Admin - Update Zone Pricing
 * POST: /admin/delivery_settings/update_zone_pricing.php
 * Headers: Authorization: Bearer <token>
 * Body: route_key (same_zone|north_central|central_south|north_south), fee
 * 
 * Updates delivery zone pricing for the given route.
 * Uses route_key to determine which rows to update:
 *   same_zone     → N→N, C→C, S→S
 *   north_central → N→C, C→N
 *   central_south → C→S, S→C
 *   north_south   → N→S, S→N
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
    
    $routeKey = filterRequest("route_key");
    $fee = filterRequest("fee");
    
    if (empty($routeKey)) {
        sendResponse(STATUS_FAIL, null, "Route key is required");
    }
    if ($fee === null || $fee === '' || !is_numeric($fee)) {
        sendResponse(STATUS_FAIL, null, "Fee must be a valid number");
    }
    if ((float)$fee < 0) {
        sendResponse(STATUS_FAIL, null, "Fee cannot be negative");
    }
    
    $feeVal = floatval($fee);
    
    // Map route_key to zone pairs
    $pairs = [];
    switch ($routeKey) {
        case 'same_zone':
            $pairs = [
                ['north', 'north'],
                ['central', 'central'],
                ['south', 'south'],
            ];
            break;
        case 'north_central':
            $pairs = [
                ['north', 'central'],
                ['central', 'north'],
            ];
            break;
        case 'central_south':
            $pairs = [
                ['central', 'south'],
                ['south', 'central'],
            ];
            break;
        case 'north_south':
            $pairs = [
                ['north', 'south'],
                ['south', 'north'],
            ];
            break;
        default:
            sendResponse(STATUS_FAIL, null, "Invalid route key. Use: same_zone, north_central, central_south, north_south");
    }
    
    $updateStmt = $conn->prepare("
        UPDATE delivery_zone_pricing 
        SET fee = ?, updated_by = ?, updated_at = NOW() 
        WHERE from_zone = ? AND to_zone = ?
    ");
    
    $updated = 0;
    foreach ($pairs as $pair) {
        $updateStmt->execute([$feeVal, $user['user_id'], $pair[0], $pair[1]]);
        $updated += $updateStmt->rowCount();
    }
    
    logActivity($conn, $user['user_id'], 'update', 'delivery_zone_pricing', null,
        "Updated zone pricing: $routeKey = $feeVal");
    
    sendResponse(STATUS_SUCCESS, [
        'route_key' => $routeKey,
        'fee' => $feeVal,
        'rows_updated' => $updated
    ], "Zone pricing updated successfully");
    
} catch (PDOException $e) {
    error_log("Update zone pricing error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to update zone pricing");
}
?>
