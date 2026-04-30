<?php
/**
 * Vendor - Accept Order
 * POST: /vendor/orders/accept.php
 * Headers: Authorization: Bearer <token>
 * Body: order_id
 *
 * Multi-vendor safe workflow:
 * - Updates THIS vendor's order_vendors.status → vendor_accepted
 * - If ALL vendors have accepted → updates orders.status → vendor_accepted
 *   and notifies customer + admins
 * - If NOT all vendors accepted yet → notifies admins only (partial progress)
 *
 * order_vendors.status: sent_to_vendor → vendor_accepted
 * orders.status: sent_to_vendor → vendor_accepted (only when ALL vendors accepted)
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

    // Verify vendor access and check THIS vendor's current status
    $checkStmt = $conn->prepare(
        "SELECT ov.id, ov.status, o.order_number
         FROM order_vendors ov
         JOIN orders o ON ov.order_id = o.id
         WHERE ov.order_id = ? AND ov.vendor_id = ?"
    );
    $checkStmt->execute([$orderId, $vendor['id']]);
    $orderVendor = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if (!$orderVendor) {
        sendResponse(STATUS_FAIL, null, "Order not found");
    }

    if ($orderVendor['status'] !== ORDER_SENT_TO_VENDOR) {
        sendResponse(STATUS_FAIL, null,
            "Order can only be accepted from 'sent_to_vendor' status. Current: " . $orderVendor['status']);
    }

    $orderNumber = $orderVendor['order_number'];

    $conn->beginTransaction();
    try {
        // ── Step 1: Update ONLY this vendor's order_vendors row ──
        $conn->prepare(
            "UPDATE order_vendors
             SET status = ?, accepted_at = NOW()
             WHERE order_id = ? AND vendor_id = ?"
        )->execute([ORDER_VENDOR_ACCEPTED, $orderId, $vendor['id']]);

        // ── Step 2: Check if ALL vendors for this order have now accepted ──
        $allCheckStmt = $conn->prepare(
            "SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) AS accepted_count
             FROM order_vendors
             WHERE order_id = ?"
        );
        $allCheckStmt->execute([ORDER_VENDOR_ACCEPTED, $orderId]);
        $counts = $allCheckStmt->fetch(PDO::FETCH_ASSOC);

        $allAccepted = ((int)$counts['total'] > 0 && (int)$counts['total'] === (int)$counts['accepted_count']);

        if ($allAccepted) {
            // All vendors accepted → advance the main order status
            $conn->prepare(
                "UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?"
            )->execute([ORDER_VENDOR_ACCEPTED, $orderId]);
        }

        $conn->commit();

        logActivity($conn, $user['user_id'], 'accept', 'order', $orderId,
            "Vendor {$vendor['name']} accepted order");

        // ── Push Notifications ──
        $notifData = [
            'order_id'     => $orderId,
            'order_number' => $orderNumber,
            'status'       => ORDER_VENDOR_ACCEPTED
        ];

        if ($allAccepted) {
            // ALL vendors accepted → notify customer + admins
            $customerId = getOrderCustomerId($conn, $orderId);
            if ($customerId) {
                notifyUser($conn, $customerId,
                    '🍳 Order Accepted!',
                    'All vendors accepted your order #' . $orderNumber,
                    'order_status', $notifData);
            }

            notifyAllAdmins($conn,
                'All Vendors Accepted #' . $orderNumber,
                'All vendors have accepted order #' . $orderNumber,
                'order_status', $notifData);

        } else {
            // Partial acceptance → notify admins only (not customer yet)
            $remaining = (int)$counts['total'] - (int)$counts['accepted_count'];
            notifyAllAdmins($conn,
                'Vendor Accepted #' . $orderNumber,
                $vendor['name'] . ' accepted the order (' . $remaining . ' vendor(s) still pending)',
                'order_status', $notifData);
        }

        sendResponse(STATUS_SUCCESS, [
            'order_id'     => $orderId,
            'order_number' => $orderNumber,
            'vendor_status' => ORDER_VENDOR_ACCEPTED,
            'all_vendors_accepted' => $allAccepted
        ], $allAccepted ? "Order accepted — all vendors confirmed" : "Order accepted — waiting for other vendors");

    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }

} catch (PDOException $e) {
    error_log("Vendor accept order error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to accept order");
}
?>
