<?php
/**
 * User App - Set Default Address
 * POST: /user_addresses/set_default.php
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

    $conn->beginTransaction();
    try {
        // Unset all defaults
        $conn->prepare("UPDATE customer_addresses SET is_default = 0 WHERE user_id = ?")
             ->execute([$customer['customer_id']]);

        // Set new default
        $conn->prepare("UPDATE customer_addresses SET is_default = 1, updated_at = NOW() WHERE id = ?")
             ->execute([$addressId]);

        $conn->commit();
        sendResponse(STATUS_SUCCESS, ['address_id' => $addressId], "Default address set");

    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }

} catch (PDOException $e) {
    error_log("Set default address error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to set default address");
}
?>
