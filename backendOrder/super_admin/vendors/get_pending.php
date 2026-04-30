<?php
/**
 * Super Admin - Get Pending Vendors
 * GET: /super_admin/vendors/get_pending.php
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
    
    $stmt = $conn->prepare("
        SELECT * FROM view_vendor_list 
        WHERE status = 'pending' 
        ORDER BY registered_at ASC
    ");
    $stmt->execute();
    $vendors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    sendResponse(STATUS_SUCCESS, [
        'vendors' => $vendors,
        'total' => count($vendors)
    ], "Pending vendors retrieved successfully");
    
} catch (PDOException $e) {
    error_log("Get pending vendors error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve pending vendors");
}
?>
