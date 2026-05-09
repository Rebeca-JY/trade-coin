<?php

namespace App\controllers;

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/UserProduct.php';

use App\models\UserProduct;

class UserSellController {
    private $productModel;

    public function __construct() {
        $this->productModel = new UserProduct();
    }

    public function store() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../views/sellpage.php');
            exit;
        }

        // --- BYPASS LOGIN UNTUK TESTING ---
        // Pastikan di tabel 'users' kamu sudah ada minimal 1 user dengan ID 1
        $seller_id = 1; 
        // ----------------------------------

        $product_title = $_POST['product_name'] ?? '';
        $product_desc  = $_POST['description'] ?? '';
        $price         = $_POST['price'] ?? 0; // Ambil data harga

        // Proses Gambar
        $target_dir = __DIR__ . "/../../public/foto/";
        $unique_file_name = "";

        if (!empty($_FILES['product_image']['name'])) {
            $unique_file_name = time() . "_" . basename($_FILES['product_image']['name']);
            $target_file = $target_dir . $unique_file_name;

            if (!move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
                echo "<script>alert('Gagal upload gambar. Cek folder public/foto/'); window.history.back();</script>";
                exit;
            }
        }

       $data = [
        'seller_id'     => $seller_id,
        'product_title' => $product_title,
        'product_desc'  => $product_desc,
        'price'         => $price,
        'product_image' => $unique_file_name
        ];
        

        if ($this->productModel->create($data)) {
            echo "<script>
                    alert('BERHASIL! Data masuk ke database.');
                    window.location.href='../views/sellpage.php?status=success';
                  </script>";
            exit;
        } else {
            echo "Gagal menyimpan ke database. Cek apakah kolom 'price' sudah ada di phpMyAdmin.";
        }
    }
}

$controller = new UserSellController();
$controller->store();