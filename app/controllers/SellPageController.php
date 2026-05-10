<?php
namespace App\controllers;

use App\models\Product;

class SellPageController
{
    private Product $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    private function render(string $view, array $data = [])
    {
        extract($data);
        require __DIR__ . '/../views/' . $view . '.php';
    }

    public function index()
    {
        $this->render('sellpage', [
            'errors' => [],
            'successMessage' => '',
            'formData' => [],
        ]);
    }

    public function submit()
    {
        $errors = [];
        $formData = [
            'product_name' => trim($_POST['product_name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'price' => trim($_POST['price'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'seller_name' => trim($_POST['seller_name'] ?? ''),
            'gambar' => trim($_POST['gambar'] ?? ''),
            'status' => 'active',
        ];

        if ($formData['product_name'] === '') {
            $errors[] = 'Nama produk wajib diisi.';
        }

        if ($formData['price'] === '' || !is_numeric($formData['price']) || (float) $formData['price'] < 0) {
            $errors[] = 'Harga harus diisi dengan nilai angka yang valid.';
        }

        if ($formData['seller_name'] === '') {
            $errors[] = 'Nama penjual wajib diisi.';
        }

        if (!empty($_FILES['product_image']['name'])) {
            $uploadDir = realpath(__DIR__ . '/../../public/foto');
            if ($uploadDir === false) {
                $errors[] = 'Folder upload tidak ditemukan.';
            } else {
                $filename = basename($_FILES['product_image']['name']);
                $targetFile = $uploadDir . DIRECTORY_SEPARATOR . $filename;
                if (move_uploaded_file($_FILES['product_image']['tmp_name'], $targetFile)) {
                    $formData['gambar'] = 'public/foto/' . $filename;
                } else {
                    $errors[] = 'Gagal mengunggah gambar. Pastikan folder public/foto dapat ditulis.';
                }
            }
        }

        if (empty($errors)) {
            try {
                $success = $this->productModel->createProduct([
                    'nama_produk' => $formData['product_name'],
                    'harga' => $formData['price'],
                    'nama_penjual' => $formData['seller_name'],
                    'deskripsi' => $formData['description'],
                    'gambar' => $formData['gambar'],
                    'status' => $formData['status'],
                ]);

                if ($success) {
                    $this->render('sellpage', [
                        'errors' => [],
                        'successMessage' => 'Produk berhasil diposting ke database.',
                        'formData' => [],
                    ]);
                    return;
                }

                $errors[] = 'Gagal menyimpan produk. Mohon cek kembali data yang Anda kirim.';
            } catch (\Throwable $e) {
                $errors[] = 'Database error: ' . $e->getMessage();
            }
        }

        $this->render('sellpage', [
            'errors' => $errors,
            'successMessage' => '',
            'formData' => $formData,
        ]);
    }
}