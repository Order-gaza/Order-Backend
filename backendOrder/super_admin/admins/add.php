<?php
/**
 * Super Admin - Add New Admin
 * POST: /super_admin/admins/add.php
 * Headers: Authorization: Bearer <token>
 * Body: name, email, phone, password, role (admin only)
 * 
 * Creates a new admin account. Super admin only.
 */

include "../../config/database.php";
include "../../config/constants.php";
include "../../core/functions.php";
include "../../core/middleware.php";

if (!isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db = new Database();
$conn = $db->connect();

try {
    $user = requireRole($conn, [ROLE_SUPER_ADMIN]);
    
    $name = filterRequest("name");
    $email = filterRequest("email");
    $phone = filterRequest("phone");
    $password = filterRequest("password");
    $role = filterRequest("role");
    $zoneCoverage = filterRequest("zone_coverage");
    
    $errors = validateRequired(['name', 'email', 'phone', 'password']);
    if (!empty($errors)) {
        sendResponse(STATUS_FAIL, null, implode(", ", $errors));
    }
    
    if (!isValidEmail($email)) {
        sendResponse(STATUS_FAIL, null, "Invalid email format");
    }
    
    if (strlen($password) < 6) {
        sendResponse(STATUS_FAIL, null, "Password must be at least 6 characters");
    }
    
    // Only allow creating admin role (not super_admin, vendor, customer)
    if (empty($role) || $role !== ROLE_ADMIN) {
        $role = ROLE_ADMIN;
    }
    
    // Check if email already exists
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->execute([$email]);
    if ($checkStmt->fetch()) {
        sendResponse(STATUS_FAIL, null, "Email already exists");
    }
    
    // Check if phone already exists
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE phone = ?");
    $checkStmt->execute([$phone]);
    if ($checkStmt->fetch()) {
        sendResponse(STATUS_FAIL, null, "Phone number already exists");
    }
    
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    // Validate zone_coverage
    $validZones = ['north', 'south'];
    if (!empty($zoneCoverage) && !in_array($zoneCoverage, $validZones)) {
        $zoneCoverage = null;
    }
    
    $insertStmt = $conn->prepare("
        INSERT INTO users 
        (name, email, phone, password_hash, role, zone_coverage, is_active, email_verified_at, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, 1, NOW(), NOW(), NOW())
    ");
    
    $insertStmt->execute([$name, $email, $phone, $passwordHash, $role, $zoneCoverage]);
    
    // Get the newly created admin (UUID is auto-generated)
    $selectStmt = $conn->prepare("
        SELECT id, name, email, phone, role, zone_coverage, is_active, created_at
        FROM users 
        WHERE email = ? AND role = ?
    ");
    $selectStmt->execute([$email, $role]);
    $newAdmin = $selectStmt->fetch(PDO::FETCH_ASSOC);
    
    if ($newAdmin) {
        logActivity($conn, $user['user_id'], 'create', 'admin', $newAdmin['id'],
            "Created new admin: {$newAdmin['name']} ({$newAdmin['email']})");
        
        sendResponse(STATUS_SUCCESS, [
            'admin' => [
                'id' => $newAdmin['id'],
                'name' => $newAdmin['name'],
                'email' => $newAdmin['email'],
                'phone' => $newAdmin['phone'],
                'role' => $newAdmin['role'],
                'zone_coverage' => $newAdmin['zone_coverage'],
                'is_active' => (bool)$newAdmin['is_active'],
                'created_at' => formatDateTime($newAdmin['created_at'])
            ]
        ], "Admin created successfully");
    } else {
        sendResponse(STATUS_FAIL, null, "Failed to create admin");
    }
    
} catch (PDOException $e) {
    error_log("Add admin error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "An error occurred while creating admin");
}
?>
