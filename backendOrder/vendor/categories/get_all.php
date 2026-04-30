<?php
/**
 * Vendor - Get All Own Categories
 * GET: /vendor/categories/get_all.php
 * Headers: Authorization: Bearer <token>
 * 
 * MODIFIED: Now includes global category info (name_ar, icon_name).
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
    $user = requireRole($conn, [ROLE_VENDOR]);

    $vendorStmt = $conn->prepare("SELECT id FROM vendors WHERE user_id = ?");
    $vendorStmt->execute([$user['user_id']]);
    $vendor = $vendorStmt->fetch(PDO::FETCH_ASSOC);

    if (!$vendor) {
        sendResponse(STATUS_FAIL, null, "Vendor profile not found");
    }

    $stmt = $conn->prepare("
        SELECT mc.*,
            gc.name_ar,
            gc.icon_name,
            (SELECT COUNT(*) FROM menu_items mi WHERE mi.category_id = mc.id) as items_count
        FROM menu_categories mc
        LEFT JOIN global_categories gc ON mc.global_category_id = gc.id
        WHERE mc.vendor_id = ?
        ORDER BY mc.sort_order ASC, mc.created_at ASC
    ");
    $stmt->execute([$vendor['id']]);
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    sendResponse(STATUS_SUCCESS, [
        'categories' => $categories,
        'total' => count($categories)
    ], "Categories retrieved");

} catch (PDOException $e) {
    error_log("Get categories error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve categories");
}
?>
