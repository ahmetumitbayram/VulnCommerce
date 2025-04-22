<?php
class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function register($username, $email, $password) {
        // Check if username or email already exists
        $existingUser = $this->db->selectOne(
            "SELECT * FROM users WHERE username = :username OR email = :email",
            ['username' => $username, 'email' => $email]
        );
        
        if ($existingUser) {
            if ($existingUser['username'] === $username) {
                return ['success' => false, 'message' => 'Bu kullanıcı adı zaten kullanılıyor'];
            }
            if ($existingUser['email'] === $email) {
                return ['success' => false, 'message' => 'Bu e-posta adresi zaten kullanılıyor'];
            }
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user
        try {
            $result = $this->db->insert('users', [
                'username' => $username,
                'email' => $email,
                'password' => $hashedPassword
            ]);
            
            if ($result) {
                return ['success' => true, 'user_id' => $result];
            } else {
                return ['success' => false, 'message' => 'Kayıt işlemi başarısız oldu'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Kayıt işlemi sırasında bir hata oluştu: ' . $e->getMessage()];
        }
    }

    public function login($username, $password) {
        // Get user by username or email
        $user = $this->db->selectOne(
            "SELECT * FROM users WHERE username = :username OR email = :username",
            ['username' => $username]
        );
        
        if (!$user) {
            return ['success' => false, 'message' => 'User not found'];
        }
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            // Set theme from user preferences
            $_SESSION['theme'] = $user['theme'];
            
            return ['success' => true, 'user' => $user];
        } else {
            return ['success' => false, 'message' => 'Invalid password'];
        }
    }

    public function logout() {
        // Destroy session
        session_unset();
        session_destroy();
        
        return true;
    }

    public function getById($id) {
        return $this->db->selectOne("SELECT * FROM users WHERE id = :id", ['id' => $id]);
    }

    public function updateProfile($userId, $data) {
        $this->db->update('users', $data, 'id = :id', ['id' => $userId]);
        return true;
    }

    public function updatePassword($userId, $oldPassword, $newPassword) {
        // Get current user
        $user = $this->getById($userId);
        
        // Verify old password
        if (!password_verify($oldPassword, $user['password'])) {
            return ['success' => false, 'message' => 'Current password is incorrect'];
        }
        
        // Hash new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Update password
        $this->db->update('users', ['password' => $hashedPassword], 'id = :id', ['id' => $userId]);
        
        return ['success' => true, 'message' => 'Password updated successfully'];
    }

    public function updateTheme($userId, $theme) {
        $this->db->update('users', ['theme' => $theme], 'id = :id', ['id' => $userId]);
        
        // Update session
        $_SESSION['theme'] = $theme;
        
        return true;
    }

    public function uploadProfileImage($userId, $image) {
        $uploadDir = PROFILE_IMAGES_DIR;
        $fileName = 'profile_' . $userId . '_' . time() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
        $targetFile = $uploadDir . $fileName;
        
        if (move_uploaded_file($image['tmp_name'], $targetFile)) {
            // Update user profile image in database
            $this->db->update('users', ['profile_image' => $fileName], 'id = :id', ['id' => $userId]);
            return ['success' => true, 'file_name' => $fileName];
        } else {
            return ['success' => false, 'message' => 'Failed to upload image'];
        }
    }
} 