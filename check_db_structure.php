<?php
require 'app/core/Database.php';
require 'app/config/database.php';

try {
    $db = Database::getInstance(
        'localhost',
        'root',
        '',
        'tradecoin'
    );
    
    echo "=== STRUKTUR TABEL PENTING ===\n\n";
    
    // Check user_points table
    echo "TABEL: user_points\n";
    try {
        $cols = $db->query('SHOW COLUMNS FROM user_points')->fetchAll(PDO::FETCH_ASSOC);
        foreach ($cols as $col) {
            echo "  - " . $col['Field'] . " (" . $col['Type'] . ")" . 
                 ($col['Null'] === 'NO' ? ' NOT NULL' : '') . 
                 ($col['Key'] ? ' KEY=' . $col['Key'] : '') . "\n";
        }
    } catch (Exception $e) {
        echo "  TABLE TIDAK ADA!\n";
    }
    
    echo "\nTABEL: point_history\n";
    try {
        $cols = $db->query('SHOW COLUMNS FROM point_history')->fetchAll(PDO::FETCH_ASSOC);
        foreach ($cols as $col) {
            echo "  - " . $col['Field'] . " (" . $col['Type'] . ")" . 
                 ($col['Null'] === 'NO' ? ' NOT NULL' : '') . 
                 ($col['Key'] ? ' KEY=' . $col['Key'] : '') . "\n";
        }
    } catch (Exception $e) {
        echo "  TABLE TIDAK ADA!\n";
    }
    
    echo "\nTABEL: users (untuk referensi)\n";
    try {
        $cols = $db->query('SHOW COLUMNS FROM users')->fetchAll(PDO::FETCH_ASSOC);
        foreach ($cols as $col) {
            echo "  - " . $col['Field'] . "\n";
        }
    } catch (Exception $e) {
        echo "  TABLE TIDAK ADA!\n";
    }
    
} catch (Exception $e) {
    echo "ERROR KONEKSI DATABASE: " . $e->getMessage() . "\n";
}
?>
