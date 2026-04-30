<?php
/**
 * Vendor - Update Menu Item
 * POST: /vendor/items/update.php
 * Headers: Authorization: Bearer <token>
 * Body: item_id, name?, name_ar?, description?, description_ar?, price?, category_id?, preparation_time?
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
    
    // Verify ownership
    $checkStmt = $conn->prepare("SELECT id FROM menu_items WHERE id = ? AND vendor_id = ?");
    $checkStmt->execute([$itemId, $vendor['id']]);
    if (!$checkStmt->fetch()) {
        sendResponse(STATUS_FAIL, null, "Menu item not found");
    }
    
    $fields = [];
    $params = [];
    
    $name = filterRequest("name");
    $nameAr = filterRequest("name_ar");
    $description = filterRequest("description");
    $descriptionAr = filterRequest("description_ar");
    $price = filterRequest("price");
    $categoryId = filterRequest("category_id");
    $prepTime = filterRequest("preparation_time");
    $discountPrice = filterRequest("discount_price");
    
    if (!empty($name)) { $fields[] = "name = ?"; $params[] = $name; }
    if ($nameAr !== null && $nameAr !== '') { $fields[] = "name_ar = ?"; $params[] = $nameAr; }
    if ($description !== null && $description !== '') { $fields[] = "description = ?"; $params[] = $description; }
    if ($descriptionAr !== null && $descriptionAr !== '') { $fields[] = "description_ar = ?"; $params[] = $descriptionAr; }
    if ($price !== null && $price !== '' && is_numeric($price)) { $fields[] = "base_price = ?"; $params[] = floatval($price); }
    if (!empty($prepTime)) { $fields[] = "preparation_time = ?"; $params[] = $prepTime; }
    if ($discountPrice !== null && $discountPrice !== '') {
        if (is_numeric($discountPrice)) { $fields[] = "discount_price = ?"; $params[] = floatval($discountPrice); }
    }
    
    if (!empty($categoryId)) {
        // Verify new category belongs to vendor
        $catCheck = $conn->prepare("SELECT id FROM menu_categories WHERE id = ? AND vendor_id = ?");
        $catCheck->execute([$categoryId, $vendor['id']]);
        if (!$catCheck->fetch()) {
            sendResponse(STATUS_FAIL, null, "Category not found");
        }
        $fields[] = "category_id = ?";
        $params[] = $categoryId;
    }
    
    if (empty($fields)) {
        sendResponse(STATUS_SUCCESS, null, "No changes provided");
    }
    
    $fields[] = "updated_at = NOW()";
    $params[] = $itemId;
    
    $conn->prepare("UPDATE menu_items SET " . implode(', ', $fields) . " WHERE id = ?")->execute($params);
    
    $selectStmt = $conn->prepare("SELECT mi.*, mc.name AS category_name FROM menu_items mi LEFT JOIN menu_categories mc ON mi.category_id = mc.id WHERE mi.id = ?");
    $selectStmt->execute([$itemId]);
    $updated = $selectStmt->fetch(PDO::FETCH_ASSOC);
    
    sendResponse(STATUS_SUCCESS, ['item' => $updated], "Menu item updated successfully");
    
} catch (PDOException $e) {
    error_log("Update menu item error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to update menu item");
}
?>
