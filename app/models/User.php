<?php

namespace App\models;

use App\Core\Database;

/**
 * User Model - Menangani operasi data user/penjual
 * Database Structure:
 * Tabel: users
 * Fields:
 * ├─ id (INT, PRIMARY KEY)
 * ├─ username (VARCHAR, UNIQUE)
 * ├─ email (VARCHAR, UNIQUE)
 * ├─ password (VARCHAR) - Hashed dengan password_hash()
 * ├─ nama_lengkap (VARCHAR)
 * ├─ nomor_hp (VARCHAR)
 * ├─ alamat (TEXT)
 * ├─ role (ENUM: 'buyer', 'seller', 'admin')
 * ├─ toko_nama (VARCHAR) - Nama toko jika role = seller
 * └─ created_at (TIMESTAMP)
 */
class User
{
    private $db;
    private $table = 'users';

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Register user baru
     * Flow:
     * 1. User submit form register dengan username, email, password
     * 2. Cek apakah username/email sudah terdaftar
     * 3. Hash password menggunakan password_hash()
     * 4. Simpan ke database
     * 
     * @param array $data [
     *     'username' => 'budi123',
     *     'email' => 'budi@email.com',
     *     'password' => 'password123',
     *     'nama_lengkap' => 'Budi Santoso',
     *     'role' => 'buyer'
     * ]
     * @return bool|array True jika berhasil, error message jika gagal
     */
    public function register($data)
    {
        // Validasi field
        if (!isset($data['username']) || !isset($data['email']) || !isset($data['password'])) {
            return ['error' => 'Username, email, dan password harus diisi'];
        }

        // Cek username sudah ada
        $existingUser = $this->getUserByUsername($data['username']);
        if (!empty($existingUser)) {
            return ['error' => 'Username sudah terdaftar'];
        }

        // Cek email sudah ada
        $existingEmail = $this->getUserByEmail($data['email']);
        if (!empty($existingEmail)) {
            return ['error' => 'Email sudah terdaftar'];
        }

        // Hash password
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['role'] = $data['role'] ?? 'buyer';
        $data['created_at'] = date('Y-m-d H:i:s');

        return $this->db->insert($this->table, $data);
    }

    /**
     * Login user
     * Flow:
     * 1. User masukkan username/email dan password
     * 2. Cari user di database
     * 3. Verify password menggunakan password_verify()
     * 4. Return user data jika cocok, atau FALSE jika tidak
     * 
     * @param string $usernameOrEmail Username atau email
     * @param string $password Password plain text
     * @return array|bool User data jika berhasil, FALSE jika gagal
     */
    public function login($usernameOrEmail, $password)
    {
        // Cari user berdasarkan username atau email
        $user = $this->db->selectOne(
            "SELECT * FROM {$this->table} WHERE username = ? OR email = ?",
            [$usernameOrEmail, $usernameOrEmail]
        );

        // User tidak ditemukan
        if (empty($user)) {
            return false;
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            return false;
        }

        // Return user data tanpa password
        unset($user['password']);
        return $user;
    }

    /**
     * Ambil user berdasarkan ID
     * @param int $id User ID
     * @return array User data (tanpa password)
     */
    public function getUserById($id)
    {
        $user = $this->db->selectOne(
            "SELECT * FROM {$this->table} WHERE id = ?",
            [$id]
        );

        if (!empty($user)) {
            unset($user['password']);
        }

        return $user ?? [];
    }

    /**
     * Ambil user berdasarkan username
     * @param string $username
     * @return array User data
     */
    public function getUserByUsername($username)
    {
        return $this->db->selectOne(
            "SELECT * FROM {$this->table} WHERE username = ?",
            [$username]
        );
    }

    /**
     * Ambil user berdasarkan email 
     * @param string $email
     * @return array User data
     */
    public function getUserByEmail($email)
    {
        return $this->db->selectOne(
            "SELECT * FROM {$this->table} WHERE email = ?",
            [$email]
        );
    }

    /**
     * Ambil semua seller/penjual
     * Untuk menampilkan list seller di marketplace
     * 
     * @return array Array berisi semua seller
     */
    public function getAllSellers()
    {
        $query = "SELECT id, username, nama_lengkap, toko_nama FROM {$this->table} 
                  WHERE role = 'seller' 
                  ORDER BY toko_nama";
        
        return $this->db->select($query);
    }

    /**
     * Update profile user
     * 
     * @param int $id User ID
     * @param array $data Data yang diupdate
     * @return bool
     */
    public function updateProfile($id, $data)
    {
        // Jangan perbolehkan update password dari method ini
        unset($data['password']);

        return $this->db->update($this->table, $data, ['id' => $id]);
    }

    /**
     * Update password user
     * @param int $id User ID
     * @param string $currentPassword Password saat ini (untuk verifikasi)
     * @param string $newPassword Password baru
     * @return bool|array True jika berhasil, error jika gagal
     */
    public function updatePassword($id, $currentPassword, $newPassword)
    {
        // Ambil user
        $user = $this->db->selectOne(
            "SELECT * FROM {$this->table} WHERE id = ?",
            [$id]
        );

        if (empty($user)) {
            return ['error' => 'User tidak ditemukan'];
        }

        // Verify current password
        if (!password_verify($currentPassword, $user['password'])) {
            return ['error' => 'Password saat ini salah'];
        }

        // Hash new password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        return $this->db->update(
            $this->table,
            ['password' => $hashedPassword],
            ['id' => $id]
        );
    }

    /**
     * Hitung total user
     * @param string $role Filter by role (optional)
     * @return int
     */
    public function countUsers($role = '')
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        
        if ($role) {
            $query .= " WHERE role = ?";
            $result = $this->db->selectOne($query, [$role]);
        } else {
            $result = $this->db->selectOne($query);
        }

        return $result['total'] ?? 0;
    }

    /**
     * Get purchase history user
     */
    public function getPurchaseHistory($userId)
    {
        $query = "SELECT id, product_title as title, seller_name, product_desc as description, product_image as img 
                  FROM purchases 
                  WHERE user_id = ? 
                  ORDER BY id DESC 
                  LIMIT 10";
        $result = $this->db->select($query, [$userId]);
        return is_array($result) ? $result : [];
    }

    /**
     * Get sales history user (produk yang dijual)
     */
    public function getSalesHistory($userId)
    {
        $query = "SELECT id, product_title as title, seller_id, product_desc as description, product_image as img 
                  FROM sales 
                  WHERE seller_id = ? 
                  ORDER BY id DESC 
                  LIMIT 10";
        $result = $this->db->select($query, [$userId]);
        return is_array($result) ? $result : [];
    }
}
?>
