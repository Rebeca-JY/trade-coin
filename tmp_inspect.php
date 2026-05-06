<?php
require 'app/core/Database.php';
require 'app/config/database.php';
try {
    $db = db();
    $tables = $db->select('SHOW TABLES');
    foreach ($tables as $table) {
        $name = current($table);
        echo "TABLE: $name\n";
        $cols = $db->select('SHOW COLUMNS FROM ' . $name);
        foreach ($cols as $col) {
            echo '  ' . $col['Field'] . ' ' . $col['Type'] . ($col['Null'] === 'NO' ? ' NOT NULL' : '') . ($col['Key'] ? ' KEY=' . $col['Key'] : '') . "\n";
        }
    }
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n";
}

