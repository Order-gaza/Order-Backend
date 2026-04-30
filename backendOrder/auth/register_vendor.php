<?php
/**
 * Order Admin - Register Vendor Endpoint
 * POST: /auth/register_vendor.php
 * Body: name, email, password, confirm_password, phone,
 *       business_name, business_type, business_phone, business_address,
 *       latitude, longitude
 *
 * Creates a user (role=vendor) + vendor profile (status=pending).
 * The vendor cannot login until a Super Admin approves them.
 */

include "../config/database.php";
include "../config/constants.php";
include "../core/functions.php";

// Only allow POST requests
if (!isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

// Get input
$name             = filterRequest("name");
$email            = filterRequest("email");
$password         = filterRequest("password");
$confirmPassword  = filterRequest("confirm_password");
$phone            = filterRequest("phone");
$businessName     = filterRequest("business_name");
$businessType     = filterRequest("business_type");
$businessPhone    = filterRequest("business_phone");
$businessAddress  = filterRequest("business_address");
$businessCity     = filterRequest("business_city");
$businessNeighborhood = filterRequest("business_neighborhood");
$deliveryZone     = filterRequest("delivery_zone");
$latitude         = filterRequest("latitude");
$longitude        = filterRequest("longitude");

// ── Validate required fields ──
$errors = validateRequired([
    'name', 'email', 'password', 'confirm_password',
    'phone', 'business_name', 'business_type',
    'business_phone', 'business_address'
]);
if (!empty($errors)) {
    sendResponse(STATUS_FAIL, null, implode(", ", $errors));
}

// Validate email
if (!isValidEmail($email)) {
    sendResponse(STATUS_FAIL, null, "Invalid email format");
}

// Validate password
if (strlen($password) < 6) {
    sendResponse(STATUS_FAIL, null, "Password must be at least 6 characters");
}
if ($password !== $confirmPassword) {
    sendResponse(STATUS_FAIL, null, "Passwords do not match");
}

// Validate business type
if (!in_array($businessType, ['restaurant', 'store', 'cafe'])) {
    sendResponse(STATUS_FAIL, null, "Business type must be restaurant, store, or cafe");
}

// Validate/resolve delivery zone from city
$validZones = ['north', 'central', 'south'];
$cityZoneMap = [
    'مدينة غزة' => 'north',
    'مدينة دير البلح' => 'central',
    'مخيم النصيرات' => 'central',
    'مخيم البريج' => 'central',
    'مخيم المغازي' => 'central',
    'مدينة خانيونس' => 'south',
];
if (empty($deliveryZone) || !in_array($deliveryZone, $validZones)) {
    $deliveryZone = !empty($businessCity) ? ($cityZoneMap[$businessCity] ?? 'north') : 'north';
}

// Auto-generate address from city/neighborhood if not provided
if (empty($businessAddress) && !empty($businessCity)) {
    $businessAddress = $businessCity . (!empty($businessNeighborhood) ? ' - ' . $businessNeighborhood : '');
}

// Default coordinates
$lat = !empty($latitude)  ? floatval($latitude)  : 31.50000000;
$lng = !empty($longitude) ? floatval($longitude) : 34.47000000;

$db   = new Database();
$conn = $db->connect();

try {
    // Check duplicate email
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        sendResponse(STATUS_FAIL, null, "Email is already registered");
    }

    $conn->beginTransaction();

    try {
        // 1) Create user with role = vendor
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $userId = generateUUID();
        $userStmt = $conn->prepare("
            INSERT INTO users (id, name, email, password_hash, phone, role, is_active)
            VALUES (?, ?, ?, ?, ?, ?, 0)
        ");
        $userStmt->execute([$userId, $name, $email, $hashedPassword, $phone, ROLE_VENDOR]);

        // 2) Create vendor profile with status = pending
        $vendorStmt = $conn->prepare("
            INSERT INTO vendors
            (user_id, name, type, phone, address, city, neighborhood, latitude, longitude, delivery_zone, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
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
            VENDOR_PENDING
        ]);

        $conn->commit();

        sendResponse(STATUS_SUCCESS, [
            'user_id'  => $userId,
            'email'    => $email,
            'status'   => VENDOR_PENDING
        ], "Vendor registration submitted. Please wait for administrator approval.");

    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }

} catch (PDOException $e) {
    error_log("Vendor registration error: " . $e->getMessage());
    sendResponse(STATUS_ERROR, null, "Registration failed. Please try again.");
}
?>
