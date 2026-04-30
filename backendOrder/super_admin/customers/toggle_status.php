<?php
/**
 * Super Admin - Toggle Customer Status
 * POST: /super_admin/customers/toggle_status.php
 * Headers: Authorization: Bearer <token>
 * Body: customer_id
 */

include "../../config/database.php";
include "../../config/constants.php";
include "../../core/functions.php";
include "../../core/middleware.php";

if (!isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    $user = requireRole($conn, [ROLE_SUPER_ADMIN]);
    
    $customerId = filterRequest("customer_id");
    
    if (empty($customerId)) {
        sendResponse(STATUS_FAIL, null, "Customer ID is required");
    }
    
    $checkStmt = $conn->prepare("SELECT id, name, is_active FROM users WHERE id = ? AND role = ?");
    $checkStmt->execute([$customerId, ROLE_CUSTOMER]);
    $customer = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$customer) {
        sendResponse(STATUS_FAIL, null, "Customer not found");
    }
    
    $newStatus = $customer['is_active'] ? 0 : 1;
    
    $updateStmt = $conn->prepare("UPDATE users SET is_active = ?, updated_at = NOW() WHERE id = ?");
    $updateStmt->execute([$newStatus, $customerId]);
    
    // If deactivating, kill their sessions
    if ($newStatus === 0) {
        $conn->prepare("DELETE FROM admin_sessions WHERE user_id = ?")->execute([$customerId]);
    }
    
    $statusText = $newStatus ? 'activated' : 'deactivated';
    logActivity($conn, $user['user_id'], $statusText, 'customer', $customerId,
        ucfirst($statusText) . " customer: {$customer['name']}");
    
    sendResponse(STATUS_SUCCESS, [
        'customer_id' => $customerId,
        'is_active' => (bool)$newStatus
    ], "Customer {$statusText} successfully");
    
} catch (PDOException $e) {
    error_log("Toggle customer status error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to toggle customer status");
}
?>
