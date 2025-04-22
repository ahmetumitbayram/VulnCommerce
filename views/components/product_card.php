<?php
/**
 * Product Card Component
 * 
 * @param array $product Product data array
 * @param bool $showActionButtons Whether to show action buttons
 */

// Initialize controllers if not already initialized
if (!isset($productController)) {
    $productController = new ProductController();
}

if (isLoggedIn()) {
    $isInCart = $productController->isProductInCart($product['id']);
    $isInFavorites = $productController->isProductInFavorites($product['id']);
    $isInCompare = $productController->isProductInCompare($product['id']);
} else {
    $isInCart = false;
    $isInFavorites = false;
    $isInCompare = false;
}

$showActionButtons = isset($showActionButtons) ? $showActionButtons : true;

// Ürün resimleri için ürün ID'sine göre renk belirleme
$colors = ['4a7bec', 'e74c3c', '27ae60', 'f39c12', '9b59b6', '1abc9c', 'd35400', '34495e', '2ecc71', 'e67e22'];
$colorIndex = $product['id'] % count($colors);
$color = $colors[$colorIndex];

// Ürün adını kısa tutmak için
$productDisplayName = htmlspecialchars(substr($product['name'], 0, 15));
if (strlen($product['name']) > 15) {
    $productDisplayName .= '...';
}
?>

<div class="card product-card h-100">
    <div class="product-image-placeholder" style="height: 200px; background-color: #<?php echo $color; ?>; display: flex; flex-direction: column; justify-content: center; align-items: center; color: white; padding: 15px; text-align: center;">
        <div style="font-size: 18px; font-weight: bold; margin-bottom: 10px;"><?php echo $productDisplayName; ?></div>
        <div style="font-size: 16px;"><?php echo formatPrice($product['price']); ?></div>
    </div>
    <div class="card-body d-flex flex-column">
        <h5 class="card-title"><?php echo $product['name']; ?></h5>
        <p class="card-text text-truncate"><?php echo $product['description']; ?></p>
        <p class="card-text fw-bold text-primary mt-auto"><?php echo formatPrice($product['price']); ?></p>
        
        <?php if ($showActionButtons): ?>
        <div class="btn-group mt-2" role="group">
            <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-primary">Detaylar</a>
            
            <?php if (!$isInCart): ?>
            <form action="cart_actions.php" method="POST" class="d-inline">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <button type="submit" class="btn btn-primary" data-bs-toggle="tooltip" title="Sepete Ekle">
                    <i class="fas fa-cart-plus"></i>
                </button>
            </form>
            <?php else: ?>
            <form action="cart_actions.php" method="POST" class="d-inline">
                <input type="hidden" name="action" value="remove">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <button type="submit" class="btn btn-danger" data-bs-toggle="tooltip" title="Sepetten Çıkar">
                    <i class="fas fa-cart-arrow-down"></i>
                </button>
            </form>
            <?php endif; ?>
            
            <?php if (!$isInFavorites): ?>
            <form action="favorite_actions.php" method="POST" class="d-inline">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <button type="submit" class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Favorilere Ekle">
                    <i class="far fa-heart"></i>
                </button>
            </form>
            <?php else: ?>
            <form action="favorite_actions.php" method="POST" class="d-inline">
                <input type="hidden" name="action" value="remove">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <button type="submit" class="btn btn-danger" data-bs-toggle="tooltip" title="Favorilerden Çıkar">
                    <i class="fas fa-heart"></i>
                </button>
            </form>
            <?php endif; ?>
            
            <?php if (!$isInCompare): ?>
            <form action="compare_actions.php" method="POST" class="d-inline">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <button type="submit" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Karşılaştır">
                    <i class="fas fa-exchange-alt"></i>
                </button>
            </form>
            <?php else: ?>
            <form action="compare_actions.php" method="POST" class="d-inline">
                <input type="hidden" name="action" value="remove">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <button type="submit" class="btn btn-secondary" data-bs-toggle="tooltip" title="Karşılaştırmadan Çıkar">
                    <i class="fas fa-times"></i>
                </button>
            </form>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div> 