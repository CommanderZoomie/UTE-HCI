<?php
// platform/contacts.php
require_once __DIR__ . '/../settings/config.php';
require_once __DIR__ . '/../settings/db.php';
require_once __DIR__ . '/../security/auth.php';
require_once __DIR__ . '/../settings/lang.php'; // <<<--- INCLUDE THE LANGUAGE LOADER HERE

$pdo = getDB();

// Protect this page: redirect if not logged in
if (!isAuthenticated()) {
    redirect(BASE_URL . 'security/login.php');
    exit;
}

$message = '';
$message_type = '';

// --- Handle Add/Edit Contact (POST request) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    $nombres = trim($_POST['nombres'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $institucion = trim($_POST['institucion'] ?? '');
    $pais = trim($_POST['pais'] ?? '');
    $ci = $_POST['ci'] ?? null;
    $telefono = $_POST['telefono'] ?? null;
    $correo = trim($_POST['correo'] ?? '');
    $foto_path = ''; // Will hold new upload path if any

    // Basic validation
    if (empty($nombres) || empty($apellidos) || empty($correo)) {
        $message = $lang['error_required_fields'] ?? 'Error: Nombres, Apellidos y Correo son campos obligatorios.';
        $message_type = 'danger';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $message = $lang['error_invalid_email'] ?? 'Error: Formato de correo electrónico inválido.';
        $message_type = 'danger';
    } else {
        // Handle photo upload (if any)
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/contact_photos/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = uniqid() . '_' . basename($_FILES['foto']['name']);
            $targetFilePath = $uploadDir . $fileName;
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

            $allowTypes = ['jpg', 'png', 'jpeg', 'gif'];
            if (in_array($fileType, $allowTypes)) {
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFilePath)) {
                    $foto_path = 'uploads/contact_photos/' . $fileName;
                } else {
                    $message = $lang['error_photo_upload'] ?? 'Error al subir la foto.';
                    $message_type = 'danger';
                }
            } else {
                $message = $lang['error_photo_type'] ?? 'Solo se permiten archivos JPG, JPEG, PNG y GIF para la foto.';
                $message_type = 'danger';
            }
        }

        // Proceed only if no upload error
        if ($message_type !== 'danger') {
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
                    $message = $lang['contact_added_success'] ?? 'Contacto agregado exitosamente.';
                    $message_type = 'success';

                    redirect(BASE_URL . 'platform/contacts.php?msg=' . urlencode($message) . '&type=' . $message_type);
                    exit;
                } catch (PDOException $e) {
                    $message = ($lang['error_add_contact'] ?? 'Error al agregar contacto: ') . $e->getMessage();
                    $message_type = 'danger';
                }
            } elseif ($action === 'edit') {
                $contactos_id = $_POST['contactos_id'] ?? null;
                if ($contactos_id) {
                    try {
                        // If no new photo uploaded, keep existing photo path
                        if (empty($foto_path)) {
                            $foto_path = $_POST['current_foto_path'] ?? '';
                        }

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
                        $message = $lang['contact_updated_success'] ?? 'Contacto actualizado exitosamente.';
                        $message_type = 'success';

                        redirect(BASE_URL . 'platform/contacts.php?msg=' . urlencode($message) . '&type=' . $message_type);
                        exit;
                    } catch (PDOException $e) {
                        $message = ($lang['error_update_contact'] ?? 'Error al actualizar contacto: ') . $e->getMessage();
                        $message_type = 'danger';
                    }
                } else {
                    $message = $lang['error_no_contact_id'] ?? 'Error: ID de contacto no especificado para edición.';
                    $message_type = 'danger';
                }
            }
        }
    }
}

// --- Handle Delete Contact (GET request with ID) ---
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete') {
    $contactos_id = $_GET['id'];
    try {
        // Delete the actual photo file if exists
        $stmt_photo = $pdo->prepare("SELECT contactos_foto FROM contacts WHERE contactos_id = :id");
        $stmt_photo->execute(['id' => $contactos_id]);
        $photo_to_delete = $stmt_photo->fetchColumn();
        if ($photo_to_delete && file_exists(__DIR__ . '/../' . $photo_to_delete)) {
            unlink(__DIR__ . '/../' . $photo_to_delete);
        }

        $stmt = $pdo->prepare("DELETE FROM contacts WHERE contactos_id = :contactos_id");
        $stmt->bindParam(':contactos_id', $contactos_id);
        $stmt->execute();
        $message = $lang['contact_deleted_success'] ?? 'Contacto eliminado exitosamente.';
        $message_type = 'success';

        redirect(BASE_URL . 'platform/contacts.php?msg=' . urlencode($message) . '&type=' . $message_type);
        exit;
    } catch (PDOException $e) {
        $message = ($lang['error_delete_contact'] ?? 'Error al eliminar contacto: ') . $e->getMessage();
        $message_type = 'danger';

        redirect(BASE_URL . 'platform/contacts.php?msg=' . urlencode($message) . '&type=' . $message_type);
        exit;
    }
}

