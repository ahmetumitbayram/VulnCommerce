<?php
require_once 'models/Product.php';

class ProductController {
    private $productModel;
    
    public function __construct() {
        $this->productModel = new Product();
    }
    
    public function getAllProducts($limit = null, $offset = 0) {
        return $this->productModel->getAll($limit, $offset);
    }
    
    public function getProductById($id) {
        return $this->productModel->getById($id);
    }
    
    public function searchProducts() {
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            $keyword = sanitize($_GET['keyword']);
            return $this->productModel->search($keyword);
        }
        
        return $this->getAllProducts(10);
    }
    
    public function getProductComments($productId) {
        return $this->productModel->getComments($productId);
    }
    
    public function addProductComment() {
        if (!isLoggedIn()) {
            setFlashMessage('danger', 'Yorum yapabilmek için giriş yapmalısınız');
            return false;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = (int)$_POST['product_id'];
            $comment = sanitize($_POST['comment']);
            $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : null;
            
            // Validate input
            if (empty($productId) || empty($comment)) {
                setFlashMessage('danger', 'Ürün ID ve yorum alanları gereklidir');
                return false;
            }
            
            // Add comment
            $result = $this->productModel->addComment($productId, $_SESSION['user_id'], $comment, $rating);
            
            if ($result) {
                setFlashMessage('success', 'Yorumunuz eklendi');
                return true;
            } else {
                setFlashMessage('danger', 'Yorum eklenirken bir hata oluştu');
                return false;
            }
        }
        
        return false;
    }
    
    public function isProductInCart($productId) {
        if (!isLoggedIn()) {
            return false;
        }
        
        return $this->productModel->isInCart($productId, $_SESSION['user_id']);
    }
    
    public function isProductInFavorites($productId) {
        if (!isLoggedIn()) {
            return false;
        }
        
        return $this->productModel->isInFavorites($productId, $_SESSION['user_id']);
    }
    
    public function isProductInCompare($productId) {
        if (!isLoggedIn()) {
            return false;
        }
        
        return $this->productModel->isInCompare($productId, $_SESSION['user_id']);
    }
    
    public function getRelatedProducts($productId, $limit = 4) {
        return $this->productModel->getRelatedProducts($productId, $limit);
    }
} 