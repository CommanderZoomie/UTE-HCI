<?php
// platform/events.php
require_once __DIR__ . '/../settings/config.php';
require_once __DIR__ . '/../settings/db.php';
require_once __DIR__ . '/../security/auth.php';

$pdo = getDB(); 

// Protect this page: redirect if not logged in
if (!isAuthenticated()) {
    redirect(BASE_URL . 'login.php');
}

$message = '';
$message_type = '';

// --- Handle Add/Edit Event (POST request) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    $evento_titulo       = $_POST['titulo'] ?? '';
    $evento_invitados    = $_POST['num_invitados'] ?? null;
    $evento_fecha        = $_POST['fecha'] ?? '';
    $evento_hora         = $_POST['hora'] ?? '';
    $evento_zona_horaria = $_POST['zona_horaria'] ?? '';
    $evento_repeticion   = isset($_POST['repeticion']) ? 1 : 0;
    $evento_recordatorio = isset($_POST['recordatorio']) ? 1 : 0;
    $evento_ubicacion    = $_POST['ubicacion_id'] ?? null;
    $evento_contacto     = $_POST['contacto_id'] ?? null;
    $evento_tipo         = $_POST['tipo_evento'] ?? '';
    $evento_descripcion  = $_POST['descripcion'] ?? '';

    // Basic validation
    if (empty($evento_titulo) || empty($evento_fecha) || empty($evento_hora) || empty($evento_ubicacion) || empty($evento_contacto)) {
        $message = 'Error: Campos obligatorios de evento incompletos.';
        $message_type = 'danger';
    } else {
        if ($action === 'add') {
            try {
                $stmt = $pdo->prepare("INSERT INTO events (
                    evento_titulo, evento_invitados, evento_fecha, evento_hora, evento_zona_horaria,
                    evento_repeticion, evento_recordatorio, evento_ubicacion, evento_contacto,
                    evento_tipo, evento_descripcion
                ) VALUES (
                    :evento_titulo, :evento_invitados, :evento_fecha, :evento_hora, :evento_zona_horaria,
                    :evento_repeticion, :evento_recordatorio, :evento_ubicacion, :evento_contacto,
                    :evento_tipo, :evento_descripcion)");

                $stmt->execute([
                    'evento_titulo' => $evento_titulo,
                    'evento_invitados' => $evento_invitados,
                    'evento_fecha' => $evento_fecha,
                    'evento_hora' => $evento_hora,
                    'evento_zona_horaria' => $evento_zona_horaria,
                    'evento_repeticion' => $evento_repeticion,
                    'evento_recordatorio' => $evento_recordatorio,
                    'evento_ubicacion' => $evento_ubicacion,
                    'evento_contacto' => $evento_contacto,
                    'evento_tipo' => $evento_tipo,
                    'evento_descripcion' => $evento_descripcion
                ]);
                $message = 'Evento agregado exitosamente.';
                $message_type = 'success';
            } catch (PDOException $e) {
                $message = 'Error al agregar evento: ' . $e->getMessage();
                $message_type = 'danger';
            }
        } elseif ($action === 'edit') {
            $evento_id = $_POST['evento_id'] ?? null;
            if ($evento_id) {
                try {
                    $stmt = $pdo->prepare("UPDATE events SET
                        evento_titulo = :evento_titulo,
                        evento_invitados = :evento_invitados,
                        evento_fecha = :evento_fecha,
                        evento_hora = :evento_hora,
                        evento_zona_horaria = :evento_zona_horaria,
                        evento_repeticion = :evento_repeticion,
                        evento_recordatorio = :evento_recordatorio,
                        evento_ubicacion = :evento_ubicacion,
                        evento_contacto = :evento_contacto,
                        evento_tipo = :evento_tipo,
                        evento_descripcion = :evento_descripcion
                    WHERE evento_id = :evento_id");

                    $stmt->execute([
                        'evento_titulo' => $evento_titulo,
                        'evento_invitados' => $evento_invitados,
                        'evento_fecha' => $evento_fecha,
                        'evento_hora' => $evento_hora,
                        'evento_zona_horaria' => $evento_zona_horaria,
                        'evento_repeticion' => $evento_repeticion,
                        'evento_recordatorio' => $evento_recordatorio,
                        'evento_ubicacion' => $evento_ubicacion,
                        'evento_contacto' => $evento_contacto,
                        'evento_tipo' => $evento_tipo,
                        'evento_descripcion' => $evento_descripcion,
                        'evento_id' => $evento_id
                    ]);
                    $message = 'Evento actualizado exitosamente.';
                    $message_type = 'success';
                } catch (PDOException $e) {
                    $message = 'Error al actualizar evento: ' . $e->getMessage();
                    $message_type = 'danger';
                }
            } else {
                $message = 'Error: ID de evento no especificado para edición.';
                $message_type = 'danger';
            }
        }
    }
}

