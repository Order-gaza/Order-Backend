<?php
/**
 * Vendor - Get Global Categories (to pick from)
 * GET: /vendor/categories/get_global.php
 * Headers: Authorization: Bearer <token>
 * 
 * Returns all active global categories so the vendor can subscribe.
 * Also marks which ones the vendor has already subscribed to.
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

    // Get all active global categories + whether this vendor has subscribed
    $stmt = $conn->prepare("
        SELECT gc.id, gc.name, gc.name_ar, gc.icon_name, gc.sort_order,
            mc.id AS menu_category_id,
            CASE WHEN mc.id IS NOT NULL THEN 1 ELSE 0 END AS is_subscribed
        FROM global_categories gc
        LEFT JOIN menu_categories mc ON mc.global_category_id = gc.id AND mc.vendor_id = ?
        WHERE gc.is_active = 1
        ORDER BY gc.sort_order ASC, gc.name ASC
    ");
    $stmt->execute([$vendor['id']]);
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    sendResponse(STATUS_SUCCESS, [
        'categories' => $categories,
        'total' => count($categories)
    ], "Global categories retrieved");

} catch (PDOException $e) {
    error_log("Get global categories (vendor) error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve categories");
}
?>
