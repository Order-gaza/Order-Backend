<?php
/**
 * Admin - Get Orders By Status
 * GET: /admin/orders/get_by_status.php?status=<status>&page=1
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
    
    $status = isset($_GET['status']) ? trim($_GET['status']) : '';
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $limit = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : ITEMS_PER_PAGE;
    $offset = ($page - 1) * $limit;
    
    if (empty($status)) {
        sendResponse(STATUS_FAIL, null, "Status is required");
    }
    
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
    $zoneFilter = "";
    if ($zoneCoverage === 'north') {
        $zoneFilter = " AND (delivery_zone_to = 'north' OR delivery_zone_to IS NULL)";
    } elseif ($zoneCoverage === 'south') {
        $zoneFilter = " AND (delivery_zone_to IN ('central', 'south') OR delivery_zone_to IS NULL)";
    }
    
    $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM view_admin_orders WHERE status = ? $zoneFilter");
    $countStmt->execute([$status]);
    $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $stmt = $conn->prepare("
        SELECT * FROM view_admin_orders 
        WHERE status = ? $zoneFilter
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
    error_log("Admin orders by status error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve orders");
}
?>
