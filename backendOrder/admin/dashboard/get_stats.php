<?php
/**
 * Admin - Dashboard Stats
 * GET: /admin/dashboard/get_stats.php
 * Headers: Authorization: Bearer <token>
 * 
 * Returns admin (delivery company) focused stats with accurate profit breakdown.
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
    
    // Orders by status
    $statusStmt = $conn->prepare("
        SELECT 
            status,
            COUNT(*) as count
        FROM orders
        GROUP BY status
    ");
    $statusStmt->execute();
    $statusCounts = [];
    while ($row = $statusStmt->fetch(PDO::FETCH_ASSOC)) {
        $statusCounts[$row['status']] = (int)$row['count'];
    }
    
    // Today's stats
    $todayStmt = $conn->prepare("
        SELECT 
            COUNT(*) as orders_today,
            COALESCE(SUM(CASE WHEN status = 'delivered' THEN total_amount ELSE 0 END), 0) as revenue_today,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_today,
            SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered_today,
            SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_today
        FROM orders 
        WHERE DATE(created_at) = CURDATE()
    ");
    $todayStmt->execute();
    $today = $todayStmt->fetch(PDO::FETCH_ASSOC);
    
    // Pending orders count (needs action)
    $pendingStmt = $conn->prepare("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'");
    $pendingStmt->execute();
    $pendingCount = (int)$pendingStmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Active vendors
    $vendorStmt = $conn->prepare("SELECT COUNT(*) as count FROM vendors WHERE status = 'approved'");
    $vendorStmt->execute();
    $activeVendors = (int)$vendorStmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Admin delivery profit from completed orders
    $deliveryStmt = $conn->prepare("
        SELECT 
            COALESCE(SUM(delivery_fee_earned), 0) as total_delivery_earnings,
            COALESCE(SUM(vendor_payout), 0) as total_paid_to_vendors,
            COALESCE(SUM(order_total), 0) as total_collected_from_users,
            COALESCE(SUM(CASE WHEN is_collected = 0 THEN service_fee_earned + commission_earned ELSE 0 END), 0) as sa_owed
        FROM platform_earnings
    ");
    $deliveryStmt->execute();
    $deliveryStats = $deliveryStmt->fetch(PDO::FETCH_ASSOC);
    
    // Today's delivery profit
    $todayDeliveryStmt = $conn->prepare("
        SELECT 
            COALESCE(SUM(delivery_fee_earned), 0) as today_delivery_earnings
        FROM platform_earnings
        WHERE DATE(earned_at) = CURDATE()
    ");
    $todayDeliveryStmt->execute();
    $todayDelivery = $todayDeliveryStmt->fetch(PDO::FETCH_ASSOC);
    
    sendResponse(STATUS_SUCCESS, [
        'pending_orders' => $pendingCount,
        'active_vendors' => $activeVendors,
        'today' => [
            'orders' => (int)$today['orders_today'],
            'revenue' => (float)$today['revenue_today'],
            'pending' => (int)$today['pending_today'],
            'delivered' => (int)$today['delivered_today'],
            'cancelled' => (int)$today['cancelled_today']
        ],
        'orders_by_status' => $statusCounts,
        'delivery_profit' => [
            'total_delivery_earnings' => (float)$deliveryStats['total_delivery_earnings'],
            'today_delivery_earnings' => (float)$todayDelivery['today_delivery_earnings'],
            'total_collected_from_users' => (float)$deliveryStats['total_collected_from_users'],
            'total_paid_to_vendors' => (float)$deliveryStats['total_paid_to_vendors'],
            'sa_owed' => (float)$deliveryStats['sa_owed']
        ]
    ], "Dashboard stats retrieved");
    
} catch (PDOException $e) {
    error_log("Admin dashboard error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve dashboard stats");
}
?>
