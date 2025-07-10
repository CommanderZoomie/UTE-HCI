<?php
// platform/contacts.php
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

// --- Handle Add/Edit Contact (POST request) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    $nombres = $_POST['nombres'] ?? '';
    $apellidos = $_POST['apellidos'] ?? '';
    $institucion = $_POST['institucion'] ?? '';
    $pais = $_POST['pais'] ?? '';
    $ci = $_POST['ci'] ?? null;
    $telefono = $_POST['telefono'] ?? null;
    $correo = $_POST['correo'] ?? '';
    $foto_path = ''; // For file upload

    // Handle photo upload
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/contact_photos/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Create directory if it doesn't exist
        }
        $fileName = uniqid() . '_' . basename($_FILES['foto']['name']);
        $targetFilePath = $uploadDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        // Allow certain file formats
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFilePath)) {
                $foto_path = 'uploads/contact_photos/' . $fileName;
            } else {
                $message = 'Error al subir la foto.';
                $message_type = 'danger';
            }
        } else {
            $message = 'Solo se permiten archivos JPG, JPEG, PNG y GIF para la foto.';
            $message_type = 'danger';
        }
    }

    // Basic validation
    if (empty($nombres) || empty($apellidos) || empty($correo)) {
        $message = 'Error: Nombres, Apellidos y Correo son campos obligatorios.';
        $message_type = 'danger';
    } else if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $message = 'Error: Formato de correo electrónico inválido.';
        $message_type = 'danger';
    } else {
        if ($action === 'add') {
            try {
                $stmt = $pdo->prepare("INSERT INTO contacts (contactos_nombres, contactos_apellidos, contactos_institucion, contactos_pais, contactos_ci, contactos_telefono, contactos_correo, contactos_foto) VALUES (:nombres, :apellidos, :institucion, :pais, :ci, :telefono, :correo, :foto)");
                $stmt->execute([
                    'nombres' => $nombres,
                    'apellidos' => $apellidos,
                    'institucion' => $institucion,
                    'pais' => $pais,
                    'ci' => $ci,
                    'telefono' => $telefono,
                    'correo' => $correo,
                    'foto' => $foto_path
                ]);
                $message = 'Contacto agregado exitosamente.';
                $message_type = 'success';
            } catch (PDOException $e) {
                $message = 'Error al agregar contacto: ' . $e->getMessage();
                $message_type = 'danger';
            }
        } elseif ($action === 'edit') {
            $contactos_id = $_POST['contactos_id'] ?? null;
            if ($contactos_id) {
                try {
                    $stmt = $pdo->prepare("UPDATE contacts SET contactos_nombres = :contactos_nombres, contactos_apellidos = :contactos_apellidos, contactos_institucion = :contactos_institucion, contactos_pais = :contactos_pais, contactos_ci = :contactos_ci, contactos_telefono = :contactos_telefono, contactos_correo = :contactos_correo, contactos_foto = :contactos_foto WHERE contactos_id = :contactos_id");
                    $stmt->execute([
                        'contactos_nombres' => $nombres,
                        'contactos_apellidos' => $apellidos,
                        'contactos_institucion' => $institucion,
                        'contactos_pais' => $pais,
                        'contactos_ci' => $ci,
                        'contactos_telefono' => $telefono,
                        'contactos_correo' => $correo,
                        'contactos_foto' => $foto_path,
                        'contactos_id' => $contactos_id
                    ]);
                    $message = 'Contacto actualizado exitosamente.';
                    $message_type = 'success';
                } catch (PDOException $e) {
                    $message = 'Error al actualizar contacto: ' . $e->getMessage();
                    $message_type = 'danger';
                }
            } else {
                $message = 'Error: ID de contacto no especificado para edición.';
                $message_type = 'danger';
            }
        }
    }
}

