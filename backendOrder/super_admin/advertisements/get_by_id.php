<?php
/**
 * Super Admin - Get Advertisement By ID
 * GET: /super_admin/advertisements/get_by_id.php?id=<uuid>
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
    $user = requireRole($conn, [ROLE_SUPER_ADMIN]);
    
    $adId = isset($_GET['id']) ? trim($_GET['id']) : filterRequest("id");
    
    if (empty($adId)) {
        sendResponse(STATUS_FAIL, null, "Advertisement ID is required");
    }
    
    $stmt = $conn->prepare("
        SELECT a.*, v.name as vendor_name 
        FROM advertisements a 
        LEFT JOIN vendors v ON a.vendor_id = v.id 
        WHERE a.id = ?
    ");
    $stmt->execute([$adId]);
    $ad = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$ad) {
        sendResponse(STATUS_FAIL, null, "Advertisement not found");
    }
    
    sendResponse(STATUS_SUCCESS, ['advertisement' => $ad], "Advertisement retrieved successfully");
    
} catch (PDOException $e) {
    error_log("Get advertisement error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve advertisement");
}
?>
