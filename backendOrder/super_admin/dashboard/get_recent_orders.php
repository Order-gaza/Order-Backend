<?php
/**
 * Super Admin - Get Recent Orders
 * GET: /super_admin/dashboard/get_recent_orders.php?limit=10
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
    
    $limit = isset($_GET['limit']) ? max(1, min(50, intval($_GET['limit']))) : 10;
    
    $stmt = $conn->prepare("
        SELECT * FROM view_admin_orders 
        ORDER BY created_at DESC 
        LIMIT ?
    ");
    $stmt->execute([$limit]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    sendResponse(STATUS_SUCCESS, [
        'orders' => $orders,
        'total' => count($orders)
    ], "Recent orders retrieved");
    
} catch (PDOException $e) {
    error_log("Recent orders error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve recent orders");
}
?>
