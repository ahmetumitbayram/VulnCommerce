<?php
// Include header
require_once 'views/components/header.php';

// Check if already logged in
if (isLoggedIn()) {
    redirect('index.php');
}

// Initialize controller
$userController = new UserController();

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController->register();
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Kayıt Ol</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="register.php" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="username" class="form-label">Kullanıcı Adı</label>
                        <input type="text" class="form-control" id="username" name="username" required minlength="3">
                        <div class="invalid-feedback">
                            Kullanıcı adı en az 3 karakter olmalıdır.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-posta Adresi</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback">
                            Geçerli bir e-posta adresi giriniz.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Şifre</label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="6">
                        <div class="invalid-feedback">
                            Şifre en az 6 karakter olmalıdır.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Şifre Tekrar</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        <div class="invalid-feedback">
                            Şifreler eşleşmiyor.
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">
                            <a href="#">Kullanım Şartları</a>'nı ve <a href="#">Gizlilik Politikası</a>'nı kabul ediyorum
                        </label>
                        <div class="invalid-feedback">
                            Devam etmek için şartları kabul etmelisiniz.
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Kayıt Ol</button>
                </form>
            </div>
            <div class="card-footer text-center">
                <p class="mb-0">Zaten hesabınız var mı? <a href="login.php">Giriş Yap</a></p>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation script
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.needs-validation');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');

    // Check if passwords match
    const validatePassword = function() {
        if (password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Şifreler eşleşmiyor');
        } else {
            confirmPassword.setCustomValidity('');
        }
    };

    password.addEventListener('change', validatePassword);
    confirmPassword.addEventListener('keyup', validatePassword);

    // Handle form validation
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});
</script>

<?php
// Include footer
require_once 'views/components/footer.php';
?> 