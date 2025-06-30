<?php
// config.php

// Database credentials
define('DB_HOST', 'sql105.infinityfree.com'); 
define('DB_NAME', 'if0_39349035_edueventos'); 
define('DB_USER', 'if0_39349035');           
define('DB_PASS', 'AnthonyUTE2025');

// Application settings
define('APP_NAME', 'EduEventos');
define('BASE_URL', 'https://edueventos.great-site.net/');

// Debug mode (set to false in production)
define('DEBUG', true);
if (DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Timezone
date_default_timezone_set('America/Guayaquil');

// Session settings
define('SESSION_NAME', 'edueventos_session');
if (session_status() == PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}

// Redirect helper
function redirect($url) {
    header("Location: " . $url);
    exit();
}
?>
