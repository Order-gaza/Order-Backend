<?php
/**
 * Vendor - Upload Logo
 * POST: /vendor/profile/upload_logo.php
 * Headers: Authorization: Bearer <token>
 * Body: logo (FILE)
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
    
    $vendorStmt = $conn->prepare("SELECT id, logo_url FROM vendors WHERE user_id = ?");
    $vendorStmt->execute([$user['user_id']]);
    $vendor = $vendorStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$vendor) {
        sendResponse(STATUS_FAIL, null, "Vendor profile not found");
    }
    
    if (!isset($_FILES['logo']) || $_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
        sendResponse(STATUS_FAIL, null, "Logo image is required");
    }
    
    $uploadResult = uploadImage('logo', VENDORS_DIR, MAX_IMAGE_SIZE);
    
    if ($uploadResult === "fail") {
        sendResponse(STATUS_FAIL, null, "Logo upload failed. Check file type and size.");
    }
    
    // Delete old logo
    if (!empty($vendor['logo_url'])) {
        $oldPath = VENDORS_DIR . basename($vendor['logo_url']);
        if (file_exists($oldPath)) unlink($oldPath);
    }
    
    $logoUrl = IMAGE_BASE_URL . 'vendors/' . $uploadResult;
    $conn->prepare("UPDATE vendors SET logo_url = ?, updated_at = NOW() WHERE id = ?")
         ->execute([$logoUrl, $vendor['id']]);
    
    sendResponse(STATUS_SUCCESS, ['logo_url' => $logoUrl], "Logo uploaded successfully");
    
} catch (PDOException $e) {
    error_log("Upload logo error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to upload logo");
}
?>
