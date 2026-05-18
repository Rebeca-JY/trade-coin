<?php

namespace App\controllers;

use App\models\User;

class SignupController
{
    public function signupView(): void
    {
        // Untuk saat ini frontend-nya sudah ada di app/views/signin.php
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        require_once __DIR__ . '/../views/signin.php';
    }

    public function signupSubmit(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /register');
            exit;
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        
        $username = trim((string)($_POST['username'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        $confirmPassword = (string)($_POST['confirm_password'] ?? '');

        // Basic validation
        $error = '';

        if ($email === '' || $password === '' || $confirmPassword === '') {
            $error = 'Semua field harus diisi.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Format email tidak valid.';
        } elseif (strlen($password) < 8) {
            $error = 'Password minimal 8 karakter.';
        } elseif (!hash_equals($password, $confirmPassword)) {
            $error = 'Password dan confirm password tidak cocok.';
        }

        if ($error !== '') {
            require_once __DIR__ . '/../views/signin.php';
            return;
        }

        $userModel = new User();

        // Check duplicates
        $existingByEmail = $userModel->getUserByEmail($email);
        if ($existingByEmail) {
            $error = 'Email sudah terdaftar. Silakan login.';
            require_once __DIR__ . '/../views/signin.php';
            return;
        }

        // The provided sign-in (registration) form does not include username.
        // We must still populate users.username for compatibility with login.
        // Use email as username by default.

        $existingByUsername = $userModel->getUserByUsername($username);
        if ($existingByUsername) {
            $error = 'Username sudah terdaftar. Silakan login.';
            require_once __DIR__ . '/../views/signin.php';
            return;
        }

        // Register
        try {
            $userModel->register([
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'role' => 'buyer',
                // optional fields (may be nullable/empty in DB). Keep them as empty string.
                'nama_lengkap' => (string)($_POST['nama_lengkap'] ?? ''),
                'nomor_hp' => (string)($_POST['nomor_hp'] ?? ''),
                'alamat' => (string)($_POST['alamat'] ?? ''),
                'toko_nama' => (string)($_POST['toko_nama'] ?? ''),
            ]);
        } catch (\Throwable $e) {
            // Do not expose raw exception details to user.
            $error = 'Gagal mendaftarkan akun. Silakan coba lagi.';
            require_once __DIR__ . '/../views/signin.php';
            return;
        }


        // Per your preference: user logs in manually
        header('Location: /login');
        exit;
    }
}
?>
