<?php

namespace App\controllers;

use App\models\Product;

class UserJasaController
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function getUserId()
    {
        // Untuk testing tanpa login jika diperlukan, uncomment baris di bawah:
        // return 1; 
        
        return $_SESSION['user']['id'] ?? 1; // Default ke 1 untuk testing jika belum login
    }

    private function getUserName()
    {
        return $_SESSION['user']['username'] ?? 'Seller';
    }

    // Menampilkan halaman 'onsale'
    public function index()
    {
        $userName = $this->getUserName();
        // Mengambil produk berdasarkan nama penjual (bisa disesuaikan dengan ID jika tabel mendukung seller_id)
        $items = $this->productModel->getProductsByPenjual($userName);
        
        require_once '../app/views/onsale.php';
    }

    // Menampilkan halaman form create
    public function create()
    {
        $item = null; // Menandakan mode create
        require_once '../app/views/edit.php';
    }

    // Proses menyimpan data baru
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /onsale');
            exit;
        }

        $gambarPath = $this->handleUpload();

        $data = [
            'nama_produk' => $_POST['name'] ?? '',
            'deskripsi' => $_POST['description'] ?? '',
            'harga' => empty($_POST['price']) ? 0 : $_POST['price'],
            'category' => $_POST['category'] ?? '',
            'material' => $_POST['material'] ?? '',
            'nama_penjual' => $this->getUserName(),
            'gambar' => $gambarPath,
            'status' => 'active'
        ];

        if ($this->productModel->createProduct($data)) {
            header('Location: /onsale?status=success');
        } else {
            // Bisa tambahkan error handling yang lebih baik
            echo "<script>alert('Gagal menyimpan data!'); window.history.back();</script>";
        }
    }

    // Menampilkan halaman form edit
    public function edit($id)
    {
        $item = $this->productModel->getProductById($id);
        if (!$item) {
            header('Location: /onsale');
            exit;
        }
        
        require_once '../app/views/edit.php';
    }

    // Proses update data
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /onsale');
            exit;
        }

        $data = [
            'nama_produk' => $_POST['name'] ?? '',
            'deskripsi' => $_POST['description'] ?? '',
            'harga' => empty($_POST['price']) ? 0 : $_POST['price'],
            'category' => $_POST['category'] ?? '',
            'material' => $_POST['material'] ?? '',
        ];

        // Jika ada upload gambar baru
        if (!empty($_FILES['image']['name'])) {
            $gambarPath = $this->handleUpload();
            if ($gambarPath) {
                $data['gambar'] = $gambarPath;
            }
        }

        if ($this->productModel->updateProduct($id, $data)) {
            header('Location: /onsale?status=updated');
        } else {
            echo "<script>alert('Gagal mengupdate data!'); window.history.back();</script>";
        }
    }

    // Proses hapus data
    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->productModel->deleteProduct($id);
        }
        header('Location: /onsale?status=deleted');
        exit;
    }

    // Fungsi helper untuk upload gambar
    private function handleUpload()
    {
        if (empty($_FILES['image']['name'])) {
            return '';
        }

        $target_dir = __DIR__ . "/../../public/uploads/products/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $unique_file_name = time() . "_" . basename($_FILES['image']['name']);
        $target_file = $target_dir . $unique_file_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            return '/public/uploads/products/' . $unique_file_name;
        }

        return '';
    }
}
