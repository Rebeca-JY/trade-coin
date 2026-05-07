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
        $loginId = trim($loginId);

        // cari user berdasarkan email ATAU username
        $user = $this->db->selectOne(
            "SELECT * FROM users 
             WHERE email = ? 
             OR username = ?",
            [$loginId, $loginId]
        );

        // DEBUG
        // uncomment kalau mau cek
        /*
        var_dump($loginId);
        var_dump($user);
        die();
        */

        // user tidak ditemukan
        if (!$user) {
            return false;
        }

        // password salah
        if (!password_verify($password, $user['password'])) {
            return false;
        }

        // hapus password dari session
        unset($user['password']);

        return $user;
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
}