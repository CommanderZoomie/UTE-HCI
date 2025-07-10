<?php
// contactanos.php
require_once __DIR__ . '/../settings/config.php';
require_once __DIR__ . '/../settings/db.php'; // Assumes this file establishes and returns/provides a $pdo object
require_once __DIR__ . '/../security/auth.php';

$success_message = '';
$error_message = '';

// Initialize variables for form fields to avoid undefined variable notices on first load
$nombre = '';
$apellidos = '';
$telefono = '';
$correo = '';
$pais = '';
$institucion = '';
$mensaje = '';

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
        try {
            // Get the PDO connection object by calling the getDB() function from db.php
            $pdo = getDB(); // This is the crucial change

            // IMPORTANT: Check if $pdo is actually set and is an object
            if (!isset($pdo) || !$pdo instanceof PDO) {
                // If $pdo is not set or not a PDO object, it means db.php failed to establish the connection
                // or didn't make it globally available.
                $error_message = 'Error de conexión a la base de datos: La conexión PDO no está disponible. Por favor, verifica tu archivo db.php.';
                // It's crucial to stop execution here as database operations cannot proceed.
                // For a production environment, you might log this error and show a generic message.
                // For debugging, a die() is useful.
                die($error_message);
            }

            // Prepare the SQL INSERT statement for the 'clients' table
            $stmt = $pdo->prepare("INSERT INTO clients (nombres, apellidos, numero_telefonico, correo_electronico, pais, institucion_educativa, mensaje) VALUES (:nombres, :apellidos, :telefono, :correo, :pais, :institucion, :mensaje)");

            // Bind parameters to the prepared statement
            $stmt->bindParam(':nombres', $nombre);
            $stmt->bindParam(':apellidos', $apellidos);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':pais', $pais);
            $stmt->bindParam(':institucion', $institucion);
            $stmt->bindParam(':mensaje', $mensaje);

            // Execute the statement
            if ($stmt->execute()) {
                // Database insertion successful
                // Updated success message as requested
                $success_message = '¡Gracias por tu interés! Estamos en contacto pronto.';
                // Clear form fields after successful submission
                $nombre = $apellidos = $telefono = $correo = $pais = $institucion = $mensaje = '';
            } else {
                $error_message = 'Hubo un error al guardar tu mensaje en la base de datos. Por favor, inténtalo de nuevo más tarde.';
            }
        } catch (PDOException $e) {
            // Catch any database-related errors
            $error_message = 'Error de base de datos: ' . $e->getMessage();
            // Log the error for debugging (e.g., error_log($e->getMessage());)
        }
    }
}

include '../includes/header.php'; // Includes the public navigation
?>

<section class= "contact-section">
    <div class="contact-form-container">
        <h2>Déjanos tu información de contacto.</h2>
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
                <label for="nombre">Nombres <span style="color:red;">*</span></label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($nombre) ?>" required>
            </div>
            <div class="form-group">
                <label for="apellidos">Apellidos</label>
                <input type="text" id="apellidos" name="apellidos" value="<?= htmlspecialchars($apellidos) ?>">
            </div>
            <div class="form-group">
                <label for="telefono">Número Telefónico</label>
                <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($telefono) ?>">
            </div>
            <div class="form-group">
                <label for="correo">Correo Electrónico <span style="color:red;">*</span></label>
                <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($correo) ?>" required>
            </div>
            <div class="form-group">
                <label for="pais">País</label>
                <input type="text" id="pais" name="pais" value="<?= htmlspecialchars($pais) ?>">
            </div>
            <div class="form-group">
                <label for="institucion">Institución Educativa</label>
                <input type="text" id="institucion" name="institucion" value="<?= htmlspecialchars($institucion) ?>">
            </div>
            <div class="form-group">
                <label for="mensaje">Mensaje <span style="color:red;">*</span></label>
                <textarea id="mensaje" name="mensaje" rows="5" required><?= htmlspecialchars($mensaje) ?></textarea>
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
