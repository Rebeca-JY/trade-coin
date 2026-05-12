<?php
namespace App\controllers;

use App\models\User;
use App\models\UserPoint;

class LoginController
{
    public function loginView()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $error = '';
        $loginId = '';
        require_once __DIR__ . '/../views/login.php';
    }

    public function loginSubmit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url_for('/login'));
            exit;
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $loginId = trim((string)($_POST['login_id'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if ($loginId === '' || $password === '') {
            $error = 'Email/Username dan password harus diisi.';
            require_once __DIR__ . '/../views/login.php';
            return;
        }

        $userModel = new User();
        $user = $userModel->login($loginId, $password);

        if ($user === false) {
            $error = 'Email/Username atau password salah.';
            require_once __DIR__ . '/../views/login.php';
            return;
        }

        $_SESSION['user'] = $user;
        try {
            $points = new UserPoint();
            $points->ensureWallet((int) $user['id']);
            $_SESSION['user']['coins'] = (int) $points->getUserPoints((int) $user['id']);
        } catch (\Throwable $e) {
            $_SESSION['user']['coins'] = (int) ($_SESSION['user']['coins'] ?? 0);
        }
        header('Location: ' . url_for('/profile'));
        exit;
    }

    public function logout()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
        header('Location: ' . url_for('/login'));
        exit;
    }
}

?>