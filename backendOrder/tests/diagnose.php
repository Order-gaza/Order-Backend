<?php
/**
 * Quick Diagnostic - Test known failure points
 * GET: /tests/diagnose.php
 * 
 * Tests: password hash, vendor columns, advertisements columns
 */
header('Content-Type: application/json');

include "../config/database.php";
include "../config/constants.php";

$db = new Database();
$conn = $db->connect();

$results = [];

// ===== 1. CHECK SUPER ADMIN PASSWORD =====
try {
    $stmt = $conn->prepare("SELECT id, email, password_hash, role FROM users WHERE email = 'superadmin@order-app.com'");
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        $hashStart = substr($user['password_hash'], 0, 20) . '...';
        $verifyResult = password_verify('SuperAdmin@2026!', $user['password_hash']);
        $verifyPassword = password_verify('password', $user['password_hash']);
        
        $results['1_superadmin'] = [
            'found' => true,
            'email' => $user['email'],
            'role' => $user['role'],
            'hash_prefix' => $hashStart,
            'password_SuperAdmin2026_match' => $verifyResult,
            'password_password_match' => $verifyPassword,
            'fix' => !$verifyResult ? "Hash doesn't match 'SuperAdmin@2026!' — run: UPDATE users SET password_hash = '" . password_hash('SuperAdmin@2026!', PASSWORD_BCRYPT) . "' WHERE email = 'superadmin@order-app.com';" : 'OK'
        ];
    } else {
        $results['1_superadmin'] = ['found' => false, 'fix' => 'No super admin user found'];
    }
} catch (Exception $e) {
    $results['1_superadmin'] = ['error' => $e->getMessage()];
}

// ===== 2. CHECK VENDORS TABLE COLUMNS =====
try {
    $stmt = $conn->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'vendors' ORDER BY ORDINAL_POSITION");
    $stmt->execute();
    $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $results['2_vendors_columns'] = $cols;
    
    // Test actual query that fails
    $test = $conn->prepare("SELECT id, name, description, logo_url, cover_url, type, address, rating, total_ratings, min_order_amount, estimated_delivery_time, is_open FROM vendors WHERE status = 'approved' LIMIT 1");
    $test->execute();
    $results['2_vendors_query'] = 'OK - query works';
} catch (Exception $e) {
    $results['2_vendors_query'] = 'FAIL: ' . $e->getMessage();
}

// ===== 3. CHECK ADVERTISEMENTS TABLE COLUMNS =====
try {
    $stmt = $conn->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'advertisements' ORDER BY ORDINAL_POSITION");
    $stmt->execute();
    $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $results['3_ads_columns'] = $cols;
    
    // Test actual query
    $test = $conn->prepare("SELECT id, title, description, image_url, target_url, vendor_id FROM advertisements WHERE is_active = 1 AND starts_at <= NOW() AND ends_at >= NOW() LIMIT 1");
    $test->execute();
    $results['3_ads_query'] = 'OK - query works';
} catch (Exception $e) {
    $results['3_ads_query'] = 'FAIL: ' . $e->getMessage();
}

// ===== 4. CHECK MENU_ITEMS TABLE =====
try {
    $test = $conn->prepare("SELECT id, name, base_price, preparation_time FROM menu_items LIMIT 1");
    $test->execute();
    $results['4_menu_items_query'] = 'OK - base_price column exists';
} catch (Exception $e) {
    $results['4_menu_items_query'] = 'FAIL: ' . $e->getMessage();
}

// ===== 5. CHECK DELIVERY_ZONES TABLE =====
try {
    $test = $conn->prepare("SELECT id, name, base_fee, nearby_extra_fee, far_extra_fee FROM delivery_zones LIMIT 1");
    $test->execute();
    $results['5_delivery_zones_query'] = 'OK - correct columns';
} catch (Exception $e) {
    $results['5_delivery_zones_query'] = 'FAIL: ' . $e->getMessage();
}

// ===== 6. CHECK IF OLD FILES ARE SERVED =====
$results['6_server_file_check'] = [
    'php_version' => phpversion(),
    'server_time' => date('Y-m-d H:i:s'),
    'base_url' => defined('BASE_URL') ? BASE_URL : 'not defined'
];

echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>
