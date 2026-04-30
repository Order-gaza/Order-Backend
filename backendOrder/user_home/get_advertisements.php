<?php
/**
 * User App - Get Advertisements (Home Banner/Carousel)
 * GET: /user_home/get_advertisements.php
 * No auth required — public endpoint
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";

handleCorsHeaders();

if (!isRequestMethod('GET') && !isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    $stmt = $conn->prepare("
        SELECT id, title, description, image_url, target_url, vendor_id
        FROM advertisements 
        WHERE is_active = 1 AND starts_at <= NOW() AND ends_at >= NOW()
        ORDER BY sort_order ASC
    ");
    $stmt->execute();
    $ads = $stmt->fetchAll(PDO::FETCH_ASSOC);

    sendResponse(STATUS_SUCCESS, ['advertisements' => $ads], "Advertisements retrieved");

} catch (PDOException $e) {
    error_log("Get ads error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve advertisements");
}
?>
