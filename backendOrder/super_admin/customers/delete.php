<?php
/**
 * Super Admin - Delete Customer
 * POST: /super_admin/customers/delete.php
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
    
    // Get customer info
    $infoStmt = $conn->prepare("SELECT name, email, role FROM users WHERE id = ? AND role = ?");
    $infoStmt->execute([$customerId, ROLE_CUSTOMER]);
    $customerInfo = $infoStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$customerInfo) {
        sendResponse(STATUS_FAIL, null, "Customer not found");
    }
    
    // Check if customer has orders
    $orderCheck = $conn->prepare("SELECT COUNT(*) as cnt FROM orders WHERE customer_id = ?");
    $orderCheck->execute([$customerId]);
    $orderCount = (int)$orderCheck->fetch(PDO::FETCH_ASSOC)['cnt'];
    
    if ($orderCount > 0) {
        // Soft delete — just deactivate instead of hard delete
        $conn->prepare("UPDATE users SET is_active = 0, updated_at = NOW() WHERE id = ?")->execute([$customerId]);
        $conn->prepare("DELETE FROM admin_sessions WHERE user_id = ?")->execute([$customerId]);
        
        logActivity($conn, $user['user_id'], 'deactivate', 'customer', $customerId,
            "Deactivated customer with orders: {$customerInfo['name']} ({$customerInfo['email']})");
        
        sendResponse(STATUS_SUCCESS, null, 
            "Customer has {$orderCount} order(s). Account deactivated instead of deleted to preserve order history.");
    } else {
        // Hard delete — no orders, safe to remove
        $conn->prepare("DELETE FROM admin_sessions WHERE user_id = ?")->execute([$customerId]);
        $conn->prepare("DELETE FROM customer_addresses WHERE user_id = ?")->execute([$customerId]);
        
        $deleteStmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role = ?");
        $deleteStmt->execute([$customerId, ROLE_CUSTOMER]);
        
        if ($deleteStmt->rowCount() > 0) {
            logActivity($conn, $user['user_id'], 'delete', 'customer', $customerId,
                "Deleted customer: {$customerInfo['name']} ({$customerInfo['email']})");
            
            sendResponse(STATUS_SUCCESS, null, "Customer deleted successfully");
        } else {
            sendResponse(STATUS_FAIL, null, "Failed to delete customer");
        }
    }
    
} catch (PDOException $e) {
    error_log("Delete customer error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "An error occurred while deleting customer");
}
?>
