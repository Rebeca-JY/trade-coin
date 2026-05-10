<?php
namespace App\controllers;

use App\models\Product;
use App\models\User;

class ProductCreateController
{
   public function index()
    {
        require_once '../app/views/products/index.php';
    }

    public function create()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['user']['id'])) {
            header('Location: /login');
            exit;
        }

        $errors = [];
        $success = null;
        $form = [
            'nama_produk' => '',
            'deskripsi' => '',
            'harga' => '',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $form['nama_produk'] = trim((string)($_POST['nama_produk'] ?? ''));
            $form['deskripsi'] = trim((string)($_POST['deskripsi'] ?? ''));
            $rawHarga = trim((string)($_POST['harga'] ?? ''));
            $form['harga'] = preg_replace('/[^\d]/', '', $rawHarga);

            if ($form['nama_produk'] === '') {
                $errors[] = 'Nama produk wajib diisi.';
            }
            if ($form['harga'] === '' || !ctype_digit($form['harga'])) {
                $errors[] = 'Harga wajib angka (contoh: 50000).';
            }

            $gambarPath = null;
            if (isset($_FILES['product_image']) && ($_FILES['product_image']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
                $file = $_FILES['product_image'];
                $allowedMime = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
                $mime = '';
                if (function_exists('mime_content_type')) {
                    $mime = mime_content_type($file['tmp_name']) ?: '';
                }
                if ($mime === '' && !empty($file['name'])) {
                    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    if ($ext === 'jpg' || $ext === 'jpeg') {
                        $mime = 'image/jpeg';
                    }
                }
                if (!isset($allowedMime[$mime])) {
                    $errors[] = 'Gambar harus JPG, PNG, atau WEBP.';
                } elseif (($file['size'] ?? 0) > 5 * 1024 * 1024) {
                    $errors[] = 'Ukuran gambar maksimal 5MB.';
                } else {
                    $ext = $allowedMime[$mime];
                    $filename = 'product_' . (int) $_SESSION['user']['id'] . '_' . time() . '.' . $ext;
                    $targetDir = dirname(__DIR__, 2) . '/public/uploads/products';
                    $targetPath = $targetDir . '/' . $filename;
                    if (!is_dir($targetDir) && !mkdir($targetDir, 0777, true) && !is_dir($targetDir)) {
                        $errors[] = 'Folder upload tidak bisa dibuat.';
                    } elseif (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                        $errors[] = 'Gagal menyimpan gambar.';
                    } else {
                        $gambarPath = '/public/uploads/products/' . $filename;
                    }
                }
            }

            if (empty($errors)) {
                $productModel = new Product();
                $sellerName = $_SESSION['user']['username'] ?? 'Seller';

                $ok = $productModel->createProduct([
                    'nama_produk' => $form['nama_produk'],
                    'harga' => $form['harga'],
                    'nama_penjual' => $sellerName,
                    'deskripsi' => $form['deskripsi'],
                    'gambar' => $gambarPath,
                    'status' => 'active',
                ]);

                if ($ok) {
                    $userModel = new User();
                    $userModel->promoteToSeller((int)$_SESSION['user']['id']);
                    $_SESSION['user']['role'] = $_SESSION['user']['role'] === 'admin' ? 'admin' : 'seller';

                    $success = 'Produk berhasil diposting.';
                    $form = [
                        'nama_produk' => '',
                        'deskripsi' => '',
                        'harga' => '',
                    ];
                } else {
                    if ($gambarPath && is_file(dirname(__DIR__, 2) . $gambarPath)) {
                        @unlink(dirname(__DIR__, 2) . $gambarPath);
                    }
                    $errors[] = 'Gagal menyimpan produk. Cek data wajib (nama, harga, penjual).';
                }
            }
        }

        require_once '../app/views/products/ProductCreate.php';
    }
}
