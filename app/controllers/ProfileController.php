<?php

namespace App\controllers;

use App\models\User;

class ProfileController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Tampilkan profile user dengan purchase & sales history
     */
    public function index($userId = 1)
    {
        // Get user data
        $user = $this->userModel->getUserById($userId);
        
        if (!$user) {
            die('User tidak ditemukan');
        }

        // Get purchase history
        $purchases = $this->userModel->getPurchaseHistory($userId);
        
        // Get sales history
        $sales = $this->userModel->getSalesHistory($userId);

        // Siapkan data untuk view
        $data = [
            'user' => $user,
            'purchases' => $purchases,
            'sales' => $sales,
            'pageTitle' => 'Profile'
        ];

        // Load view
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
