<?php
require_once 'models/Cart.php';

class CartController {
    private $cartModel;
    
    public function __construct() {
        $this->cartModel = new Cart();
    }
    
    public function getCartItems() {
        if (!isLoggedIn()) {
            setFlashMessage('danger', 'Sepeti görüntülemek için giriş yapmalısınız');
            return [];
        }
        
        return $this->cartModel->getItems($_SESSION['user_id']);
    }
    
    public function addToCart() {
        if (!isLoggedIn()) {
            setFlashMessage('danger', 'Ürünü sepete eklemek için giriş yapmalısınız');
            return false;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = (int)$_POST['product_id'];
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            
            // Validate input
            if (empty($productId) || $quantity <= 0) {
                setFlashMessage('danger', 'Geçersiz ürün veya miktar');
                return false;
            }
            
            // Add to cart
            $result = $this->cartModel->addItem($_SESSION['user_id'], $productId, $quantity);
            
            if ($result) {
                setFlashMessage('success', 'Ürün sepete eklendi');
                return true;
            } else {
                setFlashMessage('danger', 'Ürün sepete eklenirken bir hata oluştu');
                return false;
            }
        }
        
        return false;
    }
    
    public function updateCartQuantity() {
        if (!isLoggedIn()) {
            setFlashMessage('danger', 'Sepeti güncellemek için giriş yapmalısınız');
            return false;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cartId = (int)$_POST['cart_id'];
            $quantity = (int)$_POST['quantity'];
            
            // Validate input
            if (empty($cartId) || $quantity <= 0) {
                setFlashMessage('danger', 'Geçersiz sepet ID veya miktar');
                return false;
            }
            
            // Update quantity
            $result = $this->cartModel->updateQuantity($cartId, $quantity);
            
            if ($result) {
                setFlashMessage('success', 'Sepet güncellendi');
                return true;
            } else {
                setFlashMessage('danger', 'Sepet güncellenirken bir hata oluştu');
                return false;
            }
        }
        
        return false;
    }
    
    public function removeFromCart() {
        if (!isLoggedIn()) {
            setFlashMessage('danger', 'Sepetten ürün çıkarmak için giriş yapmalısınız');
            return false;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cartId = (int)$_POST['cart_id'];
            
            // Validate input
            if (empty($cartId)) {
                setFlashMessage('danger', 'Geçersiz sepet ID');
                return false;
            }
            
            // Remove from cart
            $result = $this->cartModel->removeItem($cartId);
            
            if ($result) {
                setFlashMessage('success', 'Ürün sepetten çıkarıldı');
                return true;
            } else {
                setFlashMessage('danger', 'Ürün sepetten çıkarılırken bir hata oluştu');
                return false;
            }
        }
        
        return false;
    }
    
    public function removeByProductId() {
        if (!isLoggedIn()) {
            setFlashMessage('danger', 'Sepetten ürün çıkarmak için giriş yapmalısınız');
            return false;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = (int)$_POST['product_id'];
            
            // Validate input
            if (empty($productId)) {
                setFlashMessage('danger', 'Geçersiz ürün ID');
                return false;
            }
            
            // Remove from cart
            $result = $this->cartModel->removeItemByProductId($_SESSION['user_id'], $productId);
            
            if ($result) {
                setFlashMessage('success', 'Ürün sepetten çıkarıldı');
                return true;
            } else {
                setFlashMessage('danger', 'Ürün sepetten çıkarılırken bir hata oluştu');
                return false;
            }
        }
        
        return false;
    }
    
    public function clearCart() {
        if (!isLoggedIn()) {
            setFlashMessage('danger', 'Sepeti temizlemek için giriş yapmalısınız');
            return false;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->cartModel->clearCart($_SESSION['user_id']);
            
            if ($result) {
                setFlashMessage('success', 'Sepet temizlendi');
                return true;
            } else {
                setFlashMessage('danger', 'Sepet temizlenirken bir hata oluştu');
                return false;
            }
        }
        
        return false;
    }
    
    public function getCartCount() {
        if (!isLoggedIn()) {
            return 0;
        }
        
        return $this->cartModel->getItemCount($_SESSION['user_id']);
    }
    
    public function getCartTotal() {
        if (!isLoggedIn()) {
            return 0;
        }
        
        return $this->cartModel->getTotal($_SESSION['user_id']);
    }
} 