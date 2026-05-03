<?php

namespace App\controllers;

use App\models\Product;

/**
 * ProductController - Menangani request untuk halaman produk (customer view)
 * 
 * Flow:
 * 1. Router menerima request GET /products
 * 2. Router memanggil ProductController->index()
 * 3. Controller ambil data dari Product Model
 * 4. Data ditampilkan di View tanpa menampilkan ID
 * 
 * Perbedaan dengan AdminProductController:
 * - ProductController: View untuk customer/buyer (tampil produk, search, filter)
 * - AdminProductController: View untuk admin (manage produk, edit, delete)
 */
class ProductController
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    /**
     * Tampilkan daftar semua produk
     * 
     * Route: GET /products
     * View: app/views/products/ProductList.php
     * 
     * Data yang dikirim ke view:
     * - products: Array berisi semua produk (TANPA ID)
     * - totalProducts: Total jumlah produk
     * 
     * PENTING: 
     * - ID tidak termasuk dalam data yang dikirim ke view
     * - Hanya: nama_produk, harga, nama_penjual, deskripsi, gambar, status
     * 
     * Flow:
     * 1. Ambil semua produk dari model (hanya field yang perlu ditampilkan)
     * 2. Pass ke view untuk di-render menjadi HTML
     * 3. View menampilkan produk dalam format grid/list
     */
    public function index()
    {
        // Ambil semua produk dari model (TANPA ID)
        $products = $this->productModel->getAllProducts();

        // Hitung total produk
        $totalProducts = count($products);

        // Siapkan data untuk view
        $data = [
            'products' => $products,
            'totalProducts' => $totalProducts,
            'pageTitle' => 'Daftar Produk'
        ];

        // Load view
        return $this->render('products/ProductList', $data);
    }

    /**
     * Tampilkan detail produk berdasarkan nama
     * 
     * Route: GET /products/{id}
     * View: app/views/products/Productdetail.php
     * 
     * PENTING TENTANG ID:
     * - Route menggunakan {id}, tapi kita TIDAK menggunakan ID ini secara langsung
     * - ID dari URL bisa berupa nama produk yang di-encode
     * 
     * Contoh URL yang aman:
     * ✓ /products/laptop-gaming
     * ✓ /products/abc123def456 (encoded ID)
     * ✗ /products/1 (expose raw ID - tidak aman)
     */
    public function show($id)
    {
        // Decode ID jika diperlukan
        $productName = urldecode($id);

        // Ambil detail produk (TANPA ID)
        $product = $this->productModel->getProductByName($productName);

        // Jika produk tidak ditemukan
        if (empty($product)) {
            $data = [
                'error' => 'Produk tidak ditemukan',
                'pageTitle' => 'Error 404'
            ];
            return $this->render('errors/404', $data);
        }

        // Siapkan data untuk view
        $data = [
            'product' => $product,
            'pageTitle' => $product['nama_produk'] ?? 'Detail Produk'
        ];

        // Load view
        return $this->render('products/Productdetail', $data);
    }

    /**
     * Search/filter produk
     * 
     * Route: POST /products/search
     * 
     * Flow:
     * 1. User submit search form dengan keyword
     * 2. Controller ambil keyword dari POST
     * 3. Model cari produk yang cocok
     * 4. Return hasil di view yang sama (ProductList)
     */
    public function search()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        $keyword = $_POST['keyword'] ?? '';

        if (empty($keyword)) {
            header('Location: /products');
            exit;
        }

        // Search di model
        $products = $this->productModel->searchProducts($keyword);

        $data = [
            'products' => $products,
            'totalProducts' => count($products),
            'keyword' => htmlspecialchars($keyword),
            'pageTitle' => 'Hasil Pencarian: ' . htmlspecialchars($keyword)
        ];

        return $this->render('products/ProductList', $data);
    }

    /**
     * Filter produk berdasarkan penjual
     * 
     * Route: GET /products/by-penjual/{penjual_name}
     */
    public function filterByPenjual($namaPenjual)
    {
        $namaPenjual = urldecode($namaPenjual);
        $products = $this->productModel->getProductsByPenjual($namaPenjual);

        $data = [
            'products' => $products,
            'totalProducts' => count($products),
            'filterBy' => $namaPenjual,
            'pageTitle' => 'Produk dari ' . htmlspecialchars($namaPenjual)
        ];

        return $this->render('products/ProductList', $data);
    }

    /**
     * Helper: Load dan render view file
     */
    private function render($viewName, $data = [])
    {
        $viewPath = __DIR__ . '/../views/' . $viewName . '.php';

        if (!file_exists($viewPath)) {
            die("View tidak ditemukan: {$viewPath}");
        }

        extract($data);
        ob_start();
        include $viewPath;
        $output = ob_get_clean();
        echo $output;
    }
}
?>