// --- Fetch all contacts for listing ---
try {
    $stmt = $pdo->query("SELECT * FROM contacts ORDER BY contactos_apellidos, contactos_nombres");
    $contacts = $stmt->fetchAll();
} catch (PDOException $e) {
    $contacts = [];
    $message = ($lang['error_load_contacts'] ?? 'Error al cargar contactos: ') . $e->getMessage();
    $message_type = 'danger';
}

// Handle messages passed via URL after redirect
if (isset($_GET['msg'], $_GET['type'])) {
    $message = htmlspecialchars($_GET['msg']);
    $message_type = htmlspecialchars($_GET['type']);
}

include __DIR__ . '/../includes/header.php'; // Includes platform navigation
?>

<section class="management-section">
    <h1><?= $lang['manage_contacts_title'] ?? 'Gestión de Contactos' ?></h1>

    <?php if ($message): ?>
        <div class="alert alert-<?= htmlspecialchars($message_type) ?>"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <button class="btn btn-success" data-modal-target="addContactModal"><?= $lang['add_new_contact_btn'] ?? 'Agregar Nuevo Contacto' ?></button>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th><?= $lang['table_header_id'] ?? 'ID' ?></th>
                    <th><?= $lang['table_header_first_names'] ?? 'Nombres' ?></th>
                    <th><?= $lang['table_header_last_names'] ?? 'Apellidos' ?></th>
                    <th><?= $lang['table_header_institution'] ?? 'Institución Educativa' ?></th>
                    <th><?= $lang['table_header_country'] ?? 'País' ?></th>
                    <th><?= $lang['table_header_id_card'] ?? 'CI' ?></th>
                    <th><?= $lang['table_header_phone'] ?? 'Teléfono' ?></th>
                    <th><?= $lang['table_header_email'] ?? 'Correo' ?></th>
                    <th><?= $lang['table_header_photo'] ?? 'Foto' ?></th>
                    <th><?= $lang['table_header_actions'] ?? 'Acciones' ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($contacts)): ?>
                    <tr><td colspan="10" class="text-center"><?= $lang['no_contacts_registered'] ?? 'No hay contactos registrados.' ?></td></tr>
                <?php else: ?>
                    <?php foreach ($contacts as $contact): ?>
                        <tr>
                            <td><?= htmlspecialchars($contact['contactos_id']) ?></td>
                            <td><?= htmlspecialchars($contact['contactos_nombres']) ?></td>
                            <td><?= htmlspecialchars($contact['contactos_apellidos']) ?></td>
                            <td><?= htmlspecialchars($contact['contactos_institucion']) ?></td>
                            <td><?= htmlspecialchars($contact['contactos_pais']) ?></td>
                            <td><?= htmlspecialchars($contact['contactos_ci']) ?></td>
                            <td><?= htmlspecialchars($contact['contactos_telefono']) ?></td>
                            <td><?= htmlspecialchars($contact['contactos_correo']) ?></td>
                            <td>
                                <?php if (!empty($contact['contactos_foto'])): ?>
                                    <img src="<?= BASE_URL . htmlspecialchars($contact['contactos_foto']) ?>" alt="<?= $lang['contact_photo_alt'] ?? 'Foto de Contacto' ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                <?php else: ?>
                                    <?= $lang['not_applicable_abbr'] ?? 'N/A' ?>
                                <?php endif; ?>
                            </td>
                            <td class="action-icons">
                                <img src="<?= BASE_URL ?>images/view_icon2.jpg" alt="<?= $lang['view_details_alt'] ?? 'Ver' ?>" title="<?= $lang['view_details_title'] ?? 'Ver Detalles' ?>" onclick="alert('<?= $lang['view_contact_details_alert'] ?? 'Ver detalles del contacto:' ?> <?= htmlspecialchars($contact['contactos_nombres'] . ' ' . $contact['contactos_apellidos']) ?>');" style="width: 30px; height: 30px; cursor: pointer;">
                                <img src="<?= BASE_URL ?>images/edit_icon.jpg" alt="<?= $lang['edit_contact_alt'] ?? 'Editar' ?>" title="<?= $lang['edit_contact_title'] ?? 'Editar Contacto' ?>" onclick='openEditContactModal(<?= json_encode($contact, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>);' style="width: 30px; height: 30px; cursor: pointer;">
                                <img src="<?= BASE_URL ?>images/delete_icon.jpg" alt="<?= $lang['delete_contact_alt'] ?? 'Eliminar' ?>" title="<?= $lang['delete_contact_title'] ?? 'Eliminar Contacto' ?>" onclick="confirmDelete(<?= (int)$contact['contactos_id'] ?>, '<?= htmlspecialchars($contact['contactos_nombres'] . ' ' . $contact['contactos_apellidos']) ?>');" style="width: 30px; height: 30px; cursor: pointer;">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<div id="addContactModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="addContactTitle" aria-hidden="true" tabindex="-1">
    <div class="modal-content">
        <button class="close-button" data-modal-close="addContactModal" aria-label="<?= $lang['close_modal_aria'] ?? 'Cerrar' ?>">&times;</button>
        <h2 id="addContactTitle"><?= $lang['add_contact_modal_title'] ?? 'Nuevo Contacto' ?></h2>
        <form action="contacts.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label for="add_nombres"><?= $lang['label_first_names'] ?? 'Nombres:' ?></label>
                <input type="text" id="add_nombres" name="nombres" required>
            </div>
            <div class="form-group">
                <label for="add_apellidos"><?= $lang['label_last_names'] ?? 'Apellidos:' ?></label>
                <input type="text" id="add_apellidos" name="apellidos" required>
            </div>
            <div class="form-group">
                <label for="add_institucion"><?= $lang['label_institution'] ?? 'Institución Educativa:' ?></label>
                <input type="text" id="add_institucion" name="institucion">
            </div>
            <div class="form-group">
                <label for="add_pais"><?= $lang['label_country'] ?? 'País:' ?></label>
                <input type="text" id="add_pais" name="pais">
            </div>
            <div class="form-group">
                <label for="add_ci"><?= $lang['label_id_card'] ?? 'CI (Cédula de Identidad):' ?></label>
                <input type="number" id="add_ci" name="ci">
            </div>
            <div class="form-group">
                <label for="add_telefono"><?= $lang['label_phone'] ?? 'Número Telefónico:' ?></label>
                <input type="number" id="add_telefono" name="telefono">
            </div>
            <div class="form-group">
                <label for="add_correo"><?= $lang['label_email'] ?? 'Correo Electrónico:' ?></label>
                <input type="email" id="add_correo" name="correo" required>
            </div>
            <div class="form-group">
                <label for="add_foto"><?= $lang['label_photo'] ?? 'Foto:' ?></label>
                <input type="file" id="add_foto" name="foto" accept="image/*">
            </div>
            <div class="button-group">
                <button type="button" class="btn btn-cancel" data-modal-close="addContactModal"><?= $lang['cancel_btn'] ?? 'Cancelar' ?></button>
                <button type="submit" class="btn btn-success"><?= $lang['add_btn'] ?? 'Agregar' ?></button>
            </div>
        </form>
    </div>
