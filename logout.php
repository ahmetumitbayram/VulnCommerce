<?php
// Include header dependencies
require_once 'views/components/header.php';

// Initialize controller
$userController = new UserController();

// Handle logout
$userController->logout();

// Redirect to homepage
redirect('index.php'); 