<?php
/**
 * Admin - Dispatch Order (Out for Delivery)
 * POST: /admin/orders/dispatch.php
 * Headers: Authorization: Bearer <token>
 * Body: order_id
 *
 * Marks order as out_for_delivery.
 * Multi-vendor safe:
 * - Verifies orders.status = 'ready_for_pickup' (set only once ALL vendors are ready)
 * - Double-checks ALL order_vendors rows are 'ready_for_pickup' (safety guard)
 * - Only then updates orders.status and all order_vendors.status → out_for_delivery
 *
 * Workflow: ready_for_pickup → out_for_delivery
 */

include "../../config/database.php";
include "../../config/constants.php";
include "../../core/functions.php";
include "../../core/middleware.php";
include "../../core/notification_helper.php";

if (!isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    $user = requireRole($conn, [ROLE_SUPER_ADMIN, ROLE_ADMIN]);

    $orderId = filterRequest("order_id");

    if (empty($orderId)) {
        sendResponse(STATUS_FAIL, null, "Order ID is required");
    }

    $stmt = $conn->prepare("SELECT id, order_number, status FROM orders WHERE id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        sendResponse(STATUS_FAIL, null, "Order not found");
    }

    if ($order['status'] !== ORDER_READY_FOR_PICKUP) {
        sendResponse(STATUS_FAIL, null,
            "Order can only be dispatched from 'ready_for_pickup' status. Current: " . $order['status']);
    }

    // ── Safety double-check: verify ALL vendors are actually ready ──
    $vendorCheckStmt = $conn->prepare(
        "SELECT COUNT(*) AS total,
                SUM(CASE WHEN status = 'ready_for_pickup' THEN 1 ELSE 0 END) AS ready_count
         FROM order_vendors
         WHERE order_id = ?"
    );
    $vendorCheckStmt->execute([$orderId]);
    $vendorCounts = $vendorCheckStmt->fetch(PDO::FETCH_ASSOC);

    if ((int)$vendorCounts['total'] === 0) {
        sendResponse(STATUS_FAIL, null, "No vendors found for this order");
    }

    if ((int)$vendorCounts['total'] !== (int)$vendorCounts['ready_count']) {
        // Get names of vendors that are not ready
        $notReadyStmt = $conn->prepare(
            "SELECT v.name, ov.status
             FROM order_vendors ov
             JOIN vendors v ON ov.vendor_id = v.id
             WHERE ov.order_id = ? AND ov.status != 'ready_for_pickup'"
        );
        $notReadyStmt->execute([$orderId]);
        $notReadyVendors = $notReadyStmt->fetchAll(PDO::FETCH_ASSOC);
        $names = array_map(function($nrv) {
            return $nrv['name'] . ' (' . $nrv['status'] . ')';
        }, $notReadyVendors);
        $notReadyStr = implode(', ', $names) ?: 'unknown vendors';

        sendResponse(STATUS_FAIL, null,
            "Cannot dispatch — not all vendors are ready. Still preparing: " . $notReadyStr);
    }

    // ── All vendors confirmed ready — proceed with dispatch ──
    $conn->beginTransaction();

    try {
        $conn->prepare("UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?")
             ->execute([ORDER_OUT_FOR_DELIVERY, $orderId]);

        $conn->prepare("UPDATE order_vendors SET status = ? WHERE order_id = ?")
             ->execute([ORDER_OUT_FOR_DELIVERY, $orderId]);

        $conn->commit();
    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }

    logActivity($conn, $user['user_id'], 'dispatch', 'order', $orderId,
        "Dispatched order {$order['order_number']} for delivery");

    // ── Push Notifications ──
    $notifData = [
        'order_id'     => $orderId,
        'order_number' => $order['order_number'],
        'status'       => ORDER_OUT_FOR_DELIVERY
    ];

    // Notify customer
    $customerId = getOrderCustomerId($conn, $orderId);
    if ($customerId) {
        notifyUser($conn, $customerId,
            'Your Order is On the Way!',
            'Order #' . $order['order_number'] . ' is out for delivery.',
            'order_status', $notifData);
    }

    // Notify vendors
    notifyOrderVendors($conn, $orderId,
        'Order Dispatched #' . $order['order_number'],
        'Order has been dispatched for delivery.',
        'order_status', $notifData);

    sendResponse(STATUS_SUCCESS, [
        'order_id'     => $orderId,
        'order_number' => $order['order_number'],
        'status'       => ORDER_OUT_FOR_DELIVERY
    ], "Order dispatched for delivery");

} catch (PDOException $e) {
    error_log("Dispatch order error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to dispatch order: " . $e->getMessage());
} catch (Exception $e) {
    error_log("Dispatch order general error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to dispatch order: " . $e->getMessage());
}
?>
