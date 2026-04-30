<?php
/**
 * User App - Get Vendor By ID (Detail Page)
 * GET: /user_vendors/get_by_id.php?id=<uuid>
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
    $vendorId = isset($_GET['id']) ? trim($_GET['id']) : '';
    if (empty($vendorId)) {
        sendResponse(STATUS_FAIL, null, "Vendor ID is required");
    }

    $stmt = $conn->prepare("
        SELECT id, name, description, logo_url, cover_url, type, phone,
            address, latitude, longitude, rating, total_ratings, 
            min_order_amount, estimated_delivery_time, operating_hours, is_open,
            opening_time, closing_time,
            CASE
                WHEN is_open = 0 THEN 0
                WHEN opening_time IS NULL OR closing_time IS NULL THEN 1
                WHEN opening_time < closing_time THEN
                    IF(CURTIME() >= opening_time AND CURTIME() < closing_time, 1, 0)
                ELSE
                    IF(CURTIME() >= opening_time OR CURTIME() < closing_time, 1, 0)
            END AS is_currently_open
        FROM vendors WHERE id = ? AND status = 'approved'
    ");
    $stmt->execute([$vendorId]);
    $vendor = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$vendor) {
        sendResponse(STATUS_FAIL, null, "Vendor not found");
    }

    // Get categories with items count
    $catStmt = $conn->prepare("
        SELECT mc.id, mc.name, mc.sort_order,
            (SELECT COUNT(*) FROM menu_items mi WHERE mi.category_id = mc.id AND mi.is_available = 1) as items_count
        FROM menu_categories mc 
        WHERE mc.vendor_id = ? AND mc.is_active = 1
        ORDER BY mc.sort_order ASC
    ");
    $catStmt->execute([$vendorId]);
    $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

    sendResponse(STATUS_SUCCESS, [
        'vendor' => $vendor,
        'categories' => $categories
    ], "Vendor retrieved");

} catch (PDOException $e) {
    error_log("Get vendor error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve vendor");
}
?>
