<?php
/**
 * User App - Get Vendor Ratings
 * GET: /user_vendors/get_ratings.php?vendor_id=<uuid>&page=1
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";

handleCorsHeaders();

if (!isRequestMethod('GET') && !isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    $vendorId = isset($_GET['vendor_id']) ? trim($_GET['vendor_id']) : '';
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $limit = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : ITEMS_PER_PAGE;
    $offset = ($page - 1) * $limit;

    if (empty($vendorId)) {
        sendResponse(STATUS_FAIL, null, "Vendor ID is required");
    }

    $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM ratings WHERE vendor_id = ?");
    $countStmt->execute([$vendorId]);
    $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

    $stmt = $conn->prepare("
        SELECT r.id, r.score AS rating, r.comment, r.created_at,
            u.name AS customer_name
        FROM ratings r
        JOIN users u ON r.customer_id = u.id
        WHERE r.vendor_id = ?
        ORDER BY r.created_at DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->execute([$vendorId, $limit, $offset]);
    $ratings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get average
    $avgStmt = $conn->prepare("SELECT AVG(score) as avg_rating FROM ratings WHERE vendor_id = ?");
    $avgStmt->execute([$vendorId]);
    $avg = $avgStmt->fetch(PDO::FETCH_ASSOC);

    $totalPages = ceil($total / $limit);

    sendResponse(STATUS_SUCCESS, [
        'average_rating' => round((float)$avg['avg_rating'], 1),
        'total_ratings' => (int)$total,
        'ratings' => $ratings,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $limit,
            'total_items' => (int)$total,
            'total_pages' => $totalPages
        ]
    ], "Ratings retrieved");

} catch (PDOException $e) {
    error_log("Get ratings error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve ratings");
}
?>
