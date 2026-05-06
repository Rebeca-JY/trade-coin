<?php
// Konfigurasi Database
$host = 'localhost';
$db   = 'tradecoin'; // Pastikan sama dengan nama database di HeidiSQL
$user = 'root';      // Default user Laragon
$pass = '';          // Default password Laragon kosong

try {
    // Membuat koneksi menggunakan PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    
    // Mengatur mode error agar muncul jika ada masalah
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Opsional: echo "Koneksi berhasil!"; 
} catch (PDOException $e) {
    // Jika koneksi gagal, tampilkan pesan error
    die("Koneksi gagal: " . $e->getMessage());
}
?>