// --- Handle Delete Event (GET request with ID) ---
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $evento_id = $_GET['id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM events WHERE evento_id = :evento_id");
        $stmt->bindParam(':evento_id', $evento_id);
        $stmt->execute();
        $message = 'Evento eliminado exitosamente.';
        $message_type = 'success';
        // Redirect to clear GET parameters after successful deletion
        redirect(BASE_URL . 'platform/events.php?msg=' . urlencode($message) . '&type=' . $message_type);
    } catch (PDOException $e) {
        $message = 'Error al eliminar evento: ' . $e->getMessage();
        $message_type = 'danger';
        redirect(BASE_URL . 'platform/events.php?msg=' . urlencode($message) . '&type=' . $message_type);
    }
}

// --- Fetch all events for listing ---
try {
    $stmt = $pdo->query("SELECT e.evento_id, e.evento_titulo, e.evento_invitados, e.evento_fecha, e.evento_hora, e.evento_zona_horaria, 
           e.evento_repeticion, e.evento_recordatorio, e.evento_tipo, e.evento_descripcion,
           l.ubicacion_titulo AS ubicacion,
           c.contactos_nombres AS contacto_nombre,
           c.contactos_apellidos AS contacto_apellido
    FROM events e
    LEFT JOIN locations l ON e.evento_ubicacion = l.ubicacion_id
    LEFT JOIN contacts c ON e.evento_contacto = c.contactos_id
    ORDER BY e.evento_fecha DESC");
    $events = $stmt->fetchAll();
} catch (PDOException $e) {
    $events = []; // No events or error fetching
    $message = 'Error al cargar eventos: ' . $e->getMessage();
    $message_type = 'danger';
}

// Fetch locations and contacts for dropdowns in forms
$locations_dropdown = [];
try {
    $stmt = $pdo->query("SELECT ubicacion_id, ubicacion_titulo FROM locations ORDER BY ubicacion_titulo");
    $locations_dropdown = $stmt->fetchAll();
} catch (PDOException $e) { /* handle error silently or log */ }

$contacts_dropdown = [];
try {
    $stmt = $pdo->query("SELECT contactos_id, contactos_nombres, contactos_apellidos FROM contacts ORDER BY contactos_nombres");
    $contacts_dropdown = $stmt->fetchAll();
} catch (PDOException $e) { /* handle error silently or log */ }

// Handle messages passed via URL after redirect (e.g., after delete)
if (isset($_GET['msg']) && isset($_GET['type'])) {
    $message = htmlspecialchars($_GET['msg']);
    $message_type = htmlspecialchars($_GET['type']);
}

include __DIR__ . '/../includes/header.php'; // Includes platform navigation
?>

<section class="management-section">
    <h1>Gestión de Eventos</h1>

    <?php if ($message): ?>
        <div class="alert alert-<?= $message_type ?>"><?= $message ?></div>
    <?php endif; ?>

    <button class="btn btn-success" data-modal-target="addEventModal" onclick="resetAddEventForm()">Agregar Nuevo Evento</button>

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
                    <tr><td colspan="7" class="text-center">No hay eventos registrados.</td></tr>
                <?php else: ?>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td><?= htmlspecialchars($event['evento_id']) ?></td>
                            <td><?= htmlspecialchars($event['evento_titulo']) ?></td>
                            <td><?= htmlspecialchars(date('d M Y', strtotime($event['evento_fecha']))) ?></td>
                            <td><?= htmlspecialchars(date('H:i', strtotime($event['evento_hora']))) ?></td>
                            <td><?= htmlspecialchars($event['ubicacion'] ?: 'N/A') ?></td>
                            <td><?= htmlspecialchars($event['contacto_nombre'] . ' ' . $event['contacto_apellido'] ?: 'N/A') ?></td>
                            <td class="action-icons">
                                <img src="<?= BASE_URL ?>images/view_icon.jpg" alt="Ver Detalles" title="Ver Detalles" onclick="alert('Ver detalles del evento <?= htmlspecialchars($event['evento_titulo']) ?>');" style="width: 30px; height: 30px;">
                                <img src="<?= BASE_URL ?>images/edit_icon.jpg" alt="Editar" title="Editar Evento" onclick="openEditEventModal(<?= htmlspecialchars(json_encode($event)) ?>);" style="width: 30px; height: 30px;">
                                <img src="<?= BASE_URL ?>images/delete_icon.jpg" alt="Eliminar" title="Eliminar Evento" onclick="confirmDelete(<?= $event['evento_id'] ?>, '<?= htmlspecialchars($event['evento_titulo']) ?>');" style="width: 30px; height: 30px;">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<div id="addEventModal" class="modal">
    <div class="modal-content">
        <span class="close-button" data-modal-close="addEventModal">&times;</span>
        <h2>Nuevo Evento</h2>
        <form action="events.php" method="POST" id="addEventForm">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label for="add_titulo">Título:</label>
                <input type="text" id="add_titulo" name="titulo" required>
            </div>
            <div class="form-group">
                <label for="add_num_invitados">Número de invitados:</label>
                <input type="number" id="add_num_invitados" name="num_invitados" min="0">
            </div>
            <div class="form-group">
                <label for="add_fecha">Fecha:</label>
                <input type="date" id="add_fecha" name="fecha" required>
            </div>
            <div class="form-group">
                <label for="add_hora">Hora:</label>
                <input type="time" id="add_hora" name="hora" required>
            </div>
            <div class="form-group">
                <label for="add_zona_horaria">Zona Horaria:</label>
                <input type="text" id="add_zona_horaria" name="zona_horaria" value="UTC-5">
            </div>
            <div class="form-group">
                <label>Repetición:</label>
                <input type="radio" id="add_repeticion_si" name="repeticion" value="1"> <label for="add_repeticion_si">SI</label>
                <input type="radio" id="add_repeticion_no" name="repeticion" value="0" checked> <label for="add_repeticion_no">NO</label>
            </div>
            <div class="form-group">
                <label>Recordatorio:</label>
                <input type="radio" id="add_recordatorio_si" name="recordatorio" value="1"> <label for="add_recordatorio_si">SI</label>
                <input type="radio" id="add_recordatorio_no" name="recordatorio" value="0" checked> <label for="add_recordatorio_no">NO</label>
            </div>
            <div class="form-group">
                <label for="add_ubicacion_id">Ubicación:</label>
                <select id="add_ubicacion_id" name="ubicacion_id" required>
                    <option value="">Seleccionar Ubicación</option>
                    <?php foreach ($locations_dropdown as $loc): ?>
                        <option value="<?= htmlspecialchars($loc['ubicacion_id']) ?>"><?= htmlspecialchars($loc['ubicacion_titulo']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="add_contacto_id">Contacto de evento:</label>
                <select id="add_contacto_id" name="contacto_id" required>
                    <option value="">Seleccionar Contacto</option>
                    <?php foreach ($contacts_dropdown as $contact): ?>
                        <option value="<?= htmlspecialchars($contact['contactos_id']) ?>"><?= htmlspecialchars($contact['contactos_nombres'] . ' ' . $contact['contactos_apellidos']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="add_tipo_evento">Tipo de Evento:</label>
                <input type="text" id="add_tipo_evento" name="tipo_evento">
            </div>
            <div class="form-group">
                <label for="add_descripcion">Descripción:</label>
                <textarea id="add_descripcion" name="descripcion" rows="4"></textarea>
            </div>
            <div class="button-group">
                <button type="button" class="btn btn-cancel" data-modal-close="addEventModal">Cancelar</button>
                <button type="submit" class="btn btn-success">Agregar</button>
            </div>
        </form>
    </div>
</div>

<div id="editEventModal" class="modal">
    <div class="modal-content">
        <span class="close-button" data-modal-close="editEventModal">&times;</span>
        <h2>Editar Evento</h2>
        <form action="events.php" method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" id="edit_evento_id" name="evento_id">
            <div class="form-group">
                <label for="edit_titulo">Título:</label>
                <input type="text" id="edit_titulo" name="titulo" required>
            </div>
            <div class="form-group">
                <label for="edit_num_invitados">Número de invitados:</label>
                <input type="number" id="edit_num_invitados" name="num_invitados" min="0">
            </div>
            <div class="form-group">
                <label for="edit_fecha">Fecha:</label>
                <input type="date" id="edit_fecha" name="fecha" required>
            </div>
            <div class="form-group">
                <label for="edit_hora">Hora:</label>
                <input type="time" id="edit_hora" name="hora" required>
            </div>
            <div class="form-group">
                <label for="edit_zona_horaria">Zona Horaria:</label>
                <input type="text" id="edit_zona_horaria" name="zona_horaria">
            </div>
            <div class="form-group">
                <label>Repetición:</label>
                <input type="radio" id="edit_repeticion_si" name="repeticion" value="1"> <label for="edit_repeticion_si">SI</label>
                <input type="radio" id="edit_repeticion_no" name="repeticion" value="0"> <label for="edit_repeticion_no">NO</label>
            </div>
            <div class="form-group">
                <label>Recordatorio:</label>
                <input type="radio" id="edit_recordatorio_si" name="recordatorio" value="1"> <label for="edit_recordatorio_si">SI</label>
                <input type="radio" id="edit_recordatorio_no" name="recordatorio" value="0"> <label for="edit_recordatorio_no">NO</label>
            </div>
            <div class="form-group">
                <label for="edit_ubicacion_id">Ubicación:</label>
                <select id="edit_ubicacion_id" name="ubicacion_id" required>
                    <option value="">Seleccionar Ubicación</option>
                    <?php foreach ($locations_dropdown as $loc): ?>
                        <option value="<?= htmlspecialchars($loc['ubicacion_id']) ?>"><?= htmlspecialchars($loc['ubicacion_titulo']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="edit_contacto_id">Contacto de evento:</label>
                <select id="edit_contacto_id" name="contacto_id" required>
                    <option value="">Seleccionar Contacto</option>
                    <?php foreach ($contacts_dropdown as $contact): ?>
                        <option value="<?= htmlspecialchars($contact['contactos_id']) ?>"><?= htmlspecialchars($contact['contactos_nombres'] . ' ' . $contact['contactos_apellidos']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="edit_tipo_evento">Tipo de Evento:</label>
                <input type="text" id="edit_tipo_evento" name="tipo_evento">
            </div>
            <div class="form-group">
                <label for="edit_descripcion">Descripción:</label>
                <textarea id="edit_descripcion" name="descripcion" rows="4"></textarea>
            </div>
            <div class="button-group">
                <button type="button" class="btn btn-cancel" data-modal-close="editEventModal">Cancelar</button>
                <button type="submit" class="btn btn-success">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
// Reset Add Event modal form inputs
function resetAddEventForm() {
    const form = document.getElementById('addEventForm');
    if (form) {
        form.reset();
    }
}

// JavaScript for handling edit modal population and delete confirmation
function openEditEventModal(eventData) {
    openModal('editEventModal');

    // Assign values to edit modal inputs
    document.getElementById('edit_evento_id').value = eventData.evento_id || '';
    document.getElementById('edit_titulo').value = eventData.titulo || '';
    document.getElementById('edit_num_invitados').value = eventData.num_invitados || '';
    document.getElementById('edit_fecha').value = eventData.fecha || '';
    // Trim seconds for time input (HH:MM)
    document.getElementById('edit_hora').value = eventData.hora ? eventData.hora.substring(0, 5) : '';
    document.getElementById('edit_zona_horaria').value = eventData.zona_horaria || '';

    if (eventData.repeticion == 1) {
        document.getElementById('edit_repeticion_si').checked = true;
    } else {
        document.getElementById('edit_repeticion_no').checked = true;
    }

    if (eventData.recordatorio == 1) {
        document.getElementById('edit_recordatorio_si').checked = true;
    } else {
        document.getElementById('edit_recordatorio_no').checked = true;
    }

    document.getElementById('edit_ubicacion_id').value = eventData.ubicacion_id || '';
    document.getElementById('edit_contacto_id').value = eventData.contacto_id || '';
    document.getElementById('edit_tipo_evento').value = eventData.tipo_evento || '';
    document.getElementById('edit_descripcion').value = eventData.descripcion || '';
}

function confirmDelete(eventoId, titulo) {
    if (confirm(`¿Está seguro de eliminar el evento "${titulo}"? Esta acción no se puede deshacer.`)) {
        window.location.href = `events.php?action=delete&id=${eventoId}`;
    }
}

// Assuming openModal and closeModal exist in your script.js for modal management
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
