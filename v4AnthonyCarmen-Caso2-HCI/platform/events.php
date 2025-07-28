<?php
// platform/events.php
require_once __DIR__ . '/../settings/config.php';
require_once __DIR__ . '/../settings/db.php';
require_once __DIR__ . '/../security/auth.php';
require_once __DIR__ . '/../settings/lang.php'; // Include the language loader

$pdo = getDB(); 

// Protect this page: redirect if not logged in
if (!isAuthenticated()) {
    redirect(BASE_URL . 'security/login.php'); // Corrected redirect path for login
    exit;
}

$message = '';
$message_type = '';

// --- Handle Add/Edit Event (POST request) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Sanitize inputs
    $evento_titulo       = trim($_POST['titulo'] ?? '');
    $evento_invitados    = is_numeric($_POST['num_invitados']) ? (int)$_POST['num_invitados'] : null;
    $evento_fecha        = $_POST['fecha'] ?? '';
    $evento_hora         = $_POST['hora'] ?? '';
    $evento_zona_horaria = trim($_POST['zona_horaria'] ?? '');
    $evento_repeticion   = isset($_POST['repeticion']) && $_POST['repeticion'] == '1' ? 1 : 0;
    $evento_recordatorio = isset($_POST['recordatorio']) && $_POST['recordatorio'] == '1' ? 1 : 0;
    $evento_ubicacion    = $_POST['ubicacion_id'] ?? null;
    $evento_contacto     = $_POST['contacto_id'] ?? null;
    $evento_tipo         = trim($_POST['tipo_evento'] ?? '');
    $evento_descripcion  = trim($_POST['descripcion'] ?? '');

    // Basic validation
    if (empty($evento_titulo) || empty($evento_fecha) || empty($evento_hora) || empty($evento_ubicacion) || empty($evento_contacto)) {
        $message = $lang['error_event_required_fields'] ?? 'Error: Campos obligatorios de evento incompletos.';
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
                $message = $lang['event_added_success'] ?? 'Evento agregado exitosamente.';
                $message_type = 'success';
            } catch (PDOException $e) {
                $message = ($lang['error_add_event'] ?? 'Error al agregar evento: ') . $e->getMessage();
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
                    $message = $lang['event_updated_success'] ?? 'Evento actualizado exitosamente.';
                    $message_type = 'success';
                } catch (PDOException $e) {
                    $message = ($lang['error_update_event'] ?? 'Error al actualizar evento: ') . $e->getMessage();
                    $message_type = 'danger';
                }
            } else {
                $message = $lang['error_no_event_id'] ?? 'Error: ID de evento no especificado para edición.';
                $message_type = 'danger';
            }
        }
    }
}

// --- Handle Delete Event (GET request with ID) ---
// This part remains a direct redirect after confirmation via JS
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete') {
    $evento_id = $_GET['id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM events WHERE evento_id = :evento_id");
        $stmt->bindParam(':evento_id', $evento_id);
        $stmt->execute();
        $message = $lang['event_deleted_success'] ?? 'Evento eliminado exitosamente.';
        $message_type = 'success';
        redirect(BASE_URL . 'platform/events.php?msg=' . urlencode($message) . '&type=' . $message_type);
    } catch (PDOException $e) {
        $message = ($lang['error_delete_event'] ?? 'Error al eliminar evento: ') . $e->getMessage();
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
           c.contactos_apellidos AS contacto_apellido,
           e.evento_ubicacion, e.evento_contacto
    FROM events e
    LEFT JOIN locations l ON e.evento_ubicacion = l.ubicacion_id
    LEFT JOIN contacts c ON e.evento_contacto = c.contactos_id
    ORDER BY e.evento_fecha DESC");
    $events = $stmt->fetchAll();
} catch (PDOException $e) {
    $events = [];
    $message = ($lang['error_load_events'] ?? 'Error al cargar eventos: ') . $e->getMessage();
    $message_type = 'danger';
}

// Fetch locations and contacts for dropdowns
try {
    $locations_dropdown = $pdo->query("SELECT ubicacion_id, ubicacion_titulo FROM locations ORDER BY ubicacion_titulo")->fetchAll();
} catch (PDOException $e) {
    $locations_dropdown = [];
    error_log("Error loading locations for dropdown: " . $e->getMessage()); // Log dropdown errors
}

try {
    $contacts_dropdown = $pdo->query("SELECT contactos_id, contactos_nombres, contactos_apellidos FROM contacts ORDER BY contactos_nombres")->fetchAll();
} catch (PDOException $e) {
    $contacts_dropdown = [];
    error_log("Error loading contacts for dropdown: " . $e->getMessage()); // Log dropdown errors
}


