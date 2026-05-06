<?php

/**
 * DATABASE DIAGNOSTIC SCRIPT
 * Cek semua tabel, kolom, dan data yang ada
 */

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'tradecoin';

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die('❌ Koneksi gagal: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');

echo "<h2>🔍 DATABASE DIAGNOSTIC</h2>";
echo "<hr>";

// ===== TABEL USERS =====
echo "<h3>1️⃣ TABEL: users</h3>";
$result = mysqli_query($conn, "DESCRIBE users");
if ($result && mysqli_num_rows($result) > 0) {
    echo "✅ Tabel users ada<br>";
    echo "<strong>Struktur:</strong><br>";
    echo "<table border='1' cellpadding='5'><tr><th>Field</th><th>Type</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>" . $row['Field'] . "</td><td>" . $row['Type'] . "</td></tr>";
    }
    echo "</table><br>";
} else {
    echo "❌ Tabel users TIDAK ada<br>";
}

$result = mysqli_query($conn, "SELECT * FROM users");
$userCount = mysqli_num_rows($result);
echo "<strong>Data: $userCount baris</strong><br>";
if ($userCount > 0) {
    echo "<table border='1' cellpadding='5'><tr><th>ID</th><th>Username</th><th>Email</th><th>Coins</th><th>Profile Image</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['username'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['coins'] . "</td>";
        echo "<td>" . $row['profile_image'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "⚠️ Tidak ada data users";
}
echo "<hr>";

// ===== TABEL PURCHASES =====
echo "<h3>2️⃣ TABEL: purchases</h3>";
$result = mysqli_query($conn, "DESCRIBE purchases");
if ($result && mysqli_num_rows($result) > 0) {
    echo "✅ Tabel purchases ada<br>";
    echo "<strong>Struktur:</strong><br>";
    echo "<table border='1' cellpadding='5'><tr><th>Field</th><th>Type</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>" . $row['Field'] . "</td><td>" . $row['Type'] . "</td></tr>";
    }
    echo "</table><br>";
} else {
    echo "❌ Tabel purchases TIDAK ada<br>";
}

$result = mysqli_query($conn, "SELECT * FROM purchases");
$purchaseCount = mysqli_num_rows($result);
echo "<strong>Data: $purchaseCount baris</strong><br>";
if ($purchaseCount > 0) {
    echo "<table border='1' cellpadding='5'><tr><th>ID</th><th>User ID</th><th>Seller Name</th><th>Product Title</th><th>Product Desc</th><th>Product Image</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['user_id'] . "</td>";
        echo "<td>" . $row['seller_name'] . "</td>";
        echo "<td>" . $row['product_title'] . "</td>";
        echo "<td>" . substr($row['product_desc'], 0, 30) . "..." . "</td>";
        echo "<td>" . $row['product_image'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "⚠️ Tidak ada data purchases";
}
echo "<hr>";

// ===== TABEL SALES =====
echo "<h3>3️⃣ TABEL: sales</h3>";
$result = mysqli_query($conn, "DESCRIBE sales");
if ($result && mysqli_num_rows($result) > 0) {
    echo "✅ Tabel sales ada<br>";
    echo "<strong>Struktur:</strong><br>";
    echo "<table border='1' cellpadding='5'><tr><th>Field</th><th>Type</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>" . $row['Field'] . "</td><td>" . $row['Type'] . "</td></tr>";
    }
    echo "</table><br>";
} else {
    echo "❌ Tabel sales TIDAK ada<br>";
}

$result = mysqli_query($conn, "SELECT * FROM sales");
$salesCount = mysqli_num_rows($result);
echo "<strong>Data: $salesCount baris</strong><br>";
if ($salesCount > 0) {
    echo "<table border='1' cellpadding='5'><tr><th>ID</th><th>Seller ID</th><th>Product Title</th><th>Product Desc</th><th>Product Image</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['seller_id'] . "</td>";
        echo "<td>" . $row['product_title'] . "</td>";
        echo "<td>" . substr($row['product_desc'], 0, 30) . "..." . "</td>";
        echo "<td>" . $row['product_image'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "⚠️ Tidak ada data sales";
}
echo "<hr>";

// ===== SUMMARY =====
echo "<h3>📊 SUMMARY</h3>";
echo "<ul>";
echo "<li>Users: <strong>$userCount baris</strong> " . ($userCount > 0 ? "✅" : "❌") . "</li>";
echo "<li>Purchases: <strong>$purchaseCount baris</strong> " . ($purchaseCount > 0 ? "✅" : "❌") . "</li>";
echo "<li>Sales: <strong>$salesCount baris</strong> " . ($salesCount > 0 ? "✅" : "❌") . "</li>";
echo "</ul>";

if ($userCount == 0) {
    echo "<p style='color:red; font-weight:bold;'>⚠️ MASALAH: Tidak ada data user! Insert data dulu atau jalankan setup-profile.php</p>";
}

if ($purchaseCount == 0) {
    echo "<p style='color:orange;'>⚠️ INFO: Tidak ada purchase history. Data akan kosong di profile.</p>";
}

if ($salesCount == 0) {
    echo "<p style='color:orange;'>⚠️ INFO: Tidak ada sales history. Data akan kosong di profile.</p>";
}

mysqli_close($conn);

echo "<hr>";
echo "<p><a href='http://localhost:3000/profile'>👉 Kembali ke Profile</a></p>";
?>
