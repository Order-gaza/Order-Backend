<?php
/**
 * Order Backend - Master Test Runner
 * Runs ALL test modules and produces a combined report.
 * 
 * Run: php tests/run_all_tests.php
 * Or: https://appforyou.store/backendOrder/tests/run_all_tests.php
 */

header('Content-Type: text/plain; charset=utf-8');
echo "╔══════════════════════════════════════════════════════════════════════╗\n";
echo "║           ORDER BACKEND - FULL API TEST SUITE                       ║\n";
echo "║           Domain: appforyou.store/backendOrder                      ║\n";
echo "║           Time: " . date('Y-m-d H:i:s') . "                              ║\n";
echo "╚══════════════════════════════════════════════════════════════════════╝\n\n";

$allResults = [];
$totalPass = 0;
$totalFail = 0;
$totalWarn = 0;
$allErrors = [];

$testFiles = [
    'test_auth.php'      => 'Auth (Admin App)',
    'test_superadmin.php' => 'Super Admin',
    'test_admin.php'      => 'Admin',
    'test_vendor.php'     => 'Vendor',
    'test_user_auth.php'  => 'User Auth',
    'test_user_app.php'   => 'User App',
];

foreach ($testFiles as $file => $label) {
    echo "\n\n" . str_repeat('█', 70) . "\n";
    echo "  RUNNING: $label ($file)\n";
    echo str_repeat('█', 70) . "\n";
    
    // Reset globals
    require_once __DIR__ . '/test_helper.php';
    resetResults();
    
    // Capture output
    ob_start();
    try {
        // Each test file includes test_helper.php, so we need to isolate
        $output = shell_exec('php ' . __DIR__ . '/' . $file . ' 2>&1');
        echo $output;
    } catch (Exception $e) {
        echo "EXCEPTION: " . $e->getMessage() . "\n";
    }
    
    $totalPass += $GLOBALS['test_pass'];
    $totalFail += $GLOBALS['test_fail'];
    $totalWarn += $GLOBALS['test_warn'];
    $allErrors = array_merge($allErrors, $GLOBALS['test_errors']);
}

echo "\n\n";
echo "╔══════════════════════════════════════════════════════════════════════╗\n";
echo "║                    FINAL SUMMARY                                    ║\n";
echo "╠══════════════════════════════════════════════════════════════════════╣\n";
echo "║  PASSED: $totalPass                                              \n";
echo "║  FAILED: $totalFail                                              \n";
echo "║  ⚠️  WARNINGS: $totalWarn                                           \n";
echo "╚══════════════════════════════════════════════════════════════════════╝\n";

if (!empty($allErrors)) {
    echo "\n⛔ ALL ISSUES:\n";
    foreach ($allErrors as $i => $err) {
        echo "  " . ($i + 1) . ". $err\n";
    }
}

echo "\nTest suite complete at " . date('H:i:s') . "\n";
?>
