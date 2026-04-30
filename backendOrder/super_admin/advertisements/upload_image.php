<?php
/**
 * Super Admin - Upload Advertisement Image
 * POST: /super_admin/advertisements/upload_image.php
 * Headers: Authorization: Bearer <token>
 * Body: ad_id, image (FILE)
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
    
    $adId = filterRequest("ad_id");
    if (empty($adId)) {
        sendResponse(STATUS_FAIL, null, "Advertisement ID is required");
    }
    
    // Check ad exists
    $checkStmt = $conn->prepare("SELECT id, image_url FROM advertisements WHERE id = ?");
    $checkStmt->execute([$adId]);
    $ad = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$ad) {
        sendResponse(STATUS_FAIL, null, "Advertisement not found");
    }
    
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        sendResponse(STATUS_FAIL, null, "Image file is required");
    }
    
    $uploadResult = uploadImage('image', ADS_DIR, MAX_IMAGE_SIZE);
    
    if ($uploadResult === "fail") {
        sendResponse(STATUS_FAIL, null, "Image upload failed. Check file type and size.");
    }
    
    // Delete old image if exists
    if (!empty($ad['image_url'])) {
        $oldPath = ADS_DIR . basename($ad['image_url']);
        if (file_exists($oldPath)) {
            unlink($oldPath);
        }
    }
    
    $imageUrl = IMAGE_BASE_URL . 'advertisements/' . $uploadResult;
    $conn->prepare("UPDATE advertisements SET image_url = ? WHERE id = ?")
         ->execute([$imageUrl, $adId]);
    
    logActivity($conn, $user['user_id'], 'update', 'advertisement', $adId,
        "Uploaded image for advertisement");
    
    sendResponse(STATUS_SUCCESS, ['image_url' => $imageUrl], "Advertisement image uploaded successfully");
    
} catch (PDOException $e) {
    error_log("Upload ad image error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to upload advertisement image");
}
?>
