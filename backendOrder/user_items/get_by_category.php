<?php
/**
 * User App - Get Items By Category
 * GET: /user_items/get_by_category.php?category_id=<uuid>
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";

handleCorsHeaders();

if (!isRequestMethod('GET') && !isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    $categoryId = isset($_GET['category_id']) ? trim($_GET['category_id']) : '';
    if (empty($categoryId)) {
        sendResponse(STATUS_FAIL, null, "Category ID is required");
    }

    // Get category info
    $catStmt = $conn->prepare("
        SELECT mc.id, mc.name, v.id AS vendor_id, v.name AS vendor_name
        FROM menu_categories mc
        JOIN vendors v ON mc.vendor_id = v.id
        WHERE mc.id = ? AND mc.is_active = 1 AND v.status = 'approved'
    ");
    $catStmt->execute([$categoryId]);
    $category = $catStmt->fetch(PDO::FETCH_ASSOC);

    if (!$category) {
        sendResponse(STATUS_FAIL, null, "Category not found");
    }

    // Use vendor_id from the validated category to scope items correctly
    $itemStmt = $conn->prepare("
        SELECT id, name, name_ar, description, description_ar,
               base_price, discount_price, image_url, preparation_time, is_available
        FROM menu_items
        WHERE category_id = ? AND vendor_id = ? AND is_available = 1
        ORDER BY sort_order ASC, name ASC
    ");
    $itemStmt->execute([$categoryId, $category['vendor_id']]);
    $items = $itemStmt->fetchAll(PDO::FETCH_ASSOC);

    sendResponse(STATUS_SUCCESS, [
        'category' => $category,
        'items' => $items,
        'total' => count($items)
    ], "Items retrieved");

} catch (PDOException $e) {
    error_log("Get items by category error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve items: " . $e->getMessage());
}
?>
