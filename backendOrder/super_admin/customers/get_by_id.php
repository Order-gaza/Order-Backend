<?php
/**
 * Super Admin - Get Customer By ID
 * GET/POST: /super_admin/customers/get_by_id.php
 * Headers: Authorization: Bearer <token>
 * Body/Query: customer_id
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
    
    $customerId = filterRequest("customer_id") ?? (isset($_GET['customer_id']) ? $_GET['customer_id'] : null);
    
    if (empty($customerId)) {
        sendResponse(STATUS_FAIL, null, "Customer ID is required");
    }
    
    // Get customer info
    $stmt = $conn->prepare("
        SELECT id, name, email, phone, is_active, created_at, updated_at 
        FROM users WHERE id = ? AND role = ?
    ");
    $stmt->execute([$customerId, ROLE_CUSTOMER]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$customer) {
        sendResponse(STATUS_FAIL, null, "Customer not found");
    }
    
    $customer['is_active'] = (bool)$customer['is_active'];
    
    // Order statistics
    $statsStmt = $conn->prepare("
        SELECT 
            COUNT(*) as total_orders,
            COALESCE(SUM(total_amount), 0) as total_spent,
            MAX(created_at) as last_order_date
        FROM orders WHERE customer_id = ?
    ");
    $statsStmt->execute([$customerId]);
    $stats = $statsStmt->fetch(PDO::FETCH_ASSOC);
    
    // Order status breakdown
    $statusStmt = $conn->prepare("
        SELECT status, COUNT(*) as cnt 
        FROM orders WHERE customer_id = ? 
        GROUP BY status
    ");
    $statusStmt->execute([$customerId]);
    $statusBreakdown = [];
    while ($row = $statusStmt->fetch(PDO::FETCH_ASSOC)) {
        $statusBreakdown[$row['status']] = (int)$row['cnt'];
    }
    
    // Recent orders (last 10)
    $ordersStmt = $conn->prepare("
        SELECT o.id, o.order_number, o.status, o.total_amount, 
               o.payment_method, o.created_at,
               (SELECT v.name FROM order_vendors ov JOIN vendors v ON ov.vendor_id = v.id WHERE ov.order_id = o.id LIMIT 1) as vendor_name
        FROM orders o
        WHERE o.customer_id = ?
        ORDER BY o.created_at DESC
        LIMIT 10
    ");
    $ordersStmt->execute([$customerId]);
    $recentOrders = $ordersStmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($recentOrders as &$order) {
        $order['total_amount'] = (float)$order['total_amount'];
        $order['created_at'] = formatDateTime($order['created_at']);
    }
    
    // Addresses count
    $addrStmt = $conn->prepare("SELECT COUNT(*) as cnt FROM customer_addresses WHERE user_id = ?");
    $addrStmt->execute([$customerId]);
    $addressCount = (int)$addrStmt->fetch(PDO::FETCH_ASSOC)['cnt'];
    
    sendResponse(STATUS_SUCCESS, [
        'customer' => $customer,
        'stats' => [
            'total_orders' => (int)$stats['total_orders'],
            'total_spent' => (float)$stats['total_spent'],
            'last_order_date' => $stats['last_order_date'] ? formatDateTime($stats['last_order_date']) : null,
            'address_count' => $addressCount,
            'status_breakdown' => $statusBreakdown
        ],
        'recent_orders' => $recentOrders
    ], "Customer details retrieved");
    
} catch (PDOException $e) {
    error_log("Get customer by ID error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve customer details");
}
?>
