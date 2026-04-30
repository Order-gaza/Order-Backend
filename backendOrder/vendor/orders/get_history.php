<?php
/**
 * Vendor - Get Order History (delivered + cancelled)
 * GET: /vendor/orders/get_history.php?page=1&limit=20
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
    $offset = ($page - 1) * $limit;
    
    $countStmt = $conn->prepare("
        SELECT COUNT(*) as total FROM order_vendors 
        WHERE vendor_id = ? AND status IN ('delivered', 'cancelled')
    ");
    $countStmt->execute([$vendor['id']]);
    $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $stmt = $conn->prepare("
        SELECT o.id AS order_id, o.order_number, o.status AS order_status,
            o.total_amount, o.created_at AS order_created_at, o.delivered_at,
            ov.subtotal AS vendor_subtotal, ov.status AS vendor_order_status,
            u.name AS customer_name
        FROM order_vendors ov
        JOIN orders o ON ov.order_id = o.id
        JOIN users u ON o.customer_id = u.id
        WHERE ov.vendor_id = ? AND ov.status IN ('delivered', 'cancelled')
        ORDER BY o.created_at DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->execute([$vendor['id'], $limit, $offset]);
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
    ], "Order history retrieved");
    
} catch (PDOException $e) {
    error_log("Vendor order history error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve order history");
}
?>
