<?php
/**
 * Super Admin - Get Fee Configuration
 * GET: /super_admin/fee_config/get.php
 * Headers: Authorization: Bearer <token>
 * 
 * DB columns: id, active_model, flat_fee_amount, percentage_value,
 *             model_switch_date, updated_by, updated_at
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
    
    $stmt = $conn->prepare("SELECT * FROM fee_config ORDER BY updated_at DESC LIMIT 1");
    $stmt->execute();
    $config = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$config) {
        sendResponse(STATUS_FAIL, null, "No fee configuration found");
    }
    
    sendResponse(STATUS_SUCCESS, [
        'fee_config' => [
            'id' => $config['id'],
            'active_model' => $config['active_model'],
            'flat_fee_amount' => (float)$config['flat_fee_amount'],
            'percentage_value' => (float)$config['percentage_value'],
            'model_switch_date' => $config['model_switch_date'],
            'updated_by' => $config['updated_by'],
            'updated_at' => $config['updated_at']
        ]
    ], "Fee configuration retrieved");
    
} catch (PDOException $e) {
    error_log("Get fee config error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve fee configuration");
}
?>
