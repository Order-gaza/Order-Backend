<?php
/**
 * Vendor - Add Menu Item
 * POST: /vendor/items/add.php
 * Headers: Authorization: Bearer <token>
 * Body: category_id, name, name_ar, description?, description_ar?, price, preparation_time?, is_available?
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
    
    $categoryId = filterRequest("category_id");
    $name = filterRequest("name");
    $nameAr = filterRequest("name_ar");
    $description = filterRequest("description");
    $descriptionAr = filterRequest("description_ar");
    $price = filterRequest("price");
    $prepTime = filterRequest("preparation_time");
    
    $errors = validateRequired(['category_id', 'name', 'name_ar', 'price']);
    if (!empty($errors)) {
        sendResponse(STATUS_FAIL, null, implode(", ", $errors));
    }
    
    if (!is_numeric($price) || floatval($price) <= 0) {
        sendResponse(STATUS_FAIL, null, "Price must be a positive number");
    }
    
    // Verify category belongs to this vendor
    $catCheck = $conn->prepare("SELECT id FROM menu_categories WHERE id = ? AND vendor_id = ?");
    $catCheck->execute([$categoryId, $vendor['id']]);
    if (!$catCheck->fetch()) {
        sendResponse(STATUS_FAIL, null, "Category not found or doesn't belong to you");
    }
    
    $discountPrice = filterRequest("discount_price");
    
    $insertStmt = $conn->prepare("
        INSERT INTO menu_items 
        (vendor_id, category_id, name, name_ar, description, description_ar, base_price, discount_price, preparation_time, is_available, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW(), NOW())
    ");
    $insertStmt->execute([
        $vendor['id'],
        $categoryId,
        $name,
        $nameAr ?: null,
        $description ?: null,
        $descriptionAr ?: null,
        floatval($price),
        ($discountPrice !== null && $discountPrice !== '' && is_numeric($discountPrice)) ? floatval($discountPrice) : null,
        $prepTime ?: null
    ]);
    
    $selectStmt = $conn->prepare("SELECT * FROM menu_items WHERE vendor_id = ? AND name = ? AND category_id = ? ORDER BY created_at DESC LIMIT 1");
    $selectStmt->execute([$vendor['id'], $name, $categoryId]);
    $item = $selectStmt->fetch(PDO::FETCH_ASSOC);
    
    logActivity($conn, $user['user_id'], 'create', 'menu_item', $item['id'], "Created item: $name");
    
    sendResponse(STATUS_SUCCESS, ['item' => $item], "Menu item created successfully");
    
} catch (PDOException $e) {
    error_log("Add menu item error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to create menu item");
}
?>
