<?php
/**
 * Super Admin - Get All Vendors
 * GET: /super_admin/vendors/get_all.php
 * Headers: Authorization: Bearer <token>
 * Query: ?page=1&limit=20&search=&status=pending|approved|suspended|rejected&type=restaurant|store|cafe
 * 
 * Uses direct JOIN to return all vendor fields including logo_url, cover_url, is_open.
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
    $type = isset($_GET['type']) ? trim($_GET['type']) : '';
    $offset = ($page - 1) * $limit;
    
    $baseSelect = "
        SELECT v.id, v.name AS vendor_name, v.type, v.description,
               v.logo_url, v.cover_url, v.phone AS vendor_phone,
               v.address, v.city, v.neighborhood, v.latitude, v.longitude,
               v.opening_time, v.closing_time, v.is_open,
               CASE
                   WHEN v.is_open = 0 THEN 0
                   WHEN v.opening_time IS NULL OR v.closing_time IS NULL THEN 1
                   WHEN v.opening_time < v.closing_time THEN
                       IF(CURTIME() >= v.opening_time AND CURTIME() < v.closing_time, 1, 0)
                   ELSE
                       IF(CURTIME() >= v.opening_time OR CURTIME() < v.closing_time, 1, 0)
               END AS is_currently_open,
               v.min_order_amount, v.estimated_delivery_time,
               v.operating_hours, v.status, v.rating, v.total_ratings,
               v.commission_rate, v.delivery_zone, v.created_at, v.updated_at,
               u.name AS owner_name, u.email AS owner_email, u.phone AS owner_phone,
               u.is_active AS account_active, u.created_at AS registered_at
        FROM vendors v
        JOIN users u ON u.id = v.user_id
    ";
    
    $where = " WHERE 1=1";
    $params = [];
    $countParams = [];
    
    if (!empty($search)) {
        $searchTerm = "%$search%";
        $where .= " AND (v.name LIKE ? OR u.name LIKE ? OR u.email LIKE ? OR v.phone LIKE ?)";
        $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        $countParams = array_merge($countParams, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
    }
    
    if (!empty($status) && in_array($status, ['pending', 'approved', 'suspended', 'rejected'])) {
        $where .= " AND v.status = ?";
        $params[] = $status;
        $countParams[] = $status;
    }
    
    if (!empty($type) && in_array($type, ['restaurant', 'store', 'cafe'])) {
        $where .= " AND v.type = ?";
        $params[] = $type;
        $countParams[] = $type;
    }
    
    // Count
    $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM vendors v JOIN users u ON u.id = v.user_id" . $where);
    $countStmt->execute($countParams);
    $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Data
    $query = $baseSelect . $where . " ORDER BY v.created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $vendors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $totalPages = ceil($total / $limit);
    
    sendResponse(STATUS_SUCCESS, [
        'vendors' => $vendors,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $limit,
            'total_items' => (int)$total,
            'total_pages' => $totalPages,
            'has_next' => $page < $totalPages,
            'has_prev' => $page > 1
        ]
    ], "Vendors retrieved successfully");
    
} catch (PDOException $e) {
    error_log("Get vendors error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve vendors");
}
?>
