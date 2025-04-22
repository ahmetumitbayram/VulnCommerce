<?php
// Include header
require_once 'views/components/header.php';

// Check if user is logged in
if (!isLoggedIn()) {
    setFlashMessage('danger', 'Sepeti görüntülemek için giriş yapmalısınız');
    redirect('login.php');
}

// Initialize controller
$cartController = new CartController();

// Get cart items
$cartItems = $cartController->getCartItems();

// Get cart total
$cartTotal = $cartController->getCartTotal();
?>

<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Alışveriş Sepeti</h2>
    </div>
</div>

<?php if (count($cartItems) > 0): ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Sepetinizdeki Ürünler (<?php echo count($cartItems); ?>)</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($cartItems as $item): 
                        // Ürün resimleri için ürün ID'sine göre renk belirleme
                        $colors = ['4a7bec', 'e74c3c', '27ae60', 'f39c12', '9b59b6', '1abc9c', 'd35400', '34495e', '2ecc71', 'e67e22'];
                        $colorIndex = $item['product_id'] % count($colors);
                        $color = $colors[$colorIndex];
                    ?>
                        <div class="row mb-4 border-bottom pb-4">
                            <div class="col-md-3">
                                <div class="rounded" style="height: 150px; background-color: #<?php echo $color; ?>; display: flex; flex-direction: column; justify-content: center; align-items: center; color: white; padding: 15px; text-align: center;">
                                    <div style="font-size: 16px; font-weight: bold; margin-bottom: 10px;"><?php echo substr($item['name'], 0, 20); ?></div>
                                    <div style="font-size: 14px;"><?php echo formatPrice($item['price']); ?></div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="mb-2">
                                            <a href="product.php?id=<?php echo $item['product_id']; ?>" class="text-decoration-none">
                                                <?php echo $item['name']; ?>
                                            </a>
                                        </h5>
                                        <p class="text-primary"><?php echo formatPrice($item['price']); ?></p>
                                    </div>
                                    <form action="cart_actions.php" method="POST">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i> Kaldır
                                        </button>
                                    </form>
                                </div>
                                
                                <div class="d-flex align-items-center mt-3">
                                    <form action="cart_actions.php" method="POST" class="d-flex align-items-center">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                        <label for="quantity_<?php echo $item['id']; ?>" class="me-2">Adet:</label>
                                        <input type="number" min="1" max="10" class="form-control form-control-sm me-2" style="width: 70px" id="quantity_<?php echo $item['id']; ?>" name="quantity" value="<?php echo $item['quantity']; ?>">
                                        <button type="submit" class="btn btn-sm btn-primary">Güncelle</button>
                                    </form>
                                    
                                    <div class="ms-auto">
                                        <strong>Toplam: <?php echo formatPrice($item['total_price']); ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="card-footer">
                    <form action="cart_actions.php" method="POST" class="d-inline">
                        <input type="hidden" name="action" value="clear">
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-trash"></i> Sepeti Temizle
                        </button>
                    </form>
                    <a href="index.php" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> Alışverişe Devam Et
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Sipariş Özeti</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Ürünler Toplamı
                            <span><?php echo formatPrice($cartTotal); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Kargo
                            <span>Ücretsiz</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 fw-bold">
                            Genel Toplam
                            <span><?php echo formatPrice($cartTotal); ?></span>
                        </li>
                    </ul>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-success w-100">
                        <i class="fas fa-check"></i> Siparişi Tamamla
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                    <h3>Sepetiniz Boş</h3>
                    <p class="mb-4">Sepetinizde henüz ürün bulunmuyor.</p>
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