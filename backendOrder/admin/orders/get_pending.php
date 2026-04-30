<?php
/**
 * Admin - Get Pending Orders
 * GET: /admin/orders/get_pending.php
 * Headers: Authorization: Bearer <token>
 * 
 * Returns orders that need admin action (status = 'pending').
 * This is the admin's primary work queue.
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
    $user = requireRole($conn, [ROLE_SUPER_ADMIN, ROLE_ADMIN]);
    
    // Zone-based filtering for branch admin accounts
    $zoneCoverage = null;
    try {
        $zcStmt = $conn->prepare("SELECT zone_coverage FROM users WHERE id = ?");
        $zcStmt->execute([$user['user_id']]);
        $zcRow = $zcStmt->fetch(PDO::FETCH_ASSOC);
        if ($zcRow) $zoneCoverage = $zcRow['zone_coverage'] ?? null;
    } catch (PDOException $e) {
        error_log("zone_coverage column check: " . $e->getMessage());
        // Column may not exist yet — ignore, show all orders
    }
    $zoneFilter = "";
    if ($zoneCoverage === 'north') {
        $zoneFilter = " AND (delivery_zone_to = 'north' OR delivery_zone_to IS NULL)";
    } elseif ($zoneCoverage === 'south') {
        $zoneFilter = " AND (delivery_zone_to IN ('central', 'south') OR delivery_zone_to IS NULL)";
    }
    
    $stmt = $conn->prepare("
        SELECT * FROM view_admin_orders 
        WHERE status = 'pending' $zoneFilter
        ORDER BY created_at ASC
    ");
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    sendResponse(STATUS_SUCCESS, [
        'orders' => $orders,
        'total' => count($orders)
    ], "Pending orders retrieved");
    
} catch (PDOException $e) {
    error_log("Admin pending orders error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve pending orders");
}
?>
