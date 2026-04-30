<?php
/**
 * Gaza Order - Authentication Middleware
 * Validates authentication tokens against the unified users table
 * 
 * Key difference from Naseej: single `users` table with role ENUM,
 * UUID primary keys, and `admin_sessions` references `user_id`.
 */

/**
 * Verify admin/vendor/super_admin authentication token
 * @param PDO $conn Database connection
 * @return array User data if valid, exits with error if invalid
 */
function verifyAuth($conn) {
    // Get token from Authorization header
    $headers = getallheaders();
    $token = null;
    
    if (isset($headers['Authorization'])) {
        $authHeader = $headers['Authorization'];
        if (preg_match('/Bearer\s+(.+)/', $authHeader, $matches)) {
            $token = $matches[1];
        }
    }
    
    // Fallback: check POST data
    if (!$token && isset($_POST['token'])) {
        $token = $_POST['token'];
    }
    
    if (!$token) {
        sendResponse(STATUS_FAIL, null, ERR_UNAUTHORIZED);
    }
    
    try {
        $stmt = $conn->prepare("
            SELECT 
                s.user_id,
                u.email,
                u.name,
                u.phone,
                u.role,
                u.is_active
            FROM admin_sessions s
            INNER JOIN users u ON s.user_id = u.id
            WHERE s.token = ? 
            AND s.expires_at > NOW()
            AND u.is_active = 1
        ");
        
        $stmt->execute([$token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            sendResponse(STATUS_FAIL, null, ERR_UNAUTHORIZED);
        }
        
        return $user;
        
    } catch (PDOException $e) {
        error_log("Auth verification error: " . $e->getMessage());
        sendResponse(STATUS_ERROR, null, ERR_DATABASE);
        return []; // unreachable — sendResponse calls exit()
    }
}

/**
 * Check if user has required role
 * @param array $user User data from verifyAuth()
 * @param array $allowedRoles Array of allowed roles
 * @return bool
 */
function hasRole($user, $allowedRoles = [ROLE_SUPER_ADMIN, ROLE_ADMIN, ROLE_VENDOR]) {
    return in_array($user['role'], $allowedRoles);
}

/**
 * Require specific role(s) - verifies auth AND checks role
 * @param PDO $conn Database connection
 * @param array $roles Required roles
 * @return array User data if authorized
 */
function requireRole($conn, $roles = [ROLE_SUPER_ADMIN, ROLE_ADMIN]) {
    $user = verifyAuth($conn);
    
    if (!hasRole($user, $roles)) {
        sendResponse(STATUS_FAIL, null, ERR_PERMISSION_DENIED);
    }
    
    return $user;
}

/**
 * Create new session for user
 * @param PDO $conn Database connection
 * @param string $userId User UUID
 * @return string Session token
 */
function createSession($conn, $userId) {
    $token = generateToken();
    $expiresAt = date('Y-m-d H:i:s', strtotime('+' . ADMIN_TOKEN_EXPIRY_HOURS . ' hours'));
    
    try {
        $stmt = $conn->prepare("
            INSERT INTO admin_sessions 
            (user_id, token, ip_address, user_agent, expires_at) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $userId,
            $token,
            getClientIp(),
            $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
            $expiresAt
        ]);
        
        // Update last activity timestamp
        $updateStmt = $conn->prepare("
            UPDATE users 
            SET updated_at = NOW() 
            WHERE id = ?
        ");
        $updateStmt->execute([$userId]);
        
        return $token;
        
    } catch (PDOException $e) {
        error_log("Session creation error: " . $e->getMessage());
        sendResponse(STATUS_ERROR, null, ERR_DATABASE);
        return ''; // unreachable — sendResponse calls exit()
    }
}

/**
 * Delete session (logout)
 * @param PDO $conn Database connection
 * @param string $token Session token
 */
function deleteSession($conn, $token) {
    try {
        $stmt = $conn->prepare("DELETE FROM admin_sessions WHERE token = ?");
        $stmt->execute([$token]);
    } catch (PDOException $e) {
        error_log("Session deletion error: " . $e->getMessage());
    }
}

/**
 * Clean expired sessions
 * @param PDO $conn Database connection
 */
function cleanExpiredSessions($conn) {
    try {
        $stmt = $conn->prepare("DELETE FROM admin_sessions WHERE expires_at < NOW()");
        $stmt->execute();
    } catch (PDOException $e) {
        error_log("Session cleanup error: " . $e->getMessage());
    }
}

/**
 * Verify customer authentication token
 * Used by user_* endpoints — only allows ROLE_CUSTOMER
 * @param PDO $conn Database connection
 * @return array Customer data if valid, exits with error if invalid
 */
function verifyCustomerAuth($conn) {
    $token = extractToken();
    
    if (!$token) {
        sendResponse(STATUS_FAIL, null, ERR_UNAUTHORIZED);
    }
    
    try {
        $stmt = $conn->prepare("
            SELECT 
                s.user_id AS customer_id,
                u.id,
                u.email,
                u.name,
                u.phone,
                u.role,
                u.is_active,
                u.email_verified_at
            FROM admin_sessions s
            INNER JOIN users u ON s.user_id = u.id
            WHERE s.token = ? 
            AND s.expires_at > NOW()
            AND u.is_active = 1
            AND u.role = ?
        ");
        
        $stmt->execute([$token, ROLE_CUSTOMER]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$customer) {
            sendResponse(STATUS_FAIL, null, ERR_UNAUTHORIZED);
        }
        
        return $customer;
        
    } catch (PDOException $e) {
        error_log("Customer auth error: " . $e->getMessage());
        sendResponse(STATUS_ERROR, null, ERR_DATABASE);
        return []; // unreachable — sendResponse calls exit()
    }
}

/**
 * Create session for customer (uses longer USER_TOKEN_EXPIRY_HOURS)
 * @param PDO $conn Database connection
 * @param string $userId User UUID
 * @return array ['token' => string, 'expires_at' => string]
 */
function createUserSession($conn, $userId) {
    $token = generateToken();
    $expiresAt = date('Y-m-d H:i:s', strtotime('+' . USER_TOKEN_EXPIRY_HOURS . ' hours'));
    
    try {
        $stmt = $conn->prepare("
            INSERT INTO admin_sessions 
            (user_id, token, ip_address, user_agent, expires_at) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $userId,
            $token,
            getClientIp(),
            $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
            $expiresAt
        ]);
        
        return ['token' => $token, 'expires_at' => $expiresAt];
        
    } catch (PDOException $e) {
        error_log("User session creation error: " . $e->getMessage());
        sendResponse(STATUS_ERROR, null, ERR_DATABASE);
        return ['token' => '', 'expires_at' => '']; // unreachable — sendResponse calls exit()
    }
}

/**
 * Extract bearer token from request headers/POST/GET
 * @return string|null Token or null
 */
function extractToken() {
    $headers = getallheaders();
    $token = null;
    
    if (isset($headers['Authorization'])) {
        if (preg_match('/Bearer\s+(.+)/', $headers['Authorization'], $matches)) {
            $token = $matches[1];
        }
    }
    
    if (!$token && isset($_POST['token'])) {
        $token = $_POST['token'];
    }
    
    if (!$token && isset($_GET['token'])) {
        $token = $_GET['token'];
    }
    
    return $token;
}
?>