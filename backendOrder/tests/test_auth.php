<?php
/**
 * TEST: Auth Module (Admin App)
 * Tests: login, validate_session, logout, forgot_password, verify_otp, reset_password
 * 
 * Run: php tests/test_auth.php
 * Or via browser: https://appforyou.store/backendOrder/tests/test_auth.php
 */
require_once __DIR__ . '/test_helper.php';
header('Content-Type: text/plain; charset=utf-8');

echo "🔐 AUTH MODULE TESTS\n";
echo "Base: " . API_BASE . "\n\n";

// ── 1. Login with wrong email ─────────────────────────────────
$r = apiRequest('POST', 'auth/login.php', ['email' => 'wrong@test.com', 'password' => 'wrong123']);
assertApiFail('Login - wrong email rejected', $r);

// ── 2. Login with no data ─────────────────────────────────────
$r = apiRequest('POST', 'auth/login.php', []);
assertApiFail('Login - empty body rejected', $r);

// ── 3. Login with invalid email format ────────────────────────
$r = apiRequest('POST', 'auth/login.php', ['email' => 'not-an-email', 'password' => '123456']);
assertApiFail('Login - invalid email format rejected', $r);

// ── 4. Login GET method rejected ──────────────────────────────
$r = apiRequest('GET', 'auth/login.php');
assertApiFail('Login - GET method rejected', $r);

// ── 5. Login with default super admin ─────────────────────────
// Default password from schema: SuperAdmin@2026!
$r = apiRequest('POST', 'auth/login.php', [
    'email' => 'superadmin@order-app.com',
    'password' => 'SuperAdmin@2026!'
]);
$loginOk = assertApiSuccess('Login - super admin login', $r);

$adminToken = null;
$adminUserId = null;
if ($loginOk && isset($r['data'])) {
    $adminToken = $r['data']['token'] ?? null;
    $adminUserId = $r['data']['user']['id'] ?? $r['data']['user_id'] ?? null;
    assert_test('Login - token returned', !empty($adminToken), empty($adminToken) ? 'No token in: ' . json_encode($r['data']) : '');
    assert_test('Login - role is super_admin', 
        ($r['data']['user']['role'] ?? '') === 'super_admin',
        'Role: ' . ($r['data']['user']['role'] ?? 'missing'));
} else {
    warn_test('Login - could not test further', 'Super admin login failed. Check DB has seeded user.');
}

// ── 6. Validate Session ───────────────────────────────────────
if ($adminToken) {
    $r = apiRequest('GET', 'auth/validate_session.php', [], $adminToken);
    assertApiSuccess('Validate session - valid token', $r);
    
    // No token
    $r = apiRequest('GET', 'auth/validate_session.php', []);
    assertApiFail('Validate session - no token rejected', $r);
    
    // Fake token
    $r = apiRequest('GET', 'auth/validate_session.php', [], 'fake_token_12345');
    assertApiFail('Validate session - fake token rejected', $r);
}

// ── 7. Forgot Password ───────────────────────────────────────
$r = apiRequest('POST', 'auth/forgot_password.php', ['email' => '']);
assertApiFail('Forgot password - empty email rejected', $r);

$r = apiRequest('POST', 'auth/forgot_password.php', ['email' => 'nonexist@test.com']);
// Should succeed (don't reveal if email exists) or fail gracefully
assert_test('Forgot password - non-existent email handled', isset($r['status']), 'Response: ' . json_encode($r));

// ── 8. Change password validation ─────────────────────────────
if ($adminToken) {
    $r = apiRequest('POST', 'auth/change_password.php', [
        'current_password' => 'wrong',
        'new_password' => 'abc',
        'confirm_password' => 'abc'
    ], $adminToken);
    assertApiFail('Change password - wrong current rejected', $r);
}

// ── 9. Resend OTP ─────────────────────────────────────────────
$r = apiRequest('POST', 'auth/resend_otp.php', ['email' => 'nobody@test.com']);
assert_test('Resend OTP - handled', isset($r['status']), json_encode($r));

// ── 10. Refresh token ─────────────────────────────────────────
if ($adminToken) {
    $r = apiRequest('POST', 'auth/refresh_token.php', [], $adminToken);
    assert_test('Refresh token - responds', isset($r['status']), json_encode($r));
}

// ── 11. Logout ────────────────────────────────────────────────
// Test logout LAST (it kills the token)
if ($adminToken) {
    $r = apiRequest('POST', 'auth/logout.php', [], $adminToken);
    assertApiSuccess('Logout - success', $r);
    
    // Token should now be invalid
    $r = apiRequest('GET', 'auth/validate_session.php', [], $adminToken);
    assertApiFail('Logout - token invalid after logout', $r);
}

printResults('AUTH MODULE');
?>
