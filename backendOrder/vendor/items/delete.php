<?php
/**
 * Vendor - Delete Menu Item
 * POST: /vendor/items/delete.php
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
    
    $checkStmt = $conn->prepare("SELECT id, name, image_url FROM menu_items WHERE id = ? AND vendor_id = ?");
    $checkStmt->execute([$itemId, $vendor['id']]);
    $item = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$item) {
        sendResponse(STATUS_FAIL, null, "Menu item not found");
    }
    
    // Delete image if exists
    if (!empty($item['image_url'])) {
        $imagePath = ITEMS_DIR . basename($item['image_url']);
        if (file_exists($imagePath)) unlink($imagePath);
    }
    
    $conn->prepare("DELETE FROM menu_items WHERE id = ?")->execute([$itemId]);
    
    logActivity($conn, $user['user_id'], 'delete', 'menu_item', $itemId, "Deleted item: {$item['name']}");
    
    sendResponse(STATUS_SUCCESS, null, "Menu item deleted successfully");
    
} catch (PDOException $e) {
    error_log("Delete menu item error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to delete menu item");
}
?>
