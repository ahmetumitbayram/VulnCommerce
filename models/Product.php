<?php
class Product {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll($limit = null, $offset = 0) {
        $sql = "SELECT * FROM products ORDER BY id DESC";
        
        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
            return $this->db->select($sql, [
                'limit' => (int)$limit,
                'offset' => (int)$offset
            ]);
        }
        
        return $this->db->select($sql);
    }

    public function getById($id) {
        return $this->db->selectOne("SELECT * FROM products WHERE id = :id", ['id' => $id]);
    }

    public function search($keyword) {
        $sql = "SELECT * FROM products WHERE name LIKE :keyword OR description LIKE :keyword OR category LIKE :keyword ORDER BY id DESC";
        return $this->db->select($sql, ['keyword' => "%$keyword%"]);
    }

    public function getComments($productId) {
        $sql = "SELECT c.*, u.username, u.profile_image 
                FROM comments c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.product_id = :product_id 
                ORDER BY c.created_at DESC";
        
        return $this->db->select($sql, ['product_id' => $productId]);
    }

    public function addComment($productId, $userId, $comment, $rating = null) {
        $data = [
            'product_id' => $productId,
            'user_id' => $userId,
            'comment' => $comment
        ];
        
        if ($rating !== null) {
            $data['rating'] = $rating;
        }
        
        return $this->db->insert('comments', $data);
    }

    public function isInCart($productId, $userId) {
        $result = $this->db->selectOne(
            "SELECT * FROM cart WHERE product_id = :product_id AND user_id = :user_id",
            ['product_id' => $productId, 'user_id' => $userId]
        );
        
        return $result ? true : false;
    }

    public function isInFavorites($productId, $userId) {
        $result = $this->db->selectOne(
            "SELECT * FROM favorites WHERE product_id = :product_id AND user_id = :user_id",
            ['product_id' => $productId, 'user_id' => $userId]
        );
        
        return $result ? true : false;
    }

    public function isInCompare($productId, $userId) {
        $result = $this->db->selectOne(
            "SELECT * FROM compare WHERE product_id = :product_id AND user_id = :user_id",
            ['product_id' => $productId, 'user_id' => $userId]
        );
        
        return $result ? true : false;
    }

    public function getRelatedProducts($productId, $limit = 4) {
        $product = $this->getById($productId);
        
        if (!$product) {
            return [];
        }
        
        $sql = "SELECT * FROM products 
                WHERE category = :category AND id != :id 
                ORDER BY RAND() 
                LIMIT :limit";
        
        return $this->db->select($sql, [
            'category' => $product['category'],
            'id' => (int)$productId,
            'limit' => (int)$limit
        ]);
    }
} 