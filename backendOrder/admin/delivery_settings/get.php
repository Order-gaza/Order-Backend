<?php
/**
 * Admin - Get Delivery Fee Settings
 * GET: /admin/delivery_settings/get.php
 * Headers: Authorization: Bearer <token>
 * 
 * Returns the current base delivery fee and multi-vendor extra fee.
 * Falls back to defaults (20, 5) if columns don't exist yet.
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
    
    // Try to read from fee_config
    $stmt = $conn->prepare("SELECT * FROM fee_config ORDER BY updated_at DESC LIMIT 1");
    $stmt->execute();
    $config = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $baseFee = 20.00;
    $extraFee = 5.00;
    
    if ($config) {
        if (isset($config['base_delivery_fee'])) {
            $baseFee = (float)$config['base_delivery_fee'];
        }
        if (isset($config['multi_vendor_extra_fee'])) {
            $extraFee = (float)$config['multi_vendor_extra_fee'];
        }
    }
    
    sendResponse(STATUS_SUCCESS, [
        'base_delivery_fee' => $baseFee,
        'multi_vendor_extra_fee' => $extraFee
    ], "Delivery settings retrieved");
    
} catch (PDOException $e) {
    error_log("Get delivery settings error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve delivery settings");
}
?>
