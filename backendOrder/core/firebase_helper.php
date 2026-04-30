<?php
/**
 * Firebase Cloud Messaging (FCM) Helper
 * Uses FCM HTTP v1 API with Service Account authentication
 * 
 * Sends push notifications via:
 *   https://fcm.googleapis.com/v1/projects/{project_id}/messages:send
 */

// Path to Firebase service account JSON key
define('FIREBASE_CREDENTIALS_PATH', __DIR__ . '/../config/firebase-service-account.json');

/**
 * Get Firebase project ID from service account file
 */
function getFirebaseProjectId() {
    static $projectId = null;
    if ($projectId !== null) return $projectId;
    
    $creds = _loadFirebaseCredentials();
    $projectId = $creds['project_id'] ?? '';
    return $projectId;
}

/**
 * Load and cache Firebase credentials
 */
function _loadFirebaseCredentials() {
    static $creds = null;
    if ($creds !== null) return $creds;
    
    if (!file_exists(FIREBASE_CREDENTIALS_PATH)) {
        error_log("Firebase credentials file not found: " . FIREBASE_CREDENTIALS_PATH);
        return [];
    }
    
    $json = file_get_contents(FIREBASE_CREDENTIALS_PATH);
    $creds = json_decode($json, true);
    
    if (!$creds) {
        error_log("Failed to parse Firebase credentials JSON");
        return [];
    }
    
    return $creds;
}

/**
 * Generate a JWT for Google OAuth2 token exchange
 * @return string JWT token
 */
function _generateFirebaseJWT() {
    $creds = _loadFirebaseCredentials();
    
    if (empty($creds['client_email']) || empty($creds['private_key'])) {
        error_log("Firebase credentials missing client_email or private_key");
        return '';
    }
    
    $now = time();
    
    // JWT Header
    $header = base64_url_encode(json_encode([
        'alg' => 'RS256',
        'typ' => 'JWT'
    ]));
    
    // JWT Payload
    $payload = base64_url_encode(json_encode([
        'iss' => $creds['client_email'],
        'sub' => $creds['client_email'],
        'aud' => 'https://oauth2.googleapis.com/token',
        'iat' => $now,
        'exp' => $now + 3600,
        'scope' => 'https://www.googleapis.com/auth/firebase.messaging'
    ]));
    
    // Sign
    $signatureInput = "$header.$payload";
    $privateKey = openssl_pkey_get_private($creds['private_key']);
    
    if (!$privateKey) {
        error_log("Firebase: Failed to parse private key");
        return '';
    }
    
    openssl_sign($signatureInput, $signature, $privateKey, OPENSSL_ALGO_SHA256);
    
    $signatureEncoded = base64_url_encode($signature);
    
    return "$header.$payload.$signatureEncoded";
}

/**
 * Base64 URL-safe encode (no padding)
 */
function base64_url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

/**
 * Get OAuth2 access token from Google using JWT
 * Caches the token for reuse within the same request
 * @return string Access token
 */
function _getFirebaseAccessToken() {
    static $cachedToken = null;
    static $cachedExpiry = 0;
    
    if ($cachedToken && time() < $cachedExpiry) {
        return $cachedToken;
    }
    
    $jwt = _generateFirebaseJWT();
    if (empty($jwt)) return '';
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://oauth2.googleapis.com/token',
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt
        ]),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200) {
        error_log("Firebase OAuth2 token exchange failed (HTTP $httpCode): $response");
        return '';
    }
    
    $data = json_decode($response, true);
    if (empty($data['access_token'])) {
        error_log("Firebase OAuth2 response missing access_token: $response");
        return '';
    }
    
    $cachedToken = $data['access_token'];
    $cachedExpiry = time() + ($data['expires_in'] ?? 3500) - 60; // refresh 60s early
    
    return $cachedToken;
}

/**
 * Send a push notification to a single FCM token
 * 
 * @param string $fcmToken Device FCM token
 * @param string $title Notification title
 * @param string $body Notification body
 * @param array $data Extra data payload (key-value strings)
 * @return bool Success
 */
