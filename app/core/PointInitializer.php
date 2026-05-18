<?php
/**
 * Initialize User Point Tables
 * Dipanggil saat aplikasi bootstrap untuk memastikan tabel user_points dan point_history ada
 */

namespace App\Core;

class PointInitializer
{
    public static function ensurePointTablesExist()
    {
        try {
            if (!function_exists('db')) {
                return false;
            }
            
            $db = \db();
            
            // 1. Pastikan tabel user_points ada
            self::ensureUserPointsTable($db);
            
            // 2. Pastikan tabel point_history ada
            self::ensurePointHistoryTable($db);
            
            return true;
            
        } catch (\Exception $e) {
            error_log('PointInitializer error: ' . $e->getMessage());
            return false;
        }
    }
    
    private static function ensureUserPointsTable($db)
    {
        try {
            // Cek apakah tabel user_points ada
            $checkTable = $db->select("SHOW TABLES LIKE 'user_points'");
            
            if (!empty($checkTable)) {
                // Verifikasi struktur tabel
                $columns = $db->select("SHOW COLUMNS FROM user_points");
                $columnNames = [];
                foreach ($columns as $col) {
                    $columnNames[] = strtolower($col['Field']);
                }
                
                $required = ['id', 'user_id', 'total_points'];
                $hasAll = !array_diff($required, $columnNames);
                if ($hasAll) {
                    return true; // Tabel sudah ada dengan struktur benar
                }
            }
            
            // Tabel tidak ada atau struktur salah, buat ulang
            $createSQL = "CREATE TABLE IF NOT EXISTS user_points (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL UNIQUE,
                total_points INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            $db->execute($createSQL);
            return true;
            
        } catch (\Exception $e) {
            error_log('Error creating user_points table: ' . $e->getMessage());
            return false;
        }
    }
    
    private static function ensurePointHistoryTable($db)
    {
        try {
            // Cek apakah tabel point_history ada
            $checkTable = $db->select("SHOW TABLES LIKE 'point_history'");
            
            if (!empty($checkTable)) {
                // Verifikasi struktur tabel
                $columns = $db->select("SHOW COLUMNS FROM point_history");
                $columnNames = [];
                foreach ($columns as $col) {
                    $columnNames[] = strtolower($col['Field']);
                }
                
                $required = ['id', 'user_id', 'action', 'amount', 'created_at'];
                $hasAll = !array_diff($required, $columnNames);
                if ($hasAll) {
                    return true; // Tabel sudah ada dengan struktur benar
                }
            }
            
            // Tabel tidak ada atau struktur salah, buat ulang
            $createSQL = "CREATE TABLE IF NOT EXISTS point_history (
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
            
            $db->execute($createSQL);
            return true;
            
        } catch (\Exception $e) {
            error_log('Error creating point_history table: ' . $e->getMessage());
            return false;
        }
    }
}
?>
