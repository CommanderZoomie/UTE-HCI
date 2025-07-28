<?php
// header.php
require_once __DIR__ . '/../settings/config.php';
require_once __DIR__ . '/../security/auth.php'; // session_start() happens here
require_once __DIR__ . '/../settings/lang.php';


// theme
$current_theme = $_SESSION['theme'] ?? 'dark'; // Default to 'dark' if session theme not set
$theme_class = ($current_theme === 'light') ? 'light-mode' : '';


// general logic
$is_platform = strpos($_SERVER['REQUEST_URI'], '/platform/') !== false || isAuthenticated();

?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($_SESSION['lang'] ?? 'es-la') ?>">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars(APP_NAME) ?> - <?= htmlspecialchars($lang['gestion_eventos'] ?? 'Gestión de Eventos') ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css" />
</head>

<body class="<?= htmlspecialchars($theme_class) ?>">
<header>
    <div class="container header-content">
        <div class="logo">
            <img src="<?= BASE_URL ?>images/edueventos_logo_color.png" alt="<?= htmlspecialchars(APP_NAME) ?> Logo" loading="lazy" />
            <h1><?= htmlspecialchars(APP_NAME) ?></h1>
            <img src="<?= BASE_URL ?>images/ute_icon.png" alt="<?= htmlspecialchars(APP_NAME) ?> Logo" loading="lazy" />
        </div>
        <nav>
            <?php if ($is_platform): ?>
                <a href="<?= BASE_URL ?>platform/dashboard.php"><?= htmlspecialchars($lang['inicio'] ?? 'Inicio') ?></a>
                <a href="<?= BASE_URL ?>platform/events.php"><?= htmlspecialchars($lang['eventos'] ?? 'Eventos') ?></a>
                <a href="<?= BASE_URL ?>platform/locations.php"><?= htmlspecialchars($lang['ubicaciones'] ?? 'Ubicaciones') ?></a>
                <a href="<?= BASE_URL ?>platform/contacts.php"><?= htmlspecialchars($lang['contactos'] ?? 'Contactos') ?></a>
                <a href="<?= BASE_URL ?>security/logout.php"><?= htmlspecialchars($lang['logout'] ?? 'Cerrar sesión') ?></a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>main/inicio.php"><?= htmlspecialchars($lang['inicio'] ?? 'Inicio') ?></a>
                <a href="<?= BASE_URL ?>main/ver_eventos.php"><?= htmlspecialchars($lang['ver_eventos'] ?? 'Ver Eventos') ?></a>
                <a href="<?= BASE_URL ?>main/contactanos.php"><?= htmlspecialchars($lang['contactanos'] ?? 'Contáctanos') ?></a>
                <a href="<?= BASE_URL ?>security/login.php"><?= htmlspecialchars($lang['login'] ?? 'Ingresar') ?></a>
            <?php endif; ?>

            <button
                id="toggle-theme"
                class="btn btn-secondary"
                aria-label="Toggle light/dark mode"
                aria-pressed="<?= $theme_class === 'light-mode' ? 'true' : 'false' ?>"
                style="margin-left: 20px;">
                <?= $theme_class === 'light-mode' ? '☀️' : '🌙' ?>
            </button>

            <form method="get" action="<?= htmlspecialchars(parse_url($_SERVER['PHP_SELF'], PHP_URL_PATH)) ?>" style="display:inline;">
                <select name="lang" onchange="this.form.submit()" aria-label="Select language" style="margin-left: 10px;">
                    <option value="es-la" <?= (($_SESSION['lang'] ?? 'es-la') === 'es-la') ? 'selected' : '' ?>>🇪🇨 Español</option>
                    <option value="en-us" <?= (($_SESSION['lang'] ?? 'es-la') === 'en-us') ? 'selected' : '' ?>>🇺🇸 English</option>
                </select>
            </form>
        </nav>
    </div>
</header>

<main class="container">