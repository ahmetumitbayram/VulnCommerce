<?php
// Include header
require_once 'views/components/header.php';

// Initialize controllers
$productController = new ProductController();

// Get 10 products for homepage
$products = $productController->getAllProducts(10);
?>

<!-- Hero Section -->
<section class="jumbotron bg-light p-5 rounded">
    <div class="container">
        <h1 class="display-4">VulnCommerce'e Hoş Geldiniz</h1>
        <p class="lead">Güvenli ve kolay alışveriş deneyimi için buradayız.</p>
        <hr class="my-4">
        <p>En yeni ürünlerimize göz atın ve alışverişin keyfini çıkarın.</p>
        <div class="d-flex gap-2">
            <a class="btn btn-primary btn-lg" href="products.php" role="button">Tüm Ürünleri Gör</a>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-5">
    <div class="container">
        <h2 class="mb-4">Öne Çıkan Ürünler</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 g-4">
            <?php foreach ($products as $product): ?>
                <div class="col">
                    <?php include 'views/components/product_card.php'; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Neden Bizi Tercih Etmelisiniz?</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-shipping-fast fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Hızlı Teslimat</h5>
                        <p class="card-text">Siparişleriniz 24 saat içinde kargoya verilir.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-lock fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Güvenli Ödeme</h5>
                        <p class="card-text">128-bit SSL güvenlik sertifikası ile güvenli alışveriş.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">7/24 Destek</h5>
                        <p class="card-text">Herhangi bir sorunla karşılaştığınızda bize ulaşabilirsiniz.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
require_once 'views/components/footer.php';
?> 