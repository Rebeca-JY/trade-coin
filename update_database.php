<?php
require 'app/core/Database.php';
require 'app/config/database.php';

try {
    $db = db();
    
    echo "=== UPDATE DATABASE STRUCTURE ===\n\n";
    
    // Check current columns
    $cols = $db->select('SHOW COLUMNS FROM products');
    $existingCols = array_column($cols, 'Field');
    
    $updates = [];
    
    // Add seller_name if not exists
    if (!in_array('seller_name', $existingCols)) {
        $updates[] = "ALTER TABLE products ADD COLUMN seller_name varchar(255) NULL AFTER stock";
        echo "✓ Akan tambah kolom: seller_name\n";
    }
    
    // Add description if not exists
    if (!in_array('description', $existingCols)) {
        $updates[] = "ALTER TABLE products ADD COLUMN description text NULL AFTER seller_name";
        echo "✓ Akan tambah kolom: description\n";
    }
    
    // Add image if not exists
    if (!in_array('image', $existingCols)) {
        $updates[] = "ALTER TABLE products ADD COLUMN image varchar(255) NULL AFTER description";
        echo "✓ Akan tambah kolom: image\n";
    }
    
    // Add status if not exists
    if (!in_array('status', $existingCols)) {
        $updates[] = "ALTER TABLE products ADD COLUMN status varchar(50) DEFAULT 'active' AFTER image";
        echo "✓ Akan tambah kolom: status\n";
    }
    
    // Add created_at if not exists
    if (!in_array('created_at', $existingCols)) {
        $updates[] = "ALTER TABLE products ADD COLUMN created_at timestamp DEFAULT CURRENT_TIMESTAMP AFTER status";
        echo "✓ Akan tambah kolom: created_at\n";
    }
    
    // Check if id has AUTO_INCREMENT
    $idCol = null;
    foreach ($cols as $col) {
        if ($col['Field'] === 'id') {
            $idCol = $col;
            break;
        }
    }
    
    if ($idCol && strpos($idCol['Extra'], 'auto_increment') === false) {
        $updates[] = "ALTER TABLE products MODIFY id int NOT NULL AUTO_INCREMENT";
        echo "✓ Akan fix kolom: id AUTO_INCREMENT\n";
    }
    
    if (empty($updates)) {
        echo "✓ Struktur database sudah lengkap!\n";
    } else {
        echo "\n=== MENJALANKAN UPDATE ===\n";
        foreach ($updates as $sql) {
            echo "\nExecution: $sql\n";
            $db->execute($sql);
            echo "✓ Sukses\n";
        }
    }
    
    // Show final structure
    echo "\n=== STRUKTUR FINAL ===\n";
    $cols = $db->select('SHOW COLUMNS FROM products');
    foreach ($cols as $col) {
        echo "  - " . $col['Field'] . " (" . $col['Type'] . ")" . 
             ($col['Null'] === 'NO' ? ' NOT NULL' : '') . 
             ($col['Key'] ? ' KEY=' . $col['Key'] : '') . 
             ($col['Extra'] ? ' ' . $col['Extra'] : '') . "\n";
    }
    
    echo "\n✓ Database siap digunakan!\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    die(1);
}
