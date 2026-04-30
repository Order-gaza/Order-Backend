<?php
/**
 * Vendor - Toggle Category Status
 * POST: /vendor/categories/toggle_status.php
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
    
    $checkStmt = $conn->prepare("SELECT id, name, is_active FROM menu_categories WHERE id = ? AND vendor_id = ?");
    $checkStmt->execute([$categoryId, $vendor['id']]);
    $category = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$category) {
        sendResponse(STATUS_FAIL, null, "Category not found");
    }
    
    $newStatus = $category['is_active'] ? 0 : 1;
    $conn->prepare("UPDATE menu_categories SET is_active = ? WHERE id = ?")
         ->execute([$newStatus, $categoryId]);
    
    sendResponse(STATUS_SUCCESS, [
        'category_id' => $categoryId,
        'is_active' => (bool)$newStatus
    ], "Category " . ($newStatus ? 'activated' : 'deactivated') . " successfully");
    
} catch (PDOException $e) {
    error_log("Toggle category error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to toggle category status");
}
?>
