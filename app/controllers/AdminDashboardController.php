<?php
namespace App\controllers;

use App\models\User;
use App\models\UserPoint;

class AdminDashboardController
{
    private $userModel;
    private $userPointModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->userPointModel = new UserPoint();
    }

    private function render(string $view, array $data = [])
    {
        extract($data);
        require __DIR__ . '/../views/admin/dashboard/' . $view . '.php';
    }

    /**
     * Admin Dashboard - Main page
     */
    public function index()
    {
        // Get statistics
        $totalUsers = $this->userModel->countUsers();
        $totalAdmins = $this->userModel->countUsers('admin');
        $totalSellers = $this->userModel->countUsers('seller');
        $totalBuyers = $this->userModel->countUsers('buyer');
        $topUsers = $this->userPointModel->getTopUsers(5);
        $totalPointsDistributed = $this->userPointModel->getTotalPointsDistributed();

        $this->render('index', [
            'pageTitle' => 'Admin Dashboard',
            'totalUsers' => $totalUsers,
            'totalAdmins' => $totalAdmins,
            'totalSellers' => $totalSellers,
            'totalBuyers' => $totalBuyers,
            'topUsers' => $topUsers,
            'totalPointsDistributed' => $totalPointsDistributed,
        ]);
    }

    /**
     * Manage User Points - List
     */
    public function managePoints()
    {
        $users = $this->userPointModel->getAllUsersWithPoints();

        $this->render('manage-points', [
            'users' => $users,
            'totalUsers' => count($users),
            'pageTitle' => 'Kelola Poin User',
        ]);
    }

    /**
     * Give Points to User
     */
    public function givePoints()
    {
        $errors = [];
        $success = null;
        $userId = $_GET['id'] ?? null;

        if ($userId && !is_numeric($userId)) {
            header('Location: /admin/manage-points');
            exit;
        }

        $user = null;
        if ($userId) {
            $user = $this->userModel->getUserById($userId);
            if (!$user) {
                header('Location: /admin/manage-points');
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'] ?? null;
            $points = (int)($_POST['points'] ?? 0);
            $reason = $_POST['reason'] ?? '';

            if (!$userId || !is_numeric($userId)) {
                $errors[] = 'User harus dipilih';
            }

            if ($points <= 0) {
                $errors[] = 'Poin harus lebih dari 0';
            }

            if (empty($reason)) {
                $errors[] = 'Alasan pemberian poin harus diisi';
            }

            if (empty($errors)) {
                $user = $this->userModel->getUserById($userId);
                if (!$user) {
                    $errors[] = 'User tidak ditemukan';
                } else {
                    try {
                        $this->userPointModel->addPoints($userId, $points, $reason);
                        $success = "Poin berhasil diberikan ke {$user['username']}";
                    } catch (Exception $e) {
                        $errors[] = 'Database error: ' . $e->getMessage();
                    }
                }
            }
        }

        $allUsers = $this->userModel->getAllUsers();

        $this->render('give-points', [
            'errors' => $errors,
            'success' => $success,
            'user' => $user,
            'allUsers' => $allUsers,
            'pageTitle' => 'Berikan Poin',
        ]);
    }

    /**
     * Deduct Points from User
     */
    public function deductPoints()
    {
        $errors = [];
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'] ?? null;
            $points = (int)($_POST['points'] ?? 0);
            $reason = $_POST['reason'] ?? '';

            if (!$userId || !is_numeric($userId)) {
                $errors[] = 'User harus dipilih';
            }

            if ($points <= 0) {
                $errors[] = 'Poin harus lebih dari 0';
            }

            if (empty($reason)) {
                $errors[] = 'Alasan pengurangan poin harus diisi';
            }

            if (empty($errors)) {
                $user = $this->userModel->getUserById($userId);
                if (!$user) {
                    $errors[] = 'User tidak ditemukan';
                } else {
                    $current = $this->userPointModel->getUserPoints($userId);
                    if ($current < $points) {
                        $errors[] = "Poin user hanya {$current}, tidak bisa dikurangi {$points}";
                    } else {
                        try {
                            $this->userPointModel->deductPoints($userId, $points, $reason);
                            $success = "Poin berhasil dikurangi dari {$user['username']}";
                        } catch (Exception $e) {
                            $errors[] = 'Database error: ' . $e->getMessage();
                        }
                    }
                }
            }
        }

        $allUsers = $this->userModel->getAllUsers();

        $this->render('deduct-points', [
            'errors' => $errors,
            'success' => $success,
            'allUsers' => $allUsers,
            'pageTitle' => 'Kurangi Poin',
        ]);
    }

    /**
     * Set Points ke nilai tertentu
     */
    public function setPoints()
    {
        $errors = [];
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'] ?? null;
            $points = (int)($_POST['points'] ?? 0);
            $reason = $_POST['reason'] ?? '';

            if (!$userId || !is_numeric($userId)) {
                $errors[] = 'User harus dipilih';
            }

            if ($points < 0) {
                $errors[] = 'Poin tidak boleh negatif';
            }

            if (empty($reason)) {
                $errors[] = 'Alasan perubahan poin harus diisi';
            }

            if (empty($errors)) {
                $user = $this->userModel->getUserById($userId);
                if (!$user) {
                    $errors[] = 'User tidak ditemukan';
                } else {
                    try {
                        $this->userPointModel->setPoints($userId, $points, $reason);
                        $success = "Poin {$user['username']} berhasil diubah menjadi {$points}";
                    } catch (Exception $e) {
                        $errors[] = 'Database error: ' . $e->getMessage();
                    }
                }
            }
        }

        $allUsers = $this->userModel->getAllUsers();

        $this->render('set-points', [
            'errors' => $errors,
            'success' => $success,
            'allUsers' => $allUsers,
            'pageTitle' => 'Set Poin User',
        ]);
    }

    /**
     * View user poin history
     */
    public function pointHistory()
    {
        $userId = $_GET['id'] ?? null;

        if (!$userId || !is_numeric($userId)) {
            header('Location: /admin/manage-points');
            exit;
        }

        $user = $this->userPointModel->getUserPointDetail($userId);

        if (empty($user)) {
            header('Location: /admin/manage-points');
            exit;
        }

        $history = $this->userPointModel->getPointHistory($userId);

        $this->render('point-history', [
            'user' => $user,
            'history' => $history,
            'pageTitle' => 'Riwayat Poin - ' . $user['username'],
        ]);
    }
}
?>
