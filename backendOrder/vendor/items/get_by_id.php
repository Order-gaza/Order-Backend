<?php
/**
 * Vendor - Get Menu Item By ID
 * GET: /vendor/items/get_by_id.php?id=<uuid>
 * Headers: Authorization: Bearer <token>
 */

include "../../config/database.php";
include "../../config/constants.php";
include "../../core/functions.php";
include "../../core/middleware.php";

if (!isRequestMethod('GET') && !isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    $user = requireRole($conn, [ROLE_VENDOR]);
    
    $vendorStmt = $conn->prepare("SELECT id FROM vendors WHERE user_id = ?");
    $vendorStmt->execute([$user['user_id']]);
    $vendor = $vendorStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$vendor) {
        sendResponse(STATUS_FAIL, null, "Vendor profile not found");
    }
    
    $itemId = isset($_GET['id']) ? trim($_GET['id']) : filterRequest("id");
    if (empty($itemId)) {
        sendResponse(STATUS_FAIL, null, "Item ID is required");
    }
    
    $stmt = $conn->prepare("
        SELECT mi.*, mc.name AS category_name 
        FROM menu_items mi
        LEFT JOIN menu_categories mc ON mi.category_id = mc.id
        WHERE mi.id = ? AND mi.vendor_id = ?
    ");
    $stmt->execute([$itemId, $vendor['id']]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$item) {
        sendResponse(STATUS_FAIL, null, "Menu item not found");
    }
    
    sendResponse(STATUS_SUCCESS, ['item' => $item], "Menu item retrieved");
    
} catch (PDOException $e) {
    error_log("Get menu item error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve menu item");
}
?>
