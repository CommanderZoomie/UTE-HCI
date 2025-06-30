<?php
// login.php
require_once __DIR__ . '/../settings/config.php';
require_once __DIR__ . '/../settings/db.php';
require_once __DIR__ . '/../security/auth.php';

if (isAuthenticated()) {
    redirect(BASE_URL . 'platform/dashboard.php');
}

$error_message = '';
$username = ''; // Predefine for form refill

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? ''; // Don't trim password

    if (empty($username) || empty($password)) {
        $error_message = 'Por favor, ingrese usuario y contraseña.';
    } else {
        if (loginUser($username, $password)) {
            redirect(BASE_URL . 'platform/dashboard.php');
        } else {
            $error_message = 'Usuario o contraseña incorrectos.';
        }
    }
}

include '../includes/header.php';
?>

<section class="login-section">
    <div class="login-box">
        <h2>Login Plataforma</h2>
        <?php if ($error_message): ?>
            <div class="alert alert-danger" role="alert" aria-live="polite"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Usuario</label>
                <input type="text" id="username" name="username" required value="<?= htmlspecialchars($username ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-success">Ingresar</button>
        </form>
        <p class="text-center" style="margin-top: 15px; font-size: 0.9em;">
            <a href="#" style="color: #bbb;">¿Olvidó Usuario o Contraseña?</a><br>
            <small>Contacta tu administrador *</small>
        </p>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
