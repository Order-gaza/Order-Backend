<?php
/**
 * TEST: User App Modules (Public + Authenticated)
 * Tests: home, vendors, items, profile, cart, addresses, orders, notifications
 */
require_once __DIR__ . '/test_helper.php';
header('Content-Type: text/plain; charset=utf-8');

echo "USER APP MODULES TESTS\n";
echo "Base: " . API_BASE . "\n\n";

// ══════════════════════════════════════════════════════════════
//  PUBLIC ENDPOINTS (no auth needed)
// ══════════════════════════════════════════════════════════════

echo "── PUBLIC ENDPOINTS ──\n\n";

// Home
$r = apiRequest('GET', 'user_home/get_advertisements.php');
assertApiSuccess('Home - get advertisements', $r);

$r = apiRequest('GET', 'user_home/get_featured_vendors.php');
assertApiSuccess('Home - featured vendors', $r);

$r = apiRequest('GET', 'user_home/get_popular_items.php');
assertApiSuccess('Home - popular items', $r);

// Vendors (public)
$r = apiRequest('GET', 'user_vendors/get_all.php');
$vendorsOk = assertApiSuccess('Vendors - get all', $r);
$testVendorId = null;
if ($vendorsOk && isset($r['data']['vendors'][0]['id'])) {
    $testVendorId = $r['data']['vendors'][0]['id'];
}

if ($testVendorId) {
    $r = apiRequest('GET', 'user_vendors/get_by_id.php', ['id' => $testVendorId]);
    assertApiSuccess('Vendors - get by ID', $r);

    $r = apiRequest('GET', 'user_vendors/get_menu.php', ['vendor_id' => $testVendorId]);
    assertApiSuccess('Vendors - get menu', $r);

    $r = apiRequest('GET', 'user_vendors/get_ratings.php', ['vendor_id' => $testVendorId]);
    assertApiSuccess('Vendors - get ratings', $r);
} else {
    warn_test('Vendors', 'No vendors in DB — skipping ID-based tests');
}

$r = apiRequest('GET', 'user_vendors/get_by_type.php', ['type' => 'restaurant']);
assertApiSuccess('Vendors - by type', $r);

$r = apiRequest('GET', 'user_vendors/search.php', ['q' => 'test']);
assertApiSuccess('Vendors - search', $r);

// Items (public)
$r = apiRequest('GET', 'user_items/search.php', ['q' => 'food']);
assertApiSuccess('Items - search', $r);

// ══════════════════════════════════════════════════════════════
//  AUTHENTICATED ENDPOINTS (need customer token)
// ══════════════════════════════════════════════════════════════

echo "\n── AUTHENTICATED ENDPOINTS ──\n\n";

// We need a verified customer. Since we can't do OTP automatically,
// check if there's already a customer in the system we can use.
// Otherwise, we'll just verify security blocks work.

// Try to get a customer token by checking if we have seeded data
// For now, test that auth blocks work on all protected endpoints.

$protectedEndpoints = [
    ['GET',  'user_profile/get_profile.php',                  'Profile - get'],
    ['POST', 'user_profile/update_profile.php',               'Profile - update'],
    ['POST', 'user_profile/upload_profile_image.php',         'Profile - upload image'],
    ['POST', 'user_profile/delete_profile_image.php',         'Profile - delete image'],
    ['POST', 'user_profile/delete_account.php',               'Profile - delete account'],
    ['GET',  'user_cart/get_cart.php',                         'Cart - get'],
    ['POST', 'user_cart/add_to_cart.php',                      'Cart - add'],
    ['POST', 'user_cart/update_item.php',                      'Cart - update'],
    ['POST', 'user_cart/remove_item.php',                      'Cart - remove'],
    ['POST', 'user_cart/clear_cart.php',                       'Cart - clear'],
    ['GET',  'user_cart/get_delivery_fee.php',                 'Cart - delivery fee'],
    ['GET',  'user_orders/get_orders.php',                     'Orders - get'],
    ['POST', 'user_orders/create_order.php',                   'Orders - create'],
    ['POST', 'user_orders/cancel_order.php',                   'Orders - cancel'],
    ['POST', 'user_orders/rate_order.php',                     'Orders - rate'],
    ['GET',  'user_addresses/get_addresses.php',               'Addresses - get'],
    ['POST', 'user_addresses/add_address.php',                 'Addresses - add'],
    ['POST', 'user_addresses/update_address.php',              'Addresses - update'],
    ['POST', 'user_addresses/delete_address.php',              'Addresses - delete'],
    ['POST', 'user_addresses/set_default.php',                 'Addresses - set default'],
    ['GET',  'user_notifications/get_all.php',                 'Notifications - get all'],
    ['GET',  'user_notifications/get_unread_count.php',        'Notifications - unread count'],
    ['POST', 'user_notifications/mark_read.php',               'Notifications - mark read'],
    ['POST', 'user_notifications/mark_all_read.php',           'Notifications - mark all read'],
    ['POST', 'user_notifications/delete.php',                  'Notifications - delete'],
];

echo "Testing auth protection on " . count($protectedEndpoints) . " endpoints...\n\n";

foreach ($protectedEndpoints as $ep) {
    $method = $ep[0];
    $path = $ep[1];
    $name = $ep[2];
    
    $r = apiRequest($method, $path, []);
    assertApiFail("Security: $name - no token rejected", $r);
}

printResults('USER APP MODULES');
?>
