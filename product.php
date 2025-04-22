<?php
// Include header
require_once 'views/components/header.php';

// Initialize controllers
$productController = new ProductController();

// Get product ID from URL
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get product data
$product = $productController->getProductById($productId);

// Redirect if product not found
if (!$product) {
    setFlashMessage('danger', 'Ürün bulunamadı');
    redirect('index.php');
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $productController->addProductComment();
}

// Get product comments
$comments = $productController->getProductComments($productId);

// Get related products
$relatedProducts = $productController->getRelatedProducts($productId);

// Check product status
$isInCart = $productController->isProductInCart($productId);
$isInFavorites = $productController->isProductInFavorites($productId);
$isInCompare = $productController->isProductInCompare($productId);

// Ürün resimleri için ürün ID'sine göre renk belirleme
$colors = ['4a7bec', 'e74c3c', '27ae60', 'f39c12', '9b59b6', '1abc9c', 'd35400', '34495e', '2ecc71', 'e67e22'];
$colorIndex = $productId % count($colors);
$productColor = $colors[$colorIndex];
?>

<div class="row">
    <!-- Product Details -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <!-- Product Image -->
                    <div class="col-md-5 mb-4 mb-md-0">
                        <div class="product-image-placeholder rounded" style="height: 300px; background-color: #<?php echo $productColor; ?>; display: flex; flex-direction: column; justify-content: center; align-items: center; color: white; padding: 20px; text-align: center;">
                            <div style="font-size: 24px; font-weight: bold; margin-bottom: 15px;"><?php echo $product['name']; ?></div>
                            <div style="font-size: 18px;"><?php echo formatPrice($product['price']); ?></div>
                            <div style="margin-top: 20px; font-size: 16px;">Kategori: <?php echo $product['category']; ?></div>
                        </div>
                    </div>
                    
                    <!-- Product Info -->
                    <div class="col-md-7">
                        <h2><?php echo $product['name']; ?></h2>
                        <p class="text-muted mb-3">Kategori: <?php echo $product['category']; ?></p>
                        
                        <div class="mb-3">
                            <h4 class="text-primary"><?php echo formatPrice($product['price']); ?></h4>
                            <p class="text-success">
                                <i class="fas fa-check-circle"></i> 
                                <?php echo $product['stock'] > 0 ? 'Stokta var' : 'Stokta yok'; ?>
                            </p>
                        </div>
                        
                        <div class="mb-4">
                            <h5>Ürün Açıklaması</h5>
                            <p><?php echo $product['description']; ?></p>
                        </div>
                        
                        <div class="d-flex gap-2 mb-3">
                            <!-- Add to Cart Button -->
                            <?php if (!$isInCart): ?>
                            <form action="cart_actions.php" method="POST">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <div class="d-flex gap-2">
                                    <input type="number" class="form-control" name="quantity" value="1" min="1" max="10" style="width: 70px;">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-cart-plus"></i> Sepete Ekle
                                    </button>
                                </div>
                            </form>
                            <?php else: ?>
                            <form action="cart_actions.php" method="POST">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-cart-arrow-down"></i> Sepetten Çıkar
                                </button>
                            </form>
                            <?php endif; ?>
                            
                            <!-- Add to Favorites Button -->
                            <?php if (!$isInFavorites): ?>
                            <form action="favorite_actions.php" method="POST">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="far fa-heart"></i> Favorilere Ekle
                                </button>
                            </form>
                            <?php else: ?>
                            <form action="favorite_actions.php" method="POST">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-heart"></i> Favorilerden Çıkar
                                </button>
                            </form>
                            <?php endif; ?>
                            
                            <!-- Add to Compare Button -->
                            <?php if (!$isInCompare): ?>
                            <form action="compare_actions.php" method="POST">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="fas fa-exchange-alt"></i> Karşılaştırmaya Ekle
                                </button>
                            </form>
                            <?php else: ?>
                            <form action="compare_actions.php" method="POST">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Karşılaştırmadan Çıkar
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Comments Section -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Ürün Yorumları (<?php echo count($comments); ?>)</h5>
            </div>
            <div class="card-body">
                <?php if (count($comments) > 0): ?>
                    <?php foreach($comments as $comment): 
                        $commentColorIndex = crc32($comment['username']) % count($colors);
                        $commentColor = $colors[$commentColorIndex];
                        $initials = strtoupper(substr($comment['username'], 0, 2));
                    ?>
                        <div class="d-flex mb-4">
                            <div class="rounded-circle me-3 d-flex justify-content-center align-items-center" style="width: 50px; height: 50px; background-color: #<?php echo $commentColor; ?>; color: white; font-weight: bold;">
                                <?php echo $initials; ?>
                            </div>
                            <div>
                                <div class="d-flex align-items-center mb-1">
                                    <h6 class="mb-0 me-2"><?php echo $comment['username']; ?></h6>
                                    <small class="text-muted"><?php echo date('d.m.Y H:i', strtotime($comment['created_at'])); ?></small>
                                </div>
                                <?php if ($comment['rating']): ?>
                                    <div class="mb-2">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= $comment['rating']): ?>
                                                <i class="fas fa-star text-warning"></i>
                                            <?php else: ?>
                                                <i class="far fa-star text-warning"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                <?php endif; ?>
                                <p class="mb-0"><?php echo $comment['comment']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Bu ürün için henüz yorum yapılmamış.</p>
                <?php endif; ?>
                
                <?php if (isLoggedIn()): ?>
                    <hr>
                    <h5 class="mb-3">Yorum Yap</h5>
                    <form action="product.php?id=<?php echo $productId; ?>" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                        
                        <div class="mb-3">
                            <label for="rating" class="form-label">Puan</label>
                            <select class="form-select" id="rating" name="rating">
                                <option value="">Seçiniz</option>
                                <option value="1">1 - Çok Kötü</option>
                                <option value="2">2 - Kötü</option>
                                <option value="3">3 - Orta</option>
                                <option value="4">4 - İyi</option>
                                <option value="5">5 - Çok İyi</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="comment" class="form-label">Yorumunuz</label>
                            <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Yorumu Gönder</button>
                    </form>
                <?php else: ?>
                    <hr>
                    <div class="alert alert-info">
                        Yorum yapabilmek için <a href="login.php">giriş yapmalısınız</a>.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Benzer Ürünler</h5>
            </div>
            <div class="card-body">
                <?php if (count($relatedProducts) > 0): ?>
                    <?php foreach($relatedProducts as $relatedProduct): 
                        $relatedColorIndex = $relatedProduct['id'] % count($colors);
                        $relatedColor = $colors[$relatedColorIndex];
                    ?>
                        <div class="d-flex mb-3">
                            <div class="rounded me-3" style="width: 60px; height: 60px; background-color: #<?php echo $relatedColor; ?>; display: flex; justify-content: center; align-items: center; color: white; font-weight: bold; font-size: 12px; text-align: center;">
                                <?php echo substr($relatedProduct['name'], 0, 10); ?>
                            </div>
                            <div>
                                <h6 class="mb-0"><?php echo $relatedProduct['name']; ?></h6>
                                <p class="text-primary mb-0"><?php echo formatPrice($relatedProduct['price']); ?></p>
                                <a href="product.php?id=<?php echo $relatedProduct['id']; ?>" class="btn btn-sm btn-outline-primary mt-1">Detaylar</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Benzer ürün bulunamadı.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
require_once 'views/components/footer.php';
?> 