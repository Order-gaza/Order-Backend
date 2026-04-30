<?php
/**
 * Super Admin - Export Earnings as PDF
 * GET: /super_admin/earnings/export_pdf.php?from=2026-01-01&to=2026-03-12&vendor_id=
 * Headers: Authorization: Bearer <token>
 * 
 * Generates a simple HTML earnings report (can be printed to PDF from browser).
 * For a proper PDF, you would need a library like TCPDF or mPDF.
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
    $user = requireRole($conn, [ROLE_SUPER_ADMIN]);
    
    $from = isset($_GET['from']) ? trim($_GET['from']) : date('Y-m-01'); // Default: start of month
    $to = isset($_GET['to']) ? trim($_GET['to']) : date('Y-m-d');       // Default: today
    $vendorId = isset($_GET['vendor_id']) ? trim($_GET['vendor_id']) : '';
    
    $query = "SELECT * FROM view_earnings_ledger WHERE DATE(earned_at) >= ? AND DATE(earned_at) <= ?";
    $params = [$from, $to];
    
    if (!empty($vendorId)) {
        $query .= " AND vendor_id = ?";
        $params[] = $vendorId;
    }
    
    $query .= " ORDER BY earned_at DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $earnings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculate totals using accurate breakdown columns
    $totalEarned = 0;
    $totalCollected = 0;
    $totalUncollected = 0;
    
    foreach ($earnings as &$e) {
        $e['service_fee_earned'] = (float)($e['service_fee_earned'] ?? 0);
        $e['commission_earned'] = (float)($e['commission_earned'] ?? 0);
        $e['sa_earning'] = $e['service_fee_earned'] + $e['commission_earned'];
        $saEarning = $e['sa_earning'];
        $totalEarned += $saEarning;
        if ($e['is_collected']) {
            $totalCollected += $saEarning;
        } else {
            $totalUncollected += $saEarning;
        }
    }
    
    // Return as JSON data (Flutter app can render the PDF)
    sendResponse(STATUS_SUCCESS, [
        'report' => [
            'title' => 'Platform Earnings Report',
            'date_range' => ['from' => $from, 'to' => $to],
            'generated_at' => date('Y-m-d H:i:s'),
            'totals' => [
                'total_earned' => round($totalEarned, 2),
                'total_collected' => round($totalCollected, 2),
                'total_uncollected' => round($totalUncollected, 2),
                'total_records' => count($earnings)
            ]
        ],
        'earnings' => $earnings
    ], "Earnings report generated");
    
} catch (PDOException $e) {
    error_log("Export earnings error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to generate earnings report");
}
?>
