<?php
// Include header
require_once 'views/components/header.php';

// Check if already logged in
if (isLoggedIn()) {
    redirect('index.php');
}

// Initialize controller
$userController = new UserController();

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController->login();
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Giriş Yap</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="login.php" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="username" class="form-label">Kullanıcı Adı / E-posta</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                        <div class="invalid-feedback">
                            Kullanıcı adı veya e-posta gereklidir.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Şifre</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="invalid-feedback">
                            Şifre gereklidir.
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Beni hatırla</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>
                </form>
            </div>
            <div class="card-footer text-center">
                <p class="mb-0">Hesabınız yok mu? <a href="register.php">Kayıt Ol</a></p>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
require_once 'views/components/footer.php';
?> 