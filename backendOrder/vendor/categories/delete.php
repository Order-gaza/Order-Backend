<?php
/**
 * Vendor - Delete Category
 * POST: /vendor/categories/delete.php
 * Headers: Authorization: Bearer <token>
 * Body: category_id
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
    $checkStmt = $conn->prepare("SELECT id, name FROM menu_categories WHERE id = ? AND vendor_id = ?");
    $checkStmt->execute([$categoryId, $vendor['id']]);
    $category = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$category) {
        sendResponse(STATUS_FAIL, null, "Category not found");
    }
    
    // Check if category has items
    $itemCheck = $conn->prepare("SELECT COUNT(*) as c FROM menu_items WHERE category_id = ?");
    $itemCheck->execute([$categoryId]);
    $itemCount = (int)$itemCheck->fetch(PDO::FETCH_ASSOC)['c'];
    
    if ($itemCount > 0) {
        sendResponse(STATUS_FAIL, null, "Cannot delete category with $itemCount items. Remove items first.");
    }
    
    $conn->prepare("DELETE FROM menu_categories WHERE id = ?")->execute([$categoryId]);
    
    logActivity($conn, $user['user_id'], 'delete', 'menu_category', $categoryId,
        "Deleted category: {$category['name']}");
    
    sendResponse(STATUS_SUCCESS, null, "Category deleted successfully");
    
} catch (PDOException $e) {
    error_log("Delete category error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to delete category");
}
?>
