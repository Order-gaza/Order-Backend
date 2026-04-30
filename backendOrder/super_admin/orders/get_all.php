<?php
/**
 * Super Admin - Get All Orders
 * GET: /super_admin/orders/get_all.php
 * Headers: Authorization: Bearer <token>
 * Query: ?page=1&limit=20&status=&vendor_id=
 * 
 * Uses view_admin_orders DB view.
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
    
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $limit = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : ITEMS_PER_PAGE;
    $status = isset($_GET['status']) ? trim($_GET['status']) : '';
    $vendorId = isset($_GET['vendor_id']) ? trim($_GET['vendor_id']) : '';
    $offset = ($page - 1) * $limit;
    
    $query = "SELECT * FROM view_admin_orders WHERE 1=1";
    $countQuery = "SELECT COUNT(*) as total FROM view_admin_orders WHERE 1=1";
    $params = [];
    $countParams = [];
    
    if (!empty($status)) {
        $query .= " AND status = ?";
        $countQuery .= " AND status = ?";
        $params[] = $status;
        $countParams[] = $status;
    }
    
    if (!empty($vendorId)) {
        $query .= " AND vendor_id = ?";
        $countQuery .= " AND vendor_id = ?";
        $params[] = $vendorId;
        $countParams[] = $vendorId;
    }
    
    $countStmt = $conn->prepare($countQuery);
    $countStmt->execute($countParams);
    $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $query .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $totalPages = ceil($total / $limit);
    
    sendResponse(STATUS_SUCCESS, [
        'orders' => $orders,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $limit,
            'total_items' => (int)$total,
            'total_pages' => $totalPages,
            'has_next' => $page < $totalPages,
            'has_prev' => $page > 1
        ]
    ], "Orders retrieved successfully");
    
} catch (PDOException $e) {
    error_log("Get orders error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve orders");
}
?>
