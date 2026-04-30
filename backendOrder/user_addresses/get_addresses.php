<?php
/**
 * User App - Get All Addresses
 * GET: /user_addresses/get_addresses.php
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
        SELECT * FROM customer_addresses 
        WHERE user_id = ? 
        ORDER BY is_default DESC, created_at DESC
    ");
    $stmt->execute([$customer['customer_id']]);
    $addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    sendResponse(STATUS_SUCCESS, [
        'addresses' => $addresses,
        'total' => count($addresses)
    ], "Addresses retrieved");

} catch (PDOException $e) {
    error_log("Get addresses error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve addresses");
}
?>
