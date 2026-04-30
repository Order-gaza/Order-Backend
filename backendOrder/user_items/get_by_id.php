<?php
/**
 * User App - Get Item By ID
 * GET: /user_items/get_by_id.php?id=<uuid>
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
    $itemId = isset($_GET['id']) ? trim($_GET['id']) : '';
    if (empty($itemId)) {
        sendResponse(STATUS_FAIL, null, "Item ID is required");
    }

    $stmt = $conn->prepare("
        SELECT mi.*, mc.name AS category_name,
            v.id AS vendor_id, v.name AS vendor_name, v.logo_url AS vendor_logo, v.is_open,
            v.opening_time AS vendor_opening_time, v.closing_time AS vendor_closing_time,
            CASE
                WHEN v.is_open = 0 THEN 0
                WHEN v.opening_time IS NULL OR v.closing_time IS NULL THEN 1
                WHEN v.opening_time < v.closing_time THEN
                    IF(CURTIME() >= v.opening_time AND CURTIME() < v.closing_time, 1, 0)
                ELSE
                    IF(CURTIME() >= v.opening_time OR CURTIME() < v.closing_time, 1, 0)
            END AS is_currently_open
        FROM menu_items mi
        JOIN menu_categories mc ON mi.category_id = mc.id
        JOIN vendors v ON mi.vendor_id = v.id
        WHERE mi.id = ? AND v.status = 'approved'
    ");
    $stmt->execute([$itemId]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$item) {
        sendResponse(STATUS_FAIL, null, "Item not found");
    }

    sendResponse(STATUS_SUCCESS, ['item' => $item], "Item retrieved");

} catch (PDOException $e) {
    error_log("Get item error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve item");
}
?>
