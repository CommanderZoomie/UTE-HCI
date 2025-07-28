<?php
// main/ver_events.php
require_once __DIR__ . '/../settings/config.php';
require_once __DIR__ . '/../settings/db.php';
require_once __DIR__ . '/../settings/lang.php'; // Centralized language loader
require_once __DIR__ . '/../security/auth.php'; // Ensure session_start() is called here or in config.php

$pdo = getDB();

$message = null;
$message_type = null;

if (isset($_GET['msg']) && isset($_GET['type'])) {
    $message = htmlspecialchars($_GET['msg']);
    $message_type = htmlspecialchars($_GET['type']);
}

try {
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
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $events = [];
    $message = ($lang['error_loading_events'] ?? 'Error loading events') . ': ' . $e->getMessage();
    $message_type = 'danger';
    error_log('Event fetch error: ' . $e->getMessage());
}

include __DIR__ . '/../includes/header.php';
?>

<main class="content-wrapper"> <!-- Added <main> tag here -->
    <section class="management-section container"> <!-- Added .container here -->
        <h1><?= htmlspecialchars($lang['view_events_title'] ?? 'View Events') ?></h1>

        <?php if ($message): ?>
            <div class="alert alert-<?= htmlspecialchars($message_type) ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th><?= htmlspecialchars($lang['table_header_id'] ?? 'ID') ?></th>
                        <th><?= htmlspecialchars($lang['table_header_title'] ?? 'Title') ?></th>
                        <th><?= htmlspecialchars($lang['table_header_date'] ?? 'Date') ?></th>
                        <th><?= htmlspecialchars($lang['table_header_time'] ?? 'Time') ?></th>
                        <th><?= htmlspecialchars($lang['table_header_location'] ?? 'Location') ?></th>
                        <th><?= htmlspecialchars($lang['table_header_contact'] ?? 'Contact') ?></th>
                        <th><?= htmlspecialchars($lang['table_header_actions'] ?? 'Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($events)): ?>
                        <tr><td colspan="7" class="text-center"><?= htmlspecialchars($lang['no_events_message'] ?? 'No events found.') ?></td></tr>
                    <?php else: ?>
                        <?php foreach ($events as $event): ?>
                            <tr>
                                <td><?= htmlspecialchars($event['evento_id']) ?></td>
                                <td><?= htmlspecialchars($event['evento_titulo']) ?></td>
                                <td><?= htmlspecialchars(date($lang['date_format'] ?? 'd M Y', strtotime($event['evento_fecha']))) ?></td>
                                <td><?= htmlspecialchars(date($lang['time_format'] ?? 'H:i', strtotime($event['evento_hora']))) ?></td>
                                <td><?= htmlspecialchars($event['ubicacion'] ?: ($lang['not_applicable_abbr'] ?? 'N/A')) ?></td>
                                <td><?= htmlspecialchars(trim(($event['contacto_nombre'] ?? '') . ' ' . ($event['contacto_apellido'] ?? '')) ?: ($lang['not_applicable_abbr'] ?? 'N/A')) ?></td>
                                <td class="action-icons">
                                    <button type="button" class="btn btn-sm btn-info"
                                        onclick='showEventDetails(<?= json_encode($event, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'>
                                        <img src="<?= BASE_URL ?>images/view_icon2.jpg" alt="<?= htmlspecialchars($lang['view_details_alt'] ?? 'View Details') ?>" title="<?= htmlspecialchars($lang['view_details_title'] ?? 'View Details') ?>" style="width: 30px; height: 30px;">
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

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
</main> <!-- Closed <main> tag here -->

<script>
function showEventDetails(eventData) {
    const yes = "<?= addslashes($lang['yes'] ?? 'Yes') ?>";
    const no = "<?= addslashes($lang['no'] ?? 'No') ?>";
    const notApplicable = "<?= addslashes($lang['not_applicable_abbr'] ?? 'N/A') ?>";
    const langCode = "<?= addslashes($lang_code ?? 'en-US') ?>"; // Use $lang_code from lang.php

    document.getElementById('detail_titulo').innerText = eventData.evento_titulo || notApplicable;
    document.getElementById('detail_id').innerText = eventData.evento_id || notApplicable;
    document.getElementById('detail_invitados').innerText = eventData.evento_invitados || '0';

    if(eventData.evento_fecha) {
        let d = new Date(eventData.evento_fecha + 'T' + eventData.evento_hora); // Combine date and time for correct Date object
        document.getElementById('detail_fecha').innerText = d.toLocaleDateString(langCode, { day: '2-digit', month: 'short', year: 'numeric' });
    } else {
        document.getElementById('detail_fecha').innerText = notApplicable;
    }

    document.getElementById('detail_hora').innerText = eventData.evento_hora ? eventData.evento_hora.substring(0,5) : notApplicable;
    document.getElementById('detail_zona_horaria').innerText = eventData.evento_zona_horaria || notApplicable;
    document.getElementById('detail_repeticion').innerText = (eventData.evento_repeticion == 1) ? yes : no;
    document.getElementById('detail_recordatorio').innerText = (eventData.evento_recordatorio == 1) ? yes : no;
    document.getElementById('detail_ubicacion').innerText = eventData.ubicacion || notApplicable;

    let contacto = ((eventData.contacto_nombre || '') + ' ' + (eventData.contacto_apellido || '')).trim();
    document.getElementById('detail_contacto').innerText = contacto || notApplicable;

    document.getElementById('detail_tipo').innerText = eventData.evento_tipo || notApplicable;
    document.getElementById('detail_descripcion').innerText = eventData.evento_descripcion || notApplicable;

    openModal('viewEventModal');
}

// openModal and closeModal functions are assumed to be in script.js or included globally
// If they are not, you would need to include them here or ensure script.js is loaded correctly.
// For now, assuming they are available.
// The provided openModal/closeModal functions from your previous input are correct.
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>