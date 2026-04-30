<?php
/**
 * Vendor - Mark Order as Preparing
 * POST: /vendor/orders/mark_preparing.php
 * Headers: Authorization: Bearer <token>
 * Body: order_id
 *
 * Multi-vendor safe workflow:
 * - Updates THIS vendor's order_vendors.status → preparing
 * - If ALL vendors are now preparing → updates orders.status → preparing
 *   and notifies customer
 * - If NOT all vendors preparing yet → no customer notification
 *
 * order_vendors.status: vendor_accepted → preparing
 * orders.status: vendor_accepted → preparing (only when ALL vendors preparing)
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

    $vendorStmt = $conn->prepare("SELECT id FROM vendors WHERE user_id = ?");
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

    if ($orderVendor['status'] !== ORDER_VENDOR_ACCEPTED) {
        sendResponse(STATUS_FAIL, null,
            "Order must be in 'vendor_accepted' status. Current: " . $orderVendor['status']);
    }

    $orderNumber = $orderVendor['order_number'];

    $conn->beginTransaction();
    try {
        // ── Step 1: Update ONLY this vendor's order_vendors row ──
        $conn->prepare(
            "UPDATE order_vendors SET status = ? WHERE order_id = ? AND vendor_id = ?"
        )->execute([ORDER_PREPARING, $orderId, $vendor['id']]);

        // ── Step 2: Check if ALL vendors for this order are now preparing ──
        $allCheckStmt = $conn->prepare(
            "SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) AS preparing_count
             FROM order_vendors
             WHERE order_id = ?"
        );
        $allCheckStmt->execute([ORDER_PREPARING, $orderId]);
        $counts = $allCheckStmt->fetch(PDO::FETCH_ASSOC);

        $allPreparing = ((int)$counts['total'] > 0 && (int)$counts['total'] === (int)$counts['preparing_count']);

        if ($allPreparing) {
            // All vendors are preparing → advance the main order status
            $conn->prepare(
                "UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?"
            )->execute([ORDER_PREPARING, $orderId]);
        }

        $conn->commit();

        logActivity($conn, $user['user_id'], 'mark_preparing', 'order', $orderId, "Order being prepared");

        // ── Push Notifications ──
        $notifData = [
            'order_id'     => $orderId,
            'order_number' => $orderNumber,
            'status'       => ORDER_PREPARING
        ];

        if ($allPreparing) {
            // ALL vendors preparing → notify customer
            $customerId = getOrderCustomerId($conn, $orderId);
            if ($customerId) {
                notifyUser($conn, $customerId,
                    '👨‍🍳 Order Being Prepared',
                    'Your order #' . $orderNumber . ' is now being prepared!',
                    'order_status', $notifData);
            }
        }
        // If not all preparing yet → no notification (avoid premature customer updates)

        sendResponse(STATUS_SUCCESS, [
            'order_id'      => $orderId,
            'order_number'  => $orderNumber,
            'vendor_status' => ORDER_PREPARING,
            'all_vendors_preparing' => $allPreparing
        ], $allPreparing
            ? "Order marked as preparing — all vendors confirmed"
            : "Order marked as preparing — waiting for other vendors");

    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }

} catch (PDOException $e) {
    error_log("Mark preparing error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to update order status");
}
?>
