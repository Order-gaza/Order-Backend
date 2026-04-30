<?php
/**
 * User App - Get My Orders
 * GET: /user_orders/get_orders.php?page=1&status=
 * Headers: Authorization: Bearer <token>
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";
include "../core/middleware.php";

handleCorsHeaders();

if (!isRequestMethod('GET') && !isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    $customer = verifyCustomerAuth($conn);

    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $limit = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : ITEMS_PER_PAGE;
    $status = isset($_GET['status']) ? trim($_GET['status']) : '';
    $offset = ($page - 1) * $limit;

    $query = "SELECT id, order_number, status, subtotal, delivery_fee, total_amount, 
              payment_method, payment_status, delivery_address, created_at, delivered_at 
              FROM orders WHERE customer_id = ?";
    $countQuery = "SELECT COUNT(*) as total FROM orders WHERE customer_id = ?";
    $params = [$customer['customer_id']];
    $countParams = [$customer['customer_id']];

    if (!empty($status)) {
        $query .= " AND status = ?";
        $countQuery .= " AND status = ?";
        $params[] = $status;
        $countParams[] = $status;
    }

    $countStmt = $conn->prepare($countQuery);
    $countStmt->execute($countParams);
    $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

    $query .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;

    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Add vendor info to each order
    foreach ($orders as &$order) {
        $vStmt = $conn->prepare("
            SELECT v.name AS vendor_name, v.logo_url AS vendor_logo
            FROM order_vendors ov JOIN vendors v ON ov.vendor_id = v.id
            WHERE ov.order_id = ?
        ");
        $vStmt->execute([$order['id']]);
        $vendors = $vStmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($vendors) > 1) {
            $order['vendor_name'] = $vendors[0]['vendor_name'] . ' + ' . (count($vendors) - 1) . ' more';
            $order['vendor_logo'] = $vendors[0]['vendor_logo'];
        } else if (count($vendors) === 1) {
            $order['vendor_name'] = $vendors[0]['vendor_name'];
            $order['vendor_logo'] = $vendors[0]['vendor_logo'];
        } else {
            $order['vendor_name'] = null;
            $order['vendor_logo'] = null;
        }
    }

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
    error_log("Get orders error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve orders");
}
?>
