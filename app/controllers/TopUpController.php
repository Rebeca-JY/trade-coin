<?php
namespace App\controllers;

use App\models\UserPoint;

class TopUpController
{
    public function index()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['user']['id'])) {
            header('Location: /login');
            exit;
        }

        $errors = [];
        $success = '';
        $userId = (int) $_SESSION['user']['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $selectedCoin = (int) ($_POST['coin'] ?? 0);
            $allowed = [10, 20, 50, 75, 100, 150];

            if (!in_array($selectedCoin, $allowed, true)) {
                $errors[] = 'Paket top up tidak valid.';
            } else {
                try {
                    $pointModel = new UserPoint();
                    $pointModel->addPoints($userId, $selectedCoin, 'Top up coin');
                    $success = "Top up {$selectedCoin} coin berhasil.";
                } catch (\Throwable $e) {
                    $errors[] = 'Top up gagal. Coba lagi.';
                }
            }
        }

        $userCoins = 0;
        try {
            $pointModel = new UserPoint();
            $userCoins = (int) $pointModel->getUserPoints($userId);
        } catch (\Throwable $e) {
            $userCoins = (int) ($_SESSION['user']['coins'] ?? 0);
        }

        $packages = [10, 20, 50, 75, 100, 150];

        $topupNotice = $_SESSION['flash_topup'] ?? null;
        unset($_SESSION['flash_topup']);

        require_once '../app/views/topup.php';
    }
}

