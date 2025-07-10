<?php
// main/inicio.php
require_once '../settings/config.php'; 
require_once '../settings/db.php';     

// Fetch latest 4 public events
try {
    $pdo = getDB();
    $stmt = $pdo->query("SELECT nombre_evento, fecha, lugar FROM events ORDER BY fecha DESC LIMIT 4");
    $public_events = $stmt->fetchAll();
} catch (PDOException $e) {
    $public_events = [];
    // Optionally log error: error_log($e->getMessage());
}

include '../includes/header.php';
?>

<section class="hero-section">
    <div class="hero-content">
        <div class="hero-text">
            <h2>Organiza tus eventos sin esfuerzo</h2>
            <p>Nuestro sistema facilita la programación, gestión y visualización de eventos universitarios, ubicaciones y contactos en una sola plataforma sencilla.</p>
            <a href="<?= BASE_URL ?>security/login.php" class="btn btn-primary">Comenzar</a>
        </div>
        <div class="hero-image">
            <img src="<?= BASE_URL ?>images/reception.jpeg" alt="Organize Events">
        </div>
    </div>
</section>

<section class="about-section">
    <div class="about-content">
        <div class="about-text">
            <h3>Optimizando la gestión de eventos</h3>
            <h4>Coordina eventos educativos de manera eficiente</h4>
            <p>EduEventos simplifica todo el proceso de organización y gestión de eventos educativos al ofrecer una plataforma centralizada. Los usuarios pueden programar actividades, asignar responsabilidades y supervisar el progreso, asegurando que cada evento se desarrolle sin inconvenientes y a tiempo. Con funciones diseñadas para instituciones educativas, ayuda al personal a ahorrar tiempo, reducir errores y mejorar la calidad general de los eventos. Nuestro sistema fomenta la colaboración y la claridad, haciendo que la planificación de eventos sea sencilla y efectiva para todos los involucrados.</p>
            <a href="<?= BASE_URL ?>main/ver_eventos.php" class="btn btn-success">Ver Eventos</a>
        </div>
    </div>
</section>

<?php if (!empty($public_events)): ?>
<section class="public-events">
    <h3>Próximos Eventos</h3>
    <ul>
        <?php foreach ($public_events as $event): ?>
            <li>
                <strong><?= htmlspecialchars($event['nombre_evento']) ?></strong> - 
                <?= htmlspecialchars(date('d/m/Y', strtotime($event['fecha']))) ?> @ 
                <?= htmlspecialchars($event['lugar']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>