<?php
/**
 * Super Admin - Get Earnings Summary
 * GET: /super_admin/earnings/get_summary.php[?from=YYYY-MM-DD&to=YYYY-MM-DD]
 * Headers: Authorization: Bearer <token>
 *
 * When from/to are provided, ALL stats (totals, vendor breakdown) are filtered
 * to that date range. "this_month" then mirrors the filtered total.
 * Without date params the behaviour is unchanged (all-time + current month).
 */

include "../../config/database.php";
include "../../config/constants.php";
include "../../core/functions.php";
include "../../core/middleware.php";

if (!isRequestMethod('GET') && !isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db   = new Database();
$conn = $db->connect();

try {
    $user = requireRole($conn, [ROLE_SUPER_ADMIN]);

    // ── Optional date-range filter ──
    $from = isset($_GET['from']) ? trim($_GET['from']) : '';
    $to   = isset($_GET['to'])   ? trim($_GET['to'])   : '';
    $hasDateFilter = !empty($from) && !empty($to);
    $dateWhere  = $hasDateFilter ? 'WHERE DATE(earned_at) >= ? AND DATE(earned_at) <= ?' : '';
    $dateParams = $hasDateFilter ? [$from, $to] : [];

    // ── Overall SA totals ──
    $totalStmt = $conn->prepare("
        SELECT
            COALESCE(SUM(service_fee_earned + commission_earned), 0) AS total_sa_earnings,
            COALESCE(SUM(service_fee_earned), 0)                     AS total_service_fees,
            COALESCE(SUM(commission_earned), 0)                      AS total_commissions,
            COALESCE(SUM(delivery_fee_earned), 0)                    AS total_delivery_fees,
            COALESCE(SUM(vendor_payout), 0)                          AS total_vendor_payouts,
            COALESCE(SUM(CASE WHEN is_collected = 1
                THEN service_fee_earned + commission_earned ELSE 0 END), 0) AS total_collected,
            COALESCE(SUM(CASE WHEN is_collected = 0
                THEN service_fee_earned + commission_earned ELSE 0 END), 0) AS total_uncollected,
            COUNT(DISTINCT order_id)                                 AS total_orders
        FROM platform_earnings
        $dateWhere
    ");
    $totalStmt->execute($dateParams);
    $totals = $totalStmt->fetch(PDO::FETCH_ASSOC);

    // ── "This month" stat ──
    // When a date range is active the "period earnings" = same filtered total.
    // When no filter, compute current-calendar-month separately.
    if ($hasDateFilter) {
        $monthEarnings    = (float)$totals['total_sa_earnings'];
        $monthServiceFees = (float)$totals['total_service_fees'];
        $monthCommissions = (float)$totals['total_commissions'];
    } else {
        $monthStmt = $conn->prepare("
            SELECT
                COALESCE(SUM(service_fee_earned + commission_earned), 0) AS month_earnings,
                COALESCE(SUM(service_fee_earned), 0)                     AS month_service_fees,
                COALESCE(SUM(commission_earned), 0)                      AS month_commissions
            FROM platform_earnings
            WHERE MONTH(earned_at) = MONTH(CURDATE())
              AND YEAR(earned_at)  = YEAR(CURDATE())
        ");
        $monthStmt->execute();
        $monthData        = $monthStmt->fetch(PDO::FETCH_ASSOC);
        $monthEarnings    = (float)$monthData['month_earnings'];
        $monthServiceFees = (float)$monthData['month_service_fees'];
        $monthCommissions = (float)$monthData['month_commissions'];
    }

    // ── Averages ──
    $totalOrders = (int)$totals['total_orders'];
    $avgPerOrder = $totalOrders > 0
        ? round((float)$totals['total_sa_earnings'] / $totalOrders, 2)
        : 0;

    // ── Daily summary (last 30 days — always unfiltered, used for charts) ──
    $dailyStmt = $conn->prepare("
        SELECT * FROM view_earnings_daily_summary
        ORDER BY earning_date DESC
        LIMIT 30
    ");
    $dailyStmt->execute();
    $dailySummary = $dailyStmt->fetchAll(PDO::FETCH_ASSOC);

    // ── Per-vendor breakdown (date-filtered when range is provided) ──
    $vendorDateJoin = $hasDateFilter
        ? 'AND DATE(pe.earned_at) >= ? AND DATE(pe.earned_at) <= ?'
        : '';
    $vendorParams = $hasDateFilter ? [$from, $to] : [];

    $vendorStmt = $conn->prepare("
        SELECT
            v.id                AS vendor_id,
            v.name              AS vendor_name,
            v.commission_rate,
            COALESCE(SUM(pe.commission_earned), 0)                           AS total_commission_earned,
            COALESCE(SUM(pe.service_fee_earned), 0)                          AS total_service_fee_earned,
            COALESCE(SUM(pe.commission_earned + pe.service_fee_earned), 0)   AS total_sa_earned,
            COALESCE(SUM(CASE WHEN pe.is_collected = 0
                THEN pe.commission_earned + pe.service_fee_earned ELSE 0 END), 0) AS uncollected,
            COALESCE(SUM(pe.vendor_payout), 0)                               AS total_vendor_payout,
            COUNT(pe.id)                                                     AS total_orders
        FROM vendors v
        LEFT JOIN platform_earnings pe
            ON v.id = pe.vendor_id $vendorDateJoin
        WHERE v.status = 'approved'
        GROUP BY v.id, v.name, v.commission_rate
        ORDER BY uncollected DESC
    ");
    $vendorStmt->execute($vendorParams);
    $vendorSummary = $vendorStmt->fetchAll(PDO::FETCH_ASSOC);

    sendResponse(STATUS_SUCCESS, [
        'total_sa_earnings'       => (float)$totals['total_sa_earnings'],
        'total_service_fees'      => (float)$totals['total_service_fees'],
        'total_commissions'       => (float)$totals['total_commissions'],
        'total_collected'         => (float)$totals['total_collected'],
        'total_uncollected'       => (float)$totals['total_uncollected'],
        'total_delivery_fees'     => (float)$totals['total_delivery_fees'],
        'total_vendor_payouts'    => (float)$totals['total_vendor_payouts'],
        'total_orders'            => $totalOrders,
        'avg_per_order'           => $avgPerOrder,
        'this_month'              => $monthEarnings,
        'this_month_service_fees' => $monthServiceFees,
        'this_month_commissions'  => $monthCommissions,
        'daily_summary'           => $dailySummary,
        'vendor_summary'          => $vendorSummary,
        'is_filtered'             => $hasDateFilter,
        'filter_from'             => $from,
        'filter_to'               => $to,
    ], "Earnings summary retrieved");

} catch (PDOException $e) {
    error_log("Earnings summary error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve earnings summary");
}
?>
