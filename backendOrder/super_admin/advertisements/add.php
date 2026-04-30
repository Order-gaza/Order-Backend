<?php
/**
 * Super Admin - Add Advertisement
 * POST: /super_admin/advertisements/add.php
 * Headers: Authorization: Bearer <token>
 * Body: title, description?, target_url?, vendor_id?, starts_at?, ends_at?, sort_order?, image_url?
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
    
    // Only title is strictly required
    if (empty($title)) {
        sendResponse(STATUS_FAIL, null, "Title is required");
    }
    
    // Default dates if not provided
    if (empty($startDate)) {
        $startDate = date('Y-m-d H:i:s');
    }
    if (empty($endDate)) {
        $endDate = date('Y-m-d H:i:s', strtotime('+30 days'));
    }
    
    // Validate dates
    if (strtotime($endDate) <= strtotime($startDate)) {
        sendResponse(STATUS_FAIL, null, "End date must be after start date");
    }
    
    // Validate vendor_id if provided
    if (!empty($vendorId)) {
        $vendorCheck = $conn->prepare("SELECT id FROM vendors WHERE id = ?");
        $vendorCheck->execute([$vendorId]);
        if (!$vendorCheck->fetch()) {
            sendResponse(STATUS_FAIL, null, "Vendor not found");
        }
    }
    
    // image_url defaults to empty string to satisfy NOT NULL constraint
    if (empty($imageUrl)) {
        $imageUrl = '';
    }
    
    $insertStmt = $conn->prepare("
        INSERT INTO advertisements 
        (title, description, image_url, target_url, vendor_id, starts_at, ends_at, sort_order, is_active, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())
    ");
    
    $insertStmt->execute([
        $title,
        $description ?: null,
        $imageUrl,
        $link ?: null,
        $vendorId ?: null,
        $startDate,
        $endDate,
        $sortOrder ? intval($sortOrder) : 0
    ]);
    
    // Get the newly created ad
    $selectStmt = $conn->prepare("
        SELECT a.*, v.name as vendor_name 
        FROM advertisements a 
        LEFT JOIN vendors v ON a.vendor_id = v.id 
        WHERE a.title = ? AND a.starts_at = ? AND a.ends_at = ? 
        ORDER BY a.created_at DESC LIMIT 1
    ");
    $selectStmt->execute([$title, $startDate, $endDate]);
    $ad = $selectStmt->fetch(PDO::FETCH_ASSOC);
    
    logActivity($conn, $user['user_id'], 'create', 'advertisement', $ad['id'],
        "Created advertisement: $title");
    
    sendResponse(STATUS_SUCCESS, ['advertisement' => $ad], "Advertisement created successfully");
    
} catch (PDOException $e) {
    error_log("Add advertisement error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to create advertisement");
}
?>
