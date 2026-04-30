<?php
/**
 * Admin - Get All Orders
 * GET: /admin/orders/get_all.php
 * Headers: Authorization: Bearer <token>
 * Query: ?page=1&limit=20&status=&date=YYYY-MM-DD
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
    
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $limit = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : ITEMS_PER_PAGE;
    $status = isset($_GET['status']) ? trim($_GET['status']) : '';
    $date = isset($_GET['date']) ? trim($_GET['date']) : '';
    $offset = ($page - 1) * $limit;
    
    $query = "SELECT * FROM view_admin_orders WHERE 1=1";
    $countQuery = "SELECT COUNT(*) as total FROM view_admin_orders WHERE 1=1";
    $params = [];
    $countParams = [];
    
    // Zone-based filtering for branch admin accounts
    $zoneCoverage = null;
    try {
        $zcStmt = $conn->prepare("SELECT zone_coverage FROM users WHERE id = ?");
        $zcStmt->execute([$user['user_id']]);
        $zcRow = $zcStmt->fetch(PDO::FETCH_ASSOC);
        if ($zcRow) $zoneCoverage = $zcRow['zone_coverage'] ?? null;
    } catch (PDOException $e) {
        error_log("zone_coverage column check: " . $e->getMessage());
        // Column may not exist yet — ignore, show all orders
    }
    if ($zoneCoverage === 'north') {
        $query .= " AND (delivery_zone_to = 'north' OR delivery_zone_to IS NULL)";
        $countQuery .= " AND (delivery_zone_to = 'north' OR delivery_zone_to IS NULL)";
    } elseif ($zoneCoverage === 'south') {
        $query .= " AND (delivery_zone_to IN ('central', 'south') OR delivery_zone_to IS NULL)";
        $countQuery .= " AND (delivery_zone_to IN ('central', 'south') OR delivery_zone_to IS NULL)";
    }
    // zone_coverage = null (super_admin or unassigned admin) sees all orders
    
    if (!empty($status)) {
        $query .= " AND status = ?";
        $countQuery .= " AND status = ?";
        $params[] = $status;
        $countParams[] = $status;
    }
    
    if (!empty($date) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $query .= " AND DATE(created_at) = ?";
        $countQuery .= " AND DATE(created_at) = ?";
        $params[] = $date;
        $countParams[] = $date;
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
    error_log("Admin get orders error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve orders");
}
?>
