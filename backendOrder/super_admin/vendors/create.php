<?php
/**
 * Super Admin - Create Vendor (pre-approved)
 * POST: /super_admin/vendors/create.php
 * Headers: Authorization: Bearer <token>
 * Body: name, email, password, phone,
 *       business_name, business_type, business_phone, business_address,
 *       latitude, longitude, commission_rate (optional)
 *
 * Creates a user (role=vendor, is_active=1) + vendor profile (status=approved).
 * This allows the Super Admin to directly add a confirmed vendor.
 */

include "../../config/database.php";
include "../../config/constants.php";
include "../../core/functions.php";
include "../../core/middleware.php";

if (!isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

$db   = new Database();
$conn = $db->connect();

try {
    $user = requireRole($conn, [ROLE_SUPER_ADMIN]);

    // Get input
    $name            = filterRequest("name");
    $email           = filterRequest("email");
    $password        = filterRequest("password");
    $phone           = filterRequest("phone");
    $businessName    = filterRequest("business_name");
    $businessType    = filterRequest("business_type");
    $businessPhone   = filterRequest("business_phone");
    $businessAddress = filterRequest("business_address");
    $businessCity    = filterRequest("business_city");
    $businessNeighborhood = filterRequest("business_neighborhood");
    $deliveryZone    = filterRequest("delivery_zone");
    $latitude        = filterRequest("latitude");
    $longitude       = filterRequest("longitude");
    $commissionRate  = filterRequest("commission_rate");

    // Validate required fields
    if (empty($name) || empty($email) || empty($password) || empty($phone)) {
        sendResponse(STATUS_FAIL, null, "Owner name, email, password and phone are required");
    }
    if (empty($businessName) || empty($businessType) || empty($businessPhone) || empty($businessAddress)) {
        sendResponse(STATUS_FAIL, null, "Business name, type, phone and address are required");
    }

    // Validate email
    if (!isValidEmail($email)) {
        sendResponse(STATUS_FAIL, null, "Invalid email format");
    }

    // Validate password
    if (strlen($password) < 6) {
        sendResponse(STATUS_FAIL, null, "Password must be at least 6 characters");
    }

    // Validate business type
    if (!in_array($businessType, ['restaurant', 'store', 'cafe'])) {
        sendResponse(STATUS_FAIL, null, "Business type must be restaurant, store, or cafe");
    }

    // Default coordinates
    $lat = !empty($latitude)  ? floatval($latitude)  : 31.50000000;
    $lng = !empty($longitude) ? floatval($longitude) : 34.47000000;
    $commission = !empty($commissionRate) ? floatval($commissionRate) : 12.00;

    // Auto-resolve delivery zone from city
    $validZones = ['north', 'central', 'south'];
    if (empty($deliveryZone) || !in_array($deliveryZone, $validZones)) {
        $cityZoneMap = [
            'مدينة غزة' => 'north',
            'مدينة دير البلح' => 'central',
            'مخيم النصيرات' => 'central',
            'مخيم البريج' => 'central',
            'مخيم المغازي' => 'central',
            'مدينة خانيونس' => 'south',
        ];
        $deliveryZone = !empty($businessCity) ? ($cityZoneMap[$businessCity] ?? 'north') : 'north';
    }

    // Auto-generate address from city/neighborhood if not provided
    if (empty($businessAddress) && !empty($businessCity)) {
        $businessAddress = $businessCity . (!empty($businessNeighborhood) ? ' - ' . $businessNeighborhood : '');
    }

    // ── Clean up orphaned vendor users from previous failed attempts ──
    // If a previous create attempt succeeded in inserting a user (role=vendor)
    // but the vendor profile INSERT failed, the user row remains orphaned.
    // We detect and remove such orphans before checking duplicates.
    $cleanupStmt = $conn->prepare("
        DELETE u FROM users u
        LEFT JOIN vendors v ON v.user_id = u.id
        WHERE u.role = ?
        AND v.id IS NULL
        AND (u.email = ? OR u.phone = ?)
    ");
    $cleanupStmt->execute([ROLE_VENDOR, $email, $phone]);

    // Check duplicate email
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        sendResponse(STATUS_FAIL, null, "Email is already registered");
    }

    // Check duplicate phone
    $stmt = $conn->prepare("SELECT id FROM users WHERE phone = ?");
    $stmt->execute([$phone]);
    if ($stmt->fetch()) {
        sendResponse(STATUS_FAIL, null, "Phone number is already registered");
    }

    $conn->beginTransaction();

    try {
        // 1) Create user with role=vendor, is_active=1 (pre-approved)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $userStmt = $conn->prepare("
            INSERT INTO users (name, email, password_hash, phone, role, is_active, email_verified_at)
            VALUES (?, ?, ?, ?, ?, 1, NOW())
        ");
        $userStmt->execute([$name, $email, $hashedPassword, $phone, ROLE_VENDOR]);

        // Get auto-generated UUID
        $userId = $conn->lastInsertId();
        if (empty($userId)) {
            // For UUID default, fetch the last inserted user
            $fetchStmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND phone = ?");
            $fetchStmt->execute([$email, $phone]);
            $userId = $fetchStmt->fetchColumn();
        }

        // 2) Create vendor profile with status=approved
        $vendorStmt = $conn->prepare("
            INSERT INTO vendors
            (user_id, name, type, phone, address, city, neighborhood, latitude, longitude, delivery_zone, status, commission_rate)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $vendorStmt->execute([
            $userId,
            $businessName,
            $businessType,
            $businessPhone,
            $businessAddress,
            $businessCity ?? '',
            $businessNeighborhood ?? '',
            $lat,
            $lng,
            $deliveryZone,
            VENDOR_APPROVED,
            $commission
        ]);

        // Get vendor ID
        $vendorId = $conn->lastInsertId();
        if (empty($vendorId)) {
            $fetchVendor = $conn->prepare("SELECT id FROM vendors WHERE user_id = ?");
            $fetchVendor->execute([$userId]);
            $vendorId = $fetchVendor->fetchColumn();
        }

        $conn->commit();

        sendResponse(STATUS_SUCCESS, [
            'vendor_id' => $vendorId,
            'user_id'   => $userId,
            'email'     => $email,
            'status'    => VENDOR_APPROVED
        ], "Vendor created and approved successfully");

    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }

} catch (PDOException $e) {
    error_log("SA create vendor error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Failed to create vendor: " . $e->getMessage());
}
?>
