<?php
// logout.php
require_once __DIR__ . '/../settings/config.php';
require_once __DIR__ . '/../security/auth.php';

// Perform logout (destroy session but DO NOT redirect immediately)
logoutUserNoRedirect();

include __DIR__ . '/../includes/header.php';
?>

<section class="logout-section" style="text-align: center; padding: 40px;">
    <h2>Gracias por usar EduEventos</h2>
    <p>Has cerrado sesión correctamente.</p>

    <nav>
        <a href="<?= BASE_URL ?>main/inicio.php" class="btn btn-primary" style="margin: 10px;">Inicio</a>
        <a href="<?= BASE_URL ?>security/login.php" class="btn btn-secondary" style="margin: 10px;">Iniciar Sesión</a>
    </nav>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
