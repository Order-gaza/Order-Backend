<?php
/**
 * Super Admin - Get Revenue Chart Data
 * GET: /super_admin/dashboard/get_revenue_chart.php?period=7|30|90
 * Headers: Authorization: Bearer <token>
 * 
 * Returns daily revenue data for charting.
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
    
    $period = isset($_GET['period']) ? intval($_GET['period']) : 7;
    if (!in_array($period, [7, 14, 30, 90])) {
        $period = 7;
    }
    
    $stmt = $conn->prepare("
        SELECT 
            DATE(created_at) as date,
            COUNT(*) as orders_count,
            COALESCE(SUM(total_amount), 0) as revenue,
            COALESCE(SUM(delivery_fee), 0) as delivery_fees,
            COALESCE(SUM(CASE WHEN status = 'delivered' THEN total_amount ELSE 0 END), 0) as delivered_revenue
        FROM orders 
        WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
        GROUP BY DATE(created_at)
        ORDER BY date ASC
    ");
    $stmt->execute([$period]);
    $chartData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format numbers
    foreach ($chartData as &$day) {
        $day['revenue'] = (float)$day['revenue'];
        $day['delivery_fees'] = (float)$day['delivery_fees'];
        $day['delivered_revenue'] = (float)$day['delivered_revenue'];
        $day['orders_count'] = (int)$day['orders_count'];
    }
    
    sendResponse(STATUS_SUCCESS, [
        'chart_data' => $chartData,
        'period_days' => $period
    ], "Revenue chart data retrieved");
    
} catch (PDOException $e) {
    error_log("Revenue chart error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve chart data");
}
?>
