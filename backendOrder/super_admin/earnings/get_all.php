<?php
/**
 * Super Admin - Get All Platform Earnings
 * GET: /super_admin/earnings/get_all.php
 * Headers: Authorization: Bearer <token>
 * Query: ?page=1&limit=20&collected=0|1&vendor_id=
 * 
 * Uses view_earnings_ledger DB view with full revenue breakdown.
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
    $collected = isset($_GET['collected']) ? $_GET['collected'] : '';
    $vendorId = isset($_GET['vendor_id']) ? trim($_GET['vendor_id']) : '';
    $offset = ($page - 1) * $limit;
    
    $query = "SELECT * FROM view_earnings_ledger WHERE 1=1";
    $countQuery = "SELECT COUNT(*) as total FROM view_earnings_ledger WHERE 1=1";
    $params = [];
    $countParams = [];
    
    if ($collected !== '' && in_array($collected, ['0', '1'])) {
        $query .= " AND is_collected = ?";
        $countQuery .= " AND is_collected = ?";
        $params[] = (int)$collected;
        $countParams[] = (int)$collected;
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
    
    $query .= " ORDER BY earned_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $earnings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format numbers
    foreach ($earnings as &$e) {
        $e['service_fee_earned'] = (float)($e['service_fee_earned'] ?? 0);
        $e['commission_earned'] = (float)($e['commission_earned'] ?? 0);
        $e['commission_rate_snapshot'] = (float)($e['commission_rate_snapshot'] ?? 0);
        $e['delivery_fee_earned'] = (float)($e['delivery_fee_earned'] ?? 0);
        $e['vendor_payout'] = (float)($e['vendor_payout'] ?? 0);
        $e['vendor_subtotal'] = (float)($e['vendor_subtotal'] ?? 0);
        $e['platform_fee'] = (float)($e['platform_fee'] ?? 0);
        $e['order_total'] = (float)($e['order_total'] ?? 0);
        $e['sa_earning'] = $e['service_fee_earned'] + $e['commission_earned'];
    }
    
    $totalPages = ceil($total / $limit);
    
    sendResponse(STATUS_SUCCESS, [
        'earnings' => $earnings,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $limit,
            'total_items' => (int)$total,
            'total_pages' => $totalPages,
            'has_next' => $page < $totalPages,
            'has_prev' => $page > 1
        ]
    ], "Earnings retrieved successfully");
    
} catch (PDOException $e) {
    error_log("Get earnings error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve earnings");
}
?>
