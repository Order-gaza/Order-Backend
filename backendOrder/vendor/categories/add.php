<?php
/**
 * Vendor - Add Menu Category (Subscribe to Global Category)
 * POST: /vendor/categories/add.php
 * Headers: Authorization: Bearer <token>
 * Body: global_category_id
 * 
 * MODIFIED: Instead of free text name, vendor picks a global category.
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

    $globalCategoryId = filterRequest("global_category_id");

    if (empty($globalCategoryId)) {
        sendResponse(STATUS_FAIL, null, "Global category ID is required");
    }

    // Verify global category exists and is active
    $gcStmt = $conn->prepare("SELECT id, name FROM global_categories WHERE id = ? AND is_active = 1");
    $gcStmt->execute([$globalCategoryId]);
    $globalCat = $gcStmt->fetch(PDO::FETCH_ASSOC);

    if (!$globalCat) {
        sendResponse(STATUS_FAIL, null, "Category not found or inactive");
    }

    // Check if already subscribed
    $checkStmt = $conn->prepare("SELECT id FROM menu_categories WHERE vendor_id = ? AND global_category_id = ?");
    $checkStmt->execute([$vendor['id'], $globalCategoryId]);
    if ($checkStmt->fetch()) {
        sendResponse(STATUS_FAIL, null, "You are already subscribed to this category");
    }

    // Get max sort order for this vendor
    $sortStmt = $conn->prepare("SELECT COALESCE(MAX(sort_order), 0) + 1 AS next_sort FROM menu_categories WHERE vendor_id = ?");
    $sortStmt->execute([$vendor['id']]);
    $nextSort = $sortStmt->fetch(PDO::FETCH_ASSOC)['next_sort'];

    // Insert subscription — use global category name as the local name
    $insertStmt = $conn->prepare("
        INSERT INTO menu_categories (vendor_id, global_category_id, name, sort_order, is_active, created_at)
        VALUES (?, ?, ?, ?, 1, NOW())
    ");
    $insertStmt->execute([
        $vendor['id'],
        $globalCategoryId,
        $globalCat['name'],
        $nextSort
    ]);

    // Fetch the created record
    $selectStmt = $conn->prepare("
        SELECT mc.*, gc.name_ar, gc.icon_name
        FROM menu_categories mc
        JOIN global_categories gc ON mc.global_category_id = gc.id
        WHERE mc.vendor_id = ? AND mc.global_category_id = ?
    ");
    $selectStmt->execute([$vendor['id'], $globalCategoryId]);
    $category = $selectStmt->fetch(PDO::FETCH_ASSOC);

    logActivity($conn, $user['user_id'], 'create', 'menu_category', $category['id'], "Subscribed to category: {$globalCat['name']}");

    sendResponse(STATUS_SUCCESS, ['category' => $category], "Category added successfully");

} catch (PDOException $e) {
    error_log("Add category error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to add category");
}
?>
