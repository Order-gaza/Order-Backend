<?php
/**
 * TEST: Vendor Module
 * Tests: profile, dashboard, categories, items, orders
 */
require_once __DIR__ . '/test_helper.php';
header('Content-Type: text/plain; charset=utf-8');

echo "🏪 VENDOR MODULE TESTS\n";
echo "Base: " . API_BASE . "\n\n";

// We need a vendor account. First login as super_admin to create one.
$r = apiRequest('POST', 'auth/login.php', [
    'email' => 'superadmin@order-app.com',
    'password' => 'SuperAdmin@2026!'
]);
$superToken = $r['data']['token'] ?? null;
if (!$superToken) { echo "CANNOT PROCEED: Super admin login failed\n"; exit; }

// Create a test vendor user directly in DB (as there's no add vendor API)
$vendorEmail = 'test_vendor_' . TEST_TIMESTAMP . '@test.com';
$vendorPhone = '059' . rand(1000000, 9999999);
$vendorUserId = null;
$vendorCreated = false;

try {
    require_once __DIR__ . '/../config/database.php';
    $db = new Database();
    $conn = $db->connect();
    
    $vendorUserId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
    $vendorId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
    
    // Insert User
    $stmt = $conn->prepare("INSERT INTO users (id, name, email, phone, password_hash, role, is_active) VALUES (?, 'Test Vendor', ?, ?, ?, 'vendor', 1)");
    $stmt->execute([$vendorUserId, $vendorEmail, $vendorPhone, password_hash('Vendor123!', PASSWORD_BCRYPT)]);
    
    // Insert Vendor Profile
    $stmt = $conn->prepare("INSERT INTO vendors (id, user_id, name, type, phone, address, latitude, longitude, status) VALUES (?, ?, 'Test Vendor Shop', 'restaurant', ?, 'Gaza', 31.5, 34.4, 'approved')");
    $stmt->execute([$vendorId, $vendorUserId, $vendorPhone]);
    
    $vendorCreated = true;
    echo "  Setup - create vendor user in DB\n";
} catch (Exception $e) {
    echo "  Setup - create vendor user failed: " . $e->getMessage() . "\n";
}

echo "  Token: " . ($vendorToken ? "obtained" : "MISSING") . "\n\n";

// ══════════════════════════════════════════════════════════════
//  PROFILE
// ══════════════════════════════════════════════════════════════

$r = apiRequest('GET', 'vendor/profile/get.php', [], $vendorToken);
assertApiSuccess('Profile - get', $r);

// ══════════════════════════════════════════════════════════════
//  DASHBOARD
// ══════════════════════════════════════════════════════════════

$r = apiRequest('GET', 'vendor/dashboard/get_stats.php', [], $vendorToken);
assertApiSuccess('Dashboard - stats', $r);

$r = apiRequest('GET', 'vendor/dashboard/get_recent_orders.php', [], $vendorToken);
assertApiSuccess('Dashboard - recent orders', $r);

// ══════════════════════════════════════════════════════════════
//  CATEGORIES
// ══════════════════════════════════════════════════════════════

$r = apiRequest('GET', 'vendor/categories/get_all.php', [], $vendorToken);
$catOk = assertApiSuccess('Categories - get all', $r);

// Add category
$r = apiRequest('POST', 'vendor/categories/add.php', [
    'name' => 'Test Category ' . TEST_TIMESTAMP,
    'description' => 'For testing',
    'sort_order' => 99
], $vendorToken);
$catAdded = assertApiSuccess('Categories - add', $r);
$testCatId = ($catAdded && isset($r['data'])) ? ($r['data']['category_id'] ?? $r['data']['id'] ?? null) : null;

// Update category
if ($testCatId) {
    $r = apiRequest('POST', 'vendor/categories/update.php', [
        'category_id' => $testCatId,
        'name' => 'Updated Category'
    ], $vendorToken);
    assertApiSuccess('Categories - update', $r);
}

// ══════════════════════════════════════════════════════════════
//  ITEMS
// ══════════════════════════════════════════════════════════════

$r = apiRequest('GET', 'vendor/items/get_all.php', [], $vendorToken);
assertApiSuccess('Items - get all', $r);

// Add item (need a category)
if ($testCatId) {
    $r = apiRequest('POST', 'vendor/items/add.php', [
        'category_id' => $testCatId,
        'name' => 'Test Item ' . TEST_TIMESTAMP,
        'description' => 'For testing',
        'base_price' => '25.00'
    ], $vendorToken);
    $itemAdded = assertApiSuccess('Items - add', $r);
    $testItemId = ($itemAdded && isset($r['data'])) ? ($r['data']['item_id'] ?? $r['data']['id'] ?? null) : null;

    if ($testItemId) {
        // Get by ID
        $r = apiRequest('GET', 'vendor/items/get_by_id.php', ['item_id' => $testItemId], $vendorToken);
        assertApiSuccess('Items - get by ID', $r);

        // Toggle availability
        $r = apiRequest('POST', 'vendor/items/toggle_availability.php', ['item_id' => $testItemId], $vendorToken);
        assertApiSuccess('Items - toggle availability', $r);

        // Delete item
        $r = apiRequest('POST', 'vendor/items/delete.php', ['item_id' => $testItemId], $vendorToken);
        assertApiSuccess('Items - delete', $r);
    }
}

// ══════════════════════════════════════════════════════════════
//  ORDERS
// ══════════════════════════════════════════════════════════════

$r = apiRequest('GET', 'vendor/orders/get_all.php', [], $vendorToken);
assertApiSuccess('Orders - get all', $r);

$r = apiRequest('GET', 'vendor/orders/get_pending.php', [], $vendorToken);
assertApiSuccess('Orders - get pending', $r);

$r = apiRequest('GET', 'vendor/orders/get_history.php', [], $vendorToken);
assertApiSuccess('Orders - get history', $r);

// ── Cleanup ──────────────────────────────────────────────────
if ($testCatId) {
    apiRequest('POST', 'vendor/categories/delete.php', ['category_id' => $testCatId], $vendorToken);
}
// Delete test vendor user via SQL since no API endpoint for it
if ($vendorUserId) {
    try {
        $conn->exec("DELETE FROM users WHERE id = '$vendorUserId'");
    } catch (Exception $e) {}
}

apiRequest('POST', 'auth/logout.php', [], $vendorToken);
apiRequest('POST', 'auth/logout.php', [], $superToken);

printResults('VENDOR MODULE');
?>
