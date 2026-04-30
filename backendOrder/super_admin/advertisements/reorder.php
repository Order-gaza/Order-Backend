<?php
/**
 * Super Admin - Reorder Advertisements
 * POST: /super_admin/advertisements/reorder.php
 * Headers: Authorization: Bearer <token>
 * Body: orders (JSON array of {id, sort_order})
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
    
    $ordersJson = filterRequest("orders", true);
    
    if (empty($ordersJson)) {
        sendResponse(STATUS_FAIL, null, "Orders data is required");
    }
    
    $orders = json_decode($ordersJson, true);
    
    if (!is_array($orders) || empty($orders)) {
        sendResponse(STATUS_FAIL, null, "Invalid orders format");
    }
    
    $conn->beginTransaction();
    
    $updateStmt = $conn->prepare("UPDATE advertisements SET sort_order = ? WHERE id = ?");
    
    foreach ($orders as $order) {
        if (empty($order['id']) || !isset($order['sort_order'])) {
            continue;
        }
        $updateStmt->execute([intval($order['sort_order']), $order['id']]);
    }
    
    $conn->commit();
    
    logActivity($conn, $user['user_id'], 'reorder', 'advertisement', null,
        "Reordered " . count($orders) . " advertisements");
    
    sendResponse(STATUS_SUCCESS, null, "Advertisements reordered successfully");
    
} catch (PDOException $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    error_log("Reorder advertisements error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to reorder advertisements");
}
?>
