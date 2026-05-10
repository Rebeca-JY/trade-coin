<?php

namespace App\models;

use PDO;
use PDOException;

class UserProduct {
    private $db;

    public function __construct() {
        // Mengambil koneksi PDO dari class Database utama
        $this->db = db()->getConnection(); 
    }

    public function create($data) {
        try {
            // Tabel: sales | Kolom: seller_id, product_title, product_desc, price, product_image
            $query = "INSERT INTO `sales` (`seller_id`, `product_title`, `product_desc`, `price`, `product_image`) 
                      VALUES (:seller_id, :product_title, :product_desc, :price, :product_image)";
            
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(':seller_id', $data['seller_id']);
            $stmt->bindParam(':product_title', $data['product_title']);
            $stmt->bindParam(':product_desc', $data['product_desc']);
            $stmt->bindParam(':price', $data['price']);
            $stmt->bindParam(':product_image', $data['product_image']);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Database Error: " . $e->getMessage());
        } 
    }
}