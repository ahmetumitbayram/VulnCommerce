<?php
// Include header
require_once 'views/components/header.php';

// Check if user is logged in
if (!isLoggedIn()) {
    setFlashMessage('danger', 'Favorileri görüntülemek için giriş yapmalısınız');
    redirect('login.php');
}

// Initialize controller
$favoritesController = new FavoritesController();

// Get favorite items
$favoriteItems = $favoritesController->getFavoriteItems();
?>

<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Favorilerim</h2>
    </div>
</div>

<?php if (count($favoriteItems) > 0): ?>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Favori Ürünleriniz (<?php echo count($favoriteItems); ?>)</h5>
                    <form action="favorite_actions.php" method="POST" class="d-inline">
                        <input type="hidden" name="action" value="clear">
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-trash"></i> Tümünü Temizle
                        </button>
                    </form>
                </div>
                <div class="card-body">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                        <?php foreach ($favoriteItems as $item): 
                            // Ürün resimleri için ürün ID'sine göre renk belirleme
                            $colors = ['4a7bec', 'e74c3c', '27ae60', 'f39c12', '9b59b6', '1abc9c', 'd35400', '34495e', '2ecc71', 'e67e22'];
                            $colorIndex = $item['product_id'] % count($colors);
                            $color = $colors[$colorIndex];
                        ?>
                            <div class="col">
                                <div class="card h-100">
                                    <div class="product-image-placeholder" style="height: 200px; background-color: #<?php echo $color; ?>; display: flex; flex-direction: column; justify-content: center; align-items: center; color: white; padding: 15px; text-align: center;">
                                        <div style="font-size: 18px; font-weight: bold; margin-bottom: 10px;"><?php echo $item['name']; ?></div>
                                        <div style="font-size: 16px;"><?php echo formatPrice($item['price']); ?></div>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <a href="product.php?id=<?php echo $item['product_id']; ?>" class="text-decoration-none">
                                                <?php echo $item['name']; ?>
                                            </a>
                                        </h5>
                                        <p class="card-text text-truncate"><?php echo $item['description']; ?></p>
                                        
                                        <div class="d-flex gap-2">
                                            <a href="product.php?id=<?php echo $item['product_id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> Detaylar
                                            </a>
                                            
                                            <form action="cart_actions.php" method="POST">
                                                <input type="hidden" name="action" value="add">
                                                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                                <input type="hidden" name="redirect" value="favorites.php">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-cart-plus"></i> Sepete Ekle
                                                </button>
                                            </form>
                                            
                                            <form action="favorite_actions.php" method="POST">
                                                <input type="hidden" name="action" value="remove">
                                                <input type="hidden" name="favorite_id" value="<?php echo $item['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
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
                    <i class="fas fa-heart fa-4x text-muted mb-3"></i>
                    <h3>Favorileriniz Boş</h3>
                    <p class="mb-4">Favorilerinizde henüz ürün bulunmuyor.</p>
                    <a href="index.php" class="btn btn-primary">Alışverişe Başla</a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
// Include footer
require_once 'views/components/footer.php';
?> 