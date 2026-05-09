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
            'category' => '',
            'material' => '',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $form['nama_produk'] = trim((string)($_POST['nama_produk'] ?? ''));
            $form['deskripsi'] = trim((string)($_POST['deskripsi'] ?? ''));
            $form['harga'] = trim((string)($_POST['harga'] ?? ''));
            $form['category'] = trim((string)($_POST['category'] ?? ''));
            $form['material'] = trim((string)($_POST['material'] ?? ''));

            if ($form['nama_produk'] === '') {
                $errors[] = 'Nama produk wajib diisi.';
            }
            if ($form['harga'] === '' || !is_numeric($form['harga'])) {
                $errors[] = 'Harga wajib angka.';
            }

            if (empty($errors)) {
                $productModel = new Product();
                $sellerName = $_SESSION['user']['username'] ?? 'Seller';

                $ok = $productModel->createProduct([
                    'nama_produk' => $form['nama_produk'],
                    'harga' => $form['harga'],
                    'nama_penjual' => $sellerName,
                    'deskripsi' => $form['deskripsi'],
                    'gambar' => null,
                    'status' => 'active',
                ]);

                if ($ok) {
                    // otomatis jadi seller kalau sebelumnya buyer (admin tidak diubah)
                    $userModel = new User();
                    $userModel->promoteToSeller((int)$_SESSION['user']['id']);
                    $_SESSION['user']['role'] = $_SESSION['user']['role'] === 'admin' ? 'admin' : 'seller';

                    $success = 'Produk berhasil diposting.';
                    // reset form
                    $form = [
                        'nama_produk' => '',
                        'deskripsi' => '',
                        'harga' => '',
                        'category' => '',
                        'material' => '',
                    ];
                } else {
                    $errors[] = 'Gagal menyimpan produk. Cek data wajib (nama, harga, penjual).';
                }
            }
        }

        require_once '../app/views/products/ProductCreate.php';
    }
}
