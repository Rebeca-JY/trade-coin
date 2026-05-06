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

    public function getAllUsers()
    {
        return $this->db->select("SELECT * FROM users");
    }

    public function getUserById($id)
    {
        return $this->db->selectOne(
            "SELECT * FROM users WHERE id = ?",
            [$id]
        );
    }

    public function getUserByUsername($username)
    {
        return $this->db->selectOne(
            "SELECT * FROM users WHERE username = ?",
            [$username]
        );
    }

    public function getUserByEmail($email)
    {
        return $this->db->selectOne(
            "SELECT * FROM users WHERE email = ?",
            [$email]
        );
    }

    // ✅ REGISTER (dipakai di controller)
    public function register($data)
    {
        return $this->db->insert('users', [
            'username'      => $data['username'],
            'email'         => $data['email'],
            'password'      => password_hash($data['password'], PASSWORD_DEFAULT),
            'role'          => $data['role'] ?? 'buyer',
            'nama_lengkap'  => $data['nama_lengkap'],
            'nomor_hp'      => $data['nomor_hp'],
            'alamat'        => $data['alamat'],
            'toko_nama'     => $data['toko_nama']
        ]);
    }

    // ✅ UPDATE (disesuaikan dengan controller kamu)
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
            $update['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        return $this->db->update('users', $update, ['id' => $data['id']]);
    }

    public function deleteUser($id)
    {
        return $this->db->delete('users', ['id' => $id]);
    }
}