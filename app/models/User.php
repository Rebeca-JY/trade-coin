<?php

namespace App\models;

require_once __DIR__ . '/../core/Database.php';

use App\Core\Database;

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // =========================
    // GET ALL USERS
    // =========================
    public function getAllUsers()
    {
        return $this->db->select("SELECT * FROM users");
    }

    // =========================
    // GET USER BY ID
    // =========================
    public function getUserById($id)
    {
        return $this->db->selectOne(
            "SELECT * FROM users WHERE id = ?",
            [$id]
        );
    }

    // =========================
    // GET USER BY USERNAME
    // =========================
    public function getUserByUsername($username)
    {
        return $this->db->selectOne(
            "SELECT * FROM users WHERE username = ?",
            [$username]
        );
    }

    // =========================
    // GET USER BY EMAIL
    // =========================
    public function getUserByEmail($email)
    {
        return $this->db->selectOne(
            "SELECT * FROM users WHERE email = ?",
            [$email]
        );
    }

    // =========================
    // LOGIN
    // =========================
    public function login($loginId, $password)
    {
        $loginId = trim((string)$loginId);
        if ($loginId === '') {
            return false;
        }

        // Email atau username (case-insensitive + trim, supaya login pakai email tetap cocok)
        $user = $this->db->selectOne(
            'SELECT * FROM users 
             WHERE LOWER(TRIM(COALESCE(email, \'\'))) = LOWER(?)
                OR LOWER(TRIM(COALESCE(username, \'\'))) = LOWER(?)',
            [$loginId, $loginId]
        );

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        unset($user['password']);

        return $user;
    }

    /**
     * Riwayat pembelian untuk halaman profil (tabel opsional).
     */
    public function getPurchaseHistory($userId)
    {
        try {
            $rows = $this->db->select(
                'SELECT id, product_title AS title, seller_name, product_desc AS description, product_image AS img
                 FROM purchases WHERE user_id = ? ORDER BY id DESC LIMIT 10',
                [$userId]
            );

            return is_array($rows) ? $rows : [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Riwayat penjualan untuk halaman profil (tabel opsional).
     */
    public function getSalesHistory($userId)
    {
        try {
            $rows = $this->db->select(
                'SELECT id, product_title AS title, seller_id, product_desc AS description, product_image AS img
                 FROM sales WHERE seller_id = ? ORDER BY id DESC LIMIT 10',
                [$userId]
            );

            return is_array($rows) ? $rows : [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    // =========================
    // REGISTER
    // =========================
    public function register($data)
    {
        return $this->db->insert('users', [

            'username'      => $data['username'],

            'email'         => $data['email'],

            'password'      => password_hash(
                $data['password'],
                PASSWORD_DEFAULT
            ),

            'role'          => $data['role'] ?? 'buyer',

            'nama_lengkap'  => $data['nama_lengkap'],

            'nomor_hp'      => $data['nomor_hp'],

            'alamat'        => $data['alamat'],

            'toko_nama'     => $data['toko_nama']
        ]);
    }

    // =========================
    // UPDATE USER
    // =========================
    public function updateUser($data)
    {
        $update = [

            'username'      => $data['username'],

            'email'         => $data['email'],

            'nama_lengkap'  => $data['nama_lengkap'],

            'nomor_hp'      => $data['nomor_hp'],

            'alamat'        => $data['alamat'],

            'role'          => $data['role'],

            'toko_nama'     => $data['toko_nama']
        ];

        // kalau password diisi
        if (!empty($data['password'])) {

            $update['password'] = password_hash(
                $data['password'],
                PASSWORD_DEFAULT
            );
        }

        return $this->db->update(
            'users',
            $update,
            ['id' => $data['id']]
        );
    }

    // =========================
    // DELETE USER
    // =========================
    public function deleteUser($id)
    {
        return $this->db->delete(
            'users',
            ['id' => $id]
        );
    }

    /**
     * Hitung user; jika $role diisi, filter per peran (admin dashboard).
     */
    public function countUsers(?string $role = null): int
    {
        if ($role !== null && $role !== '') {
            $row = $this->db->selectOne(
                'SELECT COUNT(*) AS c FROM users WHERE role = ?',
                [$role]
            );
        } else {
            $row = $this->db->selectOne('SELECT COUNT(*) AS c FROM users');
        }

        return (int)($row['c'] ?? 0);
    }
}