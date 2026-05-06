<?php
require 'app/core/Database.php';
require 'app/config/database.php';

try {
    $db = db();
    
    echo "=== INSPEKSI DATABASE ===\n\n";
    
    // Lihat struktur tabel products
    echo "STRUKTUR TABEL PRODUCTS:\n";
    $cols = $db->select('SHOW COLUMNS FROM products');
    foreach ($cols as $col) {
        echo "  - " . $col['Field'] . " (" . $col['Type'] . ")" . 
             ($col['Null'] === 'NO' ? ' NOT NULL' : '') . 
             ($col['Key'] ? ' KEY=' . $col['Key'] : '') . 
             ($col['Extra'] ? ' ' . $col['Extra'] : '') . "\n";
    }
    
    echo "\nKOLOM YANG DIHARAPKAN MODEL:\n";
    echo "  - id (INT PRIMARY KEY AUTO_INCREMENT)\n";
    echo "  - nama_produk / name (VARCHAR 255 NOT NULL)\n";
    echo "  - harga / price (DECIMAL 10,2 NOT NULL)\n";
    echo "  - stock (INT NOT NULL)\n";
    echo "  - nama_penjual / seller_name (VARCHAR 255)\n";
    echo "  - deskripsi / description (TEXT)\n";
    echo "  - gambar / image (VARCHAR 255)\n";
    echo "  - status (ENUM/VARCHAR)\n";
    echo "  - created_at (TIMESTAMP)\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