// --- Handle Delete Contact (GET request with ID) ---
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $contactos_id = $_GET['id'];
    try {
        // Optional: Delete the actual photo file from the server
        $stmt_photo = $pdo->prepare("SELECT contactos_foto FROM contacts WHERE contactos_id = :id");
        $stmt_photo->execute(['id' => $contactos_id]);
        $photo_to_delete = $stmt_photo->fetchColumn();
        if ($photo_to_delete && file_exists(__DIR__ . '/../' . $photo_to_delete)) {
            unlink(__DIR__ . '/../' . $photo_to_delete);
        }

        $stmt = $pdo->prepare("DELETE FROM contacts WHERE contactos_id = :contactos_id");
        $stmt->bindParam(':contactos_id', $contactos_id);
        $stmt->execute();
        $message = 'Contacto eliminado exitosamente.';
        $message_type = 'success';
        redirect(BASE_URL . 'platform/contacts.php?msg=' . urlencode($message) . '&type=' . $message_type);
    } catch (PDOException $e) {
        $message = 'Error al eliminar contacto: ' . $e->getMessage();
        $message_type = 'danger';
        redirect(BASE_URL . 'platform/contacts.php?msg=' . urlencode($message) . '&type=' . $message_type);
    }
}

// --- Fetch all contacts for listing ---
try {
    $stmt = $pdo->query("SELECT * FROM contacts ORDER BY contactos_apellidos, contactos_nombres");
    $contacts = $stmt->fetchAll();
} catch (PDOException $e) {
    $contacts = [];
    $message = 'Error al cargar contactos: ' . $e->getMessage();
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
    <h1>Gestión de Contactos</h1>

    <?php if ($message): ?>
        <div class="alert alert-<?= $message_type ?>"><?= $message ?></div>
    <?php endif; ?>

    <button class="btn btn-success" data-modal-target="addContactModal">Agregar Nuevo Contacto</button>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Institución Educativa</th>
                    <th>CI</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Foto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($contacts)): ?>
                    <tr><td colspan="9" class="text-center">No hay contactos registrados.</td></tr>
                <?php else: ?>
                    <?php foreach ($contacts as $contact): ?>
                        <tr>
                            <td><?= htmlspecialchars($contact['contactos_id']) ?></td>
                            <td><?= htmlspecialchars($contact['contactos_nombres']) ?></td>
                            <td><?= htmlspecialchars($contact['contactos_apellidos']) ?></td>
                            <td><?= htmlspecialchars($contact['contactos_institucion']) ?></td>
                            <td><?= htmlspecialchars($contact['contactos_ci']) ?></td>
                            <td><?= htmlspecialchars($contact['contactos_telefono']) ?></td>
                            <td><?= htmlspecialchars($contact['contactos_correo']) ?></td>
                            <td>
                                <?php if (!empty($contact['contactos_foto'])): ?>
                                    <img src="<?= BASE_URL . htmlspecialchars($contact['contactos_foto']) ?>" alt="Foto" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td class="action-icons">
                                <img src="<?= BASE_URL ?>images/view_icon.jpg" alt="Ver" title="Ver Detalles" onclick="alert('Ver detalles del contacto: <?= htmlspecialchars($contact['contactos_nombres'] . ' ' . $contact['contactos_apellidos']) ?>');"style="width: 30px; height: 30px;">
                                <img src="<?= BASE_URL ?>images/edit_icon.jpg" alt="Editar" title="Editar Contacto" onclick="openEditContactModal(<?= htmlspecialchars(json_encode($contact)) ?>);"style="width: 30px; height: 30px;">
                                <img src="<?= BASE_URL ?>images/delete_icon.jpg" alt="Eliminar" title="Eliminar Contacto" onclick="confirmDelete(<?= $contact['contactos_id'] ?>, '<?= htmlspecialchars($contact['contactos_nombres'] . ' ' . $contact['contactos_apellidos']) ?>');"style="width: 30px; height: 30px;">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<div id="addContactModal" class="modal">
    <div class="modal-content">
        <span class="close-button" data-modal-close="addContactModal">&times;</span>
        <h2>Nuevo Contacto</h2>
        <form action="contacts.php" method="POST" enctype="multipart/form-data"> <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label for="add_nombres">Nombres:</label>
                <input type="text" id="add_nombres" name="nombres" required>
            </div>
            <div class="form-group">
                <label for="add_apellidos">Apellidos:</label>
                <input type="text" id="add_apellidos" name="apellidos" required>
            </div>
            <div class="form-group">
                <label for="add_institucion">Institución Educativa:</label>
                <input type="text" id="add_institucion" name="institucion">
            </div>
            <div class="form-group">
                <label for="add_pais">País:</label>
                <input type="text" id="add_pais" name="pais">
            </div>
            <div class="form-group">
                <label for="add_ci">CI (Cédula de Identidad):</label>
                <input type="number" id="add_ci" name="ci">
            </div>
            <div class="form-group">
                <label for="add_telefono">Número Telefónico:</label>
                <input type="number" id="add_telefono" name="telefono">
            </div>
            <div class="form-group">
                <label for="add_correo">Correo Electrónico:</label>
                <input type="email" id="add_correo" name="correo" required>
            </div>
            <div class="form-group">
                <label for="add_foto">Foto:</label>
                <input type="file" id="add_foto" name="foto" accept="image/*">
            </div>
            <div class="button-group">
                <button type="button" class="btn btn-cancel" data-modal-close="addContactModal">Cancelar</button>
                <button type="submit" class="btn btn-success">Agregar</button>
            </div>
        </form>
    </div>
