<?php
/**
 * Vendor - Reorder Categories
 * POST: /vendor/categories/reorder.php
 * Headers: Authorization: Bearer <token>
 * Body: order (JSON array of {id, sort_order})
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
    
    $vendorStmt = $conn->prepare("SELECT id FROM vendors WHERE user_id = ?");
    $vendorStmt->execute([$user['user_id']]);
    $vendor = $vendorStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$vendor) {
        sendResponse(STATUS_FAIL, null, "Vendor profile not found");
    }
    
    $orderData = filterRequest("order");
    if (empty($orderData)) {
        sendResponse(STATUS_FAIL, null, "Order data is required");
    }
    
    $items = json_decode($orderData, true);
    if (!is_array($items) || empty($items)) {
        sendResponse(STATUS_FAIL, null, "Invalid order data format");
    }
    
    $conn->beginTransaction();
    try {
        $updateStmt = $conn->prepare("UPDATE menu_categories SET sort_order = ? WHERE id = ? AND vendor_id = ?");
        foreach ($items as $item) {
            if (!isset($item['id']) || !isset($item['sort_order'])) {
                $conn->rollBack();
                sendResponse(STATUS_FAIL, null, "Each item must have 'id' and 'sort_order'");
            }
            $updateStmt->execute([intval($item['sort_order']), $item['id'], $vendor['id']]);
        }
        $conn->commit();
        
        sendResponse(STATUS_SUCCESS, ['updated_count' => count($items)], "Categories reordered successfully");
    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }
    
} catch (PDOException $e) {
    error_log("Reorder categories error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to reorder categories");
}
?>
