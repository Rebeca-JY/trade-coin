<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cart System Setup</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .info { color: blue; }
        .section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
<h1>🛒 Trade Coin - Cart System Setup</h1>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Database config
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'tradecoin');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die('<div class="section"><p class="error">❌ Database connection failed: ' . $conn->connect_error . '</p></div>');
}
$conn->set_charset("utf8mb4");

// Step 1: Create cart table if not exists
echo '<div class="section">';
echo '<h2>Step 1: Create Cart Table</h2>';

$sql = "CREATE TABLE IF NOT EXISTS cart (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if ($conn->query($sql)) {
    echo '<p class="success">✅ Cart table ready</p>';
} else {
    echo '<p class="error">❌ Error: ' . $conn->error . '</p>';
}

// Verify table structure
$result = $conn->query("SHOW COLUMNS FROM cart");
$columns = [];
echo '<table>';
echo '<tr><th>Column</th><th>Type</th><th>Null</th><th>Key</th></tr>';
while ($row = $result->fetch_assoc()) {
    echo '<tr>';
    echo '<td>' . $row['Field'] . '</td>';
    echo '<td>' . $row['Type'] . '</td>';
    echo '<td>' . $row['Null'] . '</td>';
    echo '<td>' . ($row['Key'] ?: '-') . '</td>';
    echo '</tr>';
    $columns[] = $row['Field'];
}
echo '</table>';
echo '</div>';

// Step 2: Verify tables
echo '<div class="section">';
echo '<h2>Step 2: Verify Database Tables</h2>';

$tables = [];
$result = $conn->query("SHOW TABLES");
while ($row = $result->fetch_row()) {
    $tables[] = $row[0];
}

$required = ['users', 'product', 'cart'];
foreach ($required as $tbl) {
    if (in_array($tbl, $tables) || in_array($tbl.'s', $tables)) {
        echo '<p class="success">✅ Table <code>' . $tbl . '</code> exists</p>';
    } else {
        echo '<p class="error">⚠️ Table <code>' . $tbl . '</code> not found</p>';
    }
}
echo '</div>';

// Step 3: Test data
echo '<div class="section">';
echo '<h2>Step 3: Database Statistics</h2>';

$stats = [
    'users' => 'SELECT COUNT(*) as cnt FROM users',
    'product' => 'SELECT COUNT(*) as cnt FROM product',
    'cart' => 'SELECT COUNT(*) as cnt FROM cart'
];

foreach ($stats as $table => $query) {
    $result = $conn->query($query);
    if ($result) {
        $row = $result->fetch_assoc();
        echo '<p><code>' . $table . '</code>: <strong>' . $row['cnt'] . '</strong> records</p>';
    }
}
echo '</div>';

// Step 4: Sample products
echo '<div class="section">';
echo '<h2>Step 4: Sample Products</h2>';

$result = $conn->query("SELECT id, nama_produk, harga FROM product LIMIT 5");
if ($result && $result->num_rows > 0) {
    echo '<table>';
    echo '<tr><th>ID</th><th>Product Name</th><th>Price</th></tr>';
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . $row['nama_produk'] . '</td>';
        echo '<td>' . number_format($row['harga']) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
}
echo '</div>';

// Step 5: Test add to cart
echo '<div class="section">';
echo '<h2>Step 5: Test Add to Cart Function</h2>';

// Get first user and product
$userResult = $conn->query("SELECT id FROM users LIMIT 1");
$user = $userResult->fetch_assoc();
$userId = $user ? $user['id'] : 1;

$prodResult = $conn->query("SELECT id FROM product LIMIT 1");
$prod = $prodResult->fetch_assoc();
$prodId = $prod ? $prod['id'] : 8;

// Clear previous test
$conn->query("DELETE FROM cart WHERE user_id = $userId AND product_id = $prodId");

// Test insert
$testSql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ($userId, $prodId, 1)";
if ($conn->query($testSql)) {
    echo '<p class="success">✅ Test insert successful</p>';
    echo '<p>User ID: <strong>' . $userId . '</strong></p>';
    echo '<p>Product ID: <strong>' . $prodId . '</strong></p>';
    echo '<p>Quantity: <strong>1</strong></p>';
} else {
    echo '<p class="error">❌ Test insert failed: ' . $conn->error . '</p>';
}
echo '</div>';

// Step 6: View test cart
echo '<div class="section">';
echo '<h2>Step 6: Cart Contents (User ' . $userId . ')</h2>';

$cartSql = "SELECT c.id, c.product_id, c.quantity, c.added_at, 
                   p.nama_produk, p.harga
            FROM cart c
            LEFT JOIN product p ON c.product_id = p.id
            WHERE c.user_id = $userId
            ORDER BY c.added_at DESC";

$cartResult = $conn->query($cartSql);
if ($cartResult && $cartResult->num_rows > 0) {
    echo '<table>';
    echo '<tr><th>Cart ID</th><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th><th>Added</th></tr>';
    while ($row = $cartResult->fetch_assoc()) {
        $subtotal = $row['harga'] * $row['quantity'];
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . $row['nama_produk'] . '</td>';
        echo '<td>' . number_format($row['harga']) . '</td>';
        echo '<td>' . $row['quantity'] . '</td>';
        echo '<td>' . number_format($subtotal) . '</td>';
        echo '<td>' . $row['added_at'] . '</td>';
        echo '</tr>';
    }
    echo '</table>';
    echo '<p class="success">✅ Cart system is working!</p>';
} else {
    echo '<p class="info">ℹ️ No items in cart</p>';
}

echo '</div>';

// Final message
echo '<div class="section">';
echo '<h2>✅ Setup Complete!</h2>';
echo '<p>Your cart system is now ready.</p>';
echo '<p><strong>Next Steps:</strong></p>';
echo '<ul>';
echo '<li><a href="/trade-coin/public/products">Go to Products</a> to add items</li>';
echo '<li><a href="/trade-coin/public/cart">Go to Cart</a> to view cart</li>';
echo '<li>Try "Add to Cart" from any product</li>';
echo '</ul>';
echo '</div>';

$conn->close();
?>

</body>
</html>
