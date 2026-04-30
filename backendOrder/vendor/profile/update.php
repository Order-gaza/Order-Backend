<?php
/**
 * Vendor - Update Own Profile
 * POST: /vendor/profile/update.php
 * Headers: Authorization: Bearer <token>
 * Body: name?, description?, phone?, address?, latitude?, longitude?,
 *       operating_hours?, min_order_amount?, estimated_delivery_time?
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
    $user = requireRole($conn, [ROLE_VENDOR]);
    
    $vendorStmt = $conn->prepare("SELECT id FROM vendors WHERE user_id = ?");
    $vendorStmt->execute([$user['user_id']]);
    $vendor = $vendorStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$vendor) {
        sendResponse(STATUS_FAIL, null, "Vendor profile not found");
    }
    
    $vendorId = $vendor['id'];
    
    // Vendor table fields
    $fields = [];
    $params = [];
    
    $name = filterRequest("name");
    $description = filterRequest("description");
    $phone = filterRequest("phone");
    $address = filterRequest("address");
    $city = filterRequest("city");
    $neighborhood = filterRequest("neighborhood");
    $deliveryZone = filterRequest("delivery_zone");
    $latitude = filterRequest("latitude");
    $longitude = filterRequest("longitude");
    $operatingHours = filterRequest("operating_hours", true); // skip HTML encoding for JSON
    $minOrder = filterRequest("min_order_amount");
    $estDelivery = filterRequest("estimated_delivery_time");
    $openingTime = filterRequest("opening_time");
    $closingTime = filterRequest("closing_time");
    
    if (!empty($name)) { $fields[] = "name = ?"; $params[] = $name; }
    if ($description !== null && $description !== '') { $fields[] = "description = ?"; $params[] = $description; }
    if (!empty($phone)) { $fields[] = "phone = ?"; $params[] = $phone; }
    if (!empty($address)) { $fields[] = "address = ?"; $params[] = $address; }
    if (!empty($city)) { $fields[] = "city = ?"; $params[] = $city; }
    if (!empty($neighborhood)) { $fields[] = "neighborhood = ?"; $params[] = $neighborhood; }
    
    // Auto-resolve delivery_zone from city if city is provided
    if (!empty($city) && empty($deliveryZone)) {
        $cityZoneMap = [
            'مدينة غزة' => 'north',
            'مدينة دير البلح' => 'central',
            'مخيم النصيرات' => 'central',
            'مخيم البريج' => 'central',
            'مخيم المغازي' => 'central',
            'مدينة خانيونس' => 'south',
        ];
        $deliveryZone = $cityZoneMap[$city] ?? null;
    }
    if (!empty($deliveryZone) && in_array($deliveryZone, ['north','central','south'])) {
        $fields[] = "delivery_zone = ?"; $params[] = $deliveryZone;
    }
    
    if ($latitude !== null && $latitude !== '') { $fields[] = "latitude = ?"; $params[] = floatval($latitude); }
    if ($longitude !== null && $longitude !== '') { $fields[] = "longitude = ?"; $params[] = floatval($longitude); }
    if ($operatingHours !== null && $operatingHours !== '') { $fields[] = "operating_hours = ?"; $params[] = $operatingHours; }
    if ($minOrder !== null && $minOrder !== '') { $fields[] = "min_order_amount = ?"; $params[] = floatval($minOrder); }
    if ($estDelivery !== null && $estDelivery !== '') { $fields[] = "estimated_delivery_time = ?"; $params[] = $estDelivery; }
    if ($openingTime !== null && $openingTime !== '') { $fields[] = "opening_time = ?"; $params[] = $openingTime; }
    if ($closingTime !== null && $closingTime !== '') { $fields[] = "closing_time = ?"; $params[] = $closingTime; }
    
    // Also update user name if provided
    if (!empty($name)) {
        $conn->prepare("UPDATE users SET name = ?, updated_at = NOW() WHERE id = ?")
             ->execute([$name, $user['user_id']]);
    }
    
    if (empty($fields)) {
        sendResponse(STATUS_SUCCESS, null, "No changes provided");
    }
    
    $fields[] = "updated_at = NOW()";
    $params[] = $vendorId;
    
    $updateStmt = $conn->prepare("UPDATE vendors SET " . implode(', ', $fields) . " WHERE id = ?");
    $updateStmt->execute($params);
    
    // Return updated profile
    $selectStmt = $conn->prepare("SELECT * FROM vendors WHERE id = ?");
    $selectStmt->execute([$vendorId]);
    $updated = $selectStmt->fetch(PDO::FETCH_ASSOC);
    
    logActivity($conn, $user['user_id'], 'update_profile', 'vendor', $vendorId, "Updated vendor profile");
    
    sendResponse(STATUS_SUCCESS, ['vendor' => $updated], "Profile updated successfully");
    
} catch (PDOException $e) {
    error_log("Vendor update profile error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to update profile");
}
?>
