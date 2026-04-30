<?php
/**
 * Vendor - Delete Item Image
 * POST: /vendor/items/delete_image.php
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
    
    $checkStmt = $conn->prepare("SELECT id, image_url FROM menu_items WHERE id = ? AND vendor_id = ?");
    $checkStmt->execute([$itemId, $vendor['id']]);
    $item = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$item) {
        sendResponse(STATUS_FAIL, null, "Menu item not found");
    }
    
    if (empty($item['image_url'])) {
        sendResponse(STATUS_FAIL, null, "No image to delete");
    }
    
    $filePath = ITEMS_DIR . basename($item['image_url']);
    if (file_exists($filePath)) unlink($filePath);
    
    $conn->prepare("UPDATE menu_items SET image_url = NULL, updated_at = NOW() WHERE id = ?")
         ->execute([$itemId]);
    
    sendResponse(STATUS_SUCCESS, null, "Item image deleted successfully");
    
} catch (PDOException $e) {
    error_log("Delete item image error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to delete item image");
}
?>
