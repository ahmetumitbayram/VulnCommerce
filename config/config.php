<?php
// Database configuration
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'vulncommerce');

// Site configuration
define('SITE_NAME', 'VulnCommerce');
define('SITE_URL', 'http://localhost');

// Session configuration
define('SESSION_NAME', 'vulncommerce_session');
define('SESSION_LIFETIME', 3600); // 1 hour

// Upload paths
define('UPLOAD_DIR', __DIR__ . '/../assets/uploads/');
define('PROFILE_IMAGES_DIR', UPLOAD_DIR . 'profiles/');
define('PRODUCT_IMAGES_DIR', UPLOAD_DIR . 'products/');

// Create upload directories if they don't exist
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}
if (!file_exists(PROFILE_IMAGES_DIR)) {
    mkdir(PROFILE_IMAGES_DIR, 0755, true);
}
if (!file_exists(PRODUCT_IMAGES_DIR)) {
    mkdir(PRODUCT_IMAGES_DIR, 0755, true);
} 