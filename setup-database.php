<?php
/**
 * Database Setup Script - Buat tabel yang diperlukan jika belum ada
 * Jalankan: php setup-database.php
 */

require 'app/core/Database.php';
require 'app/config/database.php';

try {
    $db = Database::getInstance(
        'localhost',
        'root',
        '',
        'tradecoin'
    );
    $pdo = $db->getPdo();
    
    echo "=== DATABASE SETUP ===\n\n";
    
    // 1. Create user_points table if not exists
    $sql_user_points = "CREATE TABLE IF NOT EXISTS user_points (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL UNIQUE,
        total_points INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($sql_user_points);
    echo "✓ Tabel 'user_points' ready\n";
    
    // 2. Create point_history table if not exists
    $sql_point_history = "CREATE TABLE IF NOT EXISTS point_history (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        action ENUM('add', 'deduct', 'set', 'purchase') DEFAULT 'add',
        amount INT NOT NULL,
        reason VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX (user_id),
        INDEX (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($sql_point_history);
    echo "✓ Tabel 'point_history' ready\n";
    
    // 3. Check and display table structures
    echo "\n=== VERIFIKASI STRUKTUR ===\n\n";
    
    $tables = ['user_points', 'point_history'];
    foreach ($tables as $table) {
        try {
            $cols = $pdo->query("SHOW COLUMNS FROM $table")->fetchAll(PDO::FETCH_ASSOC);
            echo "Tabel: $table\n";
            foreach ($cols as $col) {
                echo "  • " . $col['Field'] . " (" . $col['Type'] . ")\n";
            }
            echo "\n";
        } catch (Exception $e) {
            echo "ERROR on $table: " . $e->getMessage() . "\n\n";
        }
    }
    
    echo "✓ Database setup selesai!\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
?>
