<?php
/**
 * TEST: Admin Module
 * Tests: order management, customer management, analytics
 */
require_once __DIR__ . '/test_helper.php';
header('Content-Type: text/plain; charset=utf-8');

echo "🛡️ ADMIN MODULE TESTS\n";
echo "Base: " . API_BASE . "\n\n";

// Login as super admin (has admin rights too)
$r = apiRequest('POST', 'auth/login.php', [
    'email' => 'superadmin@order-app.com',
    'password' => 'SuperAdmin@2026!'
]);
$token = $r['data']['token'] ?? null;
if (!$token) { echo "CANNOT PROCEED: Login failed\n"; exit; }
echo "Logged in\n\n";

// ══════════════════════════════════════════════════════════════
//  ORDERS MANAGEMENT
// ══════════════════════════════════════════════════════════════

$r = apiRequest('GET', 'admin/orders/get_all.php', [], $token);
assertApiSuccess('Orders - get all', $r);

$r = apiRequest('GET', 'admin/orders/get_pending.php', [], $token);
assertApiSuccess('Orders - get pending', $r);

$r = apiRequest('GET', 'admin/orders/get_by_status.php', ['status' => 'pending'], $token);
assertApiSuccess('Orders - by status (pending)', $r);

$r = apiRequest('GET', 'admin/orders/get_history.php', [], $token);
assertApiSuccess('Orders - history', $r);

// ══════════════════════════════════════════════════════════════
//  (Removed Customers, Vendors, Analytics as they don't exist in admin panel)
// ══════════════════════════════════════════════════════════════

// ══════════════════════════════════════════════════════════════
//  DASHBOARD
// ══════════════════════════════════════════════════════════════

$r = apiRequest('GET', 'admin/dashboard/get_stats.php', [], $token);
assertApiSuccess('Dashboard - stats', $r);

$r = apiRequest('GET', 'admin/dashboard/get_recent_orders.php', [], $token);
assertApiSuccess('Dashboard - recent orders', $r);

// ── Permission checks ─────────────────────────────────────────
// No token
$r = apiRequest('GET', 'admin/orders/get_all.php', []);
assertApiFail('Security - no token rejected', $r);

// Fake token
$r = apiRequest('GET', 'admin/orders/get_all.php', [], 'invalid_token_xyz');
assertApiFail('Security - fake token rejected', $r);

apiRequest('POST', 'auth/logout.php', [], $token);
printResults('ADMIN MODULE');
?>
