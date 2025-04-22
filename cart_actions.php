<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database, functions and controller - but NOT the header
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'controllers/CartController.php';

// Check if user is logged in
if (!isLoggedIn()) {
    setFlashMessage('danger', 'Bu işlemi gerçekleştirmek için giriş yapmalısınız');
    redirect('login.php');
    exit;
}

// Initialize controller
$cartController = new CartController();

// Get the action and redirect URL
$action = isset($_POST['action']) ? $_POST['action'] : '';
$redirectUrl = isset($_POST['redirect']) ? $_POST['redirect'] : 'index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add to cart
    if ($action === 'add' && isset($_POST['product_id'])) {
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        $result = $cartController->addToCart();
        
        if ($result) {
            // If from product page, redirect back to it
            if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'product.php') !== false) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect($redirectUrl);
            }
        }
    } 
    // Remove from cart
    else if ($action === 'remove' && isset($_POST['cart_id'])) {
        $result = $cartController->removeFromCart();
        
        if ($result) {
            redirect('cart.php');
        }
    }
    // Remove by product ID
    else if ($action === 'remove' && isset($_POST['product_id'])) {
        $result = $cartController->removeByProductId();
        
        if ($result) {
            // If from product page, redirect back to it
            if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'product.php') !== false) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect($redirectUrl);
            }
        }
    }
    // Update quantity
    else if ($action === 'update' && isset($_POST['cart_id']) && isset($_POST['quantity'])) {
        $result = $cartController->updateCartQuantity();
        
        if ($result) {
            redirect('cart.php');
        }
    }
    // Clear cart
    else if ($action === 'clear') {
        $result = $cartController->clearCart();
        
        if ($result) {
            redirect('cart.php');
        }
    }
}

// Default redirect
redirect($redirectUrl); 