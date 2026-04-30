<?php
/**
 * Vendor - Mark Order as Ready for Pickup
 * POST: /vendor/orders/mark_ready.php
 * Headers: Authorization: Bearer <token>
 * Body: order_id
 *
 * Multi-vendor safe workflow:
 * - Updates THIS vendor's order_vendors.status → ready_for_pickup + ready_at = NOW()
 * - If ALL vendors are now ready → updates orders.status → ready_for_pickup
 *   and notifies BOTH admin (to dispatch) AND customer
 * - If NOT all vendors ready → notifies admins only (partial progress update)
 *
 * order_vendors.status: preparing → ready_for_pickup
 * orders.status: preparing → ready_for_pickup (only when ALL vendors ready)
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
    $user = requireRole($conn, [ROLE_VENDOR]);

    $vendorStmt = $conn->prepare("SELECT id, name FROM vendors WHERE user_id = ?");
    $vendorStmt->execute([$user['user_id']]);
    $vendor = $vendorStmt->fetch(PDO::FETCH_ASSOC);

    if (!$vendor) {
        sendResponse(STATUS_FAIL, null, "Vendor profile not found");
    }

    $orderId = filterRequest("order_id");
    if (empty($orderId)) {
        sendResponse(STATUS_FAIL, null, "Order ID is required");
    }

    // Check THIS vendor's current status
    $checkStmt = $conn->prepare(
        "SELECT ov.status, o.order_number
         FROM order_vendors ov
         JOIN orders o ON ov.order_id = o.id
         WHERE ov.order_id = ? AND ov.vendor_id = ?"
    );
    $checkStmt->execute([$orderId, $vendor['id']]);
    $orderVendor = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if (!$orderVendor) {
        sendResponse(STATUS_FAIL, null, "Order not found");
    }

    if ($orderVendor['status'] !== ORDER_PREPARING) {
        sendResponse(STATUS_FAIL, null,
            "Order must be in 'preparing' status. Current: " . $orderVendor['status']);
    }

    $orderNumber = $orderVendor['order_number'];

    $conn->beginTransaction();
    try {
        // ── Step 1: Update ONLY this vendor's order_vendors row ──
        $conn->prepare(
            "UPDATE order_vendors
             SET status = ?, ready_at = NOW()
             WHERE order_id = ? AND vendor_id = ?"
        )->execute([ORDER_READY_FOR_PICKUP, $orderId, $vendor['id']]);

        // ── Step 2: Check if ALL vendors for this order are now ready ──
        $allCheckStmt = $conn->prepare(
            "SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) AS ready_count
             FROM order_vendors
             WHERE order_id = ?"
        );
        $allCheckStmt->execute([ORDER_READY_FOR_PICKUP, $orderId]);
        $counts = $allCheckStmt->fetch(PDO::FETCH_ASSOC);

        $allReady = ((int)$counts['total'] > 0 && (int)$counts['total'] === (int)$counts['ready_count']);

        if ($allReady) {
            // All vendors ready → advance the main order status
            $conn->prepare(
                "UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?"
            )->execute([ORDER_READY_FOR_PICKUP, $orderId]);
        }

        $conn->commit();

        logActivity($conn, $user['user_id'], 'mark_ready', 'order', $orderId,
            $allReady ? "All vendors ready for pickup" : "{$vendor['name']} ready, waiting for other vendors");

        // ── Push Notifications ──
        $notifData = [
            'order_id'     => $orderId,
            'order_number' => $orderNumber,
            'status'       => ORDER_READY_FOR_PICKUP
        ];

        if ($allReady) {
            // ALL vendors ready → notify customer AND admin to dispatch
            $customerId = getOrderCustomerId($conn, $orderId);
            if ($customerId) {
                notifyUser($conn, $customerId,
                    'Order Ready!',
                    'Your order #' . $orderNumber . ' is ready for pickup!',
                    'order_status', $notifData);
            }

            // Critical: notify admin that they can now dispatch
            notifyAllAdmins($conn,
                'Ready for Pickup #' . $orderNumber,
                'All vendors ready — dispatch delivery now!',
                'order_status', $notifData);

        } else {
            // Partial readiness → notify admins only with progress info
            $remaining = (int)$counts['total'] - (int)$counts['ready_count'];
            notifyAllAdmins($conn,
                'Vendor Ready #' . $orderNumber,
                $vendor['name'] . ' is ready (' . $remaining . ' vendor(s) still preparing)',
                'order_status', $notifData);
        }

        sendResponse(STATUS_SUCCESS, [
            'order_id'      => $orderId,
            'order_number'  => $orderNumber,
            'vendor_status' => ORDER_READY_FOR_PICKUP,
            'all_vendors_ready' => $allReady
        ], $allReady
            ? "Order ready for pickup — all vendors confirmed"
            : "Order ready — waiting for other vendors");

    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }

} catch (PDOException $e) {
    error_log("Mark ready error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to update order status");
}
?>
