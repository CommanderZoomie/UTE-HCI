<?php
// main/inicio.php
require_once '../settings/config.php';
require_once '../settings/db.php';
require_once '../settings/lang.php'; // Centralized language loader

// Fetch latest 4 public events
try {
    $pdo = getDB();
    // IMPORTANT: Verify these column names match your 'events' table
    // If your table uses 'evento_titulo' and 'ubicacion_titulo', update this query:
    // $stmt = $pdo->query("SELECT evento_titulo AS nombre_evento, evento_fecha AS fecha, ubicacion_titulo AS lugar FROM events ORDER BY evento_fecha DESC LIMIT 4");
    $stmt = $pdo->query("SELECT nombre_evento, fecha, lugar FROM events ORDER BY fecha DESC LIMIT 4");
    $public_events = $stmt->fetchAll();
} catch (PDOException $e) {
    $public_events = [];
    error_log("DB error fetching public events on inicio.php: " . $e->getMessage());
}

include '../includes/header.php';
?>

<section class="hero-section">
    <div class="hero-content container"> <!-- Added .container here to center content -->
        <div class="hero-text">
            <h2><?= htmlspecialchars($lang['inicio_hero_title'] ?? 'Bienvenido a EduEventos') ?></h2>
            <p><?= htmlspecialchars($lang['inicio_hero_description'] ?? 'Organiza y gestiona tus eventos educativos de manera eficiente.') ?></p>
            <a href="<?= BASE_URL ?>security/login.php" class="btn btn-primary"><?= htmlspecialchars($lang['inicio_get_started_btn'] ?? 'Comenzar') ?></a>
        </div>
        <div class="hero-image-wrapper"> <!-- Changed class for the image container -->
            <img src="<?= BASE_URL ?>images/reception.jpeg" alt="<?= htmlspecialchars($lang['inicio_hero_image_alt'] ?? 'Organiza Eventos Educativos') ?>" loading="lazy" class="main-hero-image"/>
        </div>
    </div>
</section>

<section class="about-section">
    <div class="about-content container"> <!-- Added .container here -->
        <div class="about-text">
            <h3><?= htmlspecialchars($lang['inicio_about_title'] ?? 'Sobre Nosotros') ?></h3>
            <h4><?= htmlspecialchars($lang['inicio_about_subtitle'] ?? 'Tu Plataforma Integral de Gestión de Eventos') ?></h4>
            <p><?= htmlspecialchars($lang['inicio_about_paragraph'] ?? 'EduEventos es una herramienta diseñada para simplificar la planificación, ejecución y seguimiento de todo tipo de eventos educativos, desde conferencias hasta talleres.') ?></p>
            <a href="<?= BASE_URL ?>main/ver_eventos.php" class="btn btn-success"><?= htmlspecialchars($lang['inicio_view_events_btn'] ?? 'Ver Eventos') ?></a>
        </div>
    </div>
</section>

<?php if (!empty($public_events)): ?>
<section class="public-events container"> <!-- Added .container here -->
    <h3><?= htmlspecialchars($lang['inicio_upcoming_events_title'] ?? 'Próximos eventos') ?></h3>
    <ul>
        <?php foreach ($public_events as $event): ?>
            <li>
                <strong><?= htmlspecialchars($event['nombre_evento']) ?></strong> - 
                <?= htmlspecialchars(date($lang['date_format'] ?? 'd/m/Y', strtotime($event['fecha']))) ?> @ 
                <?= htmlspecialchars($event['lugar']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>