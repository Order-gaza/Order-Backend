<?php
/**
 * User App - Search Items
 * GET: /user_items/search.php?q=<query>
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";

mb_internal_encoding('UTF-8');
handleCorsHeaders();

if (!isRequestMethod('GET') && !isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    $q = isset($_GET['q']) ? trim($_GET['q']) : '';
    // Decode URL-encoded UTF-8 if needed
    if (mb_detect_encoding($q, 'UTF-8', true) === false) {
        $q = utf8_encode($q);
    }
    if (empty($q) || mb_strlen($q) < 1) {
        sendResponse(STATUS_FAIL, null, "Search query must be at least 1 character");
    }

    $searchTerm = "%$q%";

    $stmt = $conn->prepare("
        SELECT mi.id, mi.name, mi.name_ar, mi.description, mi.description_ar,
            mi.base_price, mi.discount_price, mi.image_url, mi.is_available,
            v.id AS vendor_id, v.name AS vendor_name, v.logo_url AS vendor_logo, v.is_open,
            v.opening_time, v.closing_time,
            CASE
                WHEN v.is_open = 0 THEN 0
                WHEN v.opening_time IS NULL OR v.closing_time IS NULL THEN 1
                WHEN v.opening_time < v.closing_time THEN
                    IF(CURTIME() >= v.opening_time AND CURTIME() < v.closing_time, 1, 0)
                ELSE
                    IF(CURTIME() >= v.opening_time OR CURTIME() < v.closing_time, 1, 0)
            END AS is_currently_open
        FROM menu_items mi
        JOIN vendors v ON mi.vendor_id = v.id
        WHERE mi.is_available = 1 AND v.status = 'approved'
        AND (mi.name LIKE ? OR mi.name_ar LIKE ? OR mi.description LIKE ? OR mi.description_ar LIKE ?)
        ORDER BY is_currently_open DESC, mi.name ASC
        LIMIT 50
    ");
    $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    sendResponse(STATUS_SUCCESS, [
        'items' => $items,
        'total' => count($items),
        'query' => $q
    ], "Search results");

} catch (PDOException $e) {
    error_log("Search items error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Search failed");
}
?>
