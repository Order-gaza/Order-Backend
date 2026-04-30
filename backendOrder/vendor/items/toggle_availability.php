<?php
/**
 * Vendor - Toggle Item Availability
 * POST: /vendor/items/toggle_availability.php
 * Headers: Authorization: Bearer <token>
 * Body: item_id
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
    
    $itemId = filterRequest("item_id");
    if (empty($itemId)) {
        sendResponse(STATUS_FAIL, null, "Item ID is required");
    }
    
    $checkStmt = $conn->prepare("SELECT id, name, is_available FROM menu_items WHERE id = ? AND vendor_id = ?");
    $checkStmt->execute([$itemId, $vendor['id']]);
    $item = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$item) {
        sendResponse(STATUS_FAIL, null, "Menu item not found");
    }
    
    $newStatus = $item['is_available'] ? 0 : 1;
    $conn->prepare("UPDATE menu_items SET is_available = ?, updated_at = NOW() WHERE id = ?")
         ->execute([$newStatus, $itemId]);
    
    sendResponse(STATUS_SUCCESS, [
        'item_id' => $itemId,
        'is_available' => (bool)$newStatus
    ], "Item " . ($newStatus ? 'marked available' : 'marked unavailable'));
    
} catch (PDOException $e) {
    error_log("Toggle availability error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to toggle item availability");
}
?>
