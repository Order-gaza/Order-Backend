<?php
/**
 * User App - Get All Favorites
 * GET: /user_favorites/get_all.php?type=vendor|item (optional filter)
 * Headers: Authorization: Bearer <token>
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";
include "../core/middleware.php";

handleCorsHeaders();

if (!isRequestMethod('GET') && !isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    $customer = verifyCustomerAuth($conn);
    $userId = $customer['customer_id'];

    $typeFilter = isset($_GET['type']) ? trim($_GET['type']) : '';

    // Get favorite vendors
    $vendors = [];
    if (empty($typeFilter) || $typeFilter === 'vendor') {
        $vStmt = $conn->prepare("
            SELECT v.id, v.name, v.logo_url, v.cover_url, v.type, v.rating, 
                v.total_ratings, v.is_open, v.estimated_delivery_time, 
                v.min_order_amount, v.address, v.opening_time, v.closing_time,
                CASE
                    WHEN v.is_open = 0 THEN 0
                    WHEN v.opening_time IS NULL OR v.closing_time IS NULL THEN 1
                    WHEN v.opening_time < v.closing_time THEN
                        IF(CURTIME() >= v.opening_time AND CURTIME() < v.closing_time, 1, 0)
                    ELSE
                        IF(CURTIME() >= v.opening_time OR CURTIME() < v.closing_time, 1, 0)
                END AS is_currently_open,
                f.created_at AS favorited_at
            FROM favorites f
            JOIN vendors v ON f.target_id = v.id
            WHERE f.user_id = ? AND f.type = 'vendor' AND v.status = 'approved'
            ORDER BY f.created_at DESC
        ");
        $vStmt->execute([$userId]);
        $vendors = $vStmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get favorite items
    $items = [];
    if (empty($typeFilter) || $typeFilter === 'item') {
        $iStmt = $conn->prepare("
            SELECT mi.id, mi.name, mi.description, mi.base_price, mi.discount_price,
                mi.image_url, mi.is_available,
                v.id AS vendor_id, v.name AS vendor_name, v.logo_url AS vendor_logo, v.is_open,
                v.opening_time, v.closing_time,
                CASE
                    WHEN v.is_open = 0 THEN 0
                    WHEN v.opening_time IS NULL OR v.closing_time IS NULL THEN 1
                    WHEN v.opening_time < v.closing_time THEN
                        IF(CURTIME() >= v.opening_time AND CURTIME() < v.closing_time, 1, 0)
                    ELSE
                        IF(CURTIME() >= v.opening_time OR CURTIME() < v.closing_time, 1, 0)
                END AS is_currently_open,
                f.created_at AS favorited_at
            FROM favorites f
            JOIN menu_items mi ON f.target_id = mi.id
            JOIN vendors v ON mi.vendor_id = v.id
            WHERE f.user_id = ? AND f.type = 'item' AND v.status = 'approved'
            ORDER BY f.created_at DESC
        ");
        $iStmt->execute([$userId]);
        $items = $iStmt->fetchAll(PDO::FETCH_ASSOC);
    }

    sendResponse(STATUS_SUCCESS, [
        'vendors' => $vendors,
        'items' => $items,
        'total_vendors' => count($vendors),
        'total_items' => count($items)
    ], "Favorites retrieved");

} catch (PDOException $e) {
    error_log("Get favorites error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve favorites");
}
?>
