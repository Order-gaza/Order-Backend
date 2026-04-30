<?php
/**
 * User App - Get Vendor Menu (All Categories + Items)
 * GET: /user_vendors/get_menu.php?vendor_id=<uuid>
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
    $vendorId = isset($_GET['vendor_id']) ? trim($_GET['vendor_id']) : '';
    if (empty($vendorId)) {
        sendResponse(STATUS_FAIL, null, "Vendor ID is required");
    }

    // Verify vendor exists and is approved
    $vendorStmt = $conn->prepare("
        SELECT id, name, is_open, opening_time, closing_time,
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
    $vendorStmt->execute([$vendorId]);
    $vendor = $vendorStmt->fetch(PDO::FETCH_ASSOC);
    if (!$vendor) {
        sendResponse(STATUS_FAIL, null, "Vendor not found");
    }

    // Only fetch categories that have at least one available item
    $catStmt = $conn->prepare("
        SELECT mc.id, mc.name, mc.sort_order,
            gc.name_ar, gc.icon_name
        FROM menu_categories mc
        LEFT JOIN global_categories gc ON mc.global_category_id = gc.id
        WHERE mc.vendor_id = ? AND mc.is_active = 1
          AND EXISTS (
              SELECT 1 FROM menu_items mi 
              WHERE mi.category_id = mc.id 
                AND mi.vendor_id = ? 
                AND mi.is_available = 1
          )
        ORDER BY mc.sort_order ASC
    ");
    $catStmt->execute([$vendorId, $vendorId]);
    $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

    $itemStmt = $conn->prepare("
        SELECT id, category_id, name, name_ar, description, description_ar,
            base_price, discount_price, image_url, 
            preparation_time, is_available, sort_order
        FROM menu_items 
        WHERE vendor_id = ? AND is_available = 1
        ORDER BY sort_order ASC, name ASC
    ");
    $itemStmt->execute([$vendorId]);
    $allItems = $itemStmt->fetchAll(PDO::FETCH_ASSOC);

    // Group items by category
    $menu = [];
    foreach ($categories as $cat) {
        $catItems = array_values(array_filter($allItems, function($item) use ($cat) {
            return $item['category_id'] === $cat['id'];
        }));
        // Double safety: only include if items exist
        if (!empty($catItems)) {
            $menu[] = [
                'category' => $cat,
                'items' => $catItems
            ];
        }
    }

    sendResponse(STATUS_SUCCESS, [
        'vendor' => $vendor,
        'menu' => $menu,
        'total_items' => count($allItems)
    ], "Menu retrieved");

} catch (PDOException $e) {
    error_log("Get menu error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve menu");
}
?>