</div>

<div id="editContactModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="editContactTitle" aria-hidden="true" tabindex="-1">
    <div class="modal-content">
        <button class="close-button" data-modal-close="editContactModal" aria-label="<?= $lang['close_modal_aria'] ?? 'Cerrar' ?>">&times;</button>
        <h2 id="editContactTitle"><?= $lang['edit_contact_modal_title'] ?? 'Editar Contacto' ?></h2>
        <form action="contacts.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" id="edit_contactos_id" name="contactos_id">
            <div class="form-group">
                <label for="edit_nombres"><?= $lang['label_first_names'] ?? 'Nombres:' ?></label>
                <input type="text" id="edit_nombres" name="nombres" required>
            </div>
            <div class="form-group">
                <label for="edit_apellidos"><?= $lang['label_last_names'] ?? 'Apellidos:' ?></label>
                <input type="text" id="edit_apellidos" name="apellidos" required>
            </div>
            <div class="form-group">
                <label for="edit_institucion"><?= $lang['label_institution'] ?? 'Institución Educativa:' ?></label>
                <input type="text" id="edit_institucion" name="institucion">
            </div>
            <div class="form-group">
                <label for="edit_pais"><?= $lang['label_country'] ?? 'País:' ?></label>
                <input type="text" id="edit_pais" name="pais">
            </div>
            <div class="form-group">
                <label for="edit_ci"><?= $lang['label_id_card'] ?? 'CI (Cédula de Identidad):' ?></label>
                <input type="number" id="edit_ci" name="ci">
            </div>
            <div class="form-group">
                <label for="edit_telefono"><?= $lang['label_phone'] ?? 'Número Telefónico:' ?></label>
                <input type="number" id="edit_telefono" name="telefono">
            </div>
            <div class="form-group">
                <label for="edit_correo"><?= $lang['label_email'] ?? 'Correo Electrónico:' ?></label>
                <input type="email" id="edit_correo" name="correo" required>
            </div>
            <div class="form-group">
                <label for="edit_foto"><?= $lang['label_photo_optional'] ?? 'Foto (dejar en blanco para mantener la actual):' ?></label>
                <input type="file" id="edit_foto" name="foto" accept="image/*">
                <input type="hidden" id="current_foto_path" name="current_foto_path">
                <div id="current_foto_preview" style="margin-top: 10px;"></div>
            </div>
            <div class="button-group">
                <button type="button" class="btn btn-cancel" data-modal-close="editContactModal"><?= $lang['cancel_btn'] ?? 'Cancelar' ?></button>
                <button type="submit" class="btn btn-success"><?= $lang['save_changes_btn'] ?? 'Guardar Cambios' ?></button>
            </div>
        </form>
    </div>
