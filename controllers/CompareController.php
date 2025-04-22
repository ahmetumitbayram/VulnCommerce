<?php
require_once 'models/Compare.php';

class CompareController {
    private $compareModel;
    
    public function __construct() {
        $this->compareModel = new Compare();
    }
    
    public function getCompareItems() {
        if (!isLoggedIn()) {
            setFlashMessage('danger', 'Karşılaştırma listesini görüntülemek için giriş yapmalısınız');
            return [];
        }
        
        return $this->compareModel->getItems($_SESSION['user_id']);
    }
    
    public function addToCompare() {
        if (!isLoggedIn()) {
            setFlashMessage('danger', 'Karşılaştırma listesine eklemek için giriş yapmalısınız');
            return false;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = (int)$_POST['product_id'];
            
            // Validate input
            if (empty($productId)) {
                setFlashMessage('danger', 'Geçersiz ürün ID');
                return false;
            }
            
            // Check if compare list already has too many items
            $count = $this->compareModel->getItemCount($_SESSION['user_id']);
            if ($count >= 4) {
                setFlashMessage('danger', 'Karşılaştırma listesine en fazla 4 ürün ekleyebilirsiniz');
                return false;
            }
            
            // Add to compare
            $result = $this->compareModel->addItem($_SESSION['user_id'], $productId);
            
            if ($result) {
                setFlashMessage('success', 'Ürün karşılaştırma listesine eklendi');
                return true;
            } else {
                setFlashMessage('danger', 'Ürün karşılaştırma listesine eklenirken bir hata oluştu');
                return false;
            }
        }
        
        return false;
    }
    
    public function removeFromCompare() {
        if (!isLoggedIn()) {
            setFlashMessage('danger', 'Karşılaştırma listesinden çıkarmak için giriş yapmalısınız');
            return false;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $compareId = (int)$_POST['compare_id'];
            
            // Validate input
            if (empty($compareId)) {
                setFlashMessage('danger', 'Geçersiz karşılaştırma ID');
                return false;
            }
            
            // Remove from compare
            $result = $this->compareModel->removeItem($compareId);
            
            if ($result) {
                setFlashMessage('success', 'Ürün karşılaştırma listesinden çıkarıldı');
                return true;
            } else {
                setFlashMessage('danger', 'Ürün karşılaştırma listesinden çıkarılırken bir hata oluştu');
                return false;
            }
        }
        
        return false;
    }
    
    public function removeByProductId() {
        if (!isLoggedIn()) {
            setFlashMessage('danger', 'Karşılaştırma listesinden çıkarmak için giriş yapmalısınız');
            return false;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = (int)$_POST['product_id'];
            
            // Validate input
            if (empty($productId)) {
                setFlashMessage('danger', 'Geçersiz ürün ID');
                return false;
            }
            
            // Remove from compare
            $result = $this->compareModel->removeItemByProductId($_SESSION['user_id'], $productId);
            
            if ($result) {
                setFlashMessage('success', 'Ürün karşılaştırma listesinden çıkarıldı');
                return true;
            } else {
                setFlashMessage('danger', 'Ürün karşılaştırma listesinden çıkarılırken bir hata oluştu');
                return false;
            }
        }
        
        return false;
    }
    
    public function clearCompare() {
        if (!isLoggedIn()) {
            setFlashMessage('danger', 'Karşılaştırma listesini temizlemek için giriş yapmalısınız');
            return false;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->compareModel->clearAll($_SESSION['user_id']);
            
            if ($result) {
                setFlashMessage('success', 'Karşılaştırma listesi temizlendi');
                return true;
            } else {
                setFlashMessage('danger', 'Karşılaştırma listesi temizlenirken bir hata oluştu');
                return false;
            }
        }
        
        return false;
    }
    
    public function getCompareCount() {
        if (!isLoggedIn()) {
            return 0;
        }
        
        return $this->compareModel->getItemCount($_SESSION['user_id']);
    }
} 