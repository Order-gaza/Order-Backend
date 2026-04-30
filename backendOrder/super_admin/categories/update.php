<?php
/**
 * Super Admin - Update Global Category
 * POST: /super_admin/categories/update.php
 * Headers: Authorization: Bearer <token>
 * Body: category_id, name?, name_ar?, icon_name?, sort_order?
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

    $categoryId = filterRequest("category_id");
    if (empty($categoryId)) {
        sendResponse(STATUS_FAIL, null, "Category ID is required");
    }

    // Check exists
    $checkStmt = $conn->prepare("SELECT * FROM global_categories WHERE id = ?");
    $checkStmt->execute([$categoryId]);
    $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);
    if (!$existing) {
        sendResponse(STATUS_FAIL, null, "Category not found");
    }

    $name = filterRequest("name");
    $nameAr = filterRequest("name_ar");
    $iconName = filterRequest("icon_name");
    $sortOrder = filterRequest("sort_order");

    // Use existing values as fallback
    $finalName = !empty($name) ? $name : $existing['name'];
    $finalNameAr = !empty($nameAr) ? $nameAr : $existing['name_ar'];
    $finalIcon = !empty($iconName) ? $iconName : $existing['icon_name'];
    $finalSort = ($sortOrder !== '') ? intval($sortOrder) : $existing['sort_order'];

    // Check duplicate name (excluding self)
    if ($finalName !== $existing['name']) {
        $dupStmt = $conn->prepare("SELECT id FROM global_categories WHERE LOWER(name) = LOWER(?) AND id != ?");
        $dupStmt->execute([$finalName, $categoryId]);
        if ($dupStmt->fetch()) {
            sendResponse(STATUS_FAIL, null, "Category with this name already exists");
        }
    }

    $updateStmt = $conn->prepare("
        UPDATE global_categories 
        SET name = ?, name_ar = ?, icon_name = ?, sort_order = ?
        WHERE id = ?
    ");
    $updateStmt->execute([$finalName, $finalNameAr, $finalIcon, $finalSort, $categoryId]);

    $selectStmt = $conn->prepare("SELECT * FROM global_categories WHERE id = ?");
    $selectStmt->execute([$categoryId]);
    $category = $selectStmt->fetch(PDO::FETCH_ASSOC);

    logActivity($conn, $user['user_id'], 'update', 'global_category', $categoryId, "Updated global category: $finalName");

    sendResponse(STATUS_SUCCESS, ['category' => $category], "Category updated successfully");

} catch (PDOException $e) {
    error_log("Update global category error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to update category");
}
?>
