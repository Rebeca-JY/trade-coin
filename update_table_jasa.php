<?php
require_once __DIR__ . '/app/config/database.php';

try {
    $db = db();
    $pdo = $db->getConnection();
    
    // Check if category exists
    $stmt = $pdo->query("SHOW COLUMNS FROM `products` LIKE 'category'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE `products` ADD `category` VARCHAR(255) NULL");
        echo "Column 'category' added.\n";
    } else {
        echo "Column 'category' already exists.\n";
    }

    // Check if material exists
    $stmt = $pdo->query("SHOW COLUMNS FROM `products` LIKE 'material'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE `products` ADD `material` VARCHAR(255) NULL");
        echo "Column 'material' added.\n";
    } else {
        echo "Column 'material' already exists.\n";
    }
    
    echo "Database updated successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
