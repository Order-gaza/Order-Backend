<?php
/**
 * User App - Get Vendors By Type
 * GET: /user_vendors/get_by_type.php?type=restaurant|bakery|grocery|pharmacy|other
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
    $type = isset($_GET['type']) ? trim($_GET['type']) : '';
    if (empty($type)) {
        sendResponse(STATUS_FAIL, null, "Vendor type is required");
    }

    $stmt = $conn->prepare("
        SELECT id, name, description, logo_url, cover_url, type,
            address, rating, total_ratings, min_order_amount, 
            estimated_delivery_time, is_open, opening_time, closing_time,
            CASE
                WHEN is_open = 0 THEN 0
                WHEN opening_time IS NULL OR closing_time IS NULL THEN 1
                WHEN opening_time < closing_time THEN
                    IF(CURTIME() >= opening_time AND CURTIME() < closing_time, 1, 0)
                ELSE
                    IF(CURTIME() >= opening_time OR CURTIME() < closing_time, 1, 0)
            END AS is_currently_open
        FROM vendors 
        WHERE status = 'approved' AND type = ?
        ORDER BY is_currently_open DESC, rating DESC
    ");
    $stmt->execute([$type]);
    $vendors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    sendResponse(STATUS_SUCCESS, [
        'vendors' => $vendors,
        'type' => $type,
        'total' => count($vendors)
    ], "Vendors retrieved for type: $type");

} catch (PDOException $e) {
    error_log("Get vendors by type error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve vendors");
}
?>
