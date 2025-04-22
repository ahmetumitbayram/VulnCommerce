<?php
require_once 'models/User.php';
require_once 'classes/UserSession.php';

class UserController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = sanitize($_POST['username'] ?? '');
            $email = sanitize($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $terms = isset($_POST['terms']) ? true : false;
            
            // Validate input
            if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
                setFlashMessage('danger', 'Tüm alanları doldurun');
                return false;
            }
            
            if (!$terms) {
                setFlashMessage('danger', 'Kullanım şartlarını kabul etmelisiniz');
                return false;
            }
            
            if ($password !== $confirmPassword) {
                setFlashMessage('danger', 'Şifreler eşleşmiyor');
                return false;
            }
            
            // Check password strength
            if (strlen($password) < 6) {
                setFlashMessage('danger', 'Şifre en az 6 karakter olmalıdır');
                return false;
            }
            
            // Register user
            $result = $this->userModel->register($username, $email, $password);
            
            if ($result['success']) {
                setFlashMessage('success', 'Kayıt başarılı. Giriş yapabilirsiniz.');
                redirect('login.php');
                return true;
            } else {
                setFlashMessage('danger', $result['message']);
                return false;
            }
        }
        
        return false;
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = sanitize($_POST['username']);
            $password = $_POST['password'];
            
            // Validate input
            if (empty($username) || empty($password)) {
                setFlashMessage('danger', 'Tüm alanları doldurun');
                return false;
            }
            
            // Login user
            $result = $this->userModel->login($username, $password);
            
            if ($result['success']) {
                // Initialize user session with preferences
                $userSession = new UserSession();
                $userSession->createSession($_SESSION['user_id']);
                
                setFlashMessage('success', 'Giriş başarılı');
                redirect('index.php');
                return true;
            } else {
                setFlashMessage('danger', $result['message']);
                return false;
            }
        }
        
        return false;
    }
    
    public function logout() {
        // Yeni UserSession sınıfını kullanarak oturumu sonlandır
        $userSession = new UserSession();
        $userSession->destroySession();
        
        setFlashMessage('success', 'Çıkış başarılı');
        redirect('login.php');
        return true;
    }
    
    public function updateProfile() {
        if (!isLoggedIn()) {
            setFlashMessage('danger', 'Önce giriş yapmalısınız');
            redirect('login.php');
            return false;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = sanitize($_POST['email']);
            
            // Validate input
            if (empty($email)) {
                setFlashMessage('danger', 'Email boş olamaz');
                return false;
            }
            
            // Update profile
            $this->userModel->updateProfile($_SESSION['user_id'], [
                'email' => $email
            ]);
            
            setFlashMessage('success', 'Profil güncellendi');
            return true;
        }
        
        return false;
    }
    
    public function updatePassword() {
        if (!isLoggedIn()) {
            setFlashMessage('danger', 'Önce giriş yapmalısınız');
            redirect('login.php');
            return false;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current_password'];
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];
            
            // Validate input
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                setFlashMessage('danger', 'Tüm alanları doldurun');
                return false;
            }
            
            if ($newPassword !== $confirmPassword) {
                setFlashMessage('danger', 'Şifreler eşleşmiyor');
                return false;
            }
            
            // Update password
            $result = $this->userModel->updatePassword($_SESSION['user_id'], $currentPassword, $newPassword);
            
            if ($result['success']) {
                setFlashMessage('success', $result['message']);
                return true;
            } else {
                setFlashMessage('danger', $result['message']);
                return false;
            }
        }
        
        return false;
    }
    
    public function updateTheme() {
        if (!isLoggedIn()) {
            setFlashMessage('danger', 'Önce giriş yapmalısınız');
            redirect('login.php');
            return false;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $theme = sanitize($_POST['theme']);
            
            // Update theme in database
            $this->userModel->updateTheme($_SESSION['user_id'], $theme);
            
            // Update user preferences in session
            $userSession = new UserSession();
            $userSession->updatePreferences($theme, null, null);
            
            setFlashMessage('success', 'Tema güncellendi');
            return true;
        }
        
        return false;
    }
    
    public function uploadProfileImage() {
        if (!isLoggedIn()) {
            setFlashMessage('danger', 'Önce giriş yapmalısınız');
            redirect('login.php');
            return false;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
            $image = $_FILES['profile_image'];
            
            // Validate image
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($image['type'], $allowedTypes)) {
                setFlashMessage('danger', 'Sadece JPEG, PNG ve GIF dosyaları yükleyebilirsiniz');
                return false;
            }
            
            if ($image['size'] > 2 * 1024 * 1024) { // 2MB
                setFlashMessage('danger', 'Dosya boyutu 2MB\'dan küçük olmalıdır');
                return false;
            }
            
            // Upload image
            $result = $this->userModel->uploadProfileImage($_SESSION['user_id'], $image);
            
            if ($result['success']) {
                setFlashMessage('success', 'Profil fotoğrafı güncellendi');
                return true;
            } else {
                setFlashMessage('danger', $result['message']);
                return false;
            }
        }
        
        return false;
    }
    
    public function updateAdvancedPreferences() {
        if (!isLoggedIn()) {
            return [
                'success' => false,
                'message' => 'Önce giriş yapmalısınız'
            ];
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['preferences'])) {
            $preferencesData = trim($_POST['preferences']);
            
            // Temel doğrulama kontrolleri
            if (empty($preferencesData)) {
                return [
                    'success' => false,
                    'message' => 'Ayarlar boş olamaz.'
                ];
            }
            
            try {
                // Yeni güvenli UserSession sınıfını kullan
                $userSession = new UserSession();
                
                // Güvenli updateAdvancedPreferences metodunu kullan
                $result = $userSession->updateAdvancedPreferences($preferencesData);
                
                if ($result['success']) {
                    setFlashMessage('success', 'Gelişmiş ayarlar güncellendi');
                } else {
                    setFlashMessage('danger', 'Ayarlar güncellenirken hata oluştu: ' . $result['message']);
                }
                
                return $result;
            } catch (Exception $e) {
                error_log('Advanced preferences update error: ' . $e->getMessage());
                return [
                    'success' => false,
                    'message' => 'Ayarlar güncellenirken hata oluştu'
                ];
            }
        }
        
        return [
            'success' => false,
            'message' => 'Geçersiz istek'
        ];
    }
} 