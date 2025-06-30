<?php
// platform/dashboard.php
require_once __DIR__ . '/../settings/config.php';
require_once __DIR__ . '/../settings/db.php';
require_once __DIR__ . '/../security/auth.php';

// Protect this page: redirect if not logged in
if (!isAuthenticated()) {
    redirect(BASE_URL . 'login.php');
}

include __DIR__ . '/../includes/header.php'; // Includes platform navigation
?>

<section class="dashboard-section">
    <h1>Bienvenida a la plataforma de <?= APP_NAME ?></h1>
    <p>Tu sistema de gestión está dividido en 3 secciones, las cuales puedes:</p>

    <div class="dashboard-actions">
        <div class="action-item">
            <img src="<?= BASE_URL ?>images/add_icon.png" alt="Agregar Nuevo">
            <p><strong>Agregar Nuevo</strong> (Evento/Ubicación/Contacto)</p>
        </div>
        <div class="action-item">
            <img src="<?= BASE_URL ?>images/view_icon.png" alt="Ver Información Adicional">
            <p><strong>Ver Información Adicional</strong></p>
        </div>
        <div class="action-item">
            <img src="<?= BASE_URL ?>images/edit_icon.png" alt="Editar Información">
            <p><strong>Editar Información</strong></p>
        </div>
        <div class="action-item">
            <img src="<?= BASE_URL ?>images/delete_icon.png" alt="Eliminar fila">
            <p><strong>Eliminar fila</strong> de la lista</p>
        </div>
    </div>

    <div class="section-summaries">
        <div class="summary-box">
            <h3>Eventos</h3>
            <p>Registrar eventos como conferencias, ferias, talleres y seminarios.</p>
            <p>Información: título, # de invitados, fecha, hora, zona horaria, repetición, recordatorio, contacto del evento, tipo de evento, ubicación y descripción.</p>
            <a href="<?= BASE_URL ?>platform/events.php" class="btn btn-success">Gestionar Eventos</a>
        </div>
        <div class="summary-box">
            <h3>Ubicaciones</h3>
            <p>Registrar lugares donde realizar los eventos.</p>
            <p>Información: título, dirección, ciudad, país y link de google maps.</p>
            <a href="<?= BASE_URL ?>platform/locations.php" class="btn btn-success">Gestionar Ubicaciones</a>
        </div>
        <div class="summary-box">
            <h3>Contactos</h3>
            <p>Registrar contactos, los cuales que son responsables de los eventos.</p>
            <p>Información: nombres, apellidos, institución educativa, país, CI, número de teléfono, correo electrónico, y subir su foto de perfil.</p>
            <a href="<?= BASE_URL ?>platform/contacts.php" class="btn btn-success">Gestionar Contactos</a>
        </div>
    </div>

    <p class="text-center" style="margin-top: 30px;">
        <small>*Contacta tu administrador por información adicional o para soporte TI</small>
    </p>

</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<style>
/* Add specific styles for the dashboard */
.dashboard-section h1 {
    text-align: center;
    color: #4CAF50;
    margin-bottom: 30px;
}

.dashboard-section p {
    text-align: center;
    font-size: 1.1em;
    margin-bottom: 25px;
}

.dashboard-actions {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 30px;
    margin-bottom: 50px;
    padding: 20px;
    background-color: #2c2c2c;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.action-item {
    text-align: center;
    flex: 1 1 200px; /* Allows items to wrap */
    max-width: 250px;
}

.action-item img {
    width: 60px;
    height: 60px;
    margin-bottom: 10px;
    filter: invert(80%) sepia(20%) saturate(1000%) hue-rotate(80deg) brightness(100%); /* Greenish tint for icons */
}

.action-item p {
    margin: 0;
    font-size: 0.95em;
    color: #bbb;
}

.section-summaries {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    gap: 30px;
}

.summary-box {
    background-color: #2c2c2c;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    flex: 1 1 300px; /* Allows boxes to grow and wrap */
    max-width: 350px;
    text-align: center;
}

.summary-box h3 {
    color: #4CAF50;
    margin-top: 0;
    margin-bottom: 15px;
}

.summary-box p {
    font-size: 0.9em;
    line-height: 1.5;
    color: #ccc;
    text-align: left; /* Keep text left-aligned within box */
    min-height: 80px; /* Ensures consistent height if descriptions vary */
}

.summary-box .btn {
    margin-top: 20px;
}
</style>