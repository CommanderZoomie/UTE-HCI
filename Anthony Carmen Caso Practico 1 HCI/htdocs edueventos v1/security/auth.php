<?php
// auth.php

require_once __DIR__ . '/../settings/config.php';
require_once __DIR__ . '/../settings/db.php';

function loginUser($username, $password) {
    $pdo = getDB();

    $username = trim($username);

    $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch();

    // Verify password using password_verify()
    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        return true;
    }

    return false;
}

function logoutUser() {
    session_unset();
    session_destroy();
    redirect(BASE_URL . 'security/login.php');
}

// New logout function that does NOT redirect, so you can show a logout page
function logoutUserNoRedirect() {
    session_unset();
    session_destroy();
}

function isAuthenticated() {
    return isset($_SESSION['user_id']);
}
