<?php
/**
 * User App - Get All Active Items
 * GET: /user_items/get_all.php?page=1&limit=20&category_id=<uuid>
 * No auth required — public endpoint
 *
 * Returns all available items from all approved vendors.
 * Optional filter by global_category_id.
 * Includes pagination.
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
    $limit = isset($_GET['limit']) ? max(1, min(50, intval($_GET['limit']))) : 20;
    $categoryId = isset($_GET['category_id']) ? trim($_GET['category_id']) : '';
    $offset = ($page - 1) * $limit;

    // Build WHERE clause
    $baseWhere = "mi.is_available = 1 AND v.status = 'approved'";
    $countParams = [];

    if (!empty($categoryId)) {
        $baseWhere .= " AND mc.global_category_id = ?";
        $countParams[] = $categoryId;
    }

    // Count total
    $countStmt = $conn->prepare("
        SELECT COUNT(*) AS total
        FROM menu_items mi
        JOIN vendors v ON mi.vendor_id = v.id
        JOIN menu_categories mc ON mi.category_id = mc.id AND mc.is_active = 1
        WHERE $baseWhere
    ");
    $countStmt->execute($countParams);
    $total = (int)$countStmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Fetch items — discounted items first, then by name
    // Use inline LIMIT values to avoid PDO integer binding issues
    $sql = "
        SELECT
            mi.id, mi.name, mi.name_ar, mi.description, mi.description_ar,
            mi.base_price, mi.discount_price, mi.image_url, mi.is_available,
            mi.preparation_time,
            v.id AS vendor_id, v.name AS vendor_name,
            v.logo_url AS vendor_logo, v.rating AS vendor_rating, v.is_open AS vendor_is_open,
            v.opening_time AS vendor_opening_time, v.closing_time AS vendor_closing_time,
            CASE
                WHEN v.is_open = 0 THEN 0
                WHEN v.opening_time IS NULL OR v.closing_time IS NULL THEN 1
                WHEN v.opening_time < v.closing_time THEN
                    IF(CURTIME() >= v.opening_time AND CURTIME() < v.closing_time, 1, 0)
                ELSE
                    IF(CURTIME() >= v.opening_time OR CURTIME() < v.closing_time, 1, 0)
            END AS vendor_is_currently_open,
            gc.id AS global_category_id, gc.name AS category_name, gc.name_ar AS category_name_ar
        FROM menu_items mi
        JOIN vendors v ON mi.vendor_id = v.id
        JOIN menu_categories mc ON mi.category_id = mc.id AND mc.is_active = 1
        LEFT JOIN global_categories gc ON mc.global_category_id = gc.id
        WHERE $baseWhere
        ORDER BY 
            CASE WHEN mi.discount_price IS NOT NULL AND mi.discount_price > 0 AND mi.discount_price < mi.base_price THEN 0 ELSE 1 END ASC,
            mi.name ASC
        LIMIT $limit OFFSET $offset
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute($countParams); // same params as count (category filter only)
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalPages = $limit > 0 ? (int)ceil($total / $limit) : 1;

    sendResponse(STATUS_SUCCESS, [
        'items' => $items,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $limit,
            'total_items' => $total,
            'total_pages' => $totalPages,
            'has_next' => $page < $totalPages,
            'has_prev' => $page > 1
        ]
    ], "Items retrieved");

} catch (PDOException $e) {
    error_log("Get all items error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve items: " . $e->getMessage());
}
?>
