<?php
/**
 * Super Admin - Toggle Global Category Status
 * POST: /super_admin/categories/toggle_status.php
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
    $user = requireRole($conn, [ROLE_SUPER_ADMIN]);

    $categoryId = filterRequest("category_id");
    if (empty($categoryId)) {
        sendResponse(STATUS_FAIL, null, "Category ID is required");
    }

    $checkStmt = $conn->prepare("SELECT id, name, is_active FROM global_categories WHERE id = ?");
    $checkStmt->execute([$categoryId]);
    $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);
    if (!$existing) {
        sendResponse(STATUS_FAIL, null, "Category not found");
    }

    $newStatus = $existing['is_active'] == 1 ? 0 : 1;
    $updateStmt = $conn->prepare("UPDATE global_categories SET is_active = ? WHERE id = ?");
    $updateStmt->execute([$newStatus, $categoryId]);

    $statusLabel = $newStatus == 1 ? 'activated' : 'deactivated';
    logActivity($conn, $user['user_id'], 'update', 'global_category', $categoryId, "Toggled global category {$existing['name']}: $statusLabel");

    sendResponse(STATUS_SUCCESS, ['is_active' => $newStatus], "Category $statusLabel successfully");

} catch (PDOException $e) {
    error_log("Toggle global category error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to toggle category");
}
?>
