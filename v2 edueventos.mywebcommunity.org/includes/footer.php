<?php
// includes/footer.php
require_once __DIR__ . '/../settings/config.php';
require_once __DIR__ . '/../security/auth.php';

?>
    </main>

    <footer role="contentinfo">
        	
            <div class="container footer-content">
                    
            <div class="footer-logo">
                <h1> <?= APP_NAME ?> </h1>
                <img src="<?= BASE_URL ?>images/edueventos_logo_color.png" alt="<?= APP_NAME ?> Logo" />
                <img src="<?= BASE_URL ?>images/ute_icon.png" alt="<?= APP_NAME ?> Logo" />
            </div>
            
            <p>Anthony Carmen</p>
            <p>anthony.carmen@ute.edu.ec</p>
            <p>UTE - Quito, Ecuador</p>
            <p>&copy; <?= date('Y') ?> <?= APP_NAME ?>. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="<?= BASE_URL ?>js/script.js"></script>
</body>
</html>
