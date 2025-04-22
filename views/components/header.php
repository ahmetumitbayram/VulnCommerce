<?php
// Start output buffering at the very beginning of the file
ob_start();

// Session start if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include dependencies
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'includes/functions.php';

// Set theme
$theme = isset($_SESSION['theme']) ? $_SESSION['theme'] : 'default';

// Load controllers
require_once 'controllers/ProductController.php';
require_once 'controllers/CartController.php';
require_once 'controllers/FavoritesController.php';
require_once 'controllers/CompareController.php';
require_once 'controllers/UserController.php';

// Initialize controllers for header counts
$cartController = new CartController();
$favoritesController = new FavoritesController();
$compareController = new CompareController();

// Get counts
$cartCount = $cartController->getCartCount();
$favoritesCount = $favoritesController->getFavoritesCount();
$compareCount = $compareController->getCompareCount();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VulnCommerce - Güvenli Alışveriş</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS for themes -->
    <style>
        /* Default Theme */
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --background-color: #ffffff;
            --text-color: #212529;
            --navbar-bg: #f8f9fa;
            --card-bg: #ffffff;
            --card-border: rgba(0,0,0,0.125);
            --footer-bg: #f8f9fa;
        }
        
        /* Dark Theme */
        <?php if ($theme === 'dark'): ?>
        :root {
            --primary-color: #375a7f;
            --secondary-color: #444444;
            --background-color: #222222;
            --text-color: #ffffff;
            --navbar-bg: #333333;
            --card-bg: #303030;
            --card-border: rgba(255,255,255,0.125);
            --footer-bg: #333333;
            --alert-bg: #444444;
            --input-bg: #2c2c2c;
            --btn-default: #444444;
        }
        
        body {
            background-color: var(--background-color);
            color: var(--text-color);
        }
        
        .card {
            background-color: var(--card-bg);
            border-color: var(--card-border);
        }
        
        .navbar {
            background-color: var(--navbar-bg) !important;
        }
        
        .navbar .navbar-brand, .navbar .nav-link {
            color: var(--text-color);
        }
        
        .navbar-light .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.75%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        .footer {
            background-color: var(--footer-bg);
            color: var(--text-color);
        }
        
        .form-control, .form-select {
            background-color: var(--input-bg);
            color: var(--text-color);
            border-color: var(--card-border);
        }
        
        .form-control:focus, .form-select:focus {
            background-color: var(--input-bg);
            color: var(--text-color);
        }
        
        .form-check-input {
            background-color: var(--input-bg);
            border-color: var(--card-border);
        }
        
        .table {
            color: var(--text-color);
        }
        
        .modal-content {
            background-color: var(--card-bg);
            color: var(--text-color);
        }
        
        .text-muted {
            color: #adb5bd !important;
        }
        
        .alert {
            background-color: var(--alert-bg);
            color: var(--text-color);
        }
        
        .dropdown-menu {
            background-color: var(--card-bg);
            border-color: var(--card-border);
        }
        
        .dropdown-item {
            color: var(--text-color);
        }
        
        .dropdown-item:hover {
            background-color: var(--navbar-bg);
            color: var(--text-color);
        }
        
        .jumbotron, .bg-light {
            background-color: var(--card-bg) !important;
            color: var(--text-color);
        }
        
        .border-0 {
            border: 1px solid var(--card-border) !important;
        }
        <?php endif; ?>
        
        /* Common Styles */
        .product-card {
            height: 100%;
            transition: transform 0.3s;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .product-card img {
            height: 200px;
            object-fit: contain;
        }
        
        .badge-cart {
            position: absolute;
            top: -8px;
            right: -8px;
        }
        
        .footer {
            margin-top: 50px;
            padding: 20px 0;
        }
    </style>

    <!-- Quick theme toggle script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tema değiştirme butonu için olay dinleyicisi
            const themeToggleLinks = document.querySelectorAll('.theme-toggle');
            
            themeToggleLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Mevcut tema kontrolü
                    const currentTheme = '<?php echo $theme; ?>';
                    const newTheme = currentTheme === 'dark' ? 'default' : 'dark';
                    
                    // AJAX ile tema değiştirme
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'settings.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            // Sayfayı yenile
                            window.location.reload();
                        }
                    };
                    
                    xhr.send('theme=' + newTheme);
                });
            });
        });
    </script>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="index.php">VulnCommerce</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Anasayfa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="products.php">Ürünler</a>
                        </li>
                    </ul>
                    
                    <!-- Search Form -->
                    <form class="d-flex me-auto" action="search.php" method="GET">
                        <input class="form-control me-2" type="search" name="keyword" placeholder="Ürün ara..." aria-label="Search">
                        <button class="btn btn-outline-primary" type="submit">Ara</button>
                    </form>
                    
                    <ul class="navbar-nav ms-auto">
                        <?php if (isLoggedIn()): ?>
                            <li class="nav-item">
                                <a class="nav-link theme-toggle" href="settings.php#theme">
                                    <i class="fas <?php echo $theme === 'dark' ? 'fa-sun' : 'fa-moon'; ?>"></i>
                                </a>
                            </li>
                            <li class="nav-item position-relative">
                                <a class="nav-link" href="cart.php">
                                    <i class="fas fa-shopping-cart"></i> Sepet
                                    <?php if ($cartCount > 0): ?>
                                        <span class="badge rounded-pill bg-danger badge-cart"><?php echo $cartCount; ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li class="nav-item position-relative">
                                <a class="nav-link" href="favorites.php">
                                    <i class="fas fa-heart"></i> Favoriler
                                    <?php if ($favoritesCount > 0): ?>
                                        <span class="badge rounded-pill bg-danger badge-cart"><?php echo $favoritesCount; ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li class="nav-item position-relative">
                                <a class="nav-link" href="compare.php">
                                    <i class="fas fa-exchange-alt"></i> Karşılaştır
                                    <?php if ($compareCount > 0): ?>
                                        <span class="badge rounded-pill bg-danger badge-cart"><?php echo $compareCount; ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user"></i> <?php echo $_SESSION['username']; ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="profile.php">Profilim</a></li>
                                    <li><a class="dropdown-item" href="settings.php">Ayarlar</a></li>
                                    <li><a class="dropdown-item" href="settings.php#theme"><i class="fas fa-paint-brush"></i> Tema Değiştir</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="logout.php" method="POST">
                                            <button type="submit" class="dropdown-item">Çıkış Yap</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="login.php">Giriş Yap</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="register.php">Kayıt Ol</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    
    <main class="container py-4">
        <?php displayFlashMessage(); ?> 