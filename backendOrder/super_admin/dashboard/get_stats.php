<?php
/**
 * Super Admin - Dashboard Stats
 * GET: /super_admin/dashboard/get_stats.php
 * Headers: Authorization: Bearer <token>
 * 
 * Returns key platform metrics with accurate SA revenue breakdown.
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
    
    // Order stats
    $statsStmt = $conn->prepare("
        SELECT 
            COUNT(*) as total_orders,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_orders,
            SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered_orders,
            SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_orders
        FROM orders
    ");
    $statsStmt->execute();
    $orderStats = $statsStmt->fetch(PDO::FETCH_ASSOC);
    
    // Vendor counts
    $vendorStmt = $conn->prepare("
        SELECT 
            COUNT(*) as total_vendors,
            SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as active_vendors,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_vendors,
            SUM(CASE WHEN status = 'suspended' THEN 1 ELSE 0 END) as suspended_vendors
        FROM vendors
    ");
    $vendorStmt->execute();
    $vendorStats = $vendorStmt->fetch(PDO::FETCH_ASSOC);
    
    // User counts
    $userStmt = $conn->prepare("
        SELECT 
            COUNT(*) as total_users,
            SUM(CASE WHEN role = 'customer' THEN 1 ELSE 0 END) as total_customers,
            SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) as total_admins
        FROM users WHERE is_active = 1
    ");
    $userStmt->execute();
    $userStats = $userStmt->fetch(PDO::FETCH_ASSOC);
    
    // Today's order stats
    $todayStmt = $conn->prepare("
        SELECT 
            COUNT(*) as orders_today,
            COALESCE(SUM(CASE WHEN status = 'delivered' THEN total_amount ELSE 0 END), 0) as revenue_today
        FROM orders 
        WHERE DATE(created_at) = CURDATE()
    ");
    $todayStmt->execute();
    $todayStats = $todayStmt->fetch(PDO::FETCH_ASSOC);
    
    // SA Earnings breakdown — ONLY from delivered (completed) orders
    $earningsStmt = $conn->prepare("
        SELECT 
            COALESCE(SUM(service_fee_earned + commission_earned), 0) as sa_total_earnings,
            COALESCE(SUM(service_fee_earned), 0) as total_service_fees,
            COALESCE(SUM(commission_earned), 0) as total_commissions,
            COALESCE(SUM(delivery_fee_earned), 0) as total_delivery_fees,
            COALESCE(SUM(vendor_payout), 0) as total_vendor_payouts,
            COALESCE(SUM(CASE WHEN is_collected = 0 THEN service_fee_earned + commission_earned ELSE 0 END), 0) as sa_uncollected,
            COALESCE(SUM(CASE WHEN is_collected = 1 THEN service_fee_earned + commission_earned ELSE 0 END), 0) as sa_collected
        FROM platform_earnings
    ");
    $earningsStmt->execute();
    $earningStats = $earningsStmt->fetch(PDO::FETCH_ASSOC);
    
    // Today's SA earnings from delivered orders
    $todayEarningsStmt = $conn->prepare("
        SELECT 
            COALESCE(SUM(service_fee_earned + commission_earned), 0) as sa_today_earnings,
            COALESCE(SUM(service_fee_earned), 0) as today_service_fees,
            COALESCE(SUM(commission_earned), 0) as today_commissions
        FROM platform_earnings
        WHERE DATE(earned_at) = CURDATE()
    ");
    $todayEarningsStmt->execute();
    $todayEarnings = $todayEarningsStmt->fetch(PDO::FETCH_ASSOC);
    
    sendResponse(STATUS_SUCCESS, [
        'orders' => [
            'total_orders' => (int)$orderStats['total_orders'],
            'pending_orders' => (int)$orderStats['pending_orders'],
            'delivered_orders' => (int)$orderStats['delivered_orders'],
            'cancelled_orders' => (int)$orderStats['cancelled_orders']
        ],
        'vendors' => $vendorStats,
        'users' => $userStats,
        'today' => [
            'orders_today' => (int)$todayStats['orders_today'],
            'revenue_today' => (float)$todayStats['revenue_today']
        ],
        'earnings' => [
            'sa_total_earnings' => (float)$earningStats['sa_total_earnings'],
            'total_service_fees' => (float)$earningStats['total_service_fees'],
            'total_commissions' => (float)$earningStats['total_commissions'],
            'total_delivery_fees' => (float)$earningStats['total_delivery_fees'],
            'total_vendor_payouts' => (float)$earningStats['total_vendor_payouts'],
            'sa_uncollected' => (float)$earningStats['sa_uncollected'],
            'sa_collected' => (float)$earningStats['sa_collected'],
            'sa_today_earnings' => (float)$todayEarnings['sa_today_earnings'],
            'today_service_fees' => (float)$todayEarnings['today_service_fees'],
            'today_commissions' => (float)$todayEarnings['today_commissions']
        ]
    ], "Dashboard stats retrieved");
    
} catch (PDOException $e) {
    error_log("Dashboard stats error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve dashboard stats");
}
?>
