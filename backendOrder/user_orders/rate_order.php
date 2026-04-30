<?php
/**
 * User App - Rate Order (supports multi-vendor orders)
 * POST: /user_orders/rate_order.php
 * Headers: Authorization: Bearer <token>
 * Body: order_id, ratings (JSON array: [{vendor_id, rating, comment?}, ...])
 *   OR (legacy single-vendor): order_id, rating (1-5), comment?
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";
include "../core/middleware.php";

handleCorsHeaders();

if (!isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    $customer = verifyCustomerAuth($conn);

    $orderId = filterRequest("order_id");

    if (empty($orderId)) {
        sendResponse(STATUS_FAIL, null, "Order ID is required");
    }

    // Verify order ownership and delivered status
    $orderStmt = $conn->prepare("SELECT id, status FROM orders WHERE id = ? AND customer_id = ?");
    $orderStmt->execute([$orderId, $customer['customer_id']]);
    $order = $orderStmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        sendResponse(STATUS_FAIL, null, "Order not found");
    }

    if ($order['status'] !== ORDER_DELIVERED) {
        sendResponse(STATUS_FAIL, null, "Only delivered orders can be rated");
    }

    // Get all vendors for this order
    $vendorStmt = $conn->prepare("SELECT ov.vendor_id, v.name as vendor_name FROM order_vendors ov JOIN vendors v ON ov.vendor_id = v.id WHERE ov.order_id = ?");
    $vendorStmt->execute([$orderId]);
    $orderVendors = $vendorStmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($orderVendors)) {
        sendResponse(STATUS_FAIL, null, "No vendors found for this order");
    }

    // Build a set of valid vendor IDs for this order
    $validVendorIds = array_column($orderVendors, 'vendor_id');

    // Parse ratings - support both new multi-vendor format and legacy single format
    $ratingsInput = [];
    $ratingsRaw = filterRequest("ratings", true);

    if (!empty($ratingsRaw)) {
        // New format: JSON array of {vendor_id, rating, comment?}
        if (is_string($ratingsRaw)) {
            $ratingsInput = json_decode($ratingsRaw, true);
        } else {
            $ratingsInput = $ratingsRaw;
        }
        if (!is_array($ratingsInput) || empty($ratingsInput)) {
            sendResponse(STATUS_FAIL, null, "Invalid ratings format");
        }
    } else {
        // Legacy single-vendor format
        $rating = filterRequest("rating");
        $comment = filterRequest("comment");

        if (empty($rating)) {
            sendResponse(STATUS_FAIL, null, "Rating is required");
        }

        // Use the first vendor (legacy behavior)
        $ratingsInput = [
            [
                'vendor_id' => $orderVendors[0]['vendor_id'],
                'rating' => $rating,
                'comment' => $comment
            ]
        ];
    }

    // Validate all ratings
    foreach ($ratingsInput as &$r) {
        $rVal = intval($r['rating']);
        if ($rVal < 1 || $rVal > 5) {
            sendResponse(STATUS_FAIL, null, "Rating must be between 1 and 5");
        }
        $r['rating'] = $rVal;

        if (!empty($r['vendor_id']) && !in_array($r['vendor_id'], $validVendorIds)) {
            sendResponse(STATUS_FAIL, null, "Invalid vendor for this order");
        }
    }
    unset($r);

    // Check which vendors are already rated
    $checkStmt = $conn->prepare("SELECT vendor_id FROM ratings WHERE order_id = ? AND customer_id = ?");
    $checkStmt->execute([$orderId, $customer['customer_id']]);
    $alreadyRated = $checkStmt->fetchAll(PDO::FETCH_COLUMN, 0);

    // Filter out already-rated vendors
    $toInsert = [];
    foreach ($ratingsInput as $r) {
        if (!in_array($r['vendor_id'], $alreadyRated)) {
            $toInsert[] = $r;
        }
    }

    if (empty($toInsert)) {
        sendResponse(STATUS_FAIL, null, "All vendors in this order have already been rated");
    }

    $conn->beginTransaction();
    try {
        $insertStmt = $conn->prepare("
            INSERT INTO ratings (order_id, customer_id, vendor_id, score, comment, created_at)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");

        $results = [];
        $updatedVendors = [];

        foreach ($toInsert as $r) {
            $insertStmt->execute([
                $orderId,
                $customer['customer_id'],
                $r['vendor_id'],
                $r['rating'],
                !empty($r['comment']) ? $r['comment'] : null
            ]);

            // Update vendor average rating
            $avgStmt = $conn->prepare("SELECT AVG(score) as avg_r, COUNT(*) as cnt FROM ratings WHERE vendor_id = ?");
            $avgStmt->execute([$r['vendor_id']]);
            $avg = $avgStmt->fetch(PDO::FETCH_ASSOC);

            $conn->prepare("UPDATE vendors SET rating = ?, total_ratings = ?, updated_at = NOW() WHERE id = ?")
                 ->execute([round($avg['avg_r'], 2), $avg['cnt'], $r['vendor_id']]);

            $results[] = [
                'vendor_id' => $r['vendor_id'],
                'rating' => $r['rating'],
                'vendor_avg_rating' => round((float)$avg['avg_r'], 1)
            ];
            $updatedVendors[] = $r['vendor_id'];
        }

        $conn->commit();

        sendResponse(STATUS_SUCCESS, [
            'ratings' => $results,
            'rated_count' => count($results)
        ], "Rating submitted");

    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }

} catch (PDOException $e) {
    error_log("Rate order error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to submit rating");
}
?>
