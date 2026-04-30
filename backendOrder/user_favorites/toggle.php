<?php
/**
 * User App - Toggle Favorite (Add/Remove)
 * POST: /user_favorites/toggle.php
 * Body: { type: 'vendor'|'item', target_id: '<uuid>' }
 * Headers: Authorization: Bearer <token>
 * Returns: { is_favorite: true/false }
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
    $userId = $customer['customer_id'];

    $type = isset($_POST['type']) ? trim($_POST['type']) : '';
    $targetId = isset($_POST['target_id']) ? trim($_POST['target_id']) : '';

    if (empty($type) || !in_array($type, ['vendor', 'item'])) {
        sendResponse(STATUS_FAIL, null, "Invalid type. Must be 'vendor' or 'item'");
    }
    if (empty($targetId)) {
        sendResponse(STATUS_FAIL, null, "Target ID is required");
    }

    // Verify target exists
    if ($type === 'vendor') {
        $check = $conn->prepare("SELECT id FROM vendors WHERE id = ? AND status = 'approved'");
    } else {
        $check = $conn->prepare("SELECT id FROM menu_items WHERE id = ?");
    }
    $check->execute([$targetId]);
    if (!$check->fetch()) {
        sendResponse(STATUS_FAIL, null, ucfirst($type) . " not found");
    }

    // Check if already favorited
    $stmt = $conn->prepare("SELECT id FROM favorites WHERE user_id = ? AND type = ? AND target_id = ?");
    $stmt->execute([$userId, $type, $targetId]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        // Remove favorite
        $del = $conn->prepare("DELETE FROM favorites WHERE id = ?");
        $del->execute([$existing['id']]);
        sendResponse(STATUS_SUCCESS, ['is_favorite' => false], "Removed from favorites");
    } else {
        // Add favorite
        $ins = $conn->prepare("INSERT INTO favorites (user_id, type, target_id) VALUES (?, ?, ?)");
        $ins->execute([$userId, $type, $targetId]);
        sendResponse(STATUS_SUCCESS, ['is_favorite' => true], "Added to favorites");
    }

} catch (PDOException $e) {
    error_log("Toggle favorite error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to update favorite");
}
?>
