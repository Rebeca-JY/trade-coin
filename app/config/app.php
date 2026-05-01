<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db   = "tradecoin";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_id = $_POST['login_id'];
    $password = $_POST['password'];


    $sql = "SELECT * FROM users WHERE username = '$login_id' OR email = '$login_id'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user) {

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
              
            header("Location: dashboard.php"); 
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username atau Email tidak ditemukan!";
    }
}
?>