<?php
// settings/lang.php

// Start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define supported languages
$allowed_langs = ['es-la', 'en-us'];
$default_lang = 'es-la';

// Sanitize and check GET param
$lang_param = $_GET['lang'] ?? null;
if ($lang_param && in_array($lang_param, $allowed_langs, true)) {
    $_SESSION['lang'] = $lang_param;
}

// Fallback to default if session not set
$lang_code = $_SESSION['lang'] ?? $default_lang;

// Initialize the $lang array
$lang = [];

// Load base language file (e.g. lang_es-la.php)
$baseLangFile = __DIR__ . "/../languages/lang_{$lang_code}.php";
if (file_exists($baseLangFile)) {
    require $baseLangFile;
} else {
    error_log("Missing base language file: $baseLangFile");
}

// Optionally load page-specific language file
$currentScript = basename($_SERVER['SCRIPT_NAME'], '.php'); // e.g., "contactanos"
$pageLangFile = __DIR__ . "/../languages/lang_{$lang_code}_{$currentScript}.php";
if (file_exists($pageLangFile)) {
    require $pageLangFile;
}
