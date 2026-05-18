<?php
namespace App\models;

use App\Core\Database;
use PDO;

class UserPoint
{
    private $db;
    private $table = 'user_points';
    private ?bool $hasUpdatedAtColumn = null;

    public function __construct()
    {
        // Gunakan koneksi yang konsisten dengan model User
        $this->db = Database::getConnection();
    }

    /**
     * Ambil total poin user
     */
    public function getUserPoints($userId)
    {
        $stmt = $this->db->prepare("SELECT total_points FROM {$this->table} WHERE user_id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_points'] ?? 0;
    }

    /**
     * Pastikan baris user_points ada (coin 0) supaya deduct aman.
     */
    public function ensureWallet(int $userId): void
    {
        $stmt = $this->db->prepare("SELECT id FROM {$this->table} WHERE user_id = ?");
        $stmt->execute([$userId]);
        if (!$stmt->fetch()) {
            $ins = $this->db->prepare("INSERT INTO {$this->table} (user_id, total_points) VALUES (?, 0)");
            $ins->execute([$userId]);
        }
    }

    /**
     * Get top users by points (Ini yang error tadi)
     */
    public function getTopUsers($limit = 5)
    {
        $sql = "SELECT u.username, u.role, COALESCE(up.total_points, 0) as total_points 
                FROM users u 
                LEFT JOIN {$this->table} up ON u.id = up.user_id 
                ORDER BY total_points DESC 
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get total poin yang beredar di sistem
     */
    public function getTotalPointsDistributed()
    {
        $stmt = $this->db->query("SELECT SUM(total_points) as total FROM {$this->table}");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Ambil semua user dengan poin mereka (untuk halaman kelola poin)
     */
    public function getAllUsersWithPoints()
    {
        // Beberapa DB lama belum punya kolom user_points.updated_at
        $lastUpdatedExpr = $this->hasUserPointsUpdatedAt()
            ? 'COALESCE(up.updated_at, u.created_at)'
            : 'u.created_at';

        $sql = "SELECT u.id, u.username, u.email, u.role, 
                       COALESCE(up.total_points, 0) as total_points,
                       {$lastUpdatedExpr} as last_updated
                FROM users u 
                LEFT JOIN {$this->table} up ON u.id = up.user_id 
                ORDER BY u.username ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function hasUserPointsUpdatedAt(): bool
    {
        if ($this->hasUpdatedAtColumn !== null) {
            return $this->hasUpdatedAtColumn;
        }

        try {
            $stmt = $this->db->query("SHOW COLUMNS FROM {$this->table} LIKE 'updated_at'");
            $this->hasUpdatedAtColumn = (bool) $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            $this->hasUpdatedAtColumn = false;
        }

        return $this->hasUpdatedAtColumn;
    }

    /**
     * Tambah atau Update Poin
     */
    public function addPoints($userId, $points, $reason)
    {
        // Cek apakah sudah ada record
        $stmt = $this->db->prepare("SELECT id FROM {$this->table} WHERE user_id = ?");
        $stmt->execute([$userId]);
        
        if ($stmt->fetch()) {
            $sql = "UPDATE {$this->table} SET total_points = total_points + ? WHERE user_id = ?";
            $this->db->prepare($sql)->execute([$points, $userId]);
        } else {
            $sql = "INSERT INTO {$this->table} (user_id, total_points) VALUES (?, ?)";
            $this->db->prepare($sql)->execute([$userId, $points]);
        }
        
        // Log activity dengan error handling
        try {
            $this->logActivity($userId, 'add', $points, $reason);
        } catch (\Throwable $e) {
            // Jangan henti proses hanya karena logging gagal
            error_log('Point logging failed: ' . $e->getMessage());
        }
    }

    private function logActivity($userId, $action, $amount, $reason)
    {
        $sql = "INSERT INTO point_history (user_id, action, amount, reason, created_at) 
                VALUES (?, ?, ?, ?, NOW())";
        $this->db->prepare($sql)->execute([$userId, $action, $amount, $reason]);
    }

    public function deductPoints($userId, $points, $reason)
    {
        $stmt = $this->db->prepare("SELECT id FROM {$this->table} WHERE user_id = ?");
        $stmt->execute([$userId]);
        if (!$stmt->fetch()) {
            throw new \RuntimeException('Record poin user tidak ditemukan');
        }

        $sql = "UPDATE {$this->table} SET total_points = total_points - ? WHERE user_id = ?";
        $this->db->prepare($sql)->execute([$points, $userId]);
        
        // Log activity dengan error handling
        try {
            $this->logActivity($userId, 'deduct', $points, $reason);
        } catch (\Throwable $e) {
            // Jangan henti proses hanya karena logging gagal
            error_log('Point logging failed: ' . $e->getMessage());
        }
    }

    public function setPoints($userId, $newTotal, $reason)
    {
        $stmt = $this->db->prepare("SELECT id FROM {$this->table} WHERE user_id = ?");
        $stmt->execute([$userId]);
        if ($stmt->fetch()) {
            $sql = "UPDATE {$this->table} SET total_points = ? WHERE user_id = ?";
            $this->db->prepare($sql)->execute([$newTotal, $userId]);
        } else {
            $sql = "INSERT INTO {$this->table} (user_id, total_points) VALUES (?, ?)";
            $this->db->prepare($sql)->execute([$userId, $newTotal]);
        }
        
        // Log activity dengan error handling
        try {
            $this->logActivity($userId, 'set', $newTotal, $reason);
        } catch (\Throwable $e) {
            // Jangan henti proses hanya karena logging gagal
            error_log('Point logging failed: ' . $e->getMessage());
        }
    }

    public function getPointHistory($userId)
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM point_history WHERE user_id = ? ORDER BY created_at DESC'
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserPointDetail($userId)
    {
        $sql = "SELECT u.id, u.username, u.email, u.role,
                       COALESCE(up.total_points, 0) AS total_points
                FROM users u
                LEFT JOIN {$this->table} up ON u.id = up.user_id
                WHERE u.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }
}