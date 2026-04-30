<?php
/**
 * User App - Add Address
 * POST: /user_addresses/add_address.php
 * Headers: Authorization: Bearer <token>
 * Body: city, neighborhood, label?, address_line?, latitude?, longitude?, phone?, notes?, is_default?
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

    $city = filterRequest("city");
    $neighborhood = filterRequest("neighborhood");
    $label = filterRequest("label");
    $addressLine = filterRequest("address_line");
    $latitude = filterRequest("latitude");
    $longitude = filterRequest("longitude");
    $phone = filterRequest("phone");
    $notes = filterRequest("notes");
    $isDefault = filterRequest("is_default");

    if (empty($city) || empty($neighborhood)) {
        sendResponse(STATUS_FAIL, null, "City and neighborhood are required");
    }

    // Auto-generate label if not provided
    if (empty($label)) {
        $label = $city . ' - ' . $neighborhood;
    }
    // Auto-generate address_line if not provided
    if (empty($addressLine)) {
        $addressLine = $neighborhood . ', ' . $city;
    }

    $conn->beginTransaction();
    try {
        // If setting as default, unset other defaults
        if ($isDefault) {
            $conn->prepare("UPDATE customer_addresses SET is_default = 0 WHERE user_id = ?")
                 ->execute([$customer['customer_id']]);
        }

        $insertStmt = $conn->prepare("
            INSERT INTO customer_addresses 
            (user_id, city, neighborhood, label, address_line, latitude, longitude, phone, notes, is_default, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        $insertStmt->execute([
            $customer['customer_id'],
            $city,
            $neighborhood,
            $label,
            $addressLine,
            $latitude ? floatval($latitude) : null,
            $longitude ? floatval($longitude) : null,
            $phone ?: null,
            $notes ?: null,
            $isDefault ? 1 : 0
        ]);

        $conn->commit();

        $addrId = $conn->lastInsertId();
        $selectStmt = $conn->prepare("SELECT * FROM customer_addresses WHERE id = ?");
        $selectStmt->execute([$addrId]);
        $address = $selectStmt->fetch(PDO::FETCH_ASSOC);

        sendResponse(STATUS_SUCCESS, ['address' => $address], "Address added");

    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }

} catch (PDOException $e) {
    error_log("Add address error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to add address");
}
?>
