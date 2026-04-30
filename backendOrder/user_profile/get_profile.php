<?php
/**
 * User App - Get Profile
 * GET: /user_profile/get_profile.php
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
    $customer = verifyCustomerAuth($conn);
    
    $stmt = $conn->prepare("
        SELECT id, name, email, phone, is_active, email_verified_at, created_at, updated_at
        FROM users WHERE id = ?
    ");
    $stmt->execute([$customer['customer_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get profile extras
    $profileStmt = $conn->prepare("SELECT profile_image_url, default_address_id FROM customer_profiles WHERE user_id = ?");
    $profileStmt->execute([$customer['customer_id']]);
    $profile = $profileStmt->fetch(PDO::FETCH_ASSOC);

    // Get order stats
    $statsStmt = $conn->prepare("
        SELECT COUNT(*) as total_orders, COALESCE(SUM(total_amount), 0) as total_spent
        FROM orders WHERE customer_id = ? AND status = 'delivered'
    ");
    $statsStmt->execute([$customer['customer_id']]);
    $stats = $statsStmt->fetch(PDO::FETCH_ASSOC);

    sendResponse(STATUS_SUCCESS, [
        'user' => [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'phone' => $user['phone'],
            'profile_image' => $profile['profile_image_url'] ?? null,
            'email_verified' => !empty($user['email_verified_at']),
            'total_orders' => (int)$stats['total_orders'],
            'total_spent' => (float)$stats['total_spent'],
            'created_at' => formatDateTime($user['created_at'])
        ]
    ], "Profile retrieved");

} catch (PDOException $e) {
    error_log("Get profile error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve profile");
}
?>
