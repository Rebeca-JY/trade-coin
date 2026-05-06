<?php

namespace App\models;

/**
 * UserPoint Model - Menangani sistem poin user
 */
class UserPoint
{
    private $db;
    private $table = 'user_points';

    public function __construct()
    {
        $this->db = db();
    }

    /**
     * Ambil total poin user
     * @param int $userId
     * @return int
     */
    public function getUserPoints($userId)
    {
        $result = $this->db->selectOne(
            "SELECT total_points FROM {$this->table} WHERE user_id = ?",
            [$userId]
        );

        return $result['total_points'] ?? 0;
    }

    /**
     * Berikan poin ke user
     * @param int $userId
     * @param int $points Jumlah poin
     * @param string $reason Alasan pemberian poin
     * @return bool
     */
    public function addPoints($userId, $points, $reason = '')
    {
        // Check apakah user sudah punya record poin
        $existing = $this->db->selectOne(
            "SELECT id FROM {$this->table} WHERE user_id = ?",
            [$userId]
        );

        if (!empty($existing)) {
            // Update existing record
            $this->db->execute(
                "UPDATE {$this->table} SET total_points = total_points + ? WHERE user_id = ?",
                [$points, $userId]
            );
        } else {
            // Create new record
            $this->db->insert($this->table, [
                'user_id' => $userId,
                'total_points' => $points,
                'last_updated' => date('Y-m-d H:i:s')
            ]);
        }

        // Log activity
        $this->logActivity($userId, 'add', $points, $reason);

        return true;
    }

    /**
     * Kurangi poin user
     * @param int $userId
     * @param int $points
     * @param string $reason
     * @return bool
     */
    public function deductPoints($userId, $points, $reason = '')
    {
        // Get current points
        $current = $this->getUserPoints($userId);

        if ($current < $points) {
            return false; // Not enough points
        }

        // Deduct points
        $this->db->execute(
            "UPDATE {$this->table} SET total_points = total_points - ? WHERE user_id = ?",
            [$points, $userId]
        );

        // Log activity
        $this->logActivity($userId, 'deduct', $points, $reason);

        return true;
    }

    /**
     * Set points ke nilai tertentu
     * @param int $userId
     * @param int $points
     * @param string $reason
     * @return bool
     */
    public function setPoints($userId, $points, $reason = '')
    {
        // Check if user has record
        $existing = $this->db->selectOne(
            "SELECT id FROM {$this->table} WHERE user_id = ?",
            [$userId]
        );

        if (!empty($existing)) {
            // Update
            $this->db->execute(
                "UPDATE {$this->table} SET total_points = ? WHERE user_id = ?",
                [$points, $userId]
            );
        } else {
            // Create
            $this->db->insert($this->table, [
                'user_id' => $userId,
                'total_points' => $points,
                'last_updated' => date('Y-m-d H:i:s')
            ]);
        }

        // Log activity
        $this->logActivity($userId, 'set', $points, $reason);

        return true;
    }

    /**
     * Log activity poin
     * @param int $userId
     * @param string $action (add, deduct, set)
     * @param int $amount
     * @param string $reason
     */
    private function logActivity($userId, $action, $amount, $reason = '')
    {
        // Create point_history table if not exists
        $this->db->execute(
            "INSERT INTO point_history (user_id, action, amount, reason, created_at) 
             VALUES (?, ?, ?, ?, ?)",
            [$userId, $action, $amount, $reason, date('Y-m-d H:i:s')]
        );
    }

    /**
     * Ambil semua user dengan poin mereka
     * @return array
     */
    public function getAllUsersWithPoints()
    {
        $query = "SELECT 
                    u.id, 
                    u.username, 
                    u.email, 
                    u.role,
                    COALESCE(up.total_points, 0) as total_points,
                    COALESCE(up.last_updated, u.created_at) as last_updated
                  FROM users u
                  LEFT JOIN {$this->table} up ON u.id = up.user_id
                  ORDER BY up.total_points DESC, u.username ASC";
        
        $result = $this->db->select($query);
        return is_array($result) ? $result : [];
    }

    /**
     * Get point history untuk user
     * @param int $userId
     * @param int $limit
     * @return array
     */
    public function getPointHistory($userId, $limit = 50)
    {
        $query = "SELECT * FROM point_history 
                  WHERE user_id = ? 
                  ORDER BY created_at DESC 
                  LIMIT ?";
        
        $result = $this->db->select($query, [$userId, $limit]);
        return is_array($result) ? $result : [];
    }

    /**
     * Get user poin dengan detail
     * @param int $userId
     * @return array
     */
    public function getUserPointDetail($userId)
    {
        $query = "SELECT 
                    u.id,
                    u.username,
                    u.email,
                    u.role,
                    COALESCE(up.total_points, 0) as total_points,
                    COALESCE(up.last_updated, u.created_at) as last_updated
                  FROM users u
                  LEFT JOIN {$this->table} up ON u.id = up.user_id
                  WHERE u.id = ?";
        
        $result = $this->db->selectOne($query, [$userId]);
        return $result ?? [];
    }

    /**
     * Get top users by points
     * @param int $limit
     * @return array
     */
    public function getTopUsers($limit = 10)
    {
        $query = "SELECT 
                    u.id,
                    u.username,
                    u.role,
                    COALESCE(up.total_points, 0) as total_points
                  FROM users u
                  LEFT JOIN {$this->table} up ON u.id = up.user_id
                  ORDER BY up.total_points DESC
                  LIMIT ?";
        
        $result = $this->db->select($query, [$limit]);
        return is_array($result) ? $result : [];
    }

    /**
     * Get total poin yang diberikan
     * @return int
     */
    public function getTotalPointsDistributed()
    {
        $result = $this->db->selectOne(
            "SELECT SUM(total_points) as total FROM {$this->table}"
        );

        return $result['total'] ?? 0;
    }
}
?>
