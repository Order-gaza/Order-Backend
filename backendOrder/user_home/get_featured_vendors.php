<?php
/**
 * User App - Get Featured Vendors
 * GET: /user_home/get_featured_vendors.php?limit=10
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
    $limit = isset($_GET['limit']) ? max(1, min(50, intval($_GET['limit']))) : 10;

    $stmt = $conn->prepare("
        SELECT v.id, v.name, v.description, v.logo_url, v.cover_url, 
            v.type, v.address, v.rating, v.total_ratings,
            v.min_order_amount, v.estimated_delivery_time, v.is_open,
            v.opening_time, v.closing_time,
            CASE
                WHEN v.is_open = 0 THEN 0
                WHEN v.opening_time IS NULL OR v.closing_time IS NULL THEN 1
                WHEN v.opening_time < v.closing_time THEN
                    IF(CURTIME() >= v.opening_time AND CURTIME() < v.closing_time, 1, 0)
                ELSE
                    IF(CURTIME() >= v.opening_time OR CURTIME() < v.closing_time, 1, 0)
            END AS is_currently_open,
            (SELECT COUNT(*) FROM menu_items mi WHERE mi.vendor_id = v.id AND mi.is_available = 1) as items_count
        FROM vendors v
        WHERE v.status = 'approved'
        ORDER BY v.rating DESC, v.total_ratings DESC
        LIMIT ?
    ");
    $stmt->execute([$limit]);
    $vendors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    sendResponse(STATUS_SUCCESS, [
        'vendors' => $vendors,
        'total' => count($vendors)
    ], "Featured vendors retrieved");

} catch (PDOException $e) {
    error_log("Get featured vendors error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve vendors");
}
?>
