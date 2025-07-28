<?php
// platform/locations.php
require_once __DIR__ . '/../settings/config.php';
require_once __DIR__ . '/../settings/db.php';
require_once __DIR__ . '/../security/auth.php';
require_once __DIR__ . '/../settings/lang.php'; // <<<--- INCLUDE THE LANGUAGE LOADER HERE

$pdo = getDB(); 

// Protect this page: redirect if not logged in
if (!isAuthenticated()) {
    redirect(BASE_URL . 'security/login.php'); // Corrected redirect path for login
    exit;
}

$message = '';
$message_type = '';

// --- Handle Add/Edit Location (POST request) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    $titulo = $_POST['titulo'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $ciudad = $_POST['ciudad'] ?? '';
    $pais = $_POST['pais'] ?? '';
    $link = $_POST['link'] ?? '';

    // Basic validation
    if (empty($titulo) || empty($direccion) || empty($ciudad) || empty($pais)) {
        $message = $lang['error_location_required_fields'] ?? 'Error: Campos obligatorios de ubicación incompletos.';
        $message_type = 'danger';
    } else {
        if ($action === 'add') {
            try {
                $stmt = $pdo->prepare("INSERT INTO locations (ubicacion_titulo, ubicacion_direccion, ubicacion_ciudad, ubicacion_pais, ubicacion_link) VALUES (:titulo, :direccion, :ciudad, :pais, :link)");
                $stmt->execute([
                    'titulo' => $titulo,
                    'direccion' => $direccion,
                    'ciudad' => $ciudad,
                    'pais' => $pais,
                    'link' => $link
                ]);
                $message = $lang['location_added_success'] ?? 'Ubicación agregada exitosamente.';
                $message_type = 'success';
            } catch (PDOException $e) {
                $message = ($lang['error_add_location'] ?? 'Error al agregar ubicación: ') . $e->getMessage();
                $message_type = 'danger';
            }
        } elseif ($action === 'edit') {
            $ubicacion_id = $_POST['ubicacion_id'] ?? null;
            if ($ubicacion_id) {
                try {
                    $stmt = $pdo->prepare("UPDATE locations SET ubicacion_titulo = :ubicacion_titulo, ubicacion_direccion = :ubicacion_direccion, ubicacion_ciudad = :ubicacion_ciudad, ubicacion_pais = :ubicacion_pais, ubicacion_link = :ubicacion_link WHERE ubicacion_id = :ubicacion_id");
                    $stmt->execute([
                        'ubicacion_titulo' => $titulo,
                        'ubicacion_direccion' => $direccion,
                        'ubicacion_ciudad' => $ciudad,
                        'ubicacion_pais' => $pais,
                        'ubicacion_link' => $link,
                        'ubicacion_id' => $ubicacion_id
                    ]);
                    $message = $lang['location_updated_success'] ?? 'Ubicación actualizada exitosamente.';
                    $message_type = 'success';
                } catch (PDOException $e) {
                    $message = ($lang['error_update_location'] ?? 'Error al actualizar ubicación: ') . $e->getMessage();
                    $message_type = 'danger';
                }
            } else {
                $message = $lang['error_no_location_id'] ?? 'Error: ID de ubicación no especificado para edición.';
                $message_type = 'danger';
            }
        }
    }
}

// --- Handle Delete Location (GET request with ID) ---
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $ubicacion_id = $_GET['id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM locations WHERE ubicacion_id = :ubicacion_id");
        $stmt->bindParam(':ubicacion_id', $ubicacion_id);
        $stmt->execute();
        $message = $lang['location_deleted_success'] ?? 'Ubicación eliminada exitosamente.';
        $message_type = 'success';
        redirect(BASE_URL . 'platform/locations.php?msg=' . urlencode($message) . '&type=' . $message_type);
    } catch (PDOException $e) {
        $message = ($lang['error_delete_location'] ?? 'Error al eliminar ubicación: ') . $e->getMessage();
        $message_type = 'danger';
        redirect(BASE_URL . 'platform/locations.php?msg=' . urlencode($message) . '&type=' . $message_type);
    }
}

// --- Fetch all locations for listing ---
try {
    $stmt = $pdo->query("SELECT * FROM locations ORDER BY ubicacion_titulo");
    $locations = $stmt->fetchAll();
} catch (PDOException $e) {
    $locations = [];
    $message = ($lang['error_load_locations'] ?? 'Error al cargar ubicaciones: ') . $e->getMessage();
    $message_type = 'danger';
}

