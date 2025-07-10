<?php
// platform/locations.php
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
        $message = 'Error: Campos obligatorios de ubicación incompletos.';
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
                $message = 'Ubicación agregada exitosamente.';
                $message_type = 'success';
            } catch (PDOException $e) {
                $message = 'Error al agregar ubicación: ' . $e->getMessage();
                $message_type = 'danger';
            }
        } elseif ($action === 'edit') {
            $ubicacion_id = $_POST['ubicacion_id'] ?? null;
            if ($ubicacion_id) {
                try {
                    $stmt = $pdo->prepare("UPDATE locations SET ubicacion_titulo = :ubicacion_titulo, ubicacion_direccion = :ubicacion_direccion, ubicacion_ciudad = :ubicacion_ciudad, ubicacion_pais = :ubicacion_pais, ubicacion_link = :ubicacion_link WHERE ubicacion_id = :ubicacion_id");
                    $stmt->execute([
                        'ubicacion_titulo' => $ubicacion_titulo,
                        'ubicacion_direccion' => $ubicacion_direccion,
                        'ubicacion_ciudad' => $ubicacion_ciudad,
                        'ubicacion_pais' => $ubicacion_pais,
                        'ubicacion_link' => $ubicacion_link,
                        'ubicacion_id' => $ubicacion_id
                    ]);
                    $message = 'Ubicación actualizada exitosamente.';
                    $message_type = 'success';
                } catch (PDOException $e) {
                    $message = 'Error al actualizar ubicación: ' . $e->getMessage();
                    $message_type = 'danger';
                }
            } else {
                $message = 'Error: ID de ubicación no especificado para edición.';
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
        $message = 'Ubicación eliminada exitosamente.';
        $message_type = 'success';
        redirect(BASE_URL . 'platform/locations.php?msg=' . urlencode($message) . '&type=' . $message_type);
    } catch (PDOException $e) {
        $message = 'Error al eliminar ubicación: ' . $e->getMessage();
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
    $message = 'Error al cargar ubicaciones: ' . $e->getMessage();
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
    <h1>Gestión de Ubicaciones</h1>

    <?php if ($message): ?>
        <div class="alert alert-<?= htmlspecialchars($message_type) ?>"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <button class="btn btn-success" data-modal-target="addLocationModal">Agregar Nueva Ubicación</button>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Dirección</th>
                    <th>Ciudad</th>
                    <th>País</th>
                    <th>Link de Google Maps</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($locations)): ?>
                    <tr><td colspan="7" class="text-center">No hay ubicaciones registradas.</td></tr>
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
                                    <a href="<?= htmlspecialchars($location['ubicacion_link']) ?>" target="_blank">Ver Mapa</a>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td class="action-icons">
                                <img src="<?= BASE_URL ?>images/view_icon.jpg" alt="Ver" title="Ver Detalles" onclick="alert('Ver detalles de la ubicación: <?= htmlspecialchars($location['ubicacion_titulo']) ?>');" style="width: 30px; height: 30px;">
                                <img src="<?= BASE_URL ?>images/edit_icon.jpg" alt="Editar" title="Editar Ubicación" onclick='openEditLocationModal(<?= json_encode($location, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);' style="width: 30px; height: 30px;">
                                <img src="<?= BASE_URL ?>images/delete_icon.jpg" alt="Eliminar" title="Eliminar Ubicación" onclick="confirmDelete(<?= $location['ubicacion_id'] ?>, '<?= htmlspecialchars($location['ubicacion_titulo']) ?>');" style="width: 30px; height: 30px;">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<div id="addLocationModal" class="modal">
    <div class="modal-content">
        <span class="close-button" data-modal-close="addLocationModal">&times;</span>
        <h2>Nueva Ubicación</h2>
        <form action="locations.php" method="POST">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label for="add_titulo">Título:</label>
                <input type="text" id="add_titulo" name="titulo" required>
            </div>
            <div class="form-group">
                <label for="add_direccion">Dirección:</label>
                <input type="text" id="add_direccion" name="direccion" required>
            </div>
            <div class="form-group">
                <label for="add_ciudad">Ciudad:</label>
                <input type="text" id="add_ciudad" name="ciudad" required>
            </div>
            <div class="form-group">
                <label for="add_pais">País:</label>
                <input type="text" id="add_pais" name="pais" required>
            </div>
            <div class="form-group">
                <label for="add_link">Link de Google Maps:</label>
                <input type="url" id="add_link" name="link">
            </div>
            <div class="button-group">
                <button type="button" class="btn btn-cancel" data-modal-close="addLocationModal">Cancelar</button>
                <button type="submit" class="btn btn-success">Agregar</button>
            </div>
        </form>
    </div>
</div>

<div id="editLocationModal" class="modal">
    <div class="modal-content">
        <span class="close-button" data-modal-close="editLocationModal">&times;</span>
        <h2>Editar Ubicación</h2>
        <form action="locations.php" method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" id="edit_ubicacion_id" name="ubicacion_id">
            <div class="form-group">
                <label for="edit_titulo">Título:</label>
                <input type="text" id="edit_titulo" name="titulo" required>
            </div>
            <div class="form-group">
                <label for="edit_direccion">Dirección:</label>
                <input type="text" id="edit_direccion" name="direccion" required>
            </div>
            <div class="form-group">
                <label for="edit_ciudad">Ciudad:</label>
                <input type="text" id="edit_ciudad" name="ciudad" required>
            </div>
            <div class="form-group">
                <label for="edit_pais">País:</label>
                <input type="text" id="edit_pais" name="pais" required>
            </div>
            <div class="form-group">
                <label for="edit_link">Link de Google Maps:</label>
                <input type="url" id="edit_link" name="link">
            </div>
            <div class="button-group">
                <button type="button" class="btn btn-cancel" data-modal-close="editLocationModal">Cancelar</button>
                <button type="submit" class="btn btn-success">Guardar Cambios</button>
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
    if (confirm('¿Estás seguro de que quieres eliminar la ubicación "' + title + '" (ID: ' + id + ')? Esto puede afectar eventos asociados.')) {
        window.location.href = 'locations.php?action=delete&id=' + id;
    }
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>