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

// Handle theme update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['theme'])) {
    $userController->updateTheme();
}

// Current theme
$currentTheme = $user['theme'];
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Ayarlar</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="settings.php" id="themeForm">
                    <h5 class="mb-4" id="theme">Tema Ayarları</h5>
                    
                    <div class="mb-3">
                        <label class="form-label">Tema Seçin</label>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card theme-card <?php echo $currentTheme === 'default' ? 'border-primary' : ''; ?>" data-theme="default">
                                    <div class="card-body text-center">
                                        <div class="form-check">
                                            <input class="form-check-input theme-radio" type="radio" name="theme" id="theme_default" value="default" <?php echo $currentTheme === 'default' ? 'checked' : ''; ?>>
                                            <label class="form-check-label w-100" for="theme_default">
                                                <i class="fas fa-sun fa-3x mb-3 text-warning"></i>
                                                <h5>Varsayılan Tema</h5>
                                                <p class="text-muted">Açık arka plan, standart renkler</p>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card theme-card <?php echo $currentTheme === 'dark' ? 'border-primary' : ''; ?>" data-theme="dark">
                                    <div class="card-body text-center">
                                        <div class="form-check">
                                            <input class="form-check-input theme-radio" type="radio" name="theme" id="theme_dark" value="dark" <?php echo $currentTheme === 'dark' ? 'checked' : ''; ?>>
                                            <label class="form-check-label w-100" for="theme_dark">
                                                <i class="fas fa-moon fa-3x mb-3 text-secondary"></i>
                                                <h5>Karanlık Tema</h5>
                                                <p class="text-muted">Koyu arka plan, göz dostu renkler</p>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary d-none" id="themeSubmitBtn">Ayarları Kaydet</button>
                    <p class="text-muted"><small>Tema otomatik olarak kaydediliyor</small></p>
                </form>
            </div>
        </div>
        
        <div class="card shadow mt-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Diğer Ayarlar</h4>
            </div>
            <div class="card-body">
                <p>Diğer ayarlar yakında eklenecek...</p>
                <a href="profile.php" class="btn btn-primary">Hesap Ayarlarına Dön</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get theme cards and form elements
    const themeCards = document.querySelectorAll('.theme-card');
    const themeRadios = document.querySelectorAll('.theme-radio');
    const themeForm = document.getElementById('themeForm');
    
    // Add click event to theme cards
    themeCards.forEach(card => {
        card.addEventListener('click', function() {
            const themeValue = this.dataset.theme;
            const radioInput = document.querySelector(`input[value="${themeValue}"]`);
            
            // Check the radio input
            radioInput.checked = true;
            
            // Update active card styling
            themeCards.forEach(c => c.classList.remove('border-primary'));
            this.classList.add('border-primary');
            
            // Submit the form
            themeForm.submit();
        });
    });
});
</script>

<?php
// Include footer
require_once 'views/components/footer.php';
?> 