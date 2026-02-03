<?php
require_once 'db_config.php';

try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'orders'");
    if ($stmt->rowCount() > 0) {
        echo "Table 'orders' exists.\n";
        $stmt = $pdo->query("DESCRIBE orders");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($columns as $col) {
            echo $col['Field'] . " - " . $col['Type'] . "\n";
        }
    } else {
        echo "Table 'orders' does NOT exist.\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>