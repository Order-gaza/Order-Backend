<?php
/**
 * User App - Get Items & Vendors By Global Category
 * GET: /user_items/get_by_global_category.php?category_id=<uuid>
 * No auth required — public endpoint
 * 
 * Returns two lists:
 *   - vendors: all vendors that have items in this category
 *   - items: all available items across all vendors in this category
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

    // Check global category exists
    $catStmt = $conn->prepare("SELECT id, name, name_ar, icon_name FROM global_categories WHERE id = ? AND is_active = 1");
    $catStmt->execute([$categoryId]);
    $category = $catStmt->fetch(PDO::FETCH_ASSOC);

    if (!$category) {
        sendResponse(STATUS_FAIL, null, "Category not found");
    }

    // Get all vendors that have items in this global category
    $vendorStmt = $conn->prepare("
        SELECT DISTINCT
            v.id, v.name, v.logo_url, v.cover_url, v.type,
            v.rating AS rating_avg, v.total_ratings AS rating_count, v.is_open,
            v.opening_time, v.closing_time,
            CASE
                WHEN v.is_open = 0 THEN 0
                WHEN v.opening_time IS NULL OR v.closing_time IS NULL THEN 1
                WHEN v.opening_time < v.closing_time THEN
                    IF(CURTIME() >= v.opening_time AND CURTIME() < v.closing_time, 1, 0)
                ELSE
                    IF(CURTIME() >= v.opening_time OR CURTIME() < v.closing_time, 1, 0)
            END AS is_currently_open,
            COUNT(DISTINCT mi.id) AS items_count
        FROM vendors v
        JOIN menu_categories mc ON mc.vendor_id = v.id AND mc.global_category_id = ? AND mc.is_active = 1
        JOIN menu_items mi ON mi.category_id = mc.id AND mi.is_available = 1
        WHERE v.status = 'approved'
        GROUP BY v.id, v.name, v.logo_url, v.cover_url, v.type, v.rating, v.total_ratings, v.is_open, v.opening_time, v.closing_time
        ORDER BY v.rating DESC, items_count DESC
    ");
    $vendorStmt->execute([$categoryId]);
    $vendors = $vendorStmt->fetchAll(PDO::FETCH_ASSOC);

    // Get all items across all vendors in this global category
    $itemStmt = $conn->prepare("
        SELECT
            mi.id, mi.name, mi.name_ar, mi.description, mi.description_ar,
            mi.base_price, mi.discount_price,
            mi.image_url, mi.preparation_time, mi.is_available,
            v.id AS vendor_id, v.name AS vendor_name, v.logo_url AS vendor_logo
        FROM menu_items mi
        JOIN menu_categories mc ON mi.category_id = mc.id AND mc.global_category_id = ? AND mc.is_active = 1
        JOIN vendors v ON mi.vendor_id = v.id AND v.status = 'approved'
        WHERE mi.is_available = 1
        ORDER BY mi.name ASC
    ");
    $itemStmt->execute([$categoryId]);
    $items = $itemStmt->fetchAll(PDO::FETCH_ASSOC);

    sendResponse(STATUS_SUCCESS, [
        'category' => $category,
        'vendors' => $vendors,
        'items' => $items,
        'vendors_count' => count($vendors),
        'items_count' => count($items)
    ], "Category data retrieved");

} catch (PDOException $e) {
    error_log("Get by global category error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve category data");
}
?>