// Handle messages passed via URL after redirect
if (isset($_GET['msg']) && isset($_GET['type'])) {
    $message = htmlspecialchars($_GET['msg']);
    $message_type = htmlspecialchars($_GET['type']);
}

include __DIR__ . '/../includes/header.php'; // Includes platform navigation
?>

<section class="management-section">
    <h1><?= $lang['manage_locations_title'] ?? 'Gestión de Ubicaciones' ?></h1>

    <?php if ($message): ?>
        <div class="alert alert-<?= htmlspecialchars($message_type) ?>"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <button class="btn btn-success" data-modal-target="addLocationModal"><?= $lang['add_new_location_btn'] ?? 'Agregar Nueva Ubicación' ?></button>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th><?= $lang['table_header_id'] ?? 'ID' ?></th>
                    <th><?= $lang['table_header_title'] ?? 'Título' ?></th>
                    <th><?= $lang['table_header_address'] ?? 'Dirección' ?></th>
                    <th><?= $lang['table_header_city'] ?? 'Ciudad' ?></th>
                    <th><?= $lang['table_header_country'] ?? 'País' ?></th>
                    <th><?= $lang['table_header_gmaps_link'] ?? 'Link de Google Maps' ?></th>
                    <th><?= $lang['table_header_actions'] ?? 'Acciones' ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($locations)): ?>
                    <tr><td colspan="7" class="text-center"><?= $lang['no_locations_registered'] ?? 'No hay ubicaciones registradas.' ?></td></tr>
                <?php else: ?>
                    <?php foreach ($locations as $location): ?>
                        <tr>
                            <td><?= htmlspecialchars($location['ubicacion_id']) ?></td>
                            <td><?= htmlspecialchars($location['ubicacion_titulo']) ?></td>
                            <td><?= htmlspecialchars($location['ubicacion_direccion']) ?></td>
                            <td><?= htmlspecialchars($location['ubicacion_ciudad']) ?></td>
                            <td><?= htmlspecialchars($location['ubicacion_pais']) ?></td>
                            <td>
                                <?php if (!empty($location['ubicacion_link'])): ?>
                                    <a href="<?= htmlspecialchars($location['ubicacion_link']) ?>" target="_blank"><?= $lang['view_map_link'] ?? 'Ver Mapa' ?></a>
                                <?php else: ?>
                                    <?= $lang['not_applicable_abbr'] ?? 'N/A' ?>
                                <?php endif; ?>
                            </td>
                            <td class="action-icons">
                                <img src="<?= BASE_URL ?>images/view_icon2.jpg" alt="<?= $lang['view_details_alt'] ?? 'Ver' ?>" title="<?= $lang['view_details_title'] ?? 'Ver Detalles' ?>" onclick="alert('<?= $lang['view_location_details_alert'] ?? 'Ver detalles de la ubicación:' ?> <?= addslashes(htmlspecialchars($location['ubicacion_titulo'])) ?>');" style="width: 30px; height: 30px; cursor:pointer;">
                                <img src="<?= BASE_URL ?>images/edit_icon.jpg" alt="<?= $lang['edit_location_alt'] ?? 'Editar' ?>" title="<?= $lang['edit_location_title'] ?? 'Editar Ubicación' ?>" onclick='openEditLocationModal(<?= json_encode($location, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);' style="width: 30px; height: 30px; cursor:pointer;">
                                <img src="<?= BASE_URL ?>images/delete_icon.jpg" alt="<?= $lang['delete_location_alt'] ?? 'Eliminar' ?>" title="<?= $lang['delete_location_title'] ?? 'Eliminar Ubicación' ?>" onclick="confirmDelete(<?= $location['ubicacion_id'] ?>, '<?= addslashes(htmlspecialchars($location['ubicacion_titulo'])) ?>');" style="width: 30px; height: 30px; cursor:pointer;">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<div id="addLocationModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="addLocationTitle" aria-hidden="true" tabindex="-1">
    <div class="modal-content">
        <span class="close-button" data-modal-close="addLocationModal" aria-label="<?= $lang['close_modal_aria'] ?? 'Cerrar' ?>">&times;</span>
        <h2 id="addLocationTitle"><?= $lang['add_location_modal_title'] ?? 'Nueva Ubicación' ?></h2>
        <form action="locations.php" method="POST">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label for="add_titulo"><?= $lang['label_title'] ?? 'Título:' ?></label>
                <input type="text" id="add_titulo" name="titulo" required>
            </div>
            <div class="form-group">
                <label for="add_direccion"><?= $lang['label_address'] ?? 'Dirección:' ?></label>
                <input type="text" id="add_direccion" name="direccion" required>
            </div>
            <div class="form-group">
                <label for="add_ciudad"><?= $lang['label_city'] ?? 'Ciudad:' ?></label>
                <input type="text" id="add_ciudad" name="ciudad" required>
            </div>
            <div class="form-group">
                <label for="add_pais"><?= $lang['label_country'] ?? 'País:' ?></label>
                <input type="text" id="add_pais" name="pais" required>
            </div>
            <div class="form-group">
                <label for="add_link"><?= $lang['label_gmaps_link'] ?? 'Link de Google Maps:' ?></label>
                <input type="url" id="add_link" name="link">
            </div>
            <div class="button-group">
                <button type="button" class="btn btn-cancel" data-modal-close="addLocationModal"><?= $lang['cancel_btn'] ?? 'Cancelar' ?></button>
                <button type="submit" class="btn btn-success"><?= $lang['add_btn'] ?? 'Agregar' ?></button>
            </div>
        </form>
    </div>
