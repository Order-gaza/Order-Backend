<?php
/**
 * Super Admin - Get All Customers
 * GET: /super_admin/customers/get_all.php
 * Headers: Authorization: Bearer <token>
 * Query: ?page=1&limit=20&search=&status=active|inactive
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
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $status = isset($_GET['status']) ? trim($_GET['status']) : '';
    $offset = ($page - 1) * $limit;
    
    $query = "SELECT id, name, email, phone, role, is_active, created_at, updated_at FROM users WHERE role = ?";
    $countQuery = "SELECT COUNT(*) as total FROM users WHERE role = ?";
    $params = [ROLE_CUSTOMER];
    $countParams = [ROLE_CUSTOMER];
    
    if (!empty($search)) {
        $searchTerm = "%$search%";
        $query .= " AND (name LIKE ? OR email LIKE ? OR phone LIKE ?)";
        $countQuery .= " AND (name LIKE ? OR email LIKE ? OR phone LIKE ?)";
        $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
        $countParams = array_merge($countParams, [$searchTerm, $searchTerm, $searchTerm]);
    }
    
    if ($status === 'active') {
        $query .= " AND is_active = 1";
        $countQuery .= " AND is_active = 1";
    } elseif ($status === 'inactive') {
        $query .= " AND is_active = 0";
        $countQuery .= " AND is_active = 0";
    }
    
    $countStmt = $conn->prepare($countQuery);
    $countStmt->execute($countParams);
    $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $query .= " ORDER BY created_at DESC LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
    
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $formatted = [];
    foreach ($customers as $c) {
        // Get order count for each customer
        $orderStmt = $conn->prepare("SELECT COUNT(*) as cnt FROM orders WHERE customer_id = ?");
        $orderStmt->execute([$c['id']]);
        $orderCount = (int)$orderStmt->fetch(PDO::FETCH_ASSOC)['cnt'];
        
        $formatted[] = [
            'id' => $c['id'],
            'name' => $c['name'],
            'email' => $c['email'],
            'phone' => $c['phone'],
            'is_active' => (bool)$c['is_active'],
            'order_count' => $orderCount,
            'created_at' => formatDateTime($c['created_at']),
            'updated_at' => formatDateTime($c['updated_at'])
        ];
    }
    
    $totalPages = ceil($total / $limit);
    
    sendResponse(STATUS_SUCCESS, [
        'customers' => $formatted,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $limit,
            'total_items' => (int)$total,
            'total_pages' => $totalPages,
            'has_next' => $page < $totalPages,
            'has_prev' => $page > 1
        ]
    ], "Customers retrieved successfully");
    
} catch (PDOException $e) {
    error_log("Get customers error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve customers");
}
?>
