<?php
/**
 * User App - Update Profile
 * POST: /user_profile/update_profile.php
 * Headers: Authorization: Bearer <token>
 * Body: name?, phone?
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

    $name = filterRequest("name");
    $phone = filterRequest("phone");

    $fields = [];
    $params = [];

    if (!empty($name)) { $fields[] = "name = ?"; $params[] = $name; }
    if (!empty($phone)) {
        $checkStmt = $conn->prepare("SELECT id FROM users WHERE phone = ? AND id != ?");
        $checkStmt->execute([$phone, $customer['customer_id']]);
        if ($checkStmt->fetch()) {
            sendResponse(STATUS_FAIL, null, "Phone number already in use");
        }
        $fields[] = "phone = ?"; $params[] = $phone;
    }

    if (empty($fields)) {
        sendResponse(STATUS_SUCCESS, null, "No changes provided");
    }

    $fields[] = "updated_at = NOW()";
    $params[] = $customer['customer_id'];

    $conn->prepare("UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?")->execute($params);

    $selectStmt = $conn->prepare("SELECT id, name, email, phone FROM users WHERE id = ?");
    $selectStmt->execute([$customer['customer_id']]);
    $updated = $selectStmt->fetch(PDO::FETCH_ASSOC);

    sendResponse(STATUS_SUCCESS, ['user' => $updated], "Profile updated successfully");

} catch (PDOException $e) {
    error_log("Update profile error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to update profile");
}
?>
