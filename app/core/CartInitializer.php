<?php
/**
 * Initialize Cart Table
 * Dipanggil saat aplikasi bootstrap untuk memastikan tabel cart ada
 */

namespace App\Core;

class CartInitializer
{
    public static function ensureCartTableExists()
    {
        try {
            // Gunakan global db() function dari database.php
            if (!function_exists('db')) {
                return false;
            }
            
            $db = \db();
            
            // Cek apakah tabel cart ada dengan struktur yang benar
            $checkTable = $db->select("SHOW TABLES LIKE 'cart'");
            if (!empty($checkTable)) {
                // Verifikasi struktur tabel
                $columns = $db->select("SHOW COLUMNS FROM cart");
                $columnNames = [];
                foreach ($columns as $col) {
                    $columnNames[] = strtolower($col['Field']);
                }
                
                $required = ['id', 'user_id', 'product_id', 'quantity'];
                $hasAll = !array_diff($required, $columnNames);
                if ($hasAll) {
                    return true; // Tabel sudah ada dengan struktur benar
                }
            }
            
            // Tabel tidak ada atau struktur salah, buat ulang
            $createSQL = "CREATE TABLE IF NOT EXISTS cart (
                id INT PRIMARY KEY AUTO_INCREMENT,
                user_id INT NOT NULL,
                product_id INT NOT NULL,
                quantity INT NOT NULL DEFAULT 1,
                added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
            
            $db->execute($createSQL);
            return true;
            
        } catch (\Exception $e) {
            error_log('Cart table initialization error: ' . $e->getMessage());
            return false;
        }
    }
}
