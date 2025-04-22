<?php
// Include header
require_once 'views/components/header.php';

// Initialize controller
$productController = new ProductController();

// Get search results
$products = $productController->searchProducts();

// Get search keyword
$keyword = isset($_GET['keyword']) ? sanitize($_GET['keyword']) : '';
?>

<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Arama Sonuçları: "<?php echo $keyword; ?>"</h2>
    </div>
</div>

<?php if (count($products) > 0): ?>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><?php echo count($products); ?> ürün bulundu</h5>
                </div>
                <div class="card-body">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                        <?php foreach ($products as $product): ?>
                            <div class="col">
                                <?php include 'views/components/product_card.php'; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-search fa-4x text-muted mb-3"></i>
                    <h3>Sonuç Bulunamadı</h3>
                    <p class="mb-4">"<?php echo $keyword; ?>" için hiçbir ürün bulunamadı.</p>
                    <a href="index.php" class="btn btn-primary">Anasayfaya Dön</a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
// Include footer
require_once 'views/components/footer.php';
?> 