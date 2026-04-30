<?php
/**
 * Admin - Update Delivery Fee Settings
 * POST: /admin/delivery_settings/update.php
 * Headers: Authorization: Bearer <token>
 * Body: base_delivery_fee, multi_vendor_extra_fee
 * 
 * Updates the delivery fee values in fee_config.
 * Only modifies delivery-related fields — does NOT touch platform fee settings.
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
    $user = requireRole($conn, [ROLE_SUPER_ADMIN, ROLE_ADMIN]);
    
    $baseFee = filterRequest("base_delivery_fee");
    $extraFee = filterRequest("multi_vendor_extra_fee");
    
    // Validate
    if ($baseFee === null || $baseFee === '' || !is_numeric($baseFee)) {
        sendResponse(STATUS_FAIL, null, "Base delivery fee is required and must be a number");
    }
    if ((float)$baseFee < 0) {
        sendResponse(STATUS_FAIL, null, "Base delivery fee cannot be negative");
    }
    
    // Get current config
    $stmt = $conn->prepare("SELECT * FROM fee_config ORDER BY updated_at DESC LIMIT 1");
    $stmt->execute();
    $current = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$current) {
        sendResponse(STATUS_FAIL, null, "No fee configuration found. Please contact Super Admin.");
    }
    
    // Build update — only touch delivery fields
    $fields = [];
    $params = [];
    
    $fields[] = "base_delivery_fee = ?";
    $params[] = floatval($baseFee);
    
    if ($extraFee !== null && $extraFee !== '' && is_numeric($extraFee)) {
        $fields[] = "multi_vendor_extra_fee = ?";
        $params[] = floatval($extraFee);
    }
    
    $fields[] = "updated_by = ?";
    $params[] = $user['user_id'];
    $fields[] = "updated_at = NOW()";
    
    $params[] = $current['id'];
    $updateStmt = $conn->prepare("UPDATE fee_config SET " . implode(', ', $fields) . " WHERE id = ?");
    $updateStmt->execute($params);
    
    // Read back updated values
    $newStmt = $conn->prepare("SELECT base_delivery_fee, multi_vendor_extra_fee FROM fee_config WHERE id = ?");
    $newStmt->execute([$current['id']]);
    $updated = $newStmt->fetch(PDO::FETCH_ASSOC);
    
    logActivity($conn, $user['user_id'], 'update', 'fee_config', $current['id'],
        "Updated delivery fee: base=" . floatval($baseFee));
    
    sendResponse(STATUS_SUCCESS, [
        'base_delivery_fee' => (float)($updated['base_delivery_fee'] ?? 20),
        'multi_vendor_extra_fee' => (float)($updated['multi_vendor_extra_fee'] ?? 5)
    ], "Delivery fee updated successfully");
    
} catch (PDOException $e) {
    error_log("Update delivery settings error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to update delivery settings");
}
?>
