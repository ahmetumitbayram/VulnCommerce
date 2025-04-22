<?php
class Compare {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getItems($userId) {
        $sql = "SELECT c.*, p.name, p.price, p.image, p.description, p.category, p.stock 
                FROM compare c 
                JOIN products p ON c.product_id = p.id 
                WHERE c.user_id = :user_id";
        
        return $this->db->select($sql, ['user_id' => $userId]);
    }

    public function addItem($userId, $productId) {
        // Check if product already in compare list
        $item = $this->db->selectOne(
            "SELECT * FROM compare WHERE user_id = :user_id AND product_id = :product_id",
            ['user_id' => $userId, 'product_id' => $productId]
        );
        
        if ($item) {
            // Already in compare list
            return $item['id'];
        } else {
            // Add to compare list
            return $this->db->insert('compare', [
                'user_id' => $userId,
                'product_id' => $productId
            ]);
        }
    }

    public function removeItem($compareId) {
        $this->db->delete('compare', 'id = :id', ['id' => $compareId]);
        return true;
    }

    public function removeItemByProductId($userId, $productId) {
        $this->db->delete('compare', 
            'user_id = :user_id AND product_id = :product_id', 
            ['user_id' => $userId, 'product_id' => $productId]
        );
        return true;
    }

    public function clearAll($userId) {
        $this->db->delete('compare', 'user_id = :user_id', ['user_id' => $userId]);
        return true;
    }

    public function getItemCount($userId) {
        $result = $this->db->selectOne(
            "SELECT COUNT(*) as count FROM compare WHERE user_id = :user_id",
            ['user_id' => $userId]
        );
        
        return $result ? $result['count'] : 0;
    }
} 