// Handle messages passed via URL after redirect
if (isset($_GET['msg'], $_GET['type'])) {
    $message = htmlspecialchars($_GET['msg']);
    $message_type = htmlspecialchars($_GET['type']);
}

include __DIR__ . '/../includes/header.php';
?>

<main class="content-wrapper">
    <section class="management-section container">
        <h1><?= $lang['manage_events_title'] ?? 'Gestión de Eventos' ?></h1>

        <?php if ($message): ?>
            <div class="alert alert-<?= htmlspecialchars($message_type) ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <button class="btn btn-success" data-modal-target="addEventModal" onclick="resetAddEventForm()"><?= $lang['add_new_event_btn'] ?? 'Agregar Nuevo Evento' ?></button>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th><?= $lang['table_header_id'] ?? 'ID' ?></th>
                        <th><?= $lang['table_header_title'] ?? 'Título' ?></th>
                        <th><?= $lang['table_header_date'] ?? 'Fecha' ?></th>
                        <th><?= $lang['table_header_time'] ?? 'Hora' ?></th>
                        <th><?= $lang['table_header_location'] ?? 'Ubicación' ?></th>
                        <th><?= $lang['table_header_contact'] ?? 'Contacto' ?></th>
                        <th><?= $lang['table_header_actions'] ?? 'Acciones' ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($events)): ?>
                        <tr><td colspan="7" class="text-center"><?= $lang['no_events_registered'] ?? 'No hay eventos registrados.' ?></td></tr>
                    <?php else: ?>
                        <?php foreach ($events as $event): ?>
                            <tr>
                                <td><?= htmlspecialchars($event['evento_id']) ?></td>
                                <td><?= htmlspecialchars($event['evento_titulo']) ?></td>
                                <td><?= htmlspecialchars(date($lang['date_format'] ?? 'd M Y', strtotime($event['evento_fecha']))) ?></td>
                                <td><?= htmlspecialchars(date($lang['time_format'] ?? 'H:i', strtotime($event['evento_hora']))) ?></td>
                                <td><?= htmlspecialchars($event['ubicacion'] ?? ($lang['not_applicable_abbr'] ?? 'N/A')) ?></td>
                                <td><?= htmlspecialchars(trim(($event['contacto_nombre'] ?? '') . ' ' . ($event['contacto_apellido'] ?? '')) ?: ($lang['not_applicable_abbr'] ?? 'N/A')) ?></td>
                                <td class="action-icons">
                                    <button type="button" class="btn btn-sm btn-info"
                                        onclick='showEventDetails(<?= json_encode(array_merge($event, [
                                            "lang_yes" => ($lang['yes'] ?? 'Yes'),
                                            "lang_no" => ($lang['no'] ?? 'No'),
                                            "lang_na" => ($lang['not_applicable_abbr'] ?? 'N/A'),
                                            "lang_code" => ($lang_code ?? 'en-US')
                                        ]), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'>
                                        <img src="<?= BASE_URL ?>images/view_icon2.jpg" alt="<?= $lang['view_details_alt'] ?? 'Ver Detalles' ?>" title="<?= $lang['view_details_title'] ?? 'Ver Detalles' ?>" style="width: 30px; height: 30px;">
                                    </button>
                                    <button type="button" class="btn btn-sm btn-primary"
                                        onclick='openEditEventModal(<?= json_encode([
                                            "evento_id" => $event["evento_id"],
                                            "titulo" => $event["evento_titulo"],
                                            "num_invitados" => $event["evento_invitados"],
                                            "fecha" => $event["evento_fecha"],
                                            "hora" => $event["evento_hora"],
                                            "zona_horaria" => $event["evento_zona_horaria"],
                                            "repeticion" => $event["evento_repeticion"],
                                            "recordatorio" => $event["evento_recordatorio"],
                                            "ubicacion_id" => $event["evento_ubicacion"] ?? '',
                                            "contacto_id" => $event["evento_contacto"] ?? '',
                                            "tipo_evento" => $event["evento_tipo"],
                                            "descripcion" => $event["evento_descripcion"]
                                        ]) ?>);'>
                                        <img src="<?= BASE_URL ?>images/edit_icon.jpg" alt="<?= $lang['edit_event_alt'] ?? 'Editar' ?>" title="<?= $lang['edit_event_title'] ?? 'Editar Evento' ?>" style="width: 30px; height: 30px;">
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick='confirmDeleteEvent(
                                            <?= $event['evento_id'] ?>,
                                            "<?= addslashes(htmlspecialchars($event['evento_titulo'])) ?>",
                                            "<?= addslashes($lang['confirm_delete_event_prompt'] ?? '¿Está seguro de eliminar el evento ') ?>",
                                            "<?= addslashes($lang['confirm_delete_event_warning'] ?? 'Esta acción no se puede deshacer.') ?>",
                                            "<?= addslashes($lang['delete_btn'] ?? 'Eliminar') ?>",
                                            "<?= addslashes($lang['cancel_btn'] ?? 'Cancelar') ?>"
                                        );'>
                                        <img src="<?= BASE_URL ?>images/delete_icon.jpg" alt="<?= $lang['delete_event_alt'] ?? 'Eliminar' ?>" title="<?= $lang['delete_event_title'] ?? 'Eliminar Evento' ?>" style="width: 30px; height: 30px;">
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Add Event Modal -->
    <div id="addEventModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="addEventTitle" aria-hidden="true" tabindex="-1">
        <div class="modal-content">
            <span class="close-button" data-modal-close="addEventModal" aria-label="<?= $lang['close_modal_aria'] ?? 'Cerrar' ?>">&times;</span>
            <h2 id="addEventTitle"><?= $lang['add_event_modal_title'] ?? 'Nuevo Evento' ?></h2>
            <form action="events.php" method="POST" id="addEventForm">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label for="add_titulo"><?= $lang['label_title'] ?? 'Título:' ?></label>
                    <input type="text" id="add_titulo" name="titulo" required>
                </div>
                <div class="form-group">
                    <label for="add_num_invitados"><?= $lang['label_num_guests'] ?? 'Número de invitados:' ?></label>
                    <input type="number" id="add_num_invitados" name="num_invitados" min="0">
                </div>
                <div class="form-group">
                    <label for="add_fecha"><?= $lang['label_date'] ?? 'Fecha:' ?></label>
                    <input type="date" id="add_fecha" name="fecha" required>
                </div>
                <div class="form-group">
                    <label for="add_hora"><?= $lang['label_time'] ?? 'Hora:' ?></label>
                    <input type="time" id="add_hora" name="hora" required>
                </div>
                <div class="form-group">
                    <label for="add_zona_horaria"><?= $lang['label_timezone'] ?? 'Zona Horaria:' ?></label>
                    <input type="text" id="add_zona_horaria" name="zona_horaria" value="UTC-5">
                </div>
                <div class="form-group">
                    <label><?= $lang['label_repetition'] ?? 'Repetición:' ?></label>
                    <input type="radio" id="add_repeticion_si" name="repeticion" value="1"> <label for="add_repeticion_si"><?= $lang['yes'] ?? 'SI' ?></label>
                    <input type="radio" id="add_repeticion_no" name="repeticion" value="0" checked> <label for="add_repeticion_no"><?= $lang['no'] ?? 'NO' ?></label>
                </div>
                <div class="form-group">
                    <label><?= $lang['label_reminder'] ?? 'Recordatorio:' ?></label>
                    <input type="radio" id="add_recordatorio_si" name="recordatorio" value="1"> <label for="add_recordatorio_si"><?= $lang['yes'] ?? 'SI' ?></label>
                    <input type="radio" id="add_recordatorio_no" name="recordatorio" value="0" checked> <label for="add_recordatorio_no"><?= $lang['no'] ?? 'NO' ?></label>
                </div>
                <div class="form-group">
                    <label for="add_ubicacion_id"><?= $lang['label_location'] ?? 'Ubicación:' ?></label>
                    <select id="add_ubicacion_id" name="ubicacion_id" required>
                        <option value=""><?= $lang['select_location_option'] ?? 'Seleccionar Ubicación' ?></option>
                        <?php foreach ($locations_dropdown as $loc): ?>
                            <option value="<?= htmlspecialchars($loc['ubicacion_id']) ?>"><?= htmlspecialchars($loc['ubicacion_titulo']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="add_contacto_id"><?= $lang['label_event_contact'] ?? 'Contacto de evento:' ?></label>
                    <select id="add_contacto_id" name="contacto_id" required>
                        <option value=""><?= $lang['select_contact_option'] ?? 'Seleccionar Contacto' ?></option>
                        <?php foreach ($contacts_dropdown as $contact): ?>
                            <option value="<?= htmlspecialchars($contact['contactos_id']) ?>"><?= htmlspecialchars($contact['contactos_nombres'] . ' ' . $contact['contactos_apellidos']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="add_tipo_evento"><?= $lang['label_event_type'] ?? 'Tipo de Evento:' ?></label>
                    <input type="text" id="add_tipo_evento" name="tipo_evento">
                </div>
                <div class="form-group">
                    <label for="add_descripcion"><?= $lang['label_description'] ?? 'Descripción:' ?></label>
                    <textarea id="add_descripcion" name="descripcion" rows="4"></textarea>
                </div>
                <div class="button-group">
                    <button type="button" class="btn btn-cancel" data-modal-close="addEventModal"><?= $lang['cancel_btn'] ?? 'Cancelar' ?></button>
                    <button type="submit" class="btn btn-success"><?= $lang['add_btn'] ?? 'Agregar' ?></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Event Modal -->
    <div id="editEventModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="editEventTitle" aria-hidden="true" tabindex="-1">
        <div class="modal-content">
            <span class="close-button" data-modal-close="editEventModal" aria-label="<?= $lang['close_modal_aria'] ?? 'Cerrar' ?>">&times;</span>
            <h2 id="editEventTitle"><?= $lang['edit_event_modal_title'] ?? 'Editar Evento' ?></h2>
            <form action="events.php" method="POST" id="editEventForm">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" id="edit_evento_id" name="evento_id">
                <div class="form-group">
                    <label for="edit_titulo"><?= $lang['label_title'] ?? 'Título:' ?></label>
                    <input type="text" id="edit_titulo" name="titulo" required>
                </div>
                <div class="form-group">
                    <label for="edit_num_invitados"><?= $lang['label_num_guests'] ?? 'Número de invitados:' ?></label>
                    <input type="number" id="edit_num_invitados" name="num_invitados" min="0">
                </div>
                <div class="form-group">
                    <label for="edit_fecha"><?= $lang['label_date'] ?? 'Fecha:' ?></label>
                    <input type="date" id="edit_fecha" name="fecha" required>
                </div>
                <div class="form-group">
                    <label for="edit_hora"><?= $lang['label_time'] ?? 'Hora:' ?></label>
                    <input type="time" id="edit_hora" name="hora" required>
                </div>
                <div class="form-group">
                    <label for="edit_zona_horaria"><?= $lang['label_timezone'] ?? 'Zona Horaria:' ?></label>
                    <input type="text" id="edit_zona_horaria" name="zona_horaria">
                </div>
                <div class="form-group">
                    <label><?= $lang['label_repetition'] ?? 'Repetición:' ?></label>
                    <input type="radio" id="edit_repeticion_si" name="repeticion" value="1"> <label for="edit_repeticion_si"><?= $lang['yes'] ?? 'SI' ?></label>
                    <input type="radio" id="edit_repeticion_no" name="repeticion" value="0"> <label for="edit_repeticion_no"><?= $lang['no'] ?? 'NO' ?></label>
                </div>
                <div class="form-group">
                    <label><?= $lang['label_reminder'] ?? 'Recordatorio:' ?></label>
                    <input type="radio" id="edit_recordatorio_si" name="recordatorio" value="1"> <label for="edit_recordatorio_si"><?= $lang['yes'] ?? 'SI' ?></label>
                    <input type="radio" id="edit_recordatorio_no" name="recordatorio" value="0"> <label for="edit_recordatorio_no"><?= $lang['no'] ?? 'NO' ?></label>
                </div>
                <div class="form-group">
                    <label for="edit_ubicacion_id"><?= $lang['label_location'] ?? 'Ubicación:' ?></label>
                    <select id="edit_ubicacion_id" name="ubicacion_id" required>
                        <option value=""><?= $lang['select_location_option'] ?? 'Seleccionar Ubicación' ?></option>
                        <?php foreach ($locations_dropdown as $loc): ?>
                            <option value="<?= htmlspecialchars($loc['ubicacion_id']) ?>"><?= htmlspecialchars($loc['ubicacion_titulo']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit_contacto_id"><?= $lang['label_event_contact'] ?? 'Contacto de evento:' ?></label>
                    <select id="edit_contacto_id" name="contacto_id" required>
                        <option value=""><?= $lang['select_contact_option'] ?? 'Seleccionar Contacto' ?></option>
                        <?php foreach ($contacts_dropdown as $contact): ?>
                            <option value="<?= htmlspecialchars($contact['contactos_id']) ?>"><?= htmlspecialchars($contact['contactos_nombres'] . ' ' . $contact['contactos_apellidos']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit_tipo_evento"><?= $lang['label_event_type'] ?? 'Tipo de Evento:' ?></label>
                    <input type="text" id="edit_tipo_evento" name="tipo_evento">
                </div>
                <div class="form-group">
                    <label for="edit_descripcion"><?= $lang['label_description'] ?? 'Descripción:' ?></label>
                    <textarea id="edit_descripcion" name="descripcion" rows="4"></textarea>
                </div>
                <div class="button-group">
                    <button type="button" class="btn btn-cancel" data-modal-close="editEventModal"><?= $lang['cancel_btn'] ?? 'Cancelar' ?></button>
                    <button type="submit" class="btn btn-success"><?= $lang['save_changes_btn'] ?? 'Guardar Cambios' ?></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Generic Confirmation Modal (for Delete) -->
    <div id="confirmActionModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="confirmActionTitle" aria-hidden="true" tabindex="-1">
        <div class="modal-content">
            <span class="close-button" data-modal-close="confirmActionModal" aria-label="<?= $lang['close_modal_aria'] ?? 'Cerrar' ?>">&times;</span>
            <h2 id="confirmActionTitle"><?= $lang['confirm_action_title'] ?? 'Confirmar Acción' ?></h2>
            <p id="confirmActionMessage" class="text-center"></p>
            <div class="button-group confirm-buttons">
                <button type="button" class="btn btn-cancel" id="cancelActionButton" data-modal-close="confirmActionModal"><?= $lang['cancel_btn'] ?? 'Cancelar' ?></button>
                <button type="button" class="btn btn-danger" id="confirmActionButton"><?= $lang['confirm_btn'] ?? 'Confirmar' ?></button>
            </div>
        </div>
    </div>

    <!-- View Event Modal (re-used from ver_events.php, but now included here for completeness) -->
    <div id="viewEventModal" class="modal" role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="modalTitle">
        <div class="modal-content" role="document" tabindex="-1">
            <button class="close-button" data-modal-close="viewEventModal" aria-label="<?= htmlspecialchars($lang['close_modal_aria'] ?? 'Close modal') ?>">&times;</button>
            <h2 id="modalTitle"><?= htmlspecialchars($lang['modal_event_details_title'] ?? 'Event Details') ?>: <span id="detail_titulo"></span></h2>
            <div class="modal-details">
                <p><strong><?= htmlspecialchars($lang['table_header_id'] ?? 'ID') ?>:</strong> <span id="detail_id"></span></p>
                <p><strong><?= htmlspecialchars($lang['label_num_guests'] ?? 'Guests') ?>:</strong> <span id="detail_invitados"></span></p>
                <p><strong><?= htmlspecialchars($lang['table_header_date'] ?? 'Date') ?>:</strong> <span id="detail_fecha"></span></p>
                <p><strong><?= htmlspecialchars($lang['table_header_time'] ?? 'Time') ?>:</strong> <span id="detail_hora"></span></p>
                <p><strong><?= htmlspecialchars($lang['label_timezone'] ?? 'Timezone') ?>:</strong> <span id="detail_zona_horaria"></span></p>
                <p><strong><?= htmlspecialchars($lang['label_repetition'] ?? 'Repetition') ?>:</strong> <span id="detail_repeticion"></span></p>
                <p><strong><?= htmlspecialchars($lang['label_reminder'] ?? 'Reminder') ?>:</strong> <span id="detail_recordatorio"></span></p>
                <p><strong><?= htmlspecialchars($lang['table_header_location'] ?? 'Location') ?>:</strong> <span id="detail_ubicacion"></span></p>
                <p><strong><?= htmlspecialchars($lang['table_header_contact'] ?? 'Contact') ?>:</strong> <span id="detail_contacto"></span></p>
                <p><strong><?= htmlspecialchars($lang['label_event_type'] ?? 'Type') ?>:</strong> <span id="detail_tipo"></span></p>
                <p><strong><?= htmlspecialchars($lang['label_description'] ?? 'Description') ?>:</strong> <span id="detail_descripcion"></span></p>
            </div>
            <div class="button-group">
                <button type="button" class="btn btn-cancel" data-modal-close="viewEventModal"><?= htmlspecialchars($lang['cancel_btn'] ?? 'Close') ?></button>
            </div>
        </div>
    </div>

</main>

<!-- No <script> block here anymore, all JS is in script.js -->

<?php include __DIR__ . '/../includes/footer.php'; ?>