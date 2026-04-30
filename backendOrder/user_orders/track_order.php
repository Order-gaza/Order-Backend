<?php
/**
 * User App - Track Order (Live Status)
 * GET: /user_orders/track_order.php?id=<uuid>
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

    $orderStmt = $conn->prepare("
        SELECT id, order_number, status, delivery_address, created_at, delivered_at
        FROM orders WHERE id = ? AND customer_id = ?
    ");
    $orderStmt->execute([$orderId, $customer['customer_id']]);
    $order = $orderStmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        sendResponse(STATUS_FAIL, null, "Order not found");
    }

    // Status timeline
    $histStmt = $conn->prepare("
        SELECT to_status AS status, created_at AS changed_at, note AS notes 
        FROM order_status_history WHERE order_id = ?
        ORDER BY created_at ASC
    ");
    $histStmt->execute([$orderId]);
    $timeline = $histStmt->fetchAll(PDO::FETCH_ASSOC);

    // Define step progression (in order)
    $steps = [
        ['status' => ORDER_PENDING,            'label' => 'Order Placed'],
        ['status' => ORDER_ADMIN_CONFIRMED,    'label' => 'Confirmed'],
        ['status' => ORDER_SENT_TO_VENDOR,     'label' => 'Sent to Restaurant'],
        ['status' => ORDER_VENDOR_ACCEPTED,    'label' => 'Accepted'],
        ['status' => ORDER_PREPARING,          'label' => 'Preparing'],
        ['status' => ORDER_READY_FOR_PICKUP,   'label' => 'Ready for Pickup'],
        ['status' => ORDER_OUT_FOR_DELIVERY,   'label' => 'On the Way'],
        ['status' => ORDER_DELIVERED,          'label' => 'Delivered'],
    ];

    // Build the ordered status progression map for forward-completion logic
    $statusOrder = array_column($steps, 'status');
    $currentIndex = array_search($order['status'], $statusOrder);

    // Statuses recorded in history (DB trigger fires on UPDATE, not on initial INSERT)
    $completedStatuses = array_column($timeline, 'status');

    // The 'pending' step is never in history (order starts as pending, no transition recorded)
    // Mark it as completed if the order has moved past it
    if ($order['status'] !== ORDER_PENDING && $order['status'] !== ORDER_CANCELLED) {
        $completedStatuses[] = ORDER_PENDING;
    }

    foreach ($steps as &$step) {
        $stepIndex = array_search($step['status'], $statusOrder);
        // A step is completed if it appears in history OR if the order is currently past it
        $isInHistory   = in_array($step['status'], $completedStatuses);
        $isPastCurrent = ($currentIndex !== false && $stepIndex !== false && $stepIndex < $currentIndex);
        $step['completed'] = $isInHistory || $isPastCurrent;
        $step['current']   = ($step['status'] === $order['status']);
    }
    unset($step);

    sendResponse(STATUS_SUCCESS, [
        'order_number'   => $order['order_number'],
        'current_status' => $order['status'],
        'is_cancelled'   => ($order['status'] === ORDER_CANCELLED),
        'steps'          => $steps,
        'timeline'       => $timeline,
    ], "Order tracking info");


} catch (PDOException $e) {
    error_log("Track order error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to track order");
}
?>
