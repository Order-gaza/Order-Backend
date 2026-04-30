<?php
/**
 * Vendor - Get Own Profile
 * GET: /vendor/profile/get.php
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
    
    // Get user info
    $userStmt = $conn->prepare("SELECT id, name, email, phone, role, created_at FROM users WHERE id = ?");
    $userStmt->execute([$user['user_id']]);
    $userData = $userStmt->fetch(PDO::FETCH_ASSOC);
    
    // Get vendor profile with computed open status
    $vendorStmt = $conn->prepare("
        SELECT *,
            CASE
                WHEN is_open = 0 THEN 0
                WHEN opening_time IS NULL OR closing_time IS NULL THEN 1
                WHEN opening_time < closing_time THEN
                    IF(CURTIME() >= opening_time AND CURTIME() < closing_time, 1, 0)
                ELSE
                    IF(CURTIME() >= opening_time OR CURTIME() < closing_time, 1, 0)
            END AS is_currently_open
        FROM vendors WHERE user_id = ?
    ");
    $vendorStmt->execute([$user['user_id']]);
    $vendorData = $vendorStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$vendorData) {
        sendResponse(STATUS_FAIL, null, "Vendor profile not found");
    }
    
    // Get counts
    $catCount = $conn->prepare("SELECT COUNT(*) as c FROM menu_categories WHERE vendor_id = ?");
    $catCount->execute([$vendorData['id']]);
    
    $itemCount = $conn->prepare("SELECT COUNT(*) as c FROM menu_items WHERE vendor_id = ?");
    $itemCount->execute([$vendorData['id']]);
    
    sendResponse(STATUS_SUCCESS, [
        'user' => $userData,
        'vendor' => $vendorData,
        'stats' => [
            'categories_count' => (int)$catCount->fetch(PDO::FETCH_ASSOC)['c'],
            'items_count' => (int)$itemCount->fetch(PDO::FETCH_ASSOC)['c']
        ]
    ], "Profile retrieved");
    
} catch (PDOException $e) {
    error_log("Vendor get profile error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve profile");
}
?>
