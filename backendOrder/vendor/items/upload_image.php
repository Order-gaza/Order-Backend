<?php
/**
 * Vendor - Upload Item Image
 * POST: /vendor/items/upload_image.php
 * Headers: Authorization: Bearer <token>
 * Body: item_id, image (FILE)
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
    
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        sendResponse(STATUS_FAIL, null, "Image file is required");
    }
    
    $uploadResult = uploadImage('image', ITEMS_DIR, MAX_IMAGE_SIZE);
    
    if ($uploadResult === "fail") {
        sendResponse(STATUS_FAIL, null, "Image upload failed. Check file type and size.");
    }
    
    // Delete old image
    if (!empty($item['image_url'])) {
        $oldPath = ITEMS_DIR . basename($item['image_url']);
        if (file_exists($oldPath)) unlink($oldPath);
    }
    
    $imageUrl = IMAGE_BASE_URL . 'items/' . $uploadResult;
    $conn->prepare("UPDATE menu_items SET image_url = ?, updated_at = NOW() WHERE id = ?")
         ->execute([$imageUrl, $itemId]);
    
    sendResponse(STATUS_SUCCESS, ['image_url' => $imageUrl], "Item image uploaded successfully");
    
} catch (PDOException $e) {
    error_log("Upload item image error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to upload item image");
}
?>
