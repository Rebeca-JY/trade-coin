<?php

/**
 * Quick Database Check
 */

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'tradecoin';

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die('❌ Database connection failed: ' . mysqli_connect_error());
}

echo "<h2>🔍 Quick Database Check</h2>";

// Check cart table
$result = mysqli_query($conn, "SHOW TABLES LIKE 'cart'");
if (mysqli_num_rows($result) > 0) {
    echo "<p>✅ Table 'cart' exists</p>";

    // Show structure
    $columns = mysqli_query($conn, "DESCRIBE cart");
    echo "<p>Columns: ";
    while ($col = mysqli_fetch_assoc($columns)) {
        echo $col['Field'] . ' (' . $col['Type'] . '), ';
    }
    echo "</p>";

    // Show data count
    $count = mysqli_query($conn, "SELECT COUNT(*) as total FROM cart");
    $row = mysqli_fetch_assoc($count);
    echo "<p>Records: " . $row['total'] . "</p>";

} else {
    echo "<p>❌ Table 'cart' does NOT exist</p>";
    echo "<p>Creating table 'cart'...</p>";

    $sql = "CREATE TABLE cart (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        product_id INT NOT NULL,
        quantity INT NOT NULL DEFAULT 1,
        added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if (mysqli_query($conn, $sql)) {
        echo "<p>✅ Table 'cart' created successfully</p>";
    } else {
        echo "<p>❌ Failed to create table: " . mysqli_error($conn) . "</p>";
    }
}

mysqli_close($conn);

echo "<hr>";
echo "<p><a href='/products'>← Back to products</a></p>";
?>