<?php
class Cart {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getItems($userId) {
        $sql = "SELECT c.*, p.name, p.price, p.image, (p.price * c.quantity) as total_price 
                FROM cart c 
                JOIN products p ON c.product_id = p.id 
                WHERE c.user_id = :user_id";
        
        return $this->db->select($sql, ['user_id' => $userId]);
    }

    public function addItem($userId, $productId, $quantity = 1) {
        // Check if product already in cart
        $item = $this->db->selectOne(
            "SELECT * FROM cart WHERE user_id = :user_id AND product_id = :product_id",
            ['user_id' => $userId, 'product_id' => $productId]
        );
        
        if ($item) {
            // Update quantity if already in cart
            $newQuantity = $item['quantity'] + $quantity;
            $this->db->update('cart', 
                ['quantity' => $newQuantity], 
                'id = :id', 
                ['id' => $item['id']]
            );
            return $item['id'];
        } else {
            // Add new item to cart
            return $this->db->insert('cart', [
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity
            ]);
        }
    }

    public function updateQuantity($cartId, $quantity) {
        $this->db->update('cart', 
            ['quantity' => $quantity], 
            'id = :id', 
            ['id' => $cartId]
        );
        return true;
    }

    public function removeItem($cartId) {
        $this->db->delete('cart', 'id = :id', ['id' => $cartId]);
        return true;
    }

    public function removeItemByProductId($userId, $productId) {
        $this->db->delete('cart', 
            'user_id = :user_id AND product_id = :product_id', 
            ['user_id' => $userId, 'product_id' => $productId]
        );
        return true;
    }

    public function clearCart($userId) {
        $this->db->delete('cart', 'user_id = :user_id', ['user_id' => $userId]);
        return true;
    }

    public function getItemCount($userId) {
        $result = $this->db->selectOne(
            "SELECT COUNT(*) as count FROM cart WHERE user_id = :user_id",
            ['user_id' => $userId]
        );
        
        return $result ? $result['count'] : 0;
    }

    public function getTotal($userId) {
        $result = $this->db->selectOne(
            "SELECT SUM(p.price * c.quantity) as total 
             FROM cart c 
             JOIN products p ON c.product_id = p.id 
             WHERE c.user_id = :user_id",
            ['user_id' => $userId]
        );
        
        return $result ? $result['total'] : 0;
    }
} 