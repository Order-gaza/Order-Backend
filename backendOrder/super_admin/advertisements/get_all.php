<?php
/**
 * Super Admin - Get All Advertisements
 * GET: /super_admin/advertisements/get_all.php
 * Headers: Authorization: Bearer <token>
 * Query: ?page=1&limit=20&active=0|1
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
    $active = isset($_GET['active']) ? $_GET['active'] : '';
    $offset = ($page - 1) * $limit;
    
    $query = "
        SELECT a.*, v.name as vendor_name,
            (SELECT COUNT(*) FROM ad_clicks ac WHERE ac.ad_id = a.id) as click_count
        FROM advertisements a 
        LEFT JOIN vendors v ON a.vendor_id = v.id 
        WHERE 1=1
    ";
    $countQuery = "SELECT COUNT(*) as total FROM advertisements WHERE 1=1";
    $params = [];
    $countParams = [];
    
    if ($active !== '' && in_array($active, ['0', '1'])) {
        $query .= " AND a.is_active = ?";
        $countQuery .= " AND is_active = ?";
        $params[] = (int)$active;
        $countParams[] = (int)$active;
    }
    
    $countStmt = $conn->prepare($countQuery);
    $countStmt->execute($countParams);
    $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $query .= " ORDER BY a.sort_order ASC, a.created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $ads = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $totalPages = ceil($total / $limit);
    
    sendResponse(STATUS_SUCCESS, [
        'advertisements' => $ads,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $limit,
            'total_items' => (int)$total,
            'total_pages' => $totalPages,
            'has_next' => $page < $totalPages,
            'has_prev' => $page > 1
        ]
    ], "Advertisements retrieved successfully");
    
} catch (PDOException $e) {
    error_log("Get advertisements error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve advertisements");
}
?>
