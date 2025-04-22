<?php
// Include header
require_once 'views/components/header.php';

// Check if user is logged in
if (!isLoggedIn()) {
    setFlashMessage('danger', 'Bu sayfayı görüntülemek için giriş yapmalısınız');
    redirect('login.php');
}

// Initialize controllers
$userController = new UserController();

// Get current user data
$user = getCurrentUser();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $userController->updateProfile();
    } elseif (isset($_POST['update_password'])) {
        $userController->updatePassword();
    } elseif (isset($_FILES['profile_image'])) {
        $userController->uploadProfileImage();
    }
}

// Get profile image URL
$profileImageUrl = file_exists('assets/uploads/profiles/' . $user['profile_image']) ? 
    'assets/uploads/profiles/' . $user['profile_image'] : 
    'assets/images/default.jpg';
?>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Profil Resmi</h5>
            </div>
            <div class="card-body text-center">
                <img src="<?php echo $profileImageUrl; ?>" alt="Profil Resmi" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                <h5><?php echo $user['username']; ?></h5>
                <p class="text-muted"><?php echo $user['email']; ?></p>
                
                <form action="profile.php" method="POST" enctype="multipart/form-data" class="mt-3">
                    <div class="mb-3">
                        <label for="profile_image" class="form-label">Profil Resmi Yükle</label>
                        <input class="form-control" type="file" id="profile_image" name="profile_image" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">Resmi Yükle</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Kullanıcı Bilgileri</h5>
            </div>
            <div class="card-body">
                <form action="profile.php" method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="update_profile" value="1">
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Kullanıcı Adı</label>
                        <input type="text" class="form-control" id="username" value="<?php echo $user['username']; ?>" readonly>
                        <small class="text-muted">Kullanıcı adı değiştirilemez</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">E-posta Adresi</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                        <div class="invalid-feedback">
                            Geçerli bir e-posta adresi giriniz.
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Bilgileri Güncelle</button>
                </form>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Şifre Değiştir</h5>
            </div>
            <div class="card-body">
                <form action="profile.php" method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="update_password" value="1">
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Mevcut Şifre</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                        <div class="invalid-feedback">
                            Mevcut şifrenizi giriniz.
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Yeni Şifre</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                        <div class="invalid-feedback">
                            Yeni şifre giriniz.
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Yeni Şifre Tekrar</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        <div class="invalid-feedback">
                            Şifreler eşleşmiyor.
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Şifreyi Değiştir</button>
                </form>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Gelişmiş Ayarlar</h5>
            </div>
            <div class="card-body">
                <form action="settings_action.php" method="POST">
                    <div class="mb-3">
                        <label for="preferences" class="form-label">Kullanıcı Özellikleri</label>
                        <textarea class="form-control" id="preferences" name="preferences" rows="5" placeholder="Base64 formatında kullanıcı ayarları"></textarea>
                        <small class="text-muted">Gelişmiş ayarlarınızı yüklemek için buraya yapıştırın</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Ayarları Güncelle</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
require_once 'views/components/footer.php';
?> 