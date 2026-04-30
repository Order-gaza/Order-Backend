<?php
/**
 * Vendor - Upload Cover Image
 * POST: /vendor/profile/upload_cover.php
 * Headers: Authorization: Bearer <token>
 * Body: cover (FILE)
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
    
    if (!isset($_FILES['cover']) || $_FILES['cover']['error'] !== UPLOAD_ERR_OK) {
        sendResponse(STATUS_FAIL, null, "Cover image is required");
    }
    
    $uploadResult = uploadImage('cover', VENDORS_DIR, MAX_IMAGE_SIZE);
    
    if ($uploadResult === "fail") {
        sendResponse(STATUS_FAIL, null, "Cover upload failed. Check file type and size.");
    }
    
    if (!empty($vendor['cover_url'])) {
        $oldPath = VENDORS_DIR . basename($vendor['cover_url']);
        if (file_exists($oldPath)) unlink($oldPath);
    }
    
    $coverUrl = IMAGE_BASE_URL . 'vendors/' . $uploadResult;
    $conn->prepare("UPDATE vendors SET cover_url = ?, updated_at = NOW() WHERE id = ?")
         ->execute([$coverUrl, $vendor['id']]);
    
    sendResponse(STATUS_SUCCESS, ['cover_url' => $coverUrl], "Cover image uploaded successfully");
    
} catch (PDOException $e) {
    error_log("Upload cover error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to upload cover image");
}
?>
