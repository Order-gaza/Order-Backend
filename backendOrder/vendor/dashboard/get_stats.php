<?php
/**
 * Vendor - Dashboard Stats
 * GET: /vendor/dashboard/get_stats.php
 * Headers: Authorization: Bearer <token>
 * 
 * Returns vendor-specific stats: gross revenue, commission deducted, and net revenue.
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
    $user = requireRole($conn, [ROLE_VENDOR]);
    
    $vendorStmt = $conn->prepare("SELECT id, commission_rate FROM vendors WHERE user_id = ?");
    $vendorStmt->execute([$user['user_id']]);
    $vendor = $vendorStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$vendor) {
        sendResponse(STATUS_FAIL, null, "Vendor profile not found");
    }
    
    $vendorId = $vendor['id'];
    $commissionRate = (float)$vendor['commission_rate'];
    
    // Order stats for this vendor (using order_vendors join with orders for delivered status)
    $orderStmt = $conn->prepare("
        SELECT 
            COUNT(*) as total_orders,
            SUM(CASE WHEN ov.status = 'sent_to_vendor' THEN 1 ELSE 0 END) as pending_orders,
            SUM(CASE WHEN ov.status = 'preparing' THEN 1 ELSE 0 END) as preparing_orders,
            SUM(CASE WHEN ov.status = 'ready_for_pickup' THEN 1 ELSE 0 END) as ready_orders
        FROM order_vendors ov
        WHERE ov.vendor_id = ?
    ");
    $orderStmt->execute([$vendorId]);
    $orderStats = $orderStmt->fetch(PDO::FETCH_ASSOC);
    
    // Revenue — only from delivered (completed) orders
    $revenueStmt = $conn->prepare("
        SELECT 
            COUNT(*) as delivered_orders,
            COALESCE(SUM(ov.subtotal), 0) as gross_revenue,
            COALESCE(SUM(ov.vendor_net_amount), 0) as net_revenue,
            COALESCE(SUM(ov.subtotal - ov.vendor_net_amount), 0) as total_commission_paid
        FROM order_vendors ov
        JOIN orders o ON ov.order_id = o.id
        WHERE ov.vendor_id = ? AND o.status = 'delivered'
    ");
    $revenueStmt->execute([$vendorId]);
    $revenue = $revenueStmt->fetch(PDO::FETCH_ASSOC);
    
    // Today's stats — orders today (all) and gross from delivered only
    $todayStmt = $conn->prepare("
        SELECT 
            COUNT(*) as orders_today,
            COALESCE(SUM(CASE WHEN o.status = 'delivered' THEN ov.subtotal ELSE 0 END), 0) as gross_today
        FROM order_vendors ov
        JOIN orders o ON ov.order_id = o.id
        WHERE ov.vendor_id = ? AND DATE(ov.created_at) = CURDATE()
    ");
    $todayStmt->execute([$vendorId]);
    $today = $todayStmt->fetch(PDO::FETCH_ASSOC);
    
    // Today's net revenue (from delivered orders today)
    $todayNetStmt = $conn->prepare("
        SELECT 
            COALESCE(SUM(ov.vendor_net_amount), 0) as net_today
        FROM order_vendors ov
        JOIN orders o ON ov.order_id = o.id
        WHERE ov.vendor_id = ? AND o.status = 'delivered' AND DATE(o.delivered_at) = CURDATE()
    ");
    $todayNetStmt->execute([$vendorId]);
    $todayNet = $todayNetStmt->fetch(PDO::FETCH_ASSOC);
    
    // Menu items count
    $itemStmt = $conn->prepare("SELECT COUNT(*) as c FROM menu_items WHERE vendor_id = ? AND is_available = 1");
    $itemStmt->execute([$vendorId]);
    $activeItems = (int)$itemStmt->fetch(PDO::FETCH_ASSOC)['c'];
    
    sendResponse(STATUS_SUCCESS, [
        'orders' => [
            'total' => (int)$orderStats['total_orders'],
            'pending' => (int)$orderStats['pending_orders'],
            'preparing' => (int)$orderStats['preparing_orders'],
            'ready' => (int)$orderStats['ready_orders'],
            'delivered' => (int)$revenue['delivered_orders']
        ],
        'revenue' => [
            'gross_revenue' => (float)$revenue['gross_revenue'],
            'net_revenue' => (float)$revenue['net_revenue'],
            'total_commission_paid' => (float)$revenue['total_commission_paid'],
            'commission_rate' => $commissionRate
        ],
        'today' => [
            'orders' => (int)$today['orders_today'],
            'gross_revenue' => (float)$today['gross_today'],
            'net_revenue' => (float)$todayNet['net_today']
        ],
        'active_menu_items' => $activeItems
    ], "Dashboard stats retrieved");
    
} catch (PDOException $e) {
    error_log("Vendor dashboard error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve dashboard stats");
}
?>
