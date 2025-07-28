<?php
// logout.php
require_once __DIR__ . '/../settings/config.php';
require_once __DIR__ . '/../settings/lang.php';
require_once __DIR__ . '/../security/auth.php';

// Perform logout (destroy session but DO NOT redirect immediately)
logoutUserNoRedirect();

include __DIR__ . '/../includes/header.php';

// Helper to safely escape strings or use fallback
function safe_lang($key, $fallback) {
    global $lang;
    return htmlspecialchars($lang[$key] ?? $fallback, ENT_QUOTES, 'UTF-8');
}
?>

<section class="logout-section" style="text-align: center; padding: 40px;">
    <h2><?= safe_lang('logout_thanks', 'Thank you for visiting!') ?></h2>
    <p><?= safe_lang('logout_message', 'You have been logged out successfully.') ?></p>

    <nav>
        <a href="<?= BASE_URL ?>main/inicio.php" class="btn btn-primary" style="margin: 10px;">
            <?= safe_lang('logout_home', 'Return to Home') ?>
        </a>
        <a href="<?= BASE_URL ?>security/login.php" class="btn btn-secondary" style="margin: 10px;">
            <?= safe_lang('logout_login', 'Log In Again') ?>
        </a>
    </nav>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
