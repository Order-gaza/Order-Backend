<?php
/**
 * Super Admin - Get All Global Categories
 * GET: /super_admin/categories/get_all.php
 * Headers: Authorization: Bearer <token>
 */

include "../../config/database.php";
include "../../config/constants.php";
include "../../core/functions.php";
include "../../core/middleware.php";

if (!isRequestMethod('GET') && !isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    $user = requireRole($conn, [ROLE_SUPER_ADMIN]);

    $stmt = $conn->prepare("
        SELECT gc.*,
            (SELECT COUNT(*) FROM menu_categories mc WHERE mc.global_category_id = gc.id) as vendors_count
        FROM global_categories gc
        ORDER BY gc.sort_order ASC, gc.created_at ASC
    ");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    sendResponse(STATUS_SUCCESS, [
        'categories' => $categories,
        'total' => count($categories)
    ], "Global categories retrieved");

} catch (PDOException $e) {
    error_log("Get global categories error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve categories");
}
?>
