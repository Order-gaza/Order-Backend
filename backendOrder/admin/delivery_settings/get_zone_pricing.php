<?php
/**
 * Admin - Get Zone Pricing
 * GET: /admin/delivery_settings/get_zone_pricing.php
 * Headers: Authorization: Bearer <token>
 * 
 * Returns all delivery zone pricing routes.
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
    
    $stmt = $conn->prepare("SELECT * FROM delivery_zone_pricing ORDER BY from_zone, to_zone");
    $stmt->execute();
    $pricing = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Also build a simplified summary for the 4 display routes
    $routes = [
        ['key' => 'same_zone', 'label_ar' => 'داخلي (نفس المنطقة)', 'label_en' => 'Same Zone (Internal)', 'fee' => 10.00],
        ['key' => 'north_central', 'label_ar' => 'غزة ↔ الوسطى', 'label_en' => 'Gaza ↔ Central', 'fee' => 20.00],
        ['key' => 'central_south', 'label_ar' => 'الوسطى ↔ خانيونس', 'label_en' => 'Central ↔ Khan Younis', 'fee' => 20.00],
        ['key' => 'north_south', 'label_ar' => 'غزة ↔ خانيونس', 'label_en' => 'Gaza ↔ Khan Younis', 'fee' => 25.00],
    ];
    
    // Fill from DB
    foreach ($pricing as $p) {
        $from = $p['from_zone'];
        $to = $p['to_zone'];
        $fee = (float)$p['fee'];
        
        if ($from === $to) {
            $routes[0]['fee'] = $fee; // same zone
        } elseif (($from === 'north' && $to === 'central') || ($from === 'central' && $to === 'north')) {
            $routes[1]['fee'] = $fee; // north-central
        } elseif (($from === 'central' && $to === 'south') || ($from === 'south' && $to === 'central')) {
            $routes[2]['fee'] = $fee; // central-south
        } elseif (($from === 'north' && $to === 'south') || ($from === 'south' && $to === 'north')) {
            $routes[3]['fee'] = $fee; // north-south
        }
    }
    
    sendResponse(STATUS_SUCCESS, [
        'routes' => $routes,
        'pricing' => $pricing
    ], "Zone pricing retrieved");
    
} catch (PDOException $e) {
    error_log("Get zone pricing error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to retrieve zone pricing");
}
?>
