<?php
// main/ver_events.php
require_once __DIR__ . '/../settings/config.php';
require_once __DIR__ . '/../settings/db.php';
require_once __DIR__ . '/../security/auth.php'; // Ensure session_start() is called here or in config.php

$pdo = getDB();

// Initialize message variables
$message = null;
$message_type = null;

// Handle messages passed via URL after redirect (e.g., after a theoretical delete)
if (isset($_GET['msg']) && isset($_GET['type'])) {
    $message = htmlspecialchars($_GET['msg']);
    $message_type = htmlspecialchars($_GET['type']);
}

// --- Fetch all events for listing ---
try {
    // Using a prepared statement for consistency, even if no user input here
    $stmt = $pdo->prepare("SELECT e.evento_id, e.evento_titulo, e.evento_invitados, e.evento_fecha, e.evento_hora, e.evento_zona_horaria,
                           e.evento_repeticion, e.evento_recordatorio, e.evento_tipo, e.evento_descripcion,
                           l.ubicacion_titulo AS ubicacion,
                           c.contactos_nombres AS contacto_nombre,
                           c.contactos_apellidos AS contacto_apellido
                    FROM events e
                    LEFT JOIN locations l ON e.evento_ubicacion = l.ubicacion_id
                    LEFT JOIN contacts c ON e.evento_contacto = c.contactos_id
                    ORDER BY e.evento_fecha DESC");
    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch as associative array for easier access
} catch (PDOException $e) {
    $events = []; // Ensure $events is an empty array if there's an error
    $message = 'Error al cargar eventos: ' . $e->getMessage();
    $message_type = 'danger';
    error_log('Event fetch error: ' . $e->getMessage()); // Log the actual error
}

include __DIR__ . '/../includes/header.php'; // Includes platform navigation
?>

<section class="management-section">
    <h1>Ver Eventos</h1>

    <?php if ($message): ?>
        <div class="alert alert-<?= $message_type ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Ubicación</th>
                    <th>Contacto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($events)): ?>
                    <tr><td colspan="7" class="text-center">No hay información.</td></tr>
                <?php else: ?>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td><?= htmlspecialchars($event['evento_id']) ?></td>
                            <td><?= htmlspecialchars($event['evento_titulo']) ?></td>
                            <td><?= htmlspecialchars(date('d M Y', strtotime($event['evento_fecha']))) ?></td>
                            <td><?= htmlspecialchars(date('H:i', strtotime($event['evento_hora']))) ?></td>
                            <td><?= htmlspecialchars($event['ubicacion'] ?: 'N/A') ?></td>
                            <td><?= htmlspecialchars(trim($event['contacto_nombre'] . ' ' . $event['contacto_apellido']) ?: 'N/A') ?></td>
                            <td class="action-icons">
                                <button type="button" class="btn btn-sm btn-info"
                                    onclick="showEventDetails(<?= htmlspecialchars(json_encode($event), ENT_QUOTES, 'UTF-8') ?>)">
                                    <img src="<?= BASE_URL ?>images/view_icon.jpg" alt="Ver Detalles" title="Ver Detalles" style="width: 30px; height: 30px;">
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<div id="viewEventModal" class="modal">
    <div class="modal-content">
        <span class="close-button" data-modal-close="viewEventModal">&times;</span>
        <h2>Detalles del Evento: <span id="detail_titulo"></span></h2>
        <div class="modal-details">
            <p><strong>ID:</strong> <span id="detail_id"></span></p>
            <p><strong>Invitados:</strong> <span id="detail_invitados"></span></p>
            <p><strong>Fecha:</strong> <span id="detail_fecha"></span></p>
            <p><strong>Hora:</strong> <span id="detail_hora"></span></p>
            <p><strong>Zona Horaria:</strong> <span id="detail_zona_horaria"></span></p>
            <p><strong>Repetición:</strong> <span id="detail_repeticion"></span></p>
            <p><strong>Recordatorio:</strong> <span id="detail_recordatorio"></span></p>
            <p><strong>Ubicación:</strong> <span id="detail_ubicacion"></span></p>
            <p><strong>Contacto:</strong> <span id="detail_contacto"></span></p>
            <p><strong>Tipo de Evento:</strong> <span id="detail_tipo"></span></p>
            <p><strong>Descripción:</strong> <span id="detail_descripcion"></span></p>
        </div>
        <div class="button-group">
            <button type="button" class="btn btn-cancel" data-modal-close="viewEventModal">Cerrar</button>
        </div>
    </div>
</div>

<script>
// Function to populate and open the event details modal
function showEventDetails(eventData) {
    document.getElementById('detail_titulo').innerText = eventData.evento_titulo || 'N/A';
    document.getElementById('detail_id').innerText = eventData.evento_id || 'N/A';
    document.getElementById('detail_invitados').innerText = eventData.evento_invitados || '0';
    document.getElementById('detail_fecha').innerText = eventData.evento_fecha ? new Date(eventData.evento_fecha).toLocaleDateString('es-ES', { day: '2-digit', month: 'short', year: 'numeric' }) : 'N/A';
    document.getElementById('detail_hora').innerText = eventData.evento_hora ? eventData.evento_hora.substring(0, 5) : 'N/A';
    document.getElementById('detail_zona_horaria').innerText = eventData.evento_zona_horaria || 'N/A';
    document.getElementById('detail_repeticion').innerText = eventData.evento_repeticion == 1 ? 'Sí' : 'No';
    document.getElementById('detail_recordatorio').innerText = eventData.evento_recordatorio == 1 ? 'Sí' : 'No';
    document.getElementById('detail_ubicacion').innerText = eventData.ubicacion || 'N/A';
    document.getElementById('detail_contacto').innerText = (eventData.contacto_nombre || '') + ' ' + (eventData.contacto_apellido || '') || 'N/A';
    document.getElementById('detail_tipo').innerText = eventData.evento_tipo || 'N/A';
    document.getElementById('detail_descripcion').innerText = eventData.evento_descripcion || 'N/A';

    openModal('viewEventModal'); // Assuming openModal function exists in your script.js
}

// Assuming openModal and closeModal functions are defined in youhttps://cp1.awardspace.net/#r global script (e.g., script.js)
// Example basic modal functions (if you don't have them yet):
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
}

// Attach event listeners for closing modals (if not already handled globally)
document.querySelectorAll('.close-button, .btn-cancel').forEach(button => {
    button.addEventListener('click', function() {
        const modalId = this.dataset.modalClose || this.closest('.modal').id;
        closeModal(modalId);
    });
});

</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>