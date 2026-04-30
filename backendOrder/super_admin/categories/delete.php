<?php
/**
 * Super Admin - Delete Global Category
 * POST: /super_admin/categories/delete.php
 * Headers: Authorization: Bearer <token>
 * Body: category_id
 * 
 * Only deletes if no vendors are subscribed to this category.
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
    $checkStmt = $conn->prepare("SELECT name FROM global_categories WHERE id = ?");
    $checkStmt->execute([$categoryId]);
    $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);
    if (!$existing) {
        sendResponse(STATUS_FAIL, null, "Category not found");
    }

    // Check if any vendors are using this category
    $usageStmt = $conn->prepare("SELECT COUNT(*) as cnt FROM menu_categories WHERE global_category_id = ?");
    $usageStmt->execute([$categoryId]);
    $usage = $usageStmt->fetch(PDO::FETCH_ASSOC);
    if ($usage['cnt'] > 0) {
        sendResponse(STATUS_FAIL, null, "Cannot delete: {$usage['cnt']} vendor(s) are using this category. Remove them first.");
    }

    $deleteStmt = $conn->prepare("DELETE FROM global_categories WHERE id = ?");
    $deleteStmt->execute([$categoryId]);

    logActivity($conn, $user['user_id'], 'delete', 'global_category', $categoryId, "Deleted global category: {$existing['name']}");

    sendResponse(STATUS_SUCCESS, null, "Category deleted successfully");

} catch (PDOException $e) {
    error_log("Delete global category error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to delete category");
}
?>
