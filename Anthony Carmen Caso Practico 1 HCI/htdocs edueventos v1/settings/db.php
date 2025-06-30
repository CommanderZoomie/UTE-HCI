<?php
// db.php
require_once 'config.php';

function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $pdo = new PDO($dsn, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            if (DEBUG) {
                die("ERROR: Could not connect. " . $e->getMessage());
            } else {
                error_log($e->getMessage());
                die("ERROR: Could not connect to database.");
            }
        }
    }
    return $pdo;
}
