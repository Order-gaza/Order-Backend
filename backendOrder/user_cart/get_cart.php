<?php
/**
 * User App - Get Cart
 * GET: /user_cart/get_cart.php
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

    $stmt = $conn->prepare("
        SELECT ci.id AS cart_item_id, ci.quantity, ci.notes,
            mi.id AS item_id, mi.name, mi.description, mi.base_price, mi.discount_price, mi.image_url, mi.is_available,
            v.id AS vendor_id, v.name AS vendor_name, v.logo_url AS vendor_logo, 
            v.min_order_amount, v.is_open, v.opening_time, v.closing_time,
            CASE
                WHEN v.is_open = 0 THEN 0
                WHEN v.opening_time IS NULL OR v.closing_time IS NULL THEN 1
                WHEN v.opening_time < v.closing_time THEN
                    IF(CURTIME() >= v.opening_time AND CURTIME() < v.closing_time, 1, 0)
                ELSE
                    IF(CURTIME() >= v.opening_time OR CURTIME() < v.closing_time, 1, 0)
            END AS is_currently_open
        FROM cart_items ci
        JOIN menu_items mi ON ci.menu_item_id = mi.id
        JOIN vendors v ON mi.vendor_id = v.id
        WHERE ci.user_id = ?
        ORDER BY ci.created_at ASC
    ");
    $stmt->execute([$customer['customer_id']]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate totals and group by vendor
    $subtotal = 0;
    $vendors_map = [];

    foreach ($items as &$item) {
        $effectivePrice = (!empty($item['discount_price']) && (float)$item['discount_price'] > 0)
            ? (float)$item['discount_price']
            : (float)$item['base_price'];
        $item['effective_price'] = $effectivePrice;
        $item['line_total'] = $effectivePrice * (int)$item['quantity'];
        $item['original_line_total'] = (float)$item['base_price'] * (int)$item['quantity'];
        $subtotal += $item['line_total'];

        $vid = $item['vendor_id'];
        if (!isset($vendors_map[$vid])) {
            $vendors_map[$vid] = [
                'id' => $vid,
                'name' => $item['vendor_name'],
                'logo_url' => $item['vendor_logo'],
                'min_order_amount' => (float)$item['min_order_amount'],
                'is_open' => (bool)$item['is_currently_open'],
                'items' => []
            ];
        }
        $vendors_map[$vid]['items'][] = $item;
    }

    $vendors = array_values($vendors_map);

    // Meets minimum logic: each vendor must meet its own minimum
    $meets_minimum = true;
    foreach ($vendors as $v) {
        $v_subtotal = 0;
        foreach ($v['items'] as $vi) {
            $v_subtotal += $vi['line_total'];
        }
        if ($v_subtotal < $v['min_order_amount']) {
            $meets_minimum = false;
            break;
        }
    }

    sendResponse(STATUS_SUCCESS, [
        'vendors' => $vendors,
        'subtotal' => round($subtotal, 2),
        'items_count' => count($items),
        'meets_minimum' => count($vendors) > 0 ? $meets_minimum : true
    ], "Cart retrieved");

} catch (PDOException $e) {
    error_log("Get cart error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve cart");
}
?>
