<?php

namespace App\controllers;

use App\models\User;
use App\models\UserPoint;

class ProfileController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Tampilkan profile user dengan purchase & sales history.
     * GET /profile → profil user yang sedang login.
     * GET /profile/{id} → profil tersebut jika milik sendiri atau role admin.
     */
    public function index($routeUserId = null)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['user']['id'])) {
            header('Location: /login');
            exit;
        }

        $sessionUserId = (int)$_SESSION['user']['id'];
        $role = $_SESSION['user']['role'] ?? 'buyer';

        if ($routeUserId !== null && $routeUserId !== '') {
            $requestedId = (int)$routeUserId;
            if ($requestedId !== $sessionUserId && $role !== 'admin') {
                header('Location: /profile');
                exit;
            }
            $userId = $requestedId;
        } else {
            $userId = $sessionUserId;
        }

        $user = $this->userModel->getUserById($userId);

        if (!$user) {
            header('Location: /login');
            exit;
        }

        unset($user['password']);

        try {
            $points = new UserPoint();
            $user['coins'] = (int) $points->getUserPoints($userId);
        } catch (\Throwable $e) {
            $user['coins'] = (int) ($user['coins'] ?? 0);
        }

        $purchases = $this->userModel->getPurchaseHistory($userId);
        $sales = $this->userModel->getSalesHistory($userId);

        $data = [
            'user' => $user,
            'purchases' => $purchases,
            'sales' => $sales,
            'pageTitle' => 'Profile',
        ];

        return $this->render('profile', $data);
    }

    /**
     * Render view dengan data
     */
    private function render($view, $data = [])
    {
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
    }
}
?>
