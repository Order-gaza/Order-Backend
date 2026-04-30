<?php
/**
 * User App - Get All Vendors
 * GET: /user_vendors/get_all.php?page=1&limit=20
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
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $limit = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : ITEMS_PER_PAGE;
    $offset = ($page - 1) * $limit;

    $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM vendors WHERE status = 'approved'");
    $countStmt->execute();
    $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

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
        WHERE status = 'approved'
        ORDER BY is_currently_open DESC, rating DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->execute([$limit, $offset]);
    $vendors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalPages = ceil($total / $limit);

    sendResponse(STATUS_SUCCESS, [
        'vendors' => $vendors,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $limit,
            'total_items' => (int)$total,
            'total_pages' => $totalPages,
            'has_next' => $page < $totalPages,
            'has_prev' => $page > 1
        ]
    ], "Vendors retrieved");

} catch (PDOException $e) {
    error_log("Get vendors error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve vendors");
}
?>
