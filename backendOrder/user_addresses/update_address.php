<?php
/**
 * User App - Update Address
 * POST: /user_addresses/update_address.php
 * Headers: Authorization: Bearer <token>
 * Body: address_id, city?, neighborhood?, label?, address_line?, latitude?, longitude?, phone?, notes?
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

    $fields = [];
    $params = [];

    $city = filterRequest("city");
    $neighborhood = filterRequest("neighborhood");
    $label = filterRequest("label");
    $addressLine = filterRequest("address_line");
    $latitude = filterRequest("latitude");
    $longitude = filterRequest("longitude");
    $phone = filterRequest("phone");
    $notes = filterRequest("notes");

    if (!empty($city)) { $fields[] = "city = ?"; $params[] = $city; }
    if (!empty($neighborhood)) { $fields[] = "neighborhood = ?"; $params[] = $neighborhood; }
    if (!empty($label)) { $fields[] = "label = ?"; $params[] = $label; }
    if (!empty($addressLine)) { $fields[] = "address_line = ?"; $params[] = $addressLine; }
    if ($latitude !== null && $latitude !== '') { $fields[] = "latitude = ?"; $params[] = floatval($latitude); }
    if ($longitude !== null && $longitude !== '') { $fields[] = "longitude = ?"; $params[] = floatval($longitude); }
    if ($phone !== null) { $fields[] = "phone = ?"; $params[] = $phone ?: null; }
    if ($notes !== null) { $fields[] = "notes = ?"; $params[] = $notes ?: null; }

    if (empty($fields)) {
        sendResponse(STATUS_SUCCESS, null, "No changes provided");
    }

    $fields[] = "updated_at = NOW()";
    $params[] = $addressId;

    $conn->prepare("UPDATE customer_addresses SET " . implode(', ', $fields) . " WHERE id = ?")->execute($params);

    $selectStmt = $conn->prepare("SELECT * FROM customer_addresses WHERE id = ?");
    $selectStmt->execute([$addressId]);

    sendResponse(STATUS_SUCCESS, ['address' => $selectStmt->fetch(PDO::FETCH_ASSOC)], "Address updated");

} catch (PDOException $e) {
    error_log("Update address error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to update address");
}
?>
