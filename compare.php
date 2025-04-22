<?php
// Include header
require_once 'views/components/header.php';

// Check if user is logged in
if (!isLoggedIn()) {
    setFlashMessage('danger', 'Karşılaştırma listesini görüntülemek için giriş yapmalısınız');
    redirect('login.php');
}

// Initialize controller
$compareController = new CompareController();

// Get compare items
$compareItems = $compareController->getCompareItems();
?>

<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Ürün Karşılaştırma</h2>
    </div>
</div>

<?php if (count($compareItems) > 0): ?>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Karşılaştırma Listeniz (<?php echo count($compareItems); ?>)</h5>
                    <form action="compare_actions.php" method="POST" class="d-inline">
                        <input type="hidden" name="action" value="clear">
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-trash"></i> Listeyi Temizle
                        </button>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 150px">Özellikler</th>
                                    <?php foreach ($compareItems as $item): ?>
                                        <th class="text-center">
                                            <form action="compare_actions.php" method="POST" class="mb-2">
                                                <input type="hidden" name="action" value="remove">
                                                <input type="hidden" name="compare_id" value="<?php echo $item['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                            
                                            <?php
                                            // Ürün resimleri için ürün ID'sine göre renk belirleme
                                            $colors = ['4a7bec', 'e74c3c', '27ae60', 'f39c12', '9b59b6', '1abc9c', 'd35400', '34495e', '2ecc71', 'e67e22'];
                                            $colorIndex = $item['product_id'] % count($colors);
                                            $color = $colors[$colorIndex];
                                            ?>
                                            <div class="mx-auto mb-2" style="height: 100px; width: 100px; background-color: #<?php echo $color; ?>; display: flex; flex-direction: column; justify-content: center; align-items: center; color: white; text-align: center; border-radius: 5px;">
                                                <div style="font-size: 14px; font-weight: bold; margin-bottom: 5px;"><?php echo substr($item['name'], 0, 15); ?></div>
                                                <div style="font-size: 12px;"><?php echo formatPrice($item['price']); ?></div>
                                            </div>
                                            
                                            <h6>
                                                <a href="product.php?id=<?php echo $item['product_id']; ?>" class="text-decoration-none">
                                                    <?php echo $item['name']; ?>
                                                </a>
                                            </h6>
                                        </th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Fiyat</strong></td>
                                    <?php foreach ($compareItems as $item): ?>
                                        <td class="text-center text-primary"><?php echo formatPrice($item['price']); ?></td>
                                    <?php endforeach; ?>
                                </tr>
                                <tr>
                                    <td><strong>Kategori</strong></td>
                                    <?php foreach ($compareItems as $item): ?>
                                        <td class="text-center"><?php echo $item['category']; ?></td>
                                    <?php endforeach; ?>
                                </tr>
                                <tr>
                                    <td><strong>Stok Durumu</strong></td>
                                    <?php foreach ($compareItems as $item): ?>
                                        <td class="text-center">
                                            <?php if ($item['stock'] > 0): ?>
                                                <span class="text-success">
                                                    <i class="fas fa-check-circle"></i> Stokta var
                                                </span>
                                            <?php else: ?>
                                                <span class="text-danger">
                                                    <i class="fas fa-times-circle"></i> Stokta yok
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                                <tr>
                                    <td><strong>Açıklama</strong></td>
                                    <?php foreach ($compareItems as $item): ?>
                                        <td><?php echo $item['description']; ?></td>
                                    <?php endforeach; ?>
                                </tr>
                                <tr>
                                    <td><strong>İşlemler</strong></td>
                                    <?php foreach ($compareItems as $item): ?>
                                        <td class="text-center">
                                            <div class="d-grid gap-2">
                                                <form action="cart_actions.php" method="POST">
                                                    <input type="hidden" name="action" value="add">
                                                    <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                                    <input type="hidden" name="redirect" value="compare.php">
                                                    <button type="submit" class="btn btn-sm btn-primary w-100">
                                                        <i class="fas fa-cart-plus"></i> Sepete Ekle
                                                    </button>
                                                </form>
                                                
                                                <form action="favorite_actions.php" method="POST">
                                                    <input type="hidden" name="action" value="add">
                                                    <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                                    <input type="hidden" name="redirect" value="compare.php">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                                        <i class="far fa-heart"></i> Favorilere Ekle
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            </tbody>
                        </table>
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
                    <i class="fas fa-exchange-alt fa-4x text-muted mb-3"></i>
                    <h3>Karşılaştırma Listeniz Boş</h3>
                    <p class="mb-4">Karşılaştırma listenizde henüz ürün bulunmuyor.</p>
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