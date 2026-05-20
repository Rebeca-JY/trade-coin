<?php
namespace App\controllers;

require_once __DIR__ . '/AdminAuthTrait.php';

use App\models\User;
use Exception;

class AdminUserController
{
    use AdminAuthTrait;

    private User $userModel;

    public function __construct()
    {
        $this->requireAdmin();
        $this->userModel = new User();
    }

    private function render(string $view, array $data = [])
    {
        extract($data);
        require __DIR__ . '/../views/admin/users/' . $view . '.php';
    }

    /**
     * Tampilkan daftar semua users
     */
    public function index()
    {
        $users = $this->userModel->getAllUsers();

        $this->render('index', [
            'users' => $users,
            'totalUsers' => count($users),
            'pageTitle' => 'Admin - Manajemen Users',
        ]);
    }

    /**
     * Tampilkan form create user baru
     */
    public function create()
    {
        $errors = [];
        $user = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = [
                'username' => $_POST['username'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'nama_lengkap' => $_POST['nama_lengkap'] ?? '',
                'nomor_hp' => $_POST['nomor_hp'] ?? '',
                'alamat' => $_POST['alamat'] ?? '',
                'role' => $_POST['role'] ?? 'buyer',
                'toko_nama' => $_POST['toko_nama'] ?? '',
            ];

            // Validasi
            if (empty($user['username'])) {
                $errors[] = 'Username harus diisi';
            }
            if (empty($user['email'])) {
                $errors[] = 'Email harus diisi';
            }
            if (empty($user['password'])) {
                $errors[] = 'Password harus diisi';
            }
            if (empty($user['nama_lengkap'])) {
                $errors[] = 'Nama lengkap harus diisi';
            }

            // Cek username sudah ada
            $existingUser = $this->userModel->getUserByUsername($user['username']);
            if (!empty($existingUser)) {
                $errors[] = 'Username sudah terdaftar';
            }

            // Cek email sudah ada
            $existingEmail = $this->userModel->getUserByEmail($user['email']);
            if (!empty($existingEmail)) {
                $errors[] = 'Email sudah terdaftar';
            }

            if (empty($errors)) {
                try {
                    $success = $this->userModel->register($user);
                    if ($success !== false) {
                        header('Location: /admin/users?success=User berhasil ditambahkan');
                        exit;
                    }
                    $errors[] = 'Gagal menyimpan user';
                } catch (Exception $e) {
                    $errors[] = 'Database error: ' . $e->getMessage();
                }
            }
        }

        $this->render('create', [
            'errors' => $errors,
            'user' => $user,
            'pageTitle' => 'Tambah User Baru',
        ]);
    }

    /**
     * Tampilkan detail user
     */
    public function show()
    {
        $id = $_GET['id'] ?? null;

        if (!$id || !is_numeric($id)) {
            header('Location: /admin/users');
            exit;
        }

        $user = $this->userModel->getUserById($id);

        if (!$user) {
            header('Location: /admin/users');
            exit;
        }

        $this->render('show', [
            'user' => $user,
            'pageTitle' => 'Detail User',
        ]);
    }

    /**
     * Tampilkan form edit user
     */
    public function edit()
    {
        $id = $_GET['id'] ?? null;

        if (!$id || !is_numeric($id)) {
            header('Location: /admin/users');
            exit;
        }

        $user = $this->userModel->getUserById($id);

        if (!$user) {
            header('Location: /admin/users');
            exit;
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $updateData = [
                'id' => $id,
                'username' => $_POST['username'] ?? '',
                'email' => $_POST['email'] ?? '',
                'nama_lengkap' => $_POST['nama_lengkap'] ?? '',
                'nomor_hp' => $_POST['nomor_hp'] ?? '',
                'alamat' => $_POST['alamat'] ?? '',
                'role' => $_POST['role'] ?? 'buyer',
                'toko_nama' => $_POST['toko_nama'] ?? '',
            ];

            // Password optional, hanya jika diisi
            if (!empty($_POST['password'])) {
                $updateData['password'] = $_POST['password'];
            }

            // Validasi
            if (empty($updateData['username'])) {
                $errors[] = 'Username harus diisi';
            }
            if (empty($updateData['email'])) {
                $errors[] = 'Email harus diisi';
            }
            if (empty($updateData['nama_lengkap'])) {
                $errors[] = 'Nama lengkap harus diisi';
            }

            // Cek username sudah ada (kecuali yang sedang diedit)
            $existingUser = $this->userModel->getUserByUsername($updateData['username']);
            if (!empty($existingUser) && $existingUser['id'] != $id) {
                $errors[] = 'Username sudah digunakan user lain';
            }

            // Cek email sudah ada (kecuali yang sedang diedit)
            $existingEmail = $this->userModel->getUserByEmail($updateData['email']);
            if (!empty($existingEmail) && $existingEmail['id'] != $id) {
                $errors[] = 'Email sudah digunakan user lain';
            }

            if (empty($errors)) {
                try {
                    $success = $this->userModel->updateUser($updateData);
                    if ($success) {
                        header('Location: /admin/users?success=User berhasil diperbarui');
                        exit;
                    }
                    $errors[] = 'Gagal memperbarui user';
                } catch (Exception $e) {
                    $errors[] = 'Database error: ' . $e->getMessage();
                }
            }

            // Perbarui data untuk form
            $user = $updateData;
        }

        $this->render('edit', [
            'errors' => $errors,
            'user' => $user,
            'pageTitle' => 'Edit User',
        ]);
    }

    /**
     * Hapus user
     */
    public function delete()
    {
        $id = $_GET['id'] ?? null;

        if (!$id || !is_numeric($id)) {
            header('Location: /admin/users');
            exit;
        }

        $user = $this->userModel->getUserById($id);

        if (!$user) {
            header('Location: /admin/users');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $success = $this->userModel->deleteUser($id);
                if ($success) {
                    header('Location: /admin/users?success=User berhasil dihapus');
                    exit;
                }
                $error = 'Gagal menghapus user';
            } catch (Exception $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }

        $this->render('delete', [
            'user' => $user,
            'error' => $error ?? null,
            'pageTitle' => 'Hapus User',
        ]);
    }
}
?>
