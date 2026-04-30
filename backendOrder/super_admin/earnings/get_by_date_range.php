<?php
/**
 * Super Admin - Get Earnings By Date Range
 * GET: /super_admin/earnings/get_by_date_range.php?from=2026-01-01&to=2026-03-12
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
    
    $from = isset($_GET['from']) ? trim($_GET['from']) : '';
    $to = isset($_GET['to']) ? trim($_GET['to']) : '';
    
    if (empty($from) || empty($to)) {
        sendResponse(STATUS_FAIL, null, "Both 'from' and 'to' dates are required (YYYY-MM-DD)");
    }
    
    // Validate date format
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $from) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $to)) {
        sendResponse(STATUS_FAIL, null, "Invalid date format. Use YYYY-MM-DD");
    }
    
    $stmt = $conn->prepare("
        SELECT * FROM view_earnings_ledger
        WHERE DATE(earned_at) >= ? AND DATE(earned_at) <= ?
        ORDER BY earned_at DESC
    ");
    $stmt->execute([$from, $to]);
    $earnings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format and add computed sa_earning field
    foreach ($earnings as &$e) {
        $e['service_fee_earned'] = (float)($e['service_fee_earned'] ?? 0);
        $e['commission_earned'] = (float)($e['commission_earned'] ?? 0);
        $e['delivery_fee_earned'] = (float)($e['delivery_fee_earned'] ?? 0);
        $e['vendor_payout'] = (float)($e['vendor_payout'] ?? 0);
        $e['sa_earning'] = $e['service_fee_earned'] + $e['commission_earned'];
    }
    
    // SA totals for the range
    $totalStmt = $conn->prepare("
        SELECT 
            COALESCE(SUM(service_fee_earned + commission_earned), 0) as total_sa_earnings,
            COALESCE(SUM(service_fee_earned), 0) as total_service_fees,
            COALESCE(SUM(commission_earned), 0) as total_commissions,
            COALESCE(SUM(CASE WHEN is_collected = 1 THEN service_fee_earned + commission_earned ELSE 0 END), 0) as collected,
            COALESCE(SUM(CASE WHEN is_collected = 0 THEN service_fee_earned + commission_earned ELSE 0 END), 0) as uncollected,
            COUNT(DISTINCT order_id) as records
        FROM platform_earnings 
        WHERE DATE(earned_at) >= ? AND DATE(earned_at) <= ?
    ");
    $totalStmt->execute([$from, $to]);
    $totals = $totalStmt->fetch(PDO::FETCH_ASSOC);
    
    sendResponse(STATUS_SUCCESS, [
        'earnings' => $earnings,
        'totals' => [
            'total_sa_earnings' => (float)$totals['total_sa_earnings'],
            'total_service_fees' => (float)$totals['total_service_fees'],
            'total_commissions' => (float)$totals['total_commissions'],
            'collected' => (float)$totals['collected'],
            'uncollected' => (float)$totals['uncollected'],
            'records' => (int)$totals['records']
        ],
        'date_range' => ['from' => $from, 'to' => $to]
    ], "Earnings for date range retrieved");
    
} catch (PDOException $e) {
    error_log("Earnings by date range error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve earnings");
}
?>
