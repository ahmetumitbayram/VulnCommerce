<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database, functions and controller - but NOT the header
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'controllers/CompareController.php';

// Check if user is logged in
if (!isLoggedIn()) {
    setFlashMessage('danger', 'Bu işlemi gerçekleştirmek için giriş yapmalısınız');
    redirect('login.php');
    exit;
}

// Initialize controller
$compareController = new CompareController();

// Get the action and redirect URL
$action = isset($_POST['action']) ? $_POST['action'] : '';
$redirectUrl = isset($_POST['redirect']) ? $_POST['redirect'] : 'index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add to compare
    if ($action === 'add' && isset($_POST['product_id'])) {
        $result = $compareController->addToCompare();
        
        if ($result) {
            // If from product page, redirect back to it
            if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'product.php') !== false) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect($redirectUrl);
            }
        }
    } 
    // Remove from compare
    else if ($action === 'remove' && isset($_POST['compare_id'])) {
        $result = $compareController->removeFromCompare();
        
        if ($result) {
            redirect('compare.php');
        }
    }
    // Remove by product ID
    else if ($action === 'remove' && isset($_POST['product_id'])) {
        $result = $compareController->removeByProductId();
        
        if ($result) {
            // If from product page, redirect back to it
            if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'product.php') !== false) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect($redirectUrl);
            }
        }
    }
    // Clear compare
    else if ($action === 'clear') {
        $result = $compareController->clearCompare();
        
        if ($result) {
            redirect('compare.php');
        }
    }
}

// Default redirect
redirect($redirectUrl); 