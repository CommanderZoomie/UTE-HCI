<?php
// contactanos.php
require_once __DIR__ . '/../settings/config.php';
require_once __DIR__ . '/../settings/db.php';
require_once __DIR__ . '/../security/auth.php';
require_once __DIR__ . '/../settings/lang.php';


$success_message = '';
$error_message = '';

$nombre = '';
$apellidos = '';
$telefono = '';
$correo = '';
$pais = '';
$institucion = '';
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $pais = trim($_POST['pais'] ?? '');
    $institucion = trim($_POST['institucion'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    if (empty($nombre) || empty($correo) || empty($mensaje)) {
        $error_message = $lang['error_required_fields'] ?? 'Please fill required fields.';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error_message = $lang['error_invalid_email'] ?? 'Invalid email format.';
    } else {
        try {
            $pdo = getDB();
            if (!isset($pdo) || !$pdo instanceof PDO) {
                throw new Exception($lang['error_db_connection'] ?? 'Database connection error.');
            }

            $stmt = $pdo->prepare("INSERT INTO clients (nombres, apellidos, numero_telefonico, correo_electronico, pais, institucion_educativa, mensaje) VALUES (:nombres, :apellidos, :telefono, :correo, :pais, :institucion, :mensaje)");

            $stmt->bindParam(':nombres', $nombre);
            $stmt->bindParam(':apellidos', $apellidos);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':pais', $pais);
            $stmt->bindParam(':institucion', $institucion);
            $stmt->bindParam(':mensaje', $mensaje);

            if ($stmt->execute()) {
                $success_message = $lang['success_thanks_contact'] ?? 'Thank you for contacting us.';
                // Clear form fields after successful submit
                $nombre = $apellidos = $telefono = $correo = $pais = $institucion = $mensaje = '';
            } else {
                $error_message = $lang['error_db_insert'] ?? 'Database insertion error.';
            }
        } catch (Exception $e) {
            error_log("Contact form DB error: " . $e->getMessage());
            $error_message = $lang['error_db'] ?? 'Database error. Please try again later.';
        }
    }
}

include '../includes/header.php';
?>

<section class="contact-section">
    <div class="contact-form-container">
        <h2><?= htmlspecialchars($lang['contact_heading'] ?? 'Leave us your contact information.') ?></h2>
        <?php if ($success_message): ?>
            <div class="alert alert-success" style="background-color: #d4edda; color: #155724; border-color: #c3e6cb; padding: 15px; margin-bottom: 20px; border-radius: 5px; font-weight: bold; text-align: center;">
                <?= htmlspecialchars($success_message) ?>
            </div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        <form action="contactanos.php" method="POST" novalidate>
            <div class="form-group">
                <label for="nombre"><?= htmlspecialchars($lang['label_first_name'] ?? 'First Name') ?> <span style="color:red;">*</span></label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($nombre) ?>" required aria-required="true">
            </div>
            <div class="form-group">
                <label for="apellidos"><?= htmlspecialchars($lang['label_last_name'] ?? 'Last Name') ?></label>
                <input type="text" id="apellidos" name="apellidos" value="<?= htmlspecialchars($apellidos) ?>">
            </div>
            <div class="form-group">
                <label for="telefono"><?= htmlspecialchars($lang['label_phone'] ?? 'Phone Number') ?></label>
                <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($telefono) ?>">
            </div>
            <div class="form-group">
                <label for="correo"><?= htmlspecialchars($lang['label_email'] ?? 'Email') ?> <span style="color:red;">*</span></label>
                <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($correo) ?>" required aria-required="true">
            </div>
            <div class="form-group">
                <label for="pais"><?= htmlspecialchars($lang['label_country'] ?? 'Country') ?></label>
                <input type="text" id="pais" name="pais" value="<?= htmlspecialchars($pais) ?>">
            </div>
            <div class="form-group">
                <label for="institucion"><?= htmlspecialchars($lang['label_institution'] ?? 'Educational Institution') ?></label>
                <input type="text" id="institucion" name="institucion" value="<?= htmlspecialchars($institucion) ?>">
            </div>
            <div class="form-group">
                <label for="mensaje"><?= htmlspecialchars($lang['label_message'] ?? 'Message') ?> <span style="color:red;">*</span></label>
                <textarea id="mensaje" name="mensaje" rows="5" required aria-required="true"><?= htmlspecialchars($mensaje) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><?= htmlspecialchars($lang['button_send'] ?? 'Send') ?></button>
        </form>
    </div>

    <div class="contact-info-section">
        <h2><?= htmlspecialchars($lang['visit_us'] ?? 'Or visit us in person') ?></h2>
        <div class="map-and-address">
            <div class="map-container">
                <a href="https://maps.app.goo.gl/n7pu796CJVNz97eW7" target="_blank" rel="noopener noreferrer">
                    <img src="<?= BASE_URL ?>images/map_placeholder.jpg" alt="<?= htmlspecialchars($lang['map_alt'] ?? 'Map Location') ?>" loading="lazy">
                </a>
            </div>
            <div class="address-details">
                <p><?= htmlspecialchars($lang['address_line1'] ?? 'Universidad UTE') ?></p>
                <p><?= htmlspecialchars($lang['address_line2'] ?? 'Campus Occidental') ?></p>
                <p><?= htmlspecialchars($lang['address_line3'] ?? 'Av. Mariana de Jesús,') ?></p>
                <p><?= htmlspecialchars($lang['address_line4'] ?? 'Quito 170129') ?></p>
                <p><?= htmlspecialchars($lang['address_line5'] ?? 'Bloque Z - Oficina 8') ?></p>
                <p><?= htmlspecialchars($lang['contact_person'] ?? 'Ing. Anthony Carmen') ?></p>
                <p><?= htmlspecialchars($lang['office_hours'] ?? '09h00 a 16h00') ?></p>
                <p><?= htmlspecialchars($lang['work_days'] ?? 'Lunes a Viernes') ?></p>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
