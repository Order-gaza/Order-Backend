<?php
/**
 * Super Admin - Get Vendor By ID (Full Detail)
 * GET: /super_admin/vendors/get_by_id.php?id=<uuid>
 * Headers: Authorization: Bearer <token>
 *
 * Returns: vendor data, owner info, menu categories with items (name + price),
 *          operating hours, logo, cover, all stats.
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
    $user = requireRole($conn, [ROLE_SUPER_ADMIN]);
    
    $vendorId = isset($_GET['id']) ? trim($_GET['id']) : filterRequest("id");
    
    if (empty($vendorId)) {
        sendResponse(STATUS_FAIL, null, "Vendor ID is required");
    }
    
    // Get vendor details with owner info
    $stmt = $conn->prepare("
        SELECT v.id, v.user_id, v.name, v.type, v.description, 
               v.logo_url, v.cover_url, v.phone, v.address, v.city, v.neighborhood,
               v.latitude, v.longitude, v.opening_time, v.closing_time,
               v.is_open,
               CASE
                   WHEN v.is_open = 0 THEN 0
                   WHEN v.opening_time IS NULL OR v.closing_time IS NULL THEN 1
                   WHEN v.opening_time < v.closing_time THEN
                       IF(CURTIME() >= v.opening_time AND CURTIME() < v.closing_time, 1, 0)
                   ELSE
                       IF(CURTIME() >= v.opening_time OR CURTIME() < v.closing_time, 1, 0)
               END AS is_currently_open,
               v.min_order_amount, v.estimated_delivery_time,
               v.operating_hours, v.status, v.rating, v.total_ratings,
               v.commission_rate, v.delivery_zone, v.created_at, v.updated_at,
               u.name AS owner_name, u.email AS owner_email, u.phone AS owner_phone,
               u.is_active AS account_active, u.created_at AS registered_at
        FROM vendors v
        JOIN users u ON u.id = v.user_id
        WHERE v.id = ?
    ");
    $stmt->execute([$vendorId]);
    $vendor = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$vendor) {
        sendResponse(STATUS_FAIL, null, "Vendor not found");
    }
    
    // Get completed orders count (safe — won't fail if table is empty)
    try {
        $ordStmt = $conn->prepare(
            "SELECT COUNT(*) as count FROM order_vendors WHERE vendor_id = ? AND status = 'delivered'"
        );
        $ordStmt->execute([$vendorId]);
        $vendor['completed_orders'] = (int)$ordStmt->fetch(PDO::FETCH_ASSOC)['count'];
    } catch (Exception $e) {
        $vendor['completed_orders'] = 0;
    }
    
    // Get menu categories
    try {
        $catStmt = $conn->prepare(
            "SELECT id, name, sort_order FROM menu_categories WHERE vendor_id = ? ORDER BY sort_order ASC, name ASC"
        );
        $catStmt->execute([$vendorId]);
        $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $categories = [];
    }
    $vendor['categories_count'] = count($categories);
    $vendor['categories'] = $categories;
    
    // Get menu items with category name
    try {
        $itemStmt = $conn->prepare("
            SELECT mi.id, mi.name, mi.base_price, mi.discount_price, mi.image_url,
                   mi.is_available, mc.name AS category_name
            FROM menu_items mi
            LEFT JOIN menu_categories mc ON mc.id = mi.category_id
            WHERE mi.vendor_id = ?
            ORDER BY mc.sort_order ASC, mc.name ASC, mi.name ASC
        ");
        $itemStmt->execute([$vendorId]);
        $items = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $items = [];
    }
    $vendor['items_count'] = count($items);
    $vendor['menu_items'] = $items;
    
    sendResponse(STATUS_SUCCESS, ['vendor' => $vendor], "Vendor retrieved successfully");
    
} catch (PDOException $e) {
    error_log("Get vendor error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve vendor: " . $e->getMessage());
}
?>
