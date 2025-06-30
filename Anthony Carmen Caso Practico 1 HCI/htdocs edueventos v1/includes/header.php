<?php
// includes/header.php
require_once __DIR__ . '/../settings/config.php';
require_once __DIR__ . '/../security/auth.php';

// Determine if we are on the platform (logged in) or public pages
$is_platform = strpos($_SERVER['REQUEST_URI'], '/platform/') !== false || isAuthenticated();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= APP_NAME ?> - Gestión de Eventos</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css" rel="stylesheet" />
</head>
<body class="dark-mode">
    <header>
        <div class="container header-content">
            <div class="logo">
                <img src="<?= BASE_URL ?>images/edueventos_logo.jpg" alt="<?= APP_NAME ?> Logo" />
                <h1><?= APP_NAME ?></h1>
            </div>
            <nav>
                <?php if ($is_platform): // Platform Navigation ?>
                    <a href="<?= BASE_URL ?>platform/dashboard.php">Inicio</a>
                    <a href="<?= BASE_URL ?>platform/events.php">Eventos</a>
                    <a href="<?= BASE_URL ?>platform/locations.php">Ubicaciones</a>
                    <a href="<?= BASE_URL ?>platform/contacts.php">Contactos</a>
                    <a href="<?= BASE_URL ?>security/logout.php">Logout</a>
                <?php else: // Public Navigation ?>
                    <a href="<?= BASE_URL ?>main/inicio.php">Inicio</a>
                    <a href="<?= BASE_URL ?>main/contactanos.php">Contáctanos</a>
                    <a href="<?= BASE_URL ?>security/login.php">Login</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="container">
