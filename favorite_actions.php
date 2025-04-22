<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database, functions and controller - but NOT the header
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'controllers/FavoritesController.php';

// Check if user is logged in
if (!isLoggedIn()) {
    setFlashMessage('danger', 'Bu işlemi gerçekleştirmek için giriş yapmalısınız');
    redirect('login.php');
    exit;
}

// Initialize controller
$favoritesController = new FavoritesController();

// Get the action and redirect URL
$action = isset($_POST['action']) ? $_POST['action'] : '';
$redirectUrl = isset($_POST['redirect']) ? $_POST['redirect'] : 'index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add to favorites
    if ($action === 'add' && isset($_POST['product_id'])) {
        $result = $favoritesController->addToFavorites();
        
        if ($result) {
            // If from product page, redirect back to it
            if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'product.php') !== false) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect($redirectUrl);
            }
        }
    } 
    // Remove from favorites
    else if ($action === 'remove' && isset($_POST['favorite_id'])) {
        $result = $favoritesController->removeFromFavorites();
        
        if ($result) {
            redirect('favorites.php');
        }
    }
    // Remove by product ID
    else if ($action === 'remove' && isset($_POST['product_id'])) {
        $result = $favoritesController->removeByProductId();
        
        if ($result) {
            // If from product page, redirect back to it
            if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'product.php') !== false) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect($redirectUrl);
            }
        }
    }
    // Clear favorites
    else if ($action === 'clear') {
        $result = $favoritesController->clearFavorites();
        
        if ($result) {
            redirect('favorites.php');
        }
    }
}

// Default redirect
redirect($redirectUrl); 