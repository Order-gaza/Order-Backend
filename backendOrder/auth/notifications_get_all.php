<?php
/**
 * Admin/Vendor/SA - Get All Notifications
 * GET: /auth/notifications_get_all.php?page=1
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
    $user = verifyAuth($conn);

    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $limit = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : ITEMS_PER_PAGE;
    $offset = ($page - 1) * $limit;

    $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM notifications WHERE user_id = ?");
    $countStmt->execute([$user['user_id']]);
    $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

    $stmt = $conn->prepare("
        SELECT id, title, body, type, data, is_read, created_at 
        FROM notifications 
        WHERE user_id = ?
        ORDER BY created_at DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->execute([$user['user_id'], $limit, $offset]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalPages = ceil($total / $limit);

    sendResponse(STATUS_SUCCESS, [
        'notifications' => $notifications,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $limit,
            'total_items' => (int)$total,
            'total_pages' => $totalPages
        ]
    ], "Notifications retrieved");

} catch (PDOException $e) {
    error_log("Get notifications error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve notifications");
}
?>
