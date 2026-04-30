<?php
/**
 * User App - Record Advertisement Click
 * POST: /user_home/record_ad_click.php
 * Headers: Authorization: Bearer <token> (optional)
 * Body: ad_id (required)
 * 
 * Lightweight fire-and-forget endpoint.
 * Works with or without auth — if logged in, records user_id.
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";
include "../core/middleware.php";

if (!isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$adId = filterRequest("ad_id");

if (empty($adId)) {
    sendResponse(STATUS_FAIL, null, "ad_id is required");
}

$db = new Database();
$conn = $db->connect();

try {
    // Verify the ad exists
    $checkStmt = $conn->prepare("SELECT id FROM advertisements WHERE id = ?");
    $checkStmt->execute([$adId]);
    if (!$checkStmt->fetch()) {
        sendResponse(STATUS_FAIL, null, "Advertisement not found");
    }

    // Try to get user_id if authenticated (optional — don't fail if not logged in)
    $userId = null;
    $token = extractToken();
    if ($token) {
        try {
            $stmt = $conn->prepare("
                SELECT s.user_id 
                FROM admin_sessions s
                INNER JOIN users u ON s.user_id = u.id
                WHERE s.token = ? AND s.expires_at > NOW() AND u.is_active = 1
            ");
            $stmt->execute([$token]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $userId = $row['user_id'];
            }
        } catch (PDOException $e) {
            // Silently ignore auth errors — click still gets recorded
        }
    }

    // Record the click
    $insertStmt = $conn->prepare("
        INSERT INTO ad_clicks (ad_id, user_id, clicked_at) 
        VALUES (?, ?, NOW())
    ");
    $insertStmt->execute([$adId, $userId]);

    sendResponse(STATUS_SUCCESS, null, "Click recorded");

} catch (PDOException $e) {
    error_log("Record ad click error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to record click");
}
?>
