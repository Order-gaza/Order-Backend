<?php
/**
 * User App - Clear Cart
 * POST: /user_cart/clear_cart.php
 * Headers: Authorization: Bearer <token>
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

    $conn->prepare("DELETE FROM cart_items WHERE user_id = ?")->execute([$customer['customer_id']]);

    sendResponse(STATUS_SUCCESS, null, "Cart cleared");

} catch (PDOException $e) {
    error_log("Clear cart error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to clear cart");
}
?>
