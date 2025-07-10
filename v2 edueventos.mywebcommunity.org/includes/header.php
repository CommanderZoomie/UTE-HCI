<?php
// includes/header.php
require_once __DIR__ . '/../settings/config.php';
require_once __DIR__ . '/../security/auth.php';

// Determine if we are on the platform (logged in) or public pages
$is_platform = strpos($_SERVER['REQUEST_URI'], '/platform/') !== false || isAuthenticated();

// Read theme cookie for body class
// The JavaScript will manage the initial state based on localStorage/system preference.
// This PHP logic can be simplified or removed, as JS is now responsible for setting the class initially.
// For now, keep it as is, but be aware it might be redundant if JS fully handles.
$theme_class = (isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'light') ? 'light-mode' : '';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= APP_NAME ?> - Gestión de Eventos</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css" />

    </head>

<body class="<?= $theme_class ?>">
    <header>
        <div class="container header-content">
            <div class="logo">
                <img src="<?= BASE_URL ?>images/edueventos_logo_color.png" alt="<?= APP_NAME ?> Logo" />
                <h1><?= APP_NAME ?></h1>
                <img src="<?= BASE_URL ?>images/ute_icon.png" alt="<?= APP_NAME ?> Logo" />
            </div>
            <nav>
                <?php if ($is_platform): ?>
                    <a href="<?= BASE_URL ?>platform/dashboard.php">Inicio</a>
                    <a href="<?= BASE_URL ?>platform/events.php">Eventos</a>
                    <a href="<?= BASE_URL ?>platform/locations.php">Ubicaciones</a>
                    <a href="<?= BASE_URL ?>platform/contacts.php">Contactos</a>
                    <a href="<?= BASE_URL ?>security/logout.php">Logout</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>main/inicio.php">Inicio</a>
                    <a href="<?= BASE_URL ?>main/ver_eventos.php">Ver Eventos</a>
                    <a href="<?= BASE_URL ?>main/contactanos.php">Contáctanos</a>
                    <a href="<?= BASE_URL ?>security/login.php">Login</a>
                <?php endif; ?>

                <button id="toggle-theme" class="btn btn-secondary" aria-label="Toggle light/dark mode" style="margin-left: 20px;">
                    <?= $theme_class === 'light-mode' ? '☀️' : '🌙' ?>
                </button>
            </nav>
        </div>
    </header>

    <main class="container">