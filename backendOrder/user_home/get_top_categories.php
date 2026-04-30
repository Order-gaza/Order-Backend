<?php
/**
 * User App - Get Top Categories (unified via global_categories)
 * GET: /user_home/get_top_categories.php?limit=10
 * No auth required — public endpoint
 * 
 * MODIFIED: Groups by global_category_id so each category appears ONCE.
 * Returns icon_name and name_ar for proper display.
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
        SELECT
            gc.id,
            gc.name,
            gc.name_ar,
            gc.icon_name,
            COUNT(DISTINCT mc.vendor_id) AS vendors_count,
            COUNT(DISTINCT mi.id) AS items_count
        FROM global_categories gc
        JOIN menu_categories mc ON mc.global_category_id = gc.id AND mc.is_active = 1
        JOIN vendors v ON mc.vendor_id = v.id AND v.status = 'approved'
        LEFT JOIN menu_items mi ON mi.category_id = mc.id AND mi.is_available = 1
        WHERE gc.is_active = 1
        GROUP BY gc.id, gc.name, gc.name_ar, gc.icon_name
        HAVING items_count > 0
        ORDER BY items_count DESC, gc.sort_order ASC
        LIMIT ?
    ");
    $stmt->execute([$limit]);
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    sendResponse(STATUS_SUCCESS, [
        'categories' => $categories,
        'total' => count($categories)
    ], "Top categories retrieved");

} catch (PDOException $e) {
    error_log("Get top categories error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve categories");
}
?>
