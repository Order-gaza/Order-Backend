<?php
/**
 * Vendor - Update Category
 * POST: /vendor/categories/update.php
 * Headers: Authorization: Bearer <token>
 * Body: category_id, name?, description?, sort_order?
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
    if (empty($categoryId)) {
        sendResponse(STATUS_FAIL, null, "Category ID is required");
    }
    
    // Verify ownership
    $checkStmt = $conn->prepare("SELECT id FROM menu_categories WHERE id = ? AND vendor_id = ?");
    $checkStmt->execute([$categoryId, $vendor['id']]);
    if (!$checkStmt->fetch()) {
        sendResponse(STATUS_FAIL, null, "Category not found");
    }
    
    $fields = [];
    $params = [];
    
    $name = filterRequest("name");
    $description = filterRequest("description");
    $sortOrder = filterRequest("sort_order");
    
    if (!empty($name)) { $fields[] = "name = ?"; $params[] = $name; }
    if ($description !== null && $description !== '') { $fields[] = "description = ?"; $params[] = $description; }
    if ($sortOrder !== null && $sortOrder !== '') { $fields[] = "sort_order = ?"; $params[] = intval($sortOrder); }
    
    if (empty($fields)) {
        sendResponse(STATUS_SUCCESS, null, "No changes provided");
    }
    
    $fields[] = "updated_at = NOW()";
    $params[] = $categoryId;
    
    $conn->prepare("UPDATE menu_categories SET " . implode(', ', $fields) . " WHERE id = ?")->execute($params);
    
    $selectStmt = $conn->prepare("SELECT * FROM menu_categories WHERE id = ?");
    $selectStmt->execute([$categoryId]);
    $updated = $selectStmt->fetch(PDO::FETCH_ASSOC);
    
    sendResponse(STATUS_SUCCESS, ['category' => $updated], "Category updated successfully");
    
} catch (PDOException $e) {
    error_log("Update category error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to update category");
}
?>