function sendFcmToToken($fcmToken, $title, $body, $data = []) {
    $accessToken = _getFirebaseAccessToken();
    if (empty($accessToken)) return false;
    
    $projectId = getFirebaseProjectId();
    if (empty($projectId)) return false;
    
    // Ensure all data values are strings (FCM requirement)
    $stringData = [];
    foreach ($data as $k => $v) {
        $stringData[$k] = strval($v);
    }
    
    $message = [
        'message' => [
            'token' => $fcmToken,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => $stringData,
            'android' => [
                'priority' => 'high',
                'notification' => [
                    'channel_id' => 'order_notifications',
                    'sound' => 'default',
                    'default_vibrate_timings' => true,
                    'default_light_settings' => true,
                ],
            ],
        ],
    ];
    
    $url = "https://fcm.googleapis.com/v1/projects/$projectId/messages:send";
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($message),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $accessToken",
            'Content-Type: application/json',
        ],
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200) {
        error_log("FCM send failed (HTTP $httpCode) to token: " . substr($fcmToken, 0, 20) . "... Response: $response");
        
        // If token is invalid, clean it up
        if ($httpCode === 404 || strpos($response, 'UNREGISTERED') !== false) {
            error_log("FCM token invalid/unregistered, should be cleaned up");
        }
        return false;
    }
    
    return true;
}

/**
 * Send push notification to ALL tokens of a specific user
 * 
 * @param PDO $conn Database connection
 * @param string $userId User UUID
 * @param string $title Notification title
 * @param string $body Notification body
 * @param array $data Extra data payload
 * @return int Number of successful sends
 */
function sendPushToUser($conn, $userId, $title, $body, $data = []) {
    try {
        $stmt = $conn->prepare("SELECT token FROM fcm_tokens WHERE user_id = ?");
        $stmt->execute([$userId]);
        $tokens = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $success = 0;
        foreach ($tokens as $token) {
            if (sendFcmToToken($token, $title, $body, $data)) {
                $success++;
            }
        }
        return $success;
    } catch (PDOException $e) {
        error_log("sendPushToUser error: " . $e->getMessage());
        return 0;
    }
}

/**
 * Send push notification to ALL users with a specific role
 * 
 * @param PDO $conn Database connection
 * @param string $role Role name (admin, super_admin, vendor, customer)
 * @param string $title Notification title
 * @param string $body Notification body
 * @param array $data Extra data payload
 * @return int Number of successful sends
 */
function sendPushToRole($conn, $role, $title, $body, $data = []) {
    try {
        $stmt = $conn->prepare("
            SELECT ft.token 
            FROM fcm_tokens ft
            JOIN users u ON ft.user_id = u.id
            WHERE u.role = ? AND u.is_active = 1
        ");
        $stmt->execute([$role]);
        $tokens = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $success = 0;
        foreach ($tokens as $token) {
            if (sendFcmToToken($token, $title, $body, $data)) {
                $success++;
            }
        }
        return $success;
    } catch (PDOException $e) {
        error_log("sendPushToRole error: " . $e->getMessage());
        return 0;
    }
}

/**
 * Send push notification to a specific vendor's user account
 * 
 * @param PDO $conn Database connection
 * @param string $vendorId Vendor UUID (from vendors table, NOT user_id)
 * @param string $title Notification title
 * @param string $body Notification body
 * @param array $data Extra data payload
 * @return int Number of successful sends
 */
function sendPushToVendor($conn, $vendorId, $title, $body, $data = []) {
    try {
        $stmt = $conn->prepare("SELECT user_id FROM vendors WHERE id = ?");
        $stmt->execute([$vendorId]);
        $vendor = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$vendor) return 0;
        
        return sendPushToUser($conn, $vendor['user_id'], $title, $body, $data);
    } catch (PDOException $e) {
        error_log("sendPushToVendor error: " . $e->getMessage());
        return 0;
    }
}
?>
