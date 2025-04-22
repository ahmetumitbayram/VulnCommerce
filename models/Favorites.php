<?php
class Favorites {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getItems($userId) {
        $sql = "SELECT f.*, p.name, p.price, p.image, p.description 
                FROM favorites f 
                JOIN products p ON f.product_id = p.id 
                WHERE f.user_id = :user_id";
        
        return $this->db->select($sql, ['user_id' => $userId]);
    }

    public function addItem($userId, $productId) {
        // Check if product already in favorites
        $item = $this->db->selectOne(
            "SELECT * FROM favorites WHERE user_id = :user_id AND product_id = :product_id",
            ['user_id' => $userId, 'product_id' => $productId]
        );
        
        if ($item) {
            // Already in favorites
            return $item['id'];
        } else {
            // Add to favorites
            return $this->db->insert('favorites', [
                'user_id' => $userId,
                'product_id' => $productId
            ]);
        }
    }

    public function removeItem($favoriteId) {
        $this->db->delete('favorites', 'id = :id', ['id' => $favoriteId]);
        return true;
    }

    public function removeItemByProductId($userId, $productId) {
        $this->db->delete('favorites', 
            'user_id = :user_id AND product_id = :product_id', 
            ['user_id' => $userId, 'product_id' => $productId]
        );
        return true;
    }

    public function clearAll($userId) {
        $this->db->delete('favorites', 'user_id = :user_id', ['user_id' => $userId]);
        return true;
    }

    public function getItemCount($userId) {
        $result = $this->db->selectOne(
            "SELECT COUNT(*) as count FROM favorites WHERE user_id = :user_id",
            ['user_id' => $userId]
        );
        
        return $result ? $result['count'] : 0;
    }
} 