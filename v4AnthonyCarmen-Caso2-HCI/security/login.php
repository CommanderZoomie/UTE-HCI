<?php
// login.php
require_once __DIR__ . '/../settings/config.php';
require_once __DIR__ . '/../settings/db.php';
require_once __DIR__ . '/../settings/lang.php';
require_once __DIR__ . '/../security/auth.php';

if (isAuthenticated()) {
    redirect(BASE_URL . 'platform/dashboard.php');
}

$error_message = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error_message = $lang['login_error_empty'] ?? 'Please enter both username and password.';
    } else {
        if (loginUser($username, $password)) {
            redirect(BASE_URL . 'platform/dashboard.php');
        } else {
            $error_message = $lang['login_error_invalid'] ?? 'Invalid username or password.';
        }
    }
}

include '../includes/header.php';
?>

<section class="login-section">
    <div class="login-box">
        <h2><?= htmlspecialchars($lang['login_title'] ?? 'Login') ?></h2>

        <?php if ($error_message): ?>
            <div class="alert alert-danger" role="alert" aria-live="polite"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST" novalidate>
            <div class="form-group">
                <label for="username"><?= htmlspecialchars($lang['login_user'] ?? 'Username') ?></label>
                <input type="text" id="username" name="username" required autocomplete="username" value="<?= htmlspecialchars($username) ?>">
            </div>
            <div class="form-group">
                <label for="password"><?= htmlspecialchars($lang['login_pass'] ?? 'Password') ?></label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn btn-success"><?= htmlspecialchars($lang['login_button'] ?? 'Log In') ?></button>
        </form>

        <p class="text-center" style="margin-top: 15px; font-size: 0.9em;">
            <a href="#" style="color: #bbb;"><?= htmlspecialchars($lang['login_forgot'] ?? 'Forgot your password?') ?></a><br>
            <small><?= htmlspecialchars($lang['login_contact_admin'] ?? 'Please contact the administrator for help.') ?></small>
        </p>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
