<?php
/**
 * Gaza Order - Constants Configuration
 * Application-wide constants and settings
 */

// API Response Status
define("STATUS_SUCCESS", "success");
define("STATUS_FAIL", "fail");
define("STATUS_ERROR", "error");

// Email Settings
define('SMTP_HOST', '');
define('SMTP_PORT', );
define('SMTP_USERNAME', '');
define('SMTP_PASSWORD', '');
define('SMTP_FROM_EMAIL', '');
define('SMTP_FROM_NAME', '');

// File Upload Settings
define("MB", 1048576);
define("MAX_IMAGE_SIZE", 5 * MB); // 5MB
define("MAX_PROFILE_SIZE", 2 * MB); // 2MB

// Allowed file extensions
define("ALLOWED_IMAGES", ["jpg", "jpeg", "png", "gif", "webp"]);

// Upload directories (anchored to config/ directory)
define("UPLOAD_DIR", __DIR__ . "/../upload/");
define("PROFILES_DIR", UPLOAD_DIR . "profiles/");
define("VENDORS_DIR", UPLOAD_DIR . "vendors/");
define("ITEMS_DIR", UPLOAD_DIR . "items/");
define("CATEGORIES_DIR", UPLOAD_DIR . "categories/");
define("ADS_DIR", UPLOAD_DIR . "advertisements/");

// Base URL for images (UPDATE THIS FOR YOUR SERVER)
define("BASE_URL", "");
define("IMAGE_BASE_URL", BASE_URL . "upload/");

// Session/Token settings - SEPARATE DURATIONS
define("ADMIN_TOKEN_EXPIRY_HOURS", 24);  // 24 hours for admin/vendor (security)
define("USER_TOKEN_EXPIRY_HOURS", 87600);  // ~10 years for users (permanent until manual logout)

define("SESSION_NAME", "order_admin_session");

// Pagination
define("ITEMS_PER_PAGE", 20);

// User Roles
define("ROLE_SUPER_ADMIN", "super_admin");
define("ROLE_ADMIN", "admin");
define("ROLE_VENDOR", "vendor");
define("ROLE_CUSTOMER", "customer");

// Order Status Constants (matching DB ENUM)
define("ORDER_PENDING", "pending");
define("ORDER_ADMIN_CONFIRMED", "admin_confirmed");
define("ORDER_SENT_TO_VENDOR", "sent_to_vendor");
define("ORDER_VENDOR_ACCEPTED", "vendor_accepted");
define("ORDER_PREPARING", "preparing");
define("ORDER_READY_FOR_PICKUP", "ready_for_pickup");
define("ORDER_OUT_FOR_DELIVERY", "out_for_delivery");
define("ORDER_DELIVERED", "delivered");
define("ORDER_CANCELLED", "cancelled");

// Payment Status Constants
define("PAYMENT_PENDING", "pending");
define("PAYMENT_COLLECTED", "collected");
define("PAYMENT_FAILED", "failed");

// Payment Method Constants
define("PAY_CASH", "cash_on_delivery");
define("PAY_BANK_TRANSFER", "bank_transfer");

// Vendor Status Constants
define("VENDOR_PENDING", "pending");
define("VENDOR_APPROVED", "approved");
define("VENDOR_SUSPENDED", "suspended");
define("VENDOR_REJECTED", "rejected");

// Fee Model Constants
define("FEE_FLAT", "flat_per_order");
define("FEE_PERCENTAGE", "percentage");

// Branch Region Constants
define("BRANCH_NORTH", "north");
define("BRANCH_SOUTH", "south");

// Error Messages
define("ERR_UNAUTHORIZED", "Unauthorized access. Please login.");
define("ERR_INVALID_DATA", "Invalid data provided.");
define("ERR_NOT_FOUND", "Resource not found.");
define("ERR_DATABASE", "Database error occurred.");
define("ERR_UPLOAD_FAILED", "File upload failed.");
define("ERR_PERMISSION_DENIED", "Permission denied.");

// Success Messages
define("MSG_SUCCESS", "Operation completed successfully.");
define("MSG_CREATED", "Resource created successfully.");
define("MSG_UPDATED", "Resource updated successfully.");
define("MSG_DELETED", "Resource deleted successfully.");

// Initialize upload directories
function initializeUploadDirectories() {
    $directories = [
        UPLOAD_DIR,
        PROFILES_DIR,
        VENDORS_DIR,
        ITEMS_DIR,
        CATEGORIES_DIR,
        ADS_DIR
    ];
    
    foreach ($directories as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}

// Call initialization
initializeUploadDirectories();
?>
