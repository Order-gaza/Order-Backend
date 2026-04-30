<?php
/**
 * User App - Update Cart Item Quantity
 * POST: /user_cart/update_item.php
 * Headers: Authorization: Bearer <token>
 * Body: cart_item_id, quantity
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

    $cartItemId = filterRequest("cart_item_id");
    $quantity = filterRequest("quantity");

    if (empty($cartItemId) || $quantity === null || $quantity === '') {
        sendResponse(STATUS_FAIL, null, "Cart item ID and quantity are required");
    }

    $qty = intval($quantity);
    if ($qty < 1) {
        sendResponse(STATUS_FAIL, null, "Quantity must be at least 1");
    }

    // Verify ownership
    $checkStmt = $conn->prepare("SELECT id FROM cart_items WHERE id = ? AND user_id = ?");
    $checkStmt->execute([$cartItemId, $customer['customer_id']]);
    if (!$checkStmt->fetch()) {
        sendResponse(STATUS_FAIL, null, "Cart item not found");
    }

    $conn->prepare("UPDATE cart_items SET quantity = ?, updated_at = NOW() WHERE id = ?")
         ->execute([$qty, $cartItemId]);

    sendResponse(STATUS_SUCCESS, ['cart_item_id' => $cartItemId, 'quantity' => $qty], "Cart updated");

} catch (PDOException $e) {
    error_log("Update cart error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to update cart");
}
?>
