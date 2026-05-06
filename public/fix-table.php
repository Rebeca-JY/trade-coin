<?php

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'tradecoin';

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die('❌ Koneksi gagal: ' . mysqli_connect_error());
}

echo "<h2>🔧 Fix Nama Tabel Database</h2>";

// Rename tabel product → products
echo "<h3>Rename tabel 'product' menjadi 'products'</h3>";

$sql = "ALTER TABLE product RENAME TO products";
if (mysqli_query($conn, $sql)) {
    echo "✅ Tabel berhasil di-rename dari 'product' menjadi 'products'<br>";
} else {
    echo "❌ Error: " . mysqli_error($conn) . "<br>";
}

// Verify
echo "<h3>Verifikasi tabel yang ada:</h3>";
$result = mysqli_query($conn, "SHOW TABLES");
echo "<ul>";
while ($row = mysqli_fetch_assoc($result)) {
    $tableName = array_values($row)[0];
    echo "<li>$tableName";
    if ($tableName === 'products') {
        echo " ✅ (BERHASIL)";
    }
    echo "</li>";
}
echo "</ul>";

// Cek data
echo "<h3>Data di tabel 'products':</h3>";
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM products");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo "Jumlah produk: <strong>" . $row['total'] . "</strong><br>";
    
    if ($row['total'] > 0) {
        $result2 = mysqli_query($conn, "SELECT * FROM products LIMIT 2");
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Nama</th><th>Harga</th></tr>";
        while ($row = mysqli_fetch_assoc($result2)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['nama_produk'] . "</td>";
            echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}

mysqli_close($conn);

echo "<hr>";
echo "<p>✅ Selesai! Sekarang coba akses <a href='http://localhost:3000/products'>http://localhost:3000/products</a></p>";
?>
