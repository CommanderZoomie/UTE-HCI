<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../settings/config.php';
require_once __DIR__ . '/../security/auth.php';
require_once __DIR__ . '/../settings/lang.php'; // Centralized language loader
?>
    </main>

    <footer role="contentinfo" aria-label="Site footer">
        <div class="container footer-content">
            <div class="footer-logo">
                <h1><?= htmlspecialchars(APP_NAME) ?></h1>
                <div class="footer-images"> 
                    <img src="<?= BASE_URL ?>images/edueventos_logo_color.png" alt="<?= htmlspecialchars(APP_NAME) ?> Logo" loading="lazy" class="footer-icon" />
                    <img src="<?= BASE_URL ?>images/ute_icon.png" alt="UTE Logo" loading="lazy" class="footer-icon" />
                </div>
            </div>

            <p><?= htmlspecialchars($lang['autor_nombre'] ?? 'Autor Nombre') ?></p>
            <p><?= htmlspecialchars($lang['autor_email'] ?? 'correo@example.com') ?></p>
            <p><?= htmlspecialchars($lang['autor_ubicacion'] ?? 'Ubicación') ?></p>
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars(APP_NAME) ?>. <?= htmlspecialchars($lang['derechos'] ?? 'Todos los derechos reservados') ?></p>
        </div>
    </footer>

    <script src="<?= BASE_URL ?>js/script.js"></script>
</body>
</html>