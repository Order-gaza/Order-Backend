<?php
/**
 * Order Backend - API Test Helper
 * Core utility for all test modules.
 * Upload this file to: backendOrder/tests/test_helper.php
 */

// Base URL - CHANGE THIS IF NEEDED
define('API_BASE', 'https://appforyou.store/backendOrder');
define('TEST_TIMESTAMP', date('His'));

// ─── Test result tracking ────────────────────────────────────────
$GLOBALS['test_results'] = [];
$GLOBALS['test_pass'] = 0;
$GLOBALS['test_fail'] = 0;
$GLOBALS['test_warn'] = 0;
$GLOBALS['test_errors'] = [];

/**
 * Make an HTTP request to the API
 */
function apiRequest($method, $endpoint, $data = [], $token = null, $isMultipart = false) {
    $url = API_BASE . '/' . ltrim($endpoint, '/');
    $ch = curl_init();

    $headers = ['Accept: application/json'];
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }

    if ($method === 'GET') {
        if (!empty($data)) {
            $url .= '?' . http_build_query($data);
        }
        curl_setopt($ch, CURLOPT_HTTPGET, true);
    } else {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($isMultipart) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        }
    }

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => true,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return ['_curl_error' => $error, '_http_code' => 0];
    }

    $decoded = json_decode($response, true);
    if ($decoded === null) {
        return [
            '_raw' => substr($response, 0, 500),
            '_http_code' => $httpCode,
            '_parse_error' => 'Invalid JSON response'
        ];
    }

    $decoded['_http_code'] = $httpCode;
    return $decoded;
}

/**
 * Assert a test condition
 */
function assert_test($name, $condition, $detail = '') {
    if ($condition) {
        $GLOBALS['test_pass']++;
        $GLOBALS['test_results'][] = ['status' => 'PASS', 'name' => $name, 'detail' => $detail];
    } else {
        $GLOBALS['test_fail']++;
        $GLOBALS['test_results'][] = ['status' => 'FAIL', 'name' => $name, 'detail' => $detail];
        $GLOBALS['test_errors'][] = "$name: $detail";
    }
}

/**
 * Warn (non-fatal issue)
 */
function warn_test($name, $detail = '') {
    $GLOBALS['test_warn']++;
    $GLOBALS['test_results'][] = ['status' => 'WARN', 'name' => $name, 'detail' => $detail];
}

/**
 * Check API response is valid JSON with expected status
 */
function assertApiSuccess($name, $response, $expectStatus = 'success') {
    if (isset($response['_curl_error'])) {
        assert_test($name, false, 'CURL Error: ' . $response['_curl_error']);
        return false;
    }
    if (isset($response['_parse_error'])) {
        assert_test($name, false, 'Parse Error: ' . $response['_parse_error'] . ' | Raw: ' . ($response['_raw'] ?? ''));
        return false;
    }
    $ok = isset($response['status']) && $response['status'] === $expectStatus;
    $detail = $ok ? '' : 'Expected status=' . $expectStatus . ', got: ' . json_encode($response);
    assert_test($name, $ok, $detail);
    return $ok;
}

function assertApiFail($name, $response) {
    return assertApiSuccess($name, $response, 'fail');
}

/**
 * Print results
 */
function printResults($moduleName) {
    echo "\n" . str_repeat('=', 70) . "\n";
    echo "  TEST RESULTS: $moduleName\n";
    echo str_repeat('=', 70) . "\n\n";

    foreach ($GLOBALS['test_results'] as $r) {
        $icon = $r['status'] === 'PASS' ? '' : ($r['status'] === 'FAIL' ? '' : '⚠️');
        echo "  $icon {$r['status']}: {$r['name']}";
        if (!empty($r['detail'])) echo "\n     └─ {$r['detail']}";
        echo "\n";
    }

    echo "\n" . str_repeat('-', 70) . "\n";
    echo "  PASS: {$GLOBALS['test_pass']}   FAIL: {$GLOBALS['test_fail']}   WARN: {$GLOBALS['test_warn']}\n";
    echo str_repeat('-', 70) . "\n";

    if (!empty($GLOBALS['test_errors'])) {
        echo "\n  ⛔ ISSUES FOUND:\n";
        foreach ($GLOBALS['test_errors'] as $i => $err) {
            echo "  " . ($i + 1) . ". $err\n";
        }
    }

    echo "\n";
    return ['pass' => $GLOBALS['test_pass'], 'fail' => $GLOBALS['test_fail'], 'warn' => $GLOBALS['test_warn'], 'errors' => $GLOBALS['test_errors']];
}

/**
 * Reset counters for a new module
 */
function resetResults() {
    $GLOBALS['test_results'] = [];
    $GLOBALS['test_pass'] = 0;
    $GLOBALS['test_fail'] = 0;
    $GLOBALS['test_warn'] = 0;
    $GLOBALS['test_errors'] = [];
}
?>
