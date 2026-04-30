<?php
/**
 * Super Admin - Update Advertisement
 * POST: /super_admin/advertisements/update.php
 * Headers: Authorization: Bearer <token>
 * Body: ad_id, title?, description?, target_url?, vendor_id?, starts_at?, ends_at?, sort_order?, image_url?
 */

include "../../config/database.php";
include "../../config/constants.php";
include "../../core/functions.php";
include "../../core/middleware.php";

if (!isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    $user = requireRole($conn, [ROLE_SUPER_ADMIN]);
    
    $adId = filterRequest("ad_id");
    
    if (empty($adId)) {
        sendResponse(STATUS_FAIL, null, "Advertisement ID is required");
    }
    
    $checkStmt = $conn->prepare("SELECT id FROM advertisements WHERE id = ?");
    $checkStmt->execute([$adId]);
    if (!$checkStmt->fetch()) {
        sendResponse(STATUS_FAIL, null, "Advertisement not found");
    }
    
    $fields = [];
    $params = [];
    
    $title = filterRequest("title");
    $description = filterRequest("description");
    // Accept both target_url and action_url for backward compatibility
    $link = filterRequest("target_url");
    if (empty($link)) {
        $link = filterRequest("action_url");
    }
    $vendorId = filterRequest("vendor_id");
    $startDate = filterRequest("starts_at");
    $endDate = filterRequest("ends_at");
    $sortOrder = filterRequest("sort_order");
    $imageUrl = filterRequest("image_url");
    
    if (!empty($title)) { $fields[] = "title = ?"; $params[] = $title; }
    if ($description !== null && $description !== '') { $fields[] = "description = ?"; $params[] = $description; }
    if ($link !== null && $link !== '') { $fields[] = "target_url = ?"; $params[] = $link; }
    if (!empty($vendorId)) { $fields[] = "vendor_id = ?"; $params[] = $vendorId; }
    if (!empty($startDate)) { $fields[] = "starts_at = ?"; $params[] = $startDate; }
    if (!empty($endDate)) { $fields[] = "ends_at = ?"; $params[] = $endDate; }
    if ($sortOrder !== null && $sortOrder !== '') { $fields[] = "sort_order = ?"; $params[] = intval($sortOrder); }
    if ($imageUrl !== null && $imageUrl !== '') { $fields[] = "image_url = ?"; $params[] = $imageUrl; }
    
    if (empty($fields)) {
        sendResponse(STATUS_SUCCESS, null, "No changes provided");
    }
    
    $params[] = $adId;
    
    $updateStmt = $conn->prepare("UPDATE advertisements SET " . implode(', ', $fields) . " WHERE id = ?");
    $updateStmt->execute($params);
    
    // Fetch updated
    $selectStmt = $conn->prepare("SELECT a.*, v.name as vendor_name FROM advertisements a LEFT JOIN vendors v ON a.vendor_id = v.id WHERE a.id = ?");
    $selectStmt->execute([$adId]);
    $updated = $selectStmt->fetch(PDO::FETCH_ASSOC);
    
    logActivity($conn, $user['user_id'], 'update', 'advertisement', $adId, "Updated advertisement: {$updated['title']}");
    
    sendResponse(STATUS_SUCCESS, ['advertisement' => $updated], "Advertisement updated successfully");
    
} catch (PDOException $e) {
    error_log("Update advertisement error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to update advertisement");
}
?>
