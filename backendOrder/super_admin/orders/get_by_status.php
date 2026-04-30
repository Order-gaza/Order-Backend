<?php
/**
 * Super Admin - Get Orders By Status
 * GET: /super_admin/orders/get_by_status.php?status=pending&page=1
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
    
    $status = isset($_GET['status']) ? trim($_GET['status']) : '';
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $limit = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : ITEMS_PER_PAGE;
    $offset = ($page - 1) * $limit;
    
    if (empty($status)) {
        sendResponse(STATUS_FAIL, null, "Status is required");
    }
    
    $validStatuses = [
        ORDER_PENDING, ORDER_ADMIN_CONFIRMED, ORDER_SENT_TO_VENDOR,
        ORDER_VENDOR_ACCEPTED, ORDER_PREPARING, ORDER_READY_FOR_PICKUP,
        ORDER_OUT_FOR_DELIVERY, ORDER_DELIVERED, ORDER_CANCELLED
    ];
    
    if (!in_array($status, $validStatuses)) {
        sendResponse(STATUS_FAIL, null, "Invalid status. Valid: " . implode(', ', $validStatuses));
    }
    
    $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM view_admin_orders WHERE status = ?");
    $countStmt->execute([$status]);
    $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $stmt = $conn->prepare("
        SELECT * FROM view_admin_orders 
        WHERE status = ? 
        ORDER BY created_at DESC 
        LIMIT ? OFFSET ?
    ");
    $stmt->execute([$status, $limit, $offset]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $totalPages = ceil($total / $limit);
    
    sendResponse(STATUS_SUCCESS, [
        'orders' => $orders,
        'status' => $status,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $limit,
            'total_items' => (int)$total,
            'total_pages' => $totalPages,
            'has_next' => $page < $totalPages,
            'has_prev' => $page > 1
        ]
    ], "Orders retrieved for status: $status");
    
} catch (PDOException $e) {
    error_log("Get orders by status error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve orders");
}
?>
