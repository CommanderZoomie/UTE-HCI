<?php
// contactanos.php
require_once __DIR__ . '/../settings/config.php';
require_once __DIR__ . '/../settings/db.php';
require_once __DIR__ . '/../security/auth.php';  

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Trim inputs to avoid leading/trailing spaces
    $nombre = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $pais = trim($_POST['pais'] ?? '');
    $institucion = trim($_POST['institucion'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    // Simple validation
    if (empty($nombre) || empty($correo) || empty($mensaje)) {
        $error_message = 'Por favor, complete los campos obligatorios (Nombre, Correo Electrónico, Mensaje).';
    } else if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Formato de correo electrónico inválido.';
    } else {
        // Replace with your actual admin email address!
        $to = 'your_admin_email@example.com';

        $subject = 'Nuevo Mensaje de Contacto - EduEventos';

        $email_content = "Nombre: " . htmlspecialchars($nombre) . "\n";
        $email_content .= "Apellidos: " . htmlspecialchars($apellidos) . "\n";
        $email_content .= "Teléfono: " . htmlspecialchars($telefono) . "\n";
        $email_content .= "Correo: " . htmlspecialchars($correo) . "\n";
        $email_content .= "País: " . htmlspecialchars($pais) . "\n";
        $email_content .= "Institución: " . htmlspecialchars($institucion) . "\n";
        $email_content .= "Mensaje:\n" . htmlspecialchars($mensaje) . "\n";

        // Headers for email, including charset UTF-8
        $headers = 'From: ' . htmlspecialchars($correo) . "\r\n" .
                   'Reply-To: ' . htmlspecialchars($correo) . "\r\n" .
                   'X-Mailer: PHP/' . phpversion() . "\r\n" .
                   'Content-Type: text/plain; charset=utf-8';

        if (mail($to, $subject, $email_content, $headers)) {
            $success_message = '¡Gracias por tu mensaje! Nos pondremos en contacto contigo pronto.';
            // Clear form fields after successful submission
            $nombre = $apellidos = $telefono = $correo = $pais = $institucion = $mensaje = '';
        } else {
            $error_message = 'Hubo un error al enviar tu mensaje. Por favor, inténtalo de nuevo más tarde.';
        }
    }
}

include '../includes/header.php'; // Includes the public navigation
?>

<section class="contact-section">
    <div class="contact-form-container">
        <h2>Déjanos tu información de contacto.</h2>
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        <form action="contactanos.php" method="POST" novalidate>
            <div class="form-group">
                <label for="nombre">Nombres <span style="color:red;">*</span></label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($nombre ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="apellidos">Apellidos</label>
                <input type="text" id="apellidos" name="apellidos" value="<?= htmlspecialchars($apellidos ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="telefono">Número Telefónico</label>
                <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($telefono ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="correo">Correo Electrónico <span style="color:red;">*</span></label>
                <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($correo ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="pais">País</label>
                <input type="text" id="pais" name="pais" value="<?= htmlspecialchars($pais ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="institucion">Institución Educativa</label>
                <input type="text" id="institucion" name="institucion" value="<?= htmlspecialchars($institucion ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="mensaje">Mensaje <span style="color:red;">*</span></label>
                <textarea id="mensaje" name="mensaje" rows="5" required><?= htmlspecialchars($mensaje ?? '') ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>

    <div class="contact-info-section">
        <h2>O visítanos en persona</h2>
        <div class="map-and-address">
            <div class="map-container">
                <a href="https://maps.app.goo.gl/n7pu796CJVNz97eW7" target="_blank" rel="noopener noreferrer">
                    <img src="<?= BASE_URL ?>images/map_placeholder.jpg" alt="Map Location">
                </a>
            </div>
            <div class="address-details">
                <p>Universidad UTE</p>
                <p>Campus Occidental</p>
                <p>Av. Mariana de Jesús,</p>
                <p>Quito 170129</p>
                <p>Bloque Z - Oficina 8</p>
                <p>Ing. Anthony Carmen</p>
                <p>09h00 a 16h00</p>
                <p>Lunes a Viernes</p>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
