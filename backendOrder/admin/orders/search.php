<?php
/**
 * Admin - Search Orders
 * GET: /admin/orders/search.php?q=<order_number|customer_name|phone>
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
    $user = requireRole($conn, [ROLE_SUPER_ADMIN, ROLE_ADMIN]);
    
    $q = isset($_GET['q']) ? trim($_GET['q']) : '';
    
    if (empty($q) || strlen($q) < 2) {
        sendResponse(STATUS_FAIL, null, "Search query must be at least 2 characters");
    }
    
    $searchTerm = "%$q%";
    
    $stmt = $conn->prepare("
        SELECT * FROM view_admin_orders 
        WHERE order_number LIKE ?
           OR customer_name LIKE ?
           OR customer_phone LIKE ?
        ORDER BY created_at DESC
        LIMIT 50
    ");
    $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    sendResponse(STATUS_SUCCESS, [
        'orders' => $orders,
        'total' => count($orders),
        'query' => $q
    ], "Search results");
    
} catch (PDOException $e) {
    error_log("Admin search orders error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Search failed");
}
?>
