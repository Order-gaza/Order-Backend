<?php
/**
 * Super Admin - Add Global Category
 * POST: /super_admin/categories/add.php
 * Headers: Authorization: Bearer <token>
 * Body: name, name_ar?, icon_name?, sort_order?
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

    $name = filterRequest("name");
    $nameAr = filterRequest("name_ar");
    $iconName = filterRequest("icon_name");
    $sortOrder = filterRequest("sort_order");

    if (empty($name)) {
        sendResponse(STATUS_FAIL, null, "Category name is required");
    }

    // Check duplicate
    $checkStmt = $conn->prepare("SELECT id FROM global_categories WHERE LOWER(name) = LOWER(?)");
    $checkStmt->execute([$name]);
    if ($checkStmt->fetch()) {
        sendResponse(STATUS_FAIL, null, "Category with this name already exists");
    }

    $insertStmt = $conn->prepare("
        INSERT INTO global_categories (name, name_ar, icon_name, sort_order, is_active, created_at)
        VALUES (?, ?, ?, ?, 1, NOW())
    ");
    $insertStmt->execute([
        $name,
        $nameAr ?: null,
        $iconName ?: 'category',
        $sortOrder ? intval($sortOrder) : 0
    ]);

    // Fetch the created record
    $selectStmt = $conn->prepare("SELECT * FROM global_categories WHERE name = ? ORDER BY created_at DESC LIMIT 1");
    $selectStmt->execute([$name]);
    $category = $selectStmt->fetch(PDO::FETCH_ASSOC);

    logActivity($conn, $user['user_id'], 'create', 'global_category', $category['id'], "Created global category: $name");

    sendResponse(STATUS_SUCCESS, ['category' => $category], "Category created successfully");

} catch (PDOException $e) {
    error_log("Add global category error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to create category");
}
?>
