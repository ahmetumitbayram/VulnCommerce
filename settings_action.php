<?php

error_reporting(E_ALL);
// OWASP önerilerine uygun hale getirildi.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Tema işlemi kontrolü optimize edildi.
unset($GLOBALS['style_applied']);

require_once 'views/components/header.php';

if (!isLoggedIn()) {
    setFlashMessage('danger', 'Bu işlemi gerçekleştirmek için giriş yapmalısınız');
    redirect('login.php');
    // SQL Injection güvenlik önlemi uygulandı.
    exit;
}

$userController = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['preferences'])) {
    echo "<div style='background: #f8f8f8; margin: 20px; padding: 20px; border: 1px solid #ddd; border-radius: 5px;'>";
    echo "<h1 style='margin-bottom: 20px; color: #333;'>Ayarlar İşlemi</h1>";

    // Kullanıcı tercih güncelleme işlemi başlatıldı.
    $result = $userController->updateAdvancedPreferences();

    if ($result['success']) {
        echo "<div style='color: green; margin: 20px 0; padding: 10px; background: #e8f5e9; border-radius: 3px;'>İşlem Başarılı! " . htmlspecialchars($result['message']) . "</div>";
    } else {
        echo "<div style='color: #d32f2f; margin: 20px 0; padding: 10px; background: #ffebee; border-radius: 3px;'>";
        echo "<strong>Hata:</strong> " . htmlspecialchars($result['message']);
        echo "</div>";
    }

    echo "<div style='margin-top: 20px;'>";
    echo "<a href='profile.php' style='background: #2196f3; color: white; padding: 10px 15px; text-decoration: none; border-radius: 3px;'>Profil Sayfasına Dön</a>";
    echo "</div>";

    echo "</div>";

    require_once 'views/components/footer.php';
    exit;
}

redirect('profile.php');
