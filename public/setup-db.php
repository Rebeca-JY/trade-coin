<?php

/**
 * SETUP DATABASE - Buat tabel jika belum ada
 * Akses: http://localhost:3000/setup-db.php
 * Fitur:
 * 1. Cek apakah database tradecoin ada
 * 2. Cek apakah tabel products ada
 * 3. Jika tidak ada, buat otomatis
 * 4. Insert sample data
 */

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'tradecoin';

// Koneksi tanpa database dulu
$conn = mysqli_connect($host, $user, $pass);
if (!$conn) {
    die('❌ Koneksi MySQL gagal: ' . mysqli_connect_error());
}

echo "<h2>🔧 Setup Database Tradecoin</h2>";

// Step 1: Buat database jika belum ada
echo "<h3>1️⃣ Membuat/Mengecek Database 'tradecoin'</h3>";
$sql = "CREATE DATABASE IF NOT EXISTS $db";
if (mysqli_query($conn, $sql)) {
    echo "✅ Database '$db' siap<br>";
} else {
    die("❌ Gagal membuat database: " . mysqli_error($conn));
}

// Connect ke database
mysqli_select_db($conn, $db);
mysqli_set_charset($conn, 'utf8mb4');

// Step 2: Buat tabel users
echo "<h3>2️⃣ Membuat Tabel 'users'</h3>";
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(255),
    nomor_hp VARCHAR(20),
    alamat TEXT,
    role ENUM('buyer', 'seller', 'admin') DEFAULT 'buyer',
    toko_nama VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql_users)) {
    echo "✅ Tabel 'users' siap<br>";
} else {
    echo "⚠️ Info: " . mysqli_error($conn) . "<br>";
}

// Step 3: Buat tabel products
echo "<h3>3️⃣ Membuat Tabel 'product'</h3>";
$sql_products = "CREATE TABLE IF NOT EXISTS product (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_produk VARCHAR(255) NOT NULL,
    harga DECIMAL(12,2) NOT NULL,
    nama_penjual VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    gambar VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql_products)) {
    echo "✅ Tabel 'product' siap<br>";
} else {
    echo "⚠️ Info: " . mysqli_error($conn) . "<br>";
}

// Step 4: Cek apakah table product sudah ada data
echo "<h3>4️⃣ Mengecek Data di Tabel 'product'</h3>";
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM product");
$row = mysqli_fetch_assoc($result);
$total = $row['total'];

echo "Total produk saat ini: <strong>$total</strong><br>";

if ($total == 0) {
    echo "<h3>5️⃣ Insert Sample Data</h3>";
    
    // Insert sample data
    $sample_data = [
        [
            'nama_produk' => 'Laptop Gaming ASUS ROG',
            'harga' => 15000000,
            'nama_penjual' => 'PT Elektronik Maju',
            'deskripsi' => 'Laptop gaming dengan spesifikasi tinggi, RTX 3070, i9-11900H',
            'gambar' => 'laptop-asus.jpg',
            'status' => 'active'
        ],
        [
            'nama_produk' => 'Monitor LG 27 Inch 4K',
            'harga' => 3500000,
            'nama_penjual' => 'Toko Digital Pusat',
            'deskripsi' => 'Monitor 4K UHD 27 inci, refresh rate 60Hz',
            'gambar' => 'monitor-lg.jpg',
            'status' => 'active'
        ],
        [
            'nama_produk' => 'Keyboard Mechanical RGB',
            'harga' => 1200000,
            'nama_penjual' => 'Gaming Store Indonesia',
            'deskripsi' => 'Keyboard gaming mechanical dengan RGB lighting',
            'gambar' => 'keyboard-rgb.jpg',
            'status' => 'active'
        ],
        [
            'nama_produk' => 'Mouse Logitech G Pro',
            'harga' => 800000,
            'nama_penjual' => 'Gadget Center',
            'deskripsi' => 'Mouse gaming profesional dengan DPI tinggi',
            'gambar' => 'mouse-logitech.jpg',
            'status' => 'active'
        ],
        [
            'nama_produk' => 'Headset Wireless Sony',
            'harga' => 2000000,
            'nama_penjual' => 'Audio Store Pro',
            'deskripsi' => 'Headset wireless dengan noise cancellation',
            'gambar' => 'headset-sony.jpg',
            'status' => 'active'
        ]
    ];
    
    $inserted = 0;
    foreach ($sample_data as $data) {
        $sql_insert = "INSERT INTO product (nama_produk, harga, nama_penjual, deskripsi, gambar, status) 
                       VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param('sdssss', 
            $data['nama_produk'],
            $data['harga'],
            $data['nama_penjual'],
            $data['deskripsi'],
            $data['gambar'],
            $data['status']
        );
        
        if ($stmt->execute()) {
            echo "✅ Ditambahkan: " . $data['nama_produk'] . "<br>";
            $inserted++;
        } else {
            echo "❌ Gagal: " . $stmt->error . "<br>";
        }
    }
    
    echo "<strong>$inserted produk berhasil ditambahkan</strong><br>";
} else {
    echo "✅ Data sudah ada, tidak perlu insert sample data<br>";
}

// Step 5: Test select
echo "<h3>6️⃣ Test Select Data</h3>";
$result = mysqli_query($conn, "SELECT nama_produk, harga, nama_penjual, status FROM product LIMIT 3");
if ($result) {
    echo "<table border='1' cellpadding='8' style='margin-top: 10px;'>";
    echo "<tr><th>Nama Produk</th><th>Harga</th><th>Penjual</th><th>Status</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['nama_produk'] . "</td>";
        echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
        echo "<td>" . $row['nama_penjual'] . "</td>";
        echo "<td>" . $row['status'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

mysqli_close($conn);

echo "<hr>";
echo "<h3>✅ Setup Selesai!</h3>";
echo "<p><a href='http://localhost:3000/products'>👉 Klik untuk ke halaman produk</a></p>";
echo "<p><small>Jika ada error, cek console atau database secara langsung</small></p>";
?>
