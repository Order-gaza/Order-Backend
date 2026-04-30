<?php
/**
 * User App - Delete Address
 * POST: /user_addresses/delete_address.php
 * Headers: Authorization: Bearer <token>
 * Body: address_id
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

    $addressId = filterRequest("address_id");
    if (empty($addressId)) {
        sendResponse(STATUS_FAIL, null, "Address ID is required");
    }

    $checkStmt = $conn->prepare("SELECT id FROM customer_addresses WHERE id = ? AND user_id = ?");
    $checkStmt->execute([$addressId, $customer['customer_id']]);
    if (!$checkStmt->fetch()) {
        sendResponse(STATUS_FAIL, null, "Address not found");
    }

    $conn->prepare("DELETE FROM customer_addresses WHERE id = ?")->execute([$addressId]);

    sendResponse(STATUS_SUCCESS, null, "Address deleted");

} catch (PDOException $e) {
    error_log("Delete address error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to delete address");
}
?>
