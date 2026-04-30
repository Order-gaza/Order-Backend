<?php
/**
 * User App - Remove Item from Cart
 * POST: /user_cart/remove_item.php
 * Headers: Authorization: Bearer <token>
 * Body: cart_item_id
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
    if (empty($cartItemId)) {
        sendResponse(STATUS_FAIL, null, "Cart item ID is required");
    }

    $checkStmt = $conn->prepare("SELECT id FROM cart_items WHERE id = ? AND user_id = ?");
    $checkStmt->execute([$cartItemId, $customer['customer_id']]);
    if (!$checkStmt->fetch()) {
        sendResponse(STATUS_FAIL, null, "Cart item not found");
    }

    $conn->prepare("DELETE FROM cart_items WHERE id = ?")->execute([$cartItemId]);

    sendResponse(STATUS_SUCCESS, null, "Item removed from cart");

} catch (PDOException $e) {
    error_log("Remove cart item error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to remove item");
}
?>
