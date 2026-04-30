<?php
/**
 * Super Admin - Update Fee Configuration
 * POST: /super_admin/fee_config/update.php
 * Headers: Authorization: Bearer <token>
 * Body: active_model, flat_fee_amount, percentage_value
 * 
 * DB columns: id, active_model, flat_fee_amount, percentage_value,
 *             model_switch_date, updated_by, updated_at
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
    
    $activeModel = filterRequest("active_model");
    $flatFeeAmount = filterRequest("flat_fee_amount");
    $percentageValue = filterRequest("percentage_value");
    
    // Get current config
    $stmt = $conn->prepare("SELECT * FROM fee_config ORDER BY updated_at DESC LIMIT 1");
    $stmt->execute();
    $current = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Build dynamic update
    $fields = [];
    $params = [];
    
    if (!empty($activeModel) && in_array($activeModel, ['flat_per_order', 'percentage'])) {
        $fields[] = "active_model = ?";
        $params[] = $activeModel;
        
        // Set model_switch_date when model changes
        if ($current && $current['active_model'] !== $activeModel) {
            $fields[] = "model_switch_date = NOW()";
        }
    }
    
    if ($flatFeeAmount !== null && $flatFeeAmount !== '' && is_numeric($flatFeeAmount)) {
        $fields[] = "flat_fee_amount = ?";
        $params[] = floatval($flatFeeAmount);
    }
    
    if ($percentageValue !== null && $percentageValue !== '' && is_numeric($percentageValue)) {
        $fields[] = "percentage_value = ?";
        $params[] = floatval($percentageValue);
    }
    
    if (empty($fields)) {
        sendResponse(STATUS_SUCCESS, null, "No changes provided");
    }
    
    $fields[] = "updated_by = ?";
    $params[] = $user['user_id'];
    $fields[] = "updated_at = NOW()";
    
    if ($current) {
        // Update existing record
        $params[] = $current['id'];
        $updateStmt = $conn->prepare("UPDATE fee_config SET " . implode(', ', $fields) . " WHERE id = ?");
        $updateStmt->execute($params);
        $configId = $current['id'];
    } else {
        // Insert new record if none exists
        $configId = generateUUID();
        $insertStmt = $conn->prepare("
            INSERT INTO fee_config (id, active_model, flat_fee_amount, percentage_value, updated_by, updated_at)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $insertStmt->execute([
            $configId,
            $activeModel ?: 'flat_per_order',
            floatval($flatFeeAmount ?: 0),
            floatval($percentageValue ?: 0),
            $user['user_id']
        ]);
    }
    
    // Fetch updated
    $newStmt = $conn->prepare("SELECT * FROM fee_config WHERE id = ?");
    $newStmt->execute([$configId]);
    $updated = $newStmt->fetch(PDO::FETCH_ASSOC);
    
    logActivity($conn, $user['user_id'], 'update', 'fee_config', $configId,
        "Updated fee configuration");
    
    sendResponse(STATUS_SUCCESS, [
        'fee_config' => [
            'id' => $updated['id'],
            'active_model' => $updated['active_model'],
            'flat_fee_amount' => (float)$updated['flat_fee_amount'],
            'percentage_value' => (float)$updated['percentage_value'],
            'model_switch_date' => $updated['model_switch_date'],
            'updated_by' => $updated['updated_by'],
            'updated_at' => $updated['updated_at']
        ]
    ], "Fee configuration updated successfully");
    
} catch (PDOException $e) {
    error_log("Update fee config error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to update fee configuration");
}
?>
