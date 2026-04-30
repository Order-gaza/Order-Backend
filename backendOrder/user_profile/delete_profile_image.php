<?php
/**
 * User App - Delete Profile Image
 * POST: /user_profile/delete_profile_image.php
 * Headers: Authorization: Bearer <token>
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

    $stmt = $conn->prepare("SELECT profile_image_url FROM customer_profiles WHERE user_id = ?");
    $stmt->execute([$customer['customer_id']]);
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$profile || empty($profile['profile_image_url'])) {
        sendResponse(STATUS_FAIL, null, "No profile image to delete");
    }

    $filePath = PROFILES_DIR . basename($profile['profile_image_url']);
    if (file_exists($filePath)) unlink($filePath);

    $conn->prepare("UPDATE customer_profiles SET profile_image_url = NULL, updated_at = NOW() WHERE user_id = ?")
         ->execute([$customer['customer_id']]);

    sendResponse(STATUS_SUCCESS, null, "Profile image deleted");

} catch (PDOException $e) {
    error_log("Delete profile image error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to delete image");
}
?>
