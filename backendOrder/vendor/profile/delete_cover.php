<?php
/**
 * Vendor - Delete Cover Image
 * POST: /vendor/profile/delete_cover.php
 * Headers: Authorization: Bearer <token>
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
    
    $vendorStmt = $conn->prepare("SELECT id, cover_url FROM vendors WHERE user_id = ?");
    $vendorStmt->execute([$user['user_id']]);
    $vendor = $vendorStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$vendor) {
        sendResponse(STATUS_FAIL, null, "Vendor profile not found");
    }
    
    if (empty($vendor['cover_url'])) {
        sendResponse(STATUS_FAIL, null, "No cover image to delete");
    }
    
    $filePath = VENDORS_DIR . basename($vendor['cover_url']);
    if (file_exists($filePath)) unlink($filePath);
    
    $conn->prepare("UPDATE vendors SET cover_url = NULL, updated_at = NOW() WHERE id = ?")
         ->execute([$vendor['id']]);
    
    sendResponse(STATUS_SUCCESS, null, "Cover image deleted successfully");
    
} catch (PDOException $e) {
    error_log("Delete cover error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to delete cover image");
}
?>