</div>

<div id="editLocationModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="editLocationTitle" aria-hidden="true" tabindex="-1">
    <div class="modal-content">
        <span class="close-button" data-modal-close="editLocationModal" aria-label="<?= $lang['close_modal_aria'] ?? 'Cerrar' ?>">&times;</span>
        <h2 id="editLocationTitle"><?= $lang['edit_location_modal_title'] ?? 'Editar Ubicación' ?></h2>
        <form action="locations.php" method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" id="edit_ubicacion_id" name="ubicacion_id">
            <div class="form-group">
                <label for="edit_titulo"><?= $lang['label_title'] ?? 'Título:' ?></label>
                <input type="text" id="edit_titulo" name="titulo" required>
            </div>
            <div class="form-group">
                <label for="edit_direccion"><?= $lang['label_address'] ?? 'Dirección:' ?></label>
                <input type="text" id="edit_direccion" name="direccion" required>
            </div>
            <div class="form-group">
                <label for="edit_ciudad"><?= $lang['label_city'] ?? 'Ciudad:' ?></label>
                <input type="text" id="edit_ciudad" name="ciudad" required>
            </div>
            <div class="form-group">
                <label for="edit_pais"><?= $lang['label_country'] ?? 'País:' ?></label>
                <input type="text" id="edit_pais" name="pais" required>
            </div>
            <div class="form-group">
                <label for="edit_link"><?= $lang['label_gmaps_link'] ?? 'Link de Google Maps:' ?></label>
                <input type="url" id="edit_link" name="link">
            </div>
            <div class="button-group">
                <button type="button" class="btn btn-cancel" data-modal-close="editLocationModal"><?= $lang['cancel_btn'] ?? 'Cancelar' ?></button>
                <button type="submit" class="btn btn-success"><?= $lang['save_changes_btn'] ?? 'Guardar Cambios' ?></button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditLocationModal(locationData) {
    document.getElementById('edit_ubicacion_id').value = locationData.ubicacion_id;
    document.getElementById('edit_titulo').value = locationData.ubicacion_titulo;
    document.getElementById('edit_direccion').value = locationData.ubicacion_direccion;
    document.getElementById('edit_ciudad').value = locationData.ubicacion_ciudad;
    document.getElementById('edit_pais').value = locationData.ubicacion_pais;
    document.getElementById('edit_link').value = locationData.ubicacion_link;
    openModal('editLocationModal');
}

function confirmDelete(id, title) {
    // JavaScript uses backticks for template literals for easy interpolation
    if (confirm(`<?= $lang['confirm_delete_location_prompt'] ?? '¿Estás seguro de que quieres eliminar la ubicación "' ?>${title}" (ID: ${id})? <?= $lang['confirm_delete_location_warning'] ?? 'Esto puede afectar eventos asociados.' ?>`)) {
        window.location.href = 'locations.php?action=delete&id=' + id;
    }
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>