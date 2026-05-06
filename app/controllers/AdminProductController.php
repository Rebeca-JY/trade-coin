<?php
namespace App\controllers;

use App\models\Product;

class AdminProductController
{
    private Product $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    private function render(string $view, array $data = [])
    {
        extract($data);
        require __DIR__ . '/../views/admin/products/' . $view . '.php';
    }

    public function index()
    {
        $products = $this->productModel->getAllProducts();

        $this->render('index', [
            'products' => $products,
            'totalProducts' => count($products),
            'pageTitle' => 'Admin Dashboard',
        ]);
    }

    public function create()
    {
        $errors = [];
        $product = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product = [
                'nama_produk' => $_POST['nama_produk'] ?? '',
                'harga' => $_POST['harga'] ?? '',
                'stock' => $_POST['stock'] ?? '',
                'nama_penjual' => $_POST['nama_penjual'] ?? '',
                'deskripsi' => $_POST['deskripsi'] ?? '',
                'gambar' => $_POST['gambar'] ?? '',
                'status' => $_POST['status'] ?? 'active',
            ];

            // Validasi harga
            if (!empty($product['harga'])) {
                $harga = (float) $product['harga'];
                if ($harga < 0 || $harga > 99999999.99) {
                    $errors[] = 'Harga harus antara 0 dan 99999999.99';
                }
            }

            // Validasi stock
            if (!empty($product['stock'])) {
                $stock = (int) $product['stock'];
                if ($stock < 0) {
                    $errors[] = 'Stok tidak boleh negatif';
                }
            }

            if (empty($errors)) {
                try {
                    $success = $this->productModel->createProduct($product);
                    if ($success) {
                        header('Location: /admin/products');
                        exit;
                    }
                    $errors[] = 'Gagal menyimpan produk. Pastikan semua field wajib sudah diisi dengan benar.';
                } catch (Exception $e) {
                    $errors[] = 'Database error: ' . $e->getMessage();
                }
            }
        }

        $this->render('create', [
            'errors' => $errors,
            'product' => $product,
            'pageTitle' => 'Tambah Produk',
        ]);
    }

    public function edit($id)
    {
        $product = $this->productModel->getProductById($id);
        if (empty($product)) {
            http_response_code(404);
            echo '<h1>Produk tidak ditemukan</h1>';
            return;
        }

        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $updateData = [
                'nama_produk' => $_POST['nama_produk'] ?? '',
                'harga' => $_POST['harga'] ?? '',
                'stock' => $_POST['stock'] ?? '',
                'nama_penjual' => $_POST['nama_penjual'] ?? '',
                'deskripsi' => $_POST['deskripsi'] ?? '',
                'gambar' => $_POST['gambar'] ?? '',
                'status' => $_POST['status'] ?? ($product['status'] ?? 'active'),
            ];

            // Validasi harga
            if (!empty($updateData['harga'])) {
                $harga = (float) $updateData['harga'];
                if ($harga < 0 || $harga > 99999999.99) {
                    $errors[] = 'Harga harus antara 0 dan 99999999.99';
                }
            }

            // Validasi stock
            if (!empty($updateData['stock'])) {
                $stock = (int) $updateData['stock'];
                if ($stock < 0) {
                    $errors[] = 'Stok tidak boleh negatif';
                }
            }

            if (empty($errors)) {
                try {
                    $success = $this->productModel->updateProduct($id, $updateData);
                    if ($success) {
                        header('Location: /admin/products');
                        exit;
                    }
                    $errors[] = 'Gagal memperbarui produk. Silakan cek kembali data Anda.';
                } catch (Exception $e) {
                    $errors[] = 'Database error: ' . $e->getMessage();
                }
            }

            $product = array_merge($product, $updateData);
        }

        $this->render('edit', [
            'errors' => $errors,
            'product' => $product,
            'pageTitle' => 'Edit Produk',
        ]);
    }

    public function delete($id)
    {
        $this->productModel->deleteProduct($id);
        header('Location: /admin/products');
        exit;
    }

    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        if (empty($product)) {
            http_response_code(404);
            echo '<h1>Produk tidak ditemukan</h1>';
            return;
        }

        $this->render('show', [
            'product' => $product,
            'pageTitle' => 'Detail Produk',
        ]);
    }
}
      