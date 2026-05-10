<?php
namespace App\controllers;

use App\models\Product;
use App\models\UserPoint;

class LandingController
{
    public function landingView()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $products = [];
        $userCoins = 0;

        try {
            $productModel = new Product();
            $products = $productModel->getAllProducts();
            if (count($products) > 8) {
                $products = array_slice($products, 0, 8);
            }
        } catch (\Throwable $e) {
            $products = [];
        }

        if (isset($_SESSION['user']['id'])) {
            try {
                $pointModel = new UserPoint();
                $userCoins = (int) $pointModel->getUserPoints((int) $_SESSION['user']['id']);
            } catch (\Throwable $e) {
                $userCoins = (int) ($_SESSION['user']['coins'] ?? 0);
            }
        }

        require_once '../app/views/landing.php';
    }
}

?>