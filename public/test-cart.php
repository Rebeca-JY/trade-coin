<?php
/**
 * Test Add to Cart Function
 * Jalankan dengan: curl atau di browser langsung
 */

session_start();

// Koneksi database
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'tradecoin';

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die(json_encode(['error' => 'Koneksi DB gagal: ' . mysqli_connect_error()]));
}

mysqli_set_charset($conn, 'utf8mb4');

// Set user session untuk test
$_SESSION['user'] = ['id' => 1];

echo "<h2>🧪 Test Add to Cart</h2>";

// 1. Cek tabel cart ada atau tidak
echo "<h3>1. Cek Tabel Cart</h3>";
$checkCart = mysqli_query($conn, "SHOW TABLES LIKE 'cart'");
if ($checkCart && mysqli_num_rows($checkCart) > 0) {
    echo "✅ Tabel 'cart' ADA<br>";
} else {
    echo "❌ Tabel 'cart' TIDAK ADA<br>";
    echo "<strong>Membuat tabel cart...</strong><br>";
    $sql = "CREATE TABLE IF NOT EXISTS cart (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        product_id INT NOT NULL,
        quantity INT NOT NULL DEFAULT 1,
        added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    if (mysqli_query($conn, $sql)) {
        echo "✅ Tabel cart berhasil dibuat<br>";
    } else {
        die("❌ Gagal membuat tabel: " . mysqli_error($conn));
    }
}

// 2. Cek produk dengan ID 8
echo "<h3>2. Cek Produk ID 8</h3>";
$checkProduct = mysqli_query($conn, "SELECT * FROM product WHERE id = 8");
if ($checkProduct && mysqli_num_rows($checkProduct) > 0) {
    $prod = mysqli_fetch_assoc($checkProduct);
    echo "✅ Produk ID 8 ditemukan:<br>";
    echo "  - Nama: " . $prod['nama_produk'] . "<br>";
    echo "  - Harga: " . $prod['harga'] . "<br>";
} else {
    // Ambil produk pertama untuk test
    $checkProduct = mysqli_query($conn, "SELECT id FROM product LIMIT 1");
    if ($checkProduct && mysqli_num_rows($checkProduct) > 0) {
        $prod = mysqli_fetch_assoc($checkProduct);
        $productId = $prod['id'];
        echo "⚠️ Produk ID 8 tidak ada, menggunakan ID " . $productId . "<br>";
    } else {
        die("❌ Tidak ada produk di database");
    }
}

// 3. Insert ke cart manual
echo "<h3>3. Test Insert ke Cart</h3>";
$userId = 1;
$prodId = $_GET['product_id'] ?? 8;
$qty = $_GET['quantity'] ?? 1;

$insertSql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $insertSql);
if (!$stmt) {
    die("❌ Prepare error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "iii", $userId, $prodId, $qty);
if (mysqli_stmt_execute($stmt)) {
    echo "✅ Insert berhasil<br>";
    echo "  - User ID: $userId<br>";
    echo "  - Product ID: $prodId<br>";
    echo "  - Quantity: $qty<br>";
} else {
    echo "❌ Insert gagal: " . mysqli_error($conn) . "<br>";
}

// 4. Lihat isi cart
echo "<h3>4. Isi Cart</h3>";
$cartSql = "SELECT c.*, p.nama_produk, p.harga FROM cart c 
            LEFT JOIN product p ON c.product_id = p.id 
            WHERE c.user_id = ? 
            ORDER BY c.added_at DESC";
$stmt = mysqli_prepare($conn, $cartSql);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Cart ID</th><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
    $subtotal = $row['harga'] * $row['quantity'];
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['nama_produk'] . "</td>";
    echo "<td>" . number_format($row['harga']) . "</td>";
    echo "<td>" . $row['quantity'] . "</td>";
    echo "<td>" . number_format($subtotal) . "</td>";
    echo "</tr>";
}
echo "</table>";

mysqli_close($conn);
?>
<hr>
<p><a href="/trade-coin/public/products">← Lihat Produk</a></p>
