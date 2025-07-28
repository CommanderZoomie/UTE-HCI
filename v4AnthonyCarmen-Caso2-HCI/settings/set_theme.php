<?php
// settings/set_theme.php
require_once __DIR__ . '/../settings/config.php';

// Get the theme preference from the request body (for POST) or query string (for GET)
// Assuming you're sending this via a POST request with 'theme' in the body.
$newTheme = filter_input(INPUT_POST, 'theme', FILTER_SANITIZE_STRING);

// If it's a GET request (less common for theme toggles, but possible):
if (!$newTheme) {
    $newTheme = filter_input(INPUT_GET, 'theme', FILTER_SANITIZE_STRING);
}

// Validate the received theme
if (in_array($newTheme, ['light', 'dark'])) {
    $_SESSION['theme'] = $newTheme;
    echo json_encode(['status' => 'success', 'theme' => $newTheme]);
} else {
    // If no valid theme is provided, perhaps toggle it or send current state.
    // For now, let's just default to 'dark' or 'light' if not set.
    // Or send an error.
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Invalid theme provided.']);
}

exit(); // Always exit after an AJAX response
?>