</div>

<div id="editContactModal" class="modal">
    <div class="modal-content">
        <span class="close-button" data-modal-close="editContactModal">&times;</span>
        <h2>Editar Contacto</h2>
        <form action="contacts.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" id="edit_contactos_id" name="contactos_id">
            <div class="form-group">
                <label for="edit_nombres">Nombres:</label>
                <input type="text" id="edit_nombres" name="nombres" required>
            </div>
            <div class="form-group">
                <label for="edit_apellidos">Apellidos:</label>
                <input type="text" id="edit_apellidos" name="apellidos" required>
            </div>
            <div class="form-group">
                <label for="edit_institucion">Institución Educativa:</label>
                <input type="text" id="edit_institucion" name="institucion">
            </div>
            <div class="form-group">
                <label for="edit_pais">País:</label>
                <input type="text" id="edit_pais" name="pais">
            </div>
            <div class="form-group">
                <label for="edit_ci">CI (Cédula de Identidad):</label>
                <input type="number" id="edit_ci" name="ci">
            </div>
            <div class="form-group">
                <label for="edit_telefono">Número Telefónico:</label>
                <input type="number" id="edit_telefono" name="telefono">
            </div>
            <div class="form-group">
                <label for="edit_correo">Correo Electrónico:</label>
                <input type="email" id="edit_correo" name="correo" required>
            </div>
            <div class="form-group">
                <label for="edit_foto">Foto (dejar en blanco para mantener la actual):</label>
                <input type="file" id="edit_foto" name="foto" accept="image/*">
                <input type="hidden" id="current_foto_path" name="current_foto_path"> <div id="current_foto_preview" style="margin-top: 10px;"></div>
            </div>
            <div class="button-group">
                <button type="button" class="btn btn-cancel" data-modal-close="editContactModal">Cancelar</button>
                <button type="submit" class="btn btn-success">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
// JavaScript for handling edit modal population and delete confirmation
function openEditContactModal(contactData) {
    document.getElementById('edit_contactos_id').value = contactData.contactos_id;
    document.getElementById('edit_nombres').value = contactData.contactos_nombres;
    document.getElementById('edit_apellidos').value = contactData.contactos_apellidos;
    document.getElementById('edit_institucion').value = contactData.contactos_institucion;
    document.getElementById('edit_pais').value = contactData.contactos_pais;
    document.getElementById('edit_ci').value = contactData.contactos_ci;
    document.getElementById('edit_telefono').value = contactData.contactos_telefono;
    document.getElementById('edit_correo').value = contactData.contactos_correo;
    document.getElementById('current_foto_path').value = contactData.contactos_foto; // Store current path

    const fotoPreviewDiv = document.getElementById('current_foto_preview');
    fotoPreviewDiv.innerHTML = ''; // Clear previous preview
    if (contactData.contactos_foto) {
        const img = document.createElement('img');
        img.src = '<?= BASE_URL ?>' + contactData.contactos_foto; // Adjust path as needed
        img.alt = 'Foto Actual';
        img.style.width = '100px';
        img.style.height = '100px';
        img.style.objectFit = 'cover';
        img.style.borderRadius = '5px';
        fotoPreviewDiv.appendChild(img);
        fotoPreviewDiv.innerHTML += '<p style="font-size:0.8em; margin-top:5px;">Foto Actual</p>';
    } else {
        fotoPreviewDiv.innerHTML = '<p style="font-size:0.8em; margin-top:5px;">No hay foto actual.</p>';
    }

    openModal('editContactModal');
}

function confirmDelete(id, name) {
    if (confirm('¿Estás seguro de que quieres eliminar el contacto "' + name + '" (ID: ' + id + ')? Esto puede afectar eventos asociados.')) {
        window.location.href = 'contacts.php?action=delete&id=' + id;
    }
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>