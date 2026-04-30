<?php
/**
 * Super Admin - Get All Admins
 * GET: /super_admin/admins/get_all.php
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
    
    // Build query — only admin role users (not vendors, not customers, not super_admin)
    $query = "SELECT id, name, email, phone, role, zone_coverage, is_active, created_at, updated_at FROM users WHERE role = ?";
    $countQuery = "SELECT COUNT(*) as total FROM users WHERE role = ?";
    $params = [ROLE_ADMIN];
    $countParams = [ROLE_ADMIN];
    
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
    
    $query .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $formatted = [];
    foreach ($admins as $a) {
        $formatted[] = [
            'id' => $a['id'],
            'name' => $a['name'],
            'email' => $a['email'],
            'phone' => $a['phone'],
            'role' => $a['role'],
            'zone_coverage' => $a['zone_coverage'],
            'is_active' => (bool)$a['is_active'],
            'created_at' => formatDateTime($a['created_at']),
            'updated_at' => formatDateTime($a['updated_at'])
        ];
    }
    
    $totalPages = ceil($total / $limit);
    
    sendResponse(STATUS_SUCCESS, [
        'admins' => $formatted,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $limit,
            'total_items' => (int)$total,
            'total_pages' => $totalPages,
            'has_next' => $page < $totalPages,
            'has_prev' => $page > 1
        ]
    ], "Admins retrieved successfully");
    
} catch (PDOException $e) {
    error_log("Get admins error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve admins");
}
?>
