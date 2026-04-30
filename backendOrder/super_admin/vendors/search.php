<?php
/**
 * Super Admin - Search Vendors
 * GET: /super_admin/vendors/search.php?q=<search_term>
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
    
    $q = isset($_GET['q']) ? trim($_GET['q']) : '';
    
    if (empty($q) || strlen($q) < 2) {
        sendResponse(STATUS_FAIL, null, "Search query must be at least 2 characters");
    }
    
    $searchTerm = "%$q%";
    
    $stmt = $conn->prepare("
        SELECT * FROM view_vendor_list 
        WHERE vendor_name LIKE ? 
           OR owner_name LIKE ? 
           OR owner_email LIKE ? 
           OR vendor_phone LIKE ?
           OR address LIKE ?
        ORDER BY registered_at DESC
        LIMIT 50
    ");
    $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
    $vendors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    sendResponse(STATUS_SUCCESS, [
        'vendors' => $vendors,
        'total' => count($vendors),
        'query' => $q
    ], "Search results");
    
} catch (PDOException $e) {
    error_log("Search vendors error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Search failed");
}
?>
