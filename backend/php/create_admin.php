<?php
require_once 'db_config.php';

// Admin credentials
$username = 'admin';
$password = 'admin123'; // Plain text password
$email = 'admin@soundex.com';

try {
    // Check if admin already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);

    if ($stmt->fetch()) {
        // Update existing admin password to plain text
        $stmt = $pdo->prepare("UPDATE users SET password = ?, role = 'admin', is_active = TRUE WHERE username = ?");
        $stmt->execute([$password, $username]);
        echo "Existing admin user updated successfully!\n";
    } else {
        // Create new admin user
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, first_name, last_name, role, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$username, $email, $password, 'Admin', 'User', 'admin', true]);
        echo "New admin user created successfully!\n";
    }

    echo "Username: $username\n";
    echo "Password: $password\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>