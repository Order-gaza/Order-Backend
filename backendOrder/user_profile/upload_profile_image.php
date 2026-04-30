<?php
/**
 * User App - Upload Profile Image
 * POST: /user_profile/upload_profile_image.php
 * Headers: Authorization: Bearer <token>
 * Body: image (FILE)
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";
include "../core/middleware.php";

handleCorsHeaders();

if (!isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    $customer = verifyCustomerAuth($conn);

    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        sendResponse(STATUS_FAIL, null, "Image file is required");
    }

    $filename = uploadImage('image', PROFILES_DIR, MAX_PROFILE_SIZE);
    if ($filename === "fail") {
        sendResponse(STATUS_FAIL, null, "Upload failed. Check file size and format.");
    }

    // Delete old image
    $oldStmt = $conn->prepare("SELECT profile_image_url FROM customer_profiles WHERE user_id = ?");
    $oldStmt->execute([$customer['customer_id']]);
    $old = $oldStmt->fetch(PDO::FETCH_ASSOC);
    if ($old && !empty($old['profile_image_url'])) {
        $oldPath = PROFILES_DIR . basename($old['profile_image_url']);
        if (file_exists($oldPath)) unlink($oldPath);
    }

    $imageUrl = IMAGE_BASE_URL . 'profiles/' . $filename;

    // Upsert customer_profiles
    $checkStmt = $conn->prepare("SELECT user_id FROM customer_profiles WHERE user_id = ?");
    $checkStmt->execute([$customer['customer_id']]);
    if ($checkStmt->fetch()) {
        $conn->prepare("UPDATE customer_profiles SET profile_image_url = ?, updated_at = NOW() WHERE user_id = ?")
             ->execute([$imageUrl, $customer['customer_id']]);
    } else {
        $conn->prepare("INSERT INTO customer_profiles (user_id, profile_image_url, created_at, updated_at) VALUES (?, ?, NOW(), NOW())")
             ->execute([$customer['customer_id'], $imageUrl]);
    }

    sendResponse(STATUS_SUCCESS, ['profile_image' => $imageUrl], "Profile image uploaded");

} catch (PDOException $e) {
    error_log("Upload profile image error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to upload image");
}
?>
