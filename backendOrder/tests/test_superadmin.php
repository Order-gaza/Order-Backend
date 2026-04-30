<?php
/**
 * TEST: Super Admin Module
 * Tests: user CRUD, vendor management, branches, zones, ads, fees, dashboard
 * 
 * Run: php tests/test_superadmin.php
 */
require_once __DIR__ . '/test_helper.php';
header('Content-Type: text/plain; charset=utf-8');

echo "👑 SUPER ADMIN MODULE TESTS\n";
echo "Base: " . API_BASE . "\n\n";

// ── Login as super admin ──────────────────────────────────────
$r = apiRequest('POST', 'auth/login.php', [
    'email' => 'superadmin@order-app.com',
    'password' => 'SuperAdmin@2026!'
]);
$token = $r['data']['token'] ?? null;
if (!$token) {
    echo "CANNOT PROCEED: Super admin login failed\n";
    echo json_encode($r) . "\n";
    exit;
}
echo "Logged in as super admin\n\n";

// ══════════════════════════════════════════════════════════════
//  USER MANAGEMENT
// ══════════════════════════════════════════════════════════════

// Create admin user
$testEmail = 'test_admin_' . TEST_TIMESTAMP . '@test.com';
    $r = apiRequest('POST', 'super_admin/admins/add.php', [
        'name' => 'Test Admin',
        'email' => $testEmail,
        'phone' => '059' . rand(1000000, 9999999),
        'password' => 'Test123!',
        'role' => 'admin'
    ], $token);
    $addOk = assertApiSuccess('Users - add admin', $r);
    $testUserId = ($addOk && isset($r['data'])) ? ($r['data']['user_id'] ?? $r['data']['id'] ?? null) : null;
    if (!$testUserId) warn_test('Users - no user_id returned', json_encode($r['data'] ?? []));

    // Get all users
    $r = apiRequest('GET', 'super_admin/admins/get_all.php', [], $token);
    assertApiSuccess('Users - get all', $r);
    if (isset($r['data'])) {
        assert_test('Users - response has users array', isset($r['data']['admins']) || isset($r['data']['users']), json_encode(array_keys($r['data'])));
    }

    // Get by role (this endpoint might not exist anymore, if admins/get_all is strictly admins)
    // $r = apiRequest('GET', 'super_admin/admins/get_by_role.php', ['role' => 'admin'], $token);
    // assertApiSuccess('Users - get by role (admin)', $r);

    // Get by ID
    if ($testUserId) {
        $r = apiRequest('GET', 'super_admin/admins/get_by_id.php', ['id' => $testUserId], $token);
        assertApiSuccess('Users - get by ID', $r);
    }

    // Update user
    if ($testUserId) {
        $r = apiRequest('POST', 'super_admin/admins/update.php', [
            'admin_id' => $testUserId, // change user_id to admin_id just in case
            'name' => 'Updated Admin'
        ], $token);
        assertApiSuccess('Users - update name', $r);
    }

    // Toggle user status
    if ($testUserId) {
        $r = apiRequest('POST', 'super_admin/admins/toggle_status.php', ['admin_id' => $testUserId], $token);
        assertApiSuccess('Users - toggle status', $r);
        // Toggle back
        $r = apiRequest('POST', 'super_admin/admins/toggle_status.php', ['admin_id' => $testUserId], $token);
        assertApiSuccess('Users - toggle status back', $r);
    }

    // Search users
    // $r = apiRequest('GET', 'super_admin/admins/search.php', ['q' => 'admin'], $token);
    // assertApiSuccess('Users - search', $r);

    // Delete user
    if ($testUserId) {
        $r = apiRequest('POST', 'super_admin/admins/delete.php', ['admin_id' => $testUserId], $token);

    assertApiSuccess('Users - delete', $r);
}

// ══════════════════════════════════════════════════════════════
//  VENDOR MANAGEMENT
// ══════════════════════════════════════════════════════════════

$r = apiRequest('GET', 'super_admin/vendors/get_all.php', [], $token);
assertApiSuccess('Vendors - get all', $r);

$r = apiRequest('GET', 'super_admin/vendors/get_pending.php', [], $token);
assertApiSuccess('Vendors - get pending', $r);

$r = apiRequest('GET', 'super_admin/vendors/get_stats.php', [], $token);
assertApiSuccess('Vendors - stats', $r);

// ══════════════════════════════════════════════════════════════
//  DELIVERY ZONES
// ══════════════════════════════════════════════════════════════

$r = apiRequest('GET', 'super_admin/delivery_zones/get_all.php', [], $token);
$zonesOk = assertApiSuccess('Zones - get all', $r);
if ($zonesOk && isset($r['data']['zones'])) {
    assert_test('Zones - has zone data', count($r['data']['zones']) > 0, 'Got ' . count($r['data']['zones']) . ' zones');
}

// ══════════════════════════════════════════════════════════════
//  FEES
// ══════════════════════════════════════════════════════════════

$r = apiRequest('GET', 'super_admin/fee_config/get.php', [], $token);
assertApiSuccess('Fees - get config', $r);

// ══════════════════════════════════════════════════════════════
//  ADVERTISEMENTS
// ══════════════════════════════════════════════════════════════

$r = apiRequest('GET', 'super_admin/advertisements/get_all.php', [], $token);
assertApiSuccess('Ads - get all', $r);

// ══════════════════════════════════════════════════════════════
//  BRANCHES (Not relevant in current DB schema)
// ══════════════════════════════════════════════════════════════

// $r = apiRequest('GET', 'super_admin/branches/get_all.php', [], $token);
// assertApiSuccess('Branches - get all', $r);

// ══════════════════════════════════════════════════════════════
//  DASHBOARD
// ══════════════════════════════════════════════════════════════

$r = apiRequest('GET', 'super_admin/dashboard/get_stats.php', [], $token);
assertApiSuccess('Dashboard - stats', $r);

$r = apiRequest('GET', 'super_admin/dashboard/get_recent_orders.php', [], $token);
assertApiSuccess('Dashboard - recent orders', $r);

$r = apiRequest('GET', 'super_admin/dashboard/get_revenue_chart.php', [], $token);
assertApiSuccess('Dashboard - revenue', $r);

// ══════════════════════════════════════════════════════════════
//  ORDERS
// ══════════════════════════════════════════════════════════════

$r = apiRequest('GET', 'super_admin/orders/get_all.php', [], $token);
assertApiSuccess('Orders - get all', $r);

$r = apiRequest('GET', 'super_admin/orders/get_by_status.php', ['status' => 'pending'], $token);
assertApiSuccess('Orders - get by status', $r);

// ── Cleanup: logout ──────────────────────────────────────────
apiRequest('POST', 'auth/logout.php', [], $token);

printResults('SUPER ADMIN MODULE');
?>
