<?php
require_once 'models/Favorites.php';

class FavoritesController {
    private $favoritesModel;
    
    public function __construct() {
        $this->favoritesModel = new Favorites();
    }
    
    public function getFavoriteItems() {
        if (!isLoggedIn()) {
            setFlashMessage('danger', 'Favorileri görüntülemek için giriş yapmalısınız');
            return [];
        }
        
        return $this->favoritesModel->getItems($_SESSION['user_id']);
    }
    
    public function addToFavorites() {
        if (!isLoggedIn()) {
            setFlashMessage('danger', 'Favorilere eklemek için giriş yapmalısınız');
            return false;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = (int)$_POST['product_id'];
            
            // Validate input
            if (empty($productId)) {
                setFlashMessage('danger', 'Geçersiz ürün ID');
                return false;
            }
            
            // Add to favorites
            $result = $this->favoritesModel->addItem($_SESSION['user_id'], $productId);
            
            if ($result) {
                setFlashMessage('success', 'Ürün favorilere eklendi');
                return true;
            } else {
                setFlashMessage('danger', 'Ürün favorilere eklenirken bir hata oluştu');
                return false;
            }
        }
        
        return false;
    }
    
    public function removeFromFavorites() {
        if (!isLoggedIn()) {
            setFlashMessage('danger', 'Favorilerden çıkarmak için giriş yapmalısınız');
            return false;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $favoriteId = (int)$_POST['favorite_id'];
            
            // Validate input
            if (empty($favoriteId)) {
                setFlashMessage('danger', 'Geçersiz favori ID');
                return false;
            }
            
            // Remove from favorites
            $result = $this->favoritesModel->removeItem($favoriteId);
            
            if ($result) {
                setFlashMessage('success', 'Ürün favorilerden çıkarıldı');
                return true;
            } else {
                setFlashMessage('danger', 'Ürün favorilerden çıkarılırken bir hata oluştu');
                return false;
            }
        }
        
        return false;
    }
    
    public function removeByProductId() {
        if (!isLoggedIn()) {
            setFlashMessage('danger', 'Favorilerden çıkarmak için giriş yapmalısınız');
            return false;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = (int)$_POST['product_id'];
            
            // Validate input
            if (empty($productId)) {
                setFlashMessage('danger', 'Geçersiz ürün ID');
                return false;
            }
            
            // Remove from favorites
            $result = $this->favoritesModel->removeItemByProductId($_SESSION['user_id'], $productId);
            
            if ($result) {
                setFlashMessage('success', 'Ürün favorilerden çıkarıldı');
                return true;
            } else {
                setFlashMessage('danger', 'Ürün favorilerden çıkarılırken bir hata oluştu');
                return false;
            }
        }
        
        return false;
    }
    
    public function clearFavorites() {
        if (!isLoggedIn()) {
            setFlashMessage('danger', 'Favorileri temizlemek için giriş yapmalısınız');
            return false;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->favoritesModel->clearAll($_SESSION['user_id']);
            
            if ($result) {
                setFlashMessage('success', 'Favoriler temizlendi');
                return true;
            } else {
                setFlashMessage('danger', 'Favoriler temizlenirken bir hata oluştu');
                return false;
            }
        }
        
        return false;
    }
    
    public function getFavoritesCount() {
        if (!isLoggedIn()) {
            return 0;
        }
        
        return $this->favoritesModel->getItemCount($_SESSION['user_id']);
    }
} 