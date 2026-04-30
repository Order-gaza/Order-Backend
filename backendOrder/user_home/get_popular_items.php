<?php
/**
 * User App - Get Popular Items (Featured Items)
 * GET: /user_home/get_popular_items.php?limit=10
 * No auth required — public endpoint
 *
 * Returns items in two priority tiers:
 * 1. ALL items with a discount — always included regardless of limit
 * 2. Additional non-discounted popular items to fill up to the limit
 * Vendor diversity: max 2 non-discounted items per vendor
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

    // ── Tier 1: ALL discounted items (always shown, no limit) ──
    $discountStmt = $conn->prepare("
        SELECT
            mi.id, mi.name, mi.name_ar, mi.description, mi.description_ar,
            mi.base_price, mi.discount_price, mi.image_url, mi.is_available,
            mi.preparation_time,
            v.id AS vendor_id, v.name AS vendor_name, v.logo_url AS vendor_logo,
            v.rating AS vendor_rating,
            1 AS has_discount
        FROM menu_items mi
        JOIN vendors v ON mi.vendor_id = v.id
        WHERE mi.is_available = 1 
          AND v.status = 'approved'
          AND mi.discount_price IS NOT NULL 
          AND mi.discount_price > 0
          AND mi.discount_price < mi.base_price
        ORDER BY (mi.base_price - mi.discount_price) DESC, v.rating DESC
    ");
    $discountStmt->execute();
    $discountedItems = $discountStmt->fetchAll(PDO::FETCH_ASSOC);

    // Track vendor IDs from discounted items
    $usedIds = [];
    $vendorCountsFromDiscount = [];
    foreach ($discountedItems as $item) {
        $usedIds[] = $item['id'];
        $vid = $item['vendor_id'];
        $vendorCountsFromDiscount[$vid] = ($vendorCountsFromDiscount[$vid] ?? 0) + 1;
    }

    // ── Tier 2: Non-discounted popular items to fill remaining slots ──
    $remaining = max(0, $limit - count($discountedItems));

    $regularItems = [];
    if ($remaining > 0) {
        $regularStmt = $conn->prepare("
            SELECT
                mi.id, mi.name, mi.name_ar, mi.description, mi.description_ar,
                mi.base_price, mi.discount_price, mi.image_url, mi.is_available,
                mi.preparation_time,
                v.id AS vendor_id, v.name AS vendor_name, v.logo_url AS vendor_logo,
                v.rating AS vendor_rating,
                0 AS has_discount,
                COUNT(oi.id) AS order_count
            FROM menu_items mi
            JOIN vendors v ON mi.vendor_id = v.id
            LEFT JOIN order_items oi ON oi.menu_item_id = mi.id
            WHERE mi.is_available = 1 
              AND v.status = 'approved'
              AND (mi.discount_price IS NULL OR mi.discount_price = 0 OR mi.discount_price >= mi.base_price)
            GROUP BY
                mi.id, mi.name, mi.name_ar, mi.description, mi.description_ar,
                mi.base_price, mi.discount_price, mi.image_url, mi.is_available,
                mi.preparation_time,
                v.id, v.name, v.logo_url, v.rating
            ORDER BY order_count DESC, v.rating DESC, mi.name ASC
            LIMIT ?
        ");
        $fetchLimit = min(80, $remaining * 4);
        $regularStmt->execute([$fetchLimit]);
        $allRegular = $regularStmt->fetchAll(PDO::FETCH_ASSOC);

        // Apply vendor diversity: max 2 items per vendor (combined with discount items)
        $maxPerVendor = 2;
        $vendorCounts = $vendorCountsFromDiscount;

        foreach ($allRegular as $item) {
            if (in_array($item['id'], $usedIds)) continue;
            $vid = $item['vendor_id'];
            $count = $vendorCounts[$vid] ?? 0;
            if ($count < $maxPerVendor) {
                $regularItems[] = $item;
                $vendorCounts[$vid] = $count + 1;
                if (count($regularItems) >= $remaining) break;
            }
        }
    }

    // Merge: discounted first, then regular
    $items = array_merge($discountedItems, $regularItems);

    sendResponse(STATUS_SUCCESS, [
        'items' => $items,
        'total' => count($items),
        'discounted_count' => count($discountedItems)
    ], "Popular items retrieved");

} catch (PDOException $e) {
    error_log("Get popular items error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve items");
}
?>
