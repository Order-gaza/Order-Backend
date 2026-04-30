<?php
/**
 * User App - Get Order Detail
 * GET: /user_orders/get_order_detail.php?id=<uuid>
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

    $orderId = isset($_GET['id']) ? trim($_GET['id']) : filterRequest("id");
    if (empty($orderId)) {
        sendResponse(STATUS_FAIL, null, "Order ID is required");
    }

    // Get order (verify ownership)
    $orderStmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND customer_id = ?");
    $orderStmt->execute([$orderId, $customer['customer_id']]);
    $order = $orderStmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        sendResponse(STATUS_FAIL, null, "Order not found");
    }

    // Get vendor info
    $vendorStmt = $conn->prepare("
        SELECT v.id as vendor_id, v.name, v.logo_url, v.phone, v.address, ov.id as order_vendor_id, ov.status as order_vendor_status, ov.subtotal
        FROM order_vendors ov JOIN vendors v ON ov.vendor_id = v.id
        WHERE ov.order_id = ?
    ");
    $vendorStmt->execute([$orderId]);
    $vendorsRaw = $vendorStmt->fetchAll(PDO::FETCH_ASSOC);

    // Get order items — use snapshot item_name to avoid breakage if menu items are deleted
    $itemsStmt = $conn->prepare("
        SELECT oi.id, oi.order_vendor_id, oi.menu_item_id,
               oi.item_name, oi.unit_price, oi.quantity, oi.line_total,
               oi.special_instructions, oi.created_at,
               mi.image_url AS item_image,
               ov.vendor_id
        FROM order_items oi
        JOIN order_vendors ov ON oi.order_vendor_id = ov.id
        LEFT JOIN menu_items mi ON oi.menu_item_id = mi.id
        WHERE ov.order_id = ?
    ");
    $itemsStmt->execute([$orderId]);
    $itemsRaw = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

    $vendors = [];
    foreach ($vendorsRaw as $v) {
        $v['items'] = [];
        foreach ($itemsRaw as $item) {
            if ($item['vendor_id'] === $v['vendor_id']) {
                $v['items'][] = $item;
            }
        }
        $vendors[] = $v;
    }

    // Get status history
    $histStmt = $conn->prepare("
        SELECT to_status AS status, created_at AS changed_at, note AS notes FROM order_status_history 
        WHERE order_id = ? ORDER BY created_at ASC
    ");
    $histStmt->execute([$orderId]);
    $history = $histStmt->fetchAll(PDO::FETCH_ASSOC);

    sendResponse(STATUS_SUCCESS, [
        'order' => $order,
        'vendors' => $vendors,
        'status_history' => $history
    ], "Order detail retrieved");

} catch (PDOException $e) {
    error_log("Order detail error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve order");
}
?>
