<?php
// General utility functions

/**
 * Sanitize input data
 */
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Redirect to a URL
 */
function redirect($url) {
    // Attempt to clear any existing output to prevent "headers already sent" errors
    if (ob_get_level()) {
        ob_end_clean();
    }
    
    // Start a new output buffer
    ob_start();
    
    header("Location: $url");
    exit;
}

/**
 * Set flash message
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Display flash message
 */
function displayFlashMessage() {
    if (isset($_SESSION['flash'])) {
        $type = $_SESSION['flash']['type'];
        $message = $_SESSION['flash']['message'];
        
        echo "<div class='alert alert-$type'>$message</div>";
        
        // Clear the flash message
        unset($_SESSION['flash']);
    }
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Require login or redirect
 */
function requireLogin() {
    if (!isLoggedIn()) {
        setFlashMessage('danger', 'You must be logged in to access this page');
        redirect('login.php');
    }
}

/**
 * Get current user data
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    $db = Database::getInstance();
    $user = $db->selectOne("SELECT * FROM users WHERE id = :id", ['id' => $_SESSION['user_id']]);
    
    return $user;
}

/**
 * Format price
 */
function formatPrice($price) {
    return number_format($price, 2, '.', ',') . ' TL';
}

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        setFlashMessage('danger', 'CSRF token validation failed');
        redirect('index.php');
    }
} 