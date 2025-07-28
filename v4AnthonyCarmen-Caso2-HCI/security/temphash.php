<?php
require_once __DIR__ . '/settings/config.php';
require_once __DIR__ . '/settings/db.php';

$pdo = getDB();

try {
    // Fetch all users
    $stmt = $pdo->query("SELECT id, password FROM users");
    $users = $stmt->fetchAll();

    foreach ($users as $user) {
        $plainPassword = $user['password'];

        // Skip if password already looks hashed (optional)
        // e.g., password hashes start with '$2y$' for bcrypt
        if (strpos($plainPassword, '$2y$') === 0) {
            echo "User ID {$user['id']} password already hashed, skipping.\n";
            continue;
        }

        // Hash the plain password
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

        // Update the DB with the hashed password
        $updateStmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
        $updateStmt->execute([
            ':password' => $hashedPassword,
            ':id' => $user['id']
        ]);

        echo "User ID {$user['id']} password hashed.\n";
    }

    echo "Password hashing completed.\n";

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
