<?php
/**
 * Profile - Get Current User Profile
 * GET: /profile/get.php
 * Headers: Authorization: Bearer <token>
 * 
 * Works for any authenticated role (super_admin, admin, vendor).
 * Returns flat user data matching the Flutter ProfileController expectations.
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";
include "../core/middleware.php";

if (!isRequestMethod('GET') && !isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    $user = verifyAuth($conn);
    
    // Get user data
    $stmt = $conn->prepare("
        SELECT id, name, email, phone, role, is_active, created_at, updated_at 
        FROM users WHERE id = ?
    ");
    $stmt->execute([$user['user_id']]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$userData) {
        sendResponse(STATUS_FAIL, null, "User not found");
    }
    
    // Return flat structure so controller can read d['name'], d['email'], etc.
    $result = [
        'id' => $userData['id'],
        'name' => $userData['name'],
        'email' => $userData['email'],
        'phone' => $userData['phone'],
        'role' => $userData['role'],
        'is_active' => (bool)$userData['is_active'],
        'created_at' => $userData['created_at'],
        'updated_at' => $userData['updated_at']
    ];
    
    // If vendor, include vendor profile under 'vendor' key
    if ($userData['role'] === ROLE_VENDOR) {
        $vendorStmt = $conn->prepare("
            SELECT id, name, phone, address, description, type, status, 
                   is_open, rating, total_ratings, logo_url, cover_url,
                   opening_time, closing_time, estimated_delivery_time,
                   min_order_amount, operating_hours, commission_rate,
                   CASE
                       WHEN is_open = 0 THEN 0
                       WHEN opening_time IS NULL OR closing_time IS NULL THEN 1
                       WHEN opening_time < closing_time THEN
                           IF(CURTIME() >= opening_time AND CURTIME() < closing_time, 1, 0)
                       ELSE
                           IF(CURTIME() >= opening_time OR CURTIME() < closing_time, 1, 0)
                   END AS is_currently_open
            FROM vendors WHERE user_id = ?
        ");
        $vendorStmt->execute([$user['user_id']]);
        $vendor = $vendorStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($vendor) {
            $vendor['is_open'] = (int)$vendor['is_open'];
            $vendor['rating'] = (float)($vendor['rating'] ?? 0);
            $vendor['total_ratings'] = (int)($vendor['total_ratings'] ?? 0);
            $vendor['commission_rate'] = (float)($vendor['commission_rate'] ?? 0);
            $result['vendor'] = $vendor;
        }
    }
    
    sendResponse(STATUS_SUCCESS, $result, "Profile retrieved successfully");
    
} catch (PDOException $e) {
    error_log("Get profile error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve profile");
}
?>
