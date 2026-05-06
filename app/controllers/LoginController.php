<?php
namespace App\controllers;

use App\models\User;

class LoginController
{
    public function LoginView()
    {
        require_once __DIR__ . '/../views/login.php';
    }

    public function loginSubmit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $loginId = trim($_POST['login_id'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($loginId) || empty($password)) {
            $error = 'Email/Username dan password harus diisi.';
            require_once __DIR__ . '/../views/login.php';
            return;
        }

        $userModel = new User();
        $user = $userModel->login($loginId, $password);

        if ($user === false) {
            $error = 'Email/Username atau password salah.';
            require_once '../app/views/login.php';
            return;
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION['user'] = $user;
        header('Location: /profile');
        exit;
    }
}

?>