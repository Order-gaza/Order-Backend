<?php
/**
 * Vendor - Get All Own Orders
 * GET: /vendor/orders/get_all.php?page=1&limit=20&status=
 * Headers: Authorization: Bearer <token>
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
    
    $vendorStmt = $conn->prepare("SELECT id FROM vendors WHERE user_id = ?");
    $vendorStmt->execute([$user['user_id']]);
    $vendor = $vendorStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$vendor) {
        sendResponse(STATUS_FAIL, null, "Vendor profile not found");
    }
    
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $limit = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : ITEMS_PER_PAGE;
    $status = isset($_GET['status']) ? trim($_GET['status']) : '';
    $offset = ($page - 1) * $limit;
    
    $query = "
        SELECT o.id, o.order_number, ov.status AS status,
            o.status AS order_status,
            o.total_amount, o.delivery_fee, o.payment_method, o.payment_status,
            o.delivery_address, o.created_at, o.customer_notes,
            ov.subtotal AS vendor_subtotal,
            u.name AS customer_name, u.phone AS customer_phone
        FROM order_vendors ov
        JOIN orders o ON ov.order_id = o.id
        JOIN users u ON o.customer_id = u.id
        WHERE ov.vendor_id = ? AND ov.status != 'pending'
    ";
    $countQuery = "SELECT COUNT(*) as total FROM order_vendors WHERE vendor_id = ? AND status != 'pending'";
    $params = [$vendor['id']];
    $countParams = [$vendor['id']];
    
    if (!empty($status)) {
        $query .= " AND ov.status = ?";
        $countQuery .= " AND status = ?";
        $params[] = $status;
        $countParams[] = $status;
    }
    
    $countStmt = $conn->prepare($countQuery);
    $countStmt->execute($countParams);
    $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $query .= " ORDER BY o.created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $totalPages = ceil($total / $limit);
    
    sendResponse(STATUS_SUCCESS, [
        'orders' => $orders,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $limit,
            'total_items' => (int)$total,
            'total_pages' => $totalPages,
            'has_next' => $page < $totalPages,
            'has_prev' => $page > 1
        ]
    ], "Orders retrieved");
    
} catch (PDOException $e) {
    error_log("Vendor get orders error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve orders");
}
?>