</div>

<script>
const BASE_URL = '<?= BASE_URL ?>';

// Modal open/close handlers (you may want to implement your modal JS here)
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
        modal.setAttribute('aria-hidden', 'false');
        // Optionally focus trap or set focus to first input
        const firstInput = modal.querySelector('input, button, select, textarea, [tabindex]:not([tabindex="-1"])');
        if (firstInput) firstInput.focus();
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
    }
}

// Attach close buttons
document.querySelectorAll('[data-modal-close]').forEach(btn => {
    btn.addEventListener('click', e => {
        const target = btn.getAttribute('data-modal-close');
        closeModal(target);
    });
});

// Open edit modal with contact data filled
function openEditContactModal(contactData) {
    document.getElementById('edit_contactos_id').value = contactData.contactos_id;
    document.getElementById('edit_nombres').value = contactData.contactos_nombres;
    document.getElementById('edit_apellidos').value = contactData.contactos_apellidos;
    document.getElementById('edit_institucion').value = contactData.contactos_institucion;
    document.getElementById('edit_pais').value = contactData.contactos_pais;
    document.getElementById('edit_ci').value = contactData.contactos_ci;
    document.getElementById('edit_telefono').value = contactData.contactos_telefono;
    document.getElementById('edit_correo').value = contactData.contactos_correo;
    document.getElementById('current_foto_path').value = contactData.contactos_foto;

    const fotoPreviewDiv = document.getElementById('current_foto_preview');
    if (contactData.contactos_foto) {
        fotoPreviewDiv.innerHTML = `<img src="${BASE_URL}${contactData.contactos_foto}" alt="${lang['contact_photo_alt'] ?? 'Current photo'}" style="width:100px; height:100px; object-fit:cover; border-radius:5px;">`;
    } else {
        fotoPreviewDiv.innerHTML = `<?= $lang['no_current_photo'] ?? 'No hay foto actual.' ?>`;
    }

    openModal('editContactModal');
}

// Open Add Contact modal
document.querySelector('button[data-modal-target="addContactModal"]').addEventListener('click', () => {
    openModal('addContactModal');
});

// Confirm before deleting contact
function confirmDelete(id, name) {
    if (confirm(`<?= $lang['confirm_delete_contact_prompt'] ?? '¿Está seguro de que desea eliminar el contacto:' ?> ${name}? <?= $lang['confirm_delete_contact_warning'] ?? 'Esta acción no se puede deshacer.' ?>`)) {
        window.location.href = `contacts.php?action=delete&id=${id}`;
    }
}

// Close modal on clicking outside modal content
window.addEventListener('click', (event) => {
    document.querySelectorAll('.modal').forEach(modal => {
        if (event.target === modal) {
            closeModal(modal.id);
        }
    });
});
</script>

<?php
include __DIR__ . '/../includes/footer.php';
