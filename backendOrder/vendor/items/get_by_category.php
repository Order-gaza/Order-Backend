<?php
/**
 * Vendor - Get Menu Items By Category
 * GET: /vendor/items/get_by_category.php?category_id=<uuid>
 * Headers: Authorization: Bearer <token>
 */

include "../../config/database.php";
include "../../config/constants.php";
include "../../core/functions.php";
include "../../core/middleware.php";

if (!isRequestMethod('GET') && !isRequestMethod('POST')) {
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
    
    $categoryId = isset($_GET['category_id']) ? trim($_GET['category_id']) : filterRequest("category_id");
    if (empty($categoryId)) {
        sendResponse(STATUS_FAIL, null, "Category ID is required");
    }
    
    // Verify category ownership
    $catCheck = $conn->prepare("SELECT id, name FROM menu_categories WHERE id = ? AND vendor_id = ?");
    $catCheck->execute([$categoryId, $vendor['id']]);
    $category = $catCheck->fetch(PDO::FETCH_ASSOC);
    
    if (!$category) {
        sendResponse(STATUS_FAIL, null, "Category not found");
    }
    
    $stmt = $conn->prepare("SELECT * FROM menu_items WHERE category_id = ? AND vendor_id = ? ORDER BY name ASC");
    $stmt->execute([$categoryId, $vendor['id']]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    sendResponse(STATUS_SUCCESS, [
        'category' => $category,
        'items' => $items,
        'total' => count($items)
    ], "Items retrieved for category");
    
} catch (PDOException $e) {
    error_log("Get items by category error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve items");
}
?>
