<?php
/**
 * User App - Search Vendors
 * GET: /user_vendors/search.php?q=<query>
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
        SELECT id, name, description, logo_url, type,
            address, rating, total_ratings, is_open, opening_time, closing_time,
            CASE
                WHEN is_open = 0 THEN 0
                WHEN opening_time IS NULL OR closing_time IS NULL THEN 1
                WHEN opening_time < closing_time THEN
                    IF(CURTIME() >= opening_time AND CURTIME() < closing_time, 1, 0)
                ELSE
                    IF(CURTIME() >= opening_time OR CURTIME() < closing_time, 1, 0)
            END AS is_currently_open
        FROM vendors 
        WHERE status = 'approved' 
        AND (name LIKE ? OR description LIKE ? OR address LIKE ?)
        ORDER BY is_currently_open DESC, rating DESC
        LIMIT 30
    ");
    $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
    $vendors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    sendResponse(STATUS_SUCCESS, [
        'vendors' => $vendors,
        'total' => count($vendors),
        'query' => $q
    ], "Search results");

} catch (PDOException $e) {
    error_log("Search vendors error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Search failed");
}
?>
