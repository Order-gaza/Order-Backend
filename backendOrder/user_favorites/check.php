<?php
/**
 * User App - Check Favorites Status
 * GET: /user_favorites/check.php?type=vendor&ids=id1,id2,id3
 * Headers: Authorization: Bearer <token>
 * Returns map of id => true/false
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
    $userId = $customer['customer_id'];

    $type = isset($_GET['type']) ? trim($_GET['type']) : '';
    $idsRaw = isset($_GET['ids']) ? trim($_GET['ids']) : '';

    if (empty($type) || !in_array($type, ['vendor', 'item'])) {
        sendResponse(STATUS_FAIL, null, "Invalid type");
    }
    if (empty($idsRaw)) {
        sendResponse(STATUS_SUCCESS, ['favorites' => []], "No IDs provided");
    }

    $ids = array_filter(array_map('trim', explode(',', $idsRaw)));
    if (empty($ids)) {
        sendResponse(STATUS_SUCCESS, ['favorites' => []], "No valid IDs");
    }

    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $params = array_merge([$userId, $type], $ids);

    $stmt = $conn->prepare("
        SELECT target_id FROM favorites 
        WHERE user_id = ? AND type = ? AND target_id IN ($placeholders)
    ");
    $stmt->execute($params);
    $favIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Build map
    $result = [];
    foreach ($ids as $id) {
        $result[$id] = in_array($id, $favIds);
    }

    sendResponse(STATUS_SUCCESS, ['favorites' => $result], "Favorites checked");

} catch (PDOException $e) {
    error_log("Check favorites error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to check favorites");
}
?>
