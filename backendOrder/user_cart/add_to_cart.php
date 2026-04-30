<?php
/**
 * User App - Add Item to Cart
 * POST: /user_cart/add_to_cart.php
 * Headers: Authorization: Bearer <token>
 * Body: menu_item_id, quantity, notes?
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";
include "../core/middleware.php";

handleCorsHeaders();

if (!isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    $customer = verifyCustomerAuth($conn);

    $menuItemId = filterRequest("menu_item_id");
    $quantity = filterRequest("quantity");
    $notes = filterRequest("notes");

    if (empty($menuItemId) || empty($quantity)) {
        sendResponse(STATUS_FAIL, null, "Item ID and quantity are required");
    }

    $qty = max(1, intval($quantity));

    // Verify item exists and is available
    $itemStmt = $conn->prepare("
        SELECT mi.id, mi.name, mi.base_price, mi.vendor_id, v.name AS vendor_name,
            v.is_open, v.opening_time, v.closing_time,
            CASE
                WHEN v.is_open = 0 THEN 0
                WHEN v.opening_time IS NULL OR v.closing_time IS NULL THEN 1
                WHEN v.opening_time < v.closing_time THEN
                    IF(CURTIME() >= v.opening_time AND CURTIME() < v.closing_time, 1, 0)
                ELSE
                    IF(CURTIME() >= v.opening_time OR CURTIME() < v.closing_time, 1, 0)
            END AS is_currently_open
        FROM menu_items mi JOIN vendors v ON mi.vendor_id = v.id
        WHERE mi.id = ? AND mi.is_available = 1 AND v.status = 'approved'
    ");
    $itemStmt->execute([$menuItemId]);
    $item = $itemStmt->fetch(PDO::FETCH_ASSOC);

    if (!$item) {
        sendResponse(STATUS_FAIL, null, "Item not found or unavailable");
    }

    // Check if vendor is currently open
    if (!$item['is_currently_open']) {
        sendResponse(STATUS_FAIL, null, "This vendor is currently closed. You cannot add items from a closed vendor.");
    }

    // Check if cart has items from a different vendor (REMOVED logic to allow multiple vendors)
    $cartCheck = $conn->prepare("
        SELECT ci.id, mi.vendor_id 
        FROM cart_items ci 
        JOIN menu_items mi ON ci.menu_item_id = mi.id
        WHERE ci.user_id = ? 
        LIMIT 1
    ");
    $cartCheck->execute([$customer['customer_id']]);
    $existingCart = $cartCheck->fetch(PDO::FETCH_ASSOC);


    // Check if item already in cart — update quantity
    $existingItem = $conn->prepare("SELECT id, quantity FROM cart_items WHERE user_id = ? AND menu_item_id = ?");
    $existingItem->execute([$customer['customer_id'], $menuItemId]);
    $existing = $existingItem->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        $newQty = $existing['quantity'] + $qty;
        $conn->prepare("UPDATE cart_items SET quantity = ?, notes = ?, updated_at = NOW() WHERE id = ?")
             ->execute([$newQty, $notes ?: null, $existing['id']]);
    } else {
        $conn->prepare("
            INSERT INTO cart_items (user_id, menu_item_id, quantity, notes, created_at, updated_at)
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ")->execute([$customer['customer_id'], $menuItemId, $qty, $notes ?: null]);
    }

    // Return updated cart count
    $countStmt = $conn->prepare("SELECT COUNT(*) as c, SUM(quantity) as total_qty FROM cart_items WHERE user_id = ?");
    $countStmt->execute([$customer['customer_id']]);
    $cartInfo = $countStmt->fetch(PDO::FETCH_ASSOC);

    sendResponse(STATUS_SUCCESS, [
        'item_name' => $item['name'],
        'quantity' => $qty,
        'cart_items_count' => (int)$cartInfo['c'],
        'cart_total_quantity' => (int)$cartInfo['total_qty']
    ], "Item added to cart");

} catch (PDOException $e) {
    error_log("Add to cart error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to add item");
}
?>
