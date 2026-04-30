<?php
/**
 * Super Admin - Delete Advertisement
 * POST: /super_admin/advertisements/delete.php
 * Headers: Authorization: Bearer <token>
 * Body: ad_id
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
    
    $infoStmt = $conn->prepare("SELECT id, title, image_url FROM advertisements WHERE id = ?");
    $infoStmt->execute([$adId]);
    $ad = $infoStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$ad) {
        sendResponse(STATUS_FAIL, null, "Advertisement not found");
    }
    
    // Delete image file if exists
    if (!empty($ad['image_url'])) {
        $imagePath = ADS_DIR . basename($ad['image_url']);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }
    
    $deleteStmt = $conn->prepare("DELETE FROM advertisements WHERE id = ?");
    $deleteStmt->execute([$adId]);
    
    if ($deleteStmt->rowCount() > 0) {
        logActivity($conn, $user['user_id'], 'delete', 'advertisement', $adId,
            "Deleted advertisement: {$ad['title']}");
        sendResponse(STATUS_SUCCESS, null, "Advertisement deleted successfully");
    } else {
        sendResponse(STATUS_FAIL, null, "Failed to delete advertisement");
    }
    
} catch (PDOException $e) {
    error_log("Delete advertisement error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to delete advertisement");
}
?>
