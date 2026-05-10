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
        $flash = $_SESSION['flash_profile'] ?? null;
        unset($_SESSION['flash_profile']);

        $data = [
            'user' => $user,
            'purchases' => $purchases,
            'sales' => $sales,
            'pageTitle' => 'Profile',
            'flash' => $flash,
        ];

        return $this->render('profile', $data);
    }

    public function uploadPhoto()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['user']['id'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /profile');
            exit;
        }

        if (!isset($_FILES['profile_image']) || ($_FILES['profile_image']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            $_SESSION['flash_profile'] = ['type' => 'error', 'message' => 'File foto belum dipilih / upload gagal.'];
            header('Location: /profile');
            exit;
        }

        $file = $_FILES['profile_image'];
        $allowedMime = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $mime = mime_content_type($file['tmp_name']) ?: '';

        if (!isset($allowedMime[$mime])) {
            $_SESSION['flash_profile'] = ['type' => 'error', 'message' => 'Format foto harus JPG, PNG, atau WEBP.'];
            header('Location: /profile');
            exit;
        }

        if (($file['size'] ?? 0) > 2 * 1024 * 1024) {
            $_SESSION['flash_profile'] = ['type' => 'error', 'message' => 'Ukuran foto maksimal 2MB.'];
            header('Location: /profile');
            exit;
        }

        $ext = $allowedMime[$mime];
        $filename = 'profile_' . (int) $_SESSION['user']['id'] . '_' . time() . '.' . $ext;
        $relativePath = '/uploads/profiles/' . $filename;
        $targetDir = dirname(__DIR__, 2) . '/public/uploads/profiles';
        $targetPath = $targetDir . '/' . $filename;

        if (!is_dir($targetDir) && !mkdir($targetDir, 0777, true) && !is_dir($targetDir)) {
            $_SESSION['flash_profile'] = ['type' => 'error', 'message' => 'Folder upload tidak bisa dibuat.'];
            header('Location: /profile');
            exit;
        }

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            $_SESSION['flash_profile'] = ['type' => 'error', 'message' => 'Gagal menyimpan foto profil.'];
            header('Location: /profile');
            exit;
        }

        $ok = $this->userModel->updateProfileImage((int) $_SESSION['user']['id'], $relativePath);
        if (!$ok) {
            $_SESSION['flash_profile'] = ['type' => 'error', 'message' => 'Foto tersimpan tapi gagal update data user.'];
            header('Location: /profile');
            exit;
        }

        $_SESSION['user']['profile_image'] = $relativePath;
        $_SESSION['flash_profile'] = ['type' => 'success', 'message' => 'Foto profil berhasil diperbarui.'];
        header('Location: /profile');
        exit;
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
