<?php

// Test Koneksi Database
echo "<h2>🔍 Test Koneksi Database MySQL</h2>";

// Konfigurasi Database
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'tradecoin';

echo "<p><strong>Konfigurasi:</strong></p>";
echo "Host: $host<br>";
echo "User: $user<br>";
echo "Password: " . ($pass ? 'Ada' : 'Kosong/Tidak ada') . "<br>";
echo "Database: $db<br>";
echo "<hr>";

// Test 1: Koneksi ke MySQL tanpa database
echo "<h3>1️⃣ Test Koneksi ke MySQL (tanpa database)</h3>";
$conn_test = mysqli_connect($host, $user, $pass);
if ($conn_test) {
    echo "✅ <strong>BERHASIL</strong> - Koneksi ke MySQL berhasil<br>";
} else {
    echo "❌ <strong>GAGAL</strong> - " . mysqli_connect_error() . "<br>";
    die();
}

// Test 2: Lihat database apa saja yang ada
echo "<h3>2️⃣ Database yang tersedia di MySQL</h3>";
$result = mysqli_query($conn_test, "SHOW DATABASES");
if ($result) {
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($result)) {
        $dbname = $row['Database'];
        echo "<li>$dbname";
        if ($dbname === 'tradecoin') {
            echo " ✅ <strong>(TARGET DATABASE)</strong>";
        }
        echo "</li>";
    }
    echo "</ul>";
} else {
    echo "❌ Gagal query: " . mysqli_error($conn_test);
}

// Test 3: Koneksi ke database tradecoin
echo "<h3>3️⃣ Test Koneksi ke Database 'tradecoin'</h3>";
$conn = mysqli_connect($host, $user, $pass, $db);
if ($conn) {
    echo "✅ <strong>BERHASIL</strong> - Koneksi ke database 'tradecoin' berhasil<br>";
    mysqli_set_charset($conn, 'utf8mb4');
    echo "✅ Charset set ke utf8mb4<br>";
} else {
    echo "❌ <strong>GAGAL</strong> - " . mysqli_connect_error() . "<br>";
    echo "⚠️ Database 'tradecoin' tidak ada atau data yang salah<br>";
    die();
}

// Test 4: Lihat tabel apa saja di database tradecoin
echo "<h3>4️⃣ Tabel yang ada di Database 'tradecoin'</h3>";
$result = mysqli_query($conn, "SHOW TABLES");
if ($result) {
    $tableCount = mysqli_num_rows($result);
    if ($tableCount > 0) {
        echo "Jumlah tabel: <strong>$tableCount</strong><br>";
        echo "<ul>";
        while ($row = mysqli_fetch_assoc($result)) {
            $tableName = array_values($row)[0];
            echo "<li>$tableName";
            if ($tableName === 'products') {
                echo " ✅ <strong>(TARGET TABLE)</strong>";
            }
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "⚠️ Database kosong, tidak ada tabel<br>";
    }
} else {
    echo "❌ Gagal query: " . mysqli_error($conn);
}

// Test 5: Cek struktur tabel products
echo "<h3>5️⃣ Struktur Tabel 'product'</h3>";
$result = mysqli_query($conn, "DESCRIBE product");
if ($result && mysqli_num_rows($result) > 0) {
    echo "✅ Tabel 'product' ditemukan<br>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . ($row['Default'] ? $row['Default'] : '-') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "❌ Tabel 'product' tidak ditemukan<br>";
    if ($result === false) {
        echo "Error: " . mysqli_error($conn);
    }
}

// Test 6: Cek jumlah data di products
echo "<h3>6️⃣ Data di Tabel 'product'</h3>";
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM product");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total = $row['total'];
    echo "Jumlah produk: <strong>$total</strong><br>";
    
    if ($total > 0) {
        echo "Preview 3 produk pertama:<br>";
        $result2 = mysqli_query($conn, "SELECT * FROM product LIMIT 3");
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Product Name</th><th>Price</th><th>Seller</th></tr>";
        while ($row = mysqli_fetch_assoc($result2)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['product_name'] . "</td>";
            echo "<td>" . $row['price'] . "</td>";
            echo "<td>" . $row['seller'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} else {
    echo "❌ Error: " . mysqli_error($conn);
}

// Test 7: Test PDO (mysqli select function)
echo "<h3>7️⃣ Test Query Select (Seperti di Application)</h3>";
$query = "SELECT product_name, price, seller FROM product LIMIT 1";
$result = mysqli_query($conn, $query);
if ($result) {
    if (mysqli_num_rows($result) > 0) {
        echo "✅ Query SELECT berhasil<br>";
        $data = mysqli_fetch_assoc($result);
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    } else {
        echo "⚠️ Query berhasil tapi tidak ada data (tabel kosong)";
    }
} else {
    echo "❌ Query gagal: " . mysqli_error($conn);
}

mysqli_close($conn);
mysqli_close($conn_test);

echo "<hr>";
echo "<h3>✅ Semua Test Selesai</h3>";
?>
