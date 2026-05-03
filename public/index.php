<?php

// Simple Autoloader - Manual require untuk setiap file yang dibutuhkan
spl_autoload_register(function ($class) {
    // Konversi namespace ke file path
    // App\Core\Router -> ../app/core/Router.php
    $prefix = 'App\\';
    $len = strlen($prefix);
    
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = __DIR__ . '/../app/' . str_replace('\\', '/', strtolower($relative_class)) . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    }
});

require_once '../app/core/Router.php';
use App\Core\Router;

$router = new Router();

// Register Route

// Landing page
// $router->add('GET', '/', 'LandingController', 'landingView');

// Keranjang
$router->add('GET', '/cart', 'CartController', 'cartView');


// Daftar Barang
$router->add('GET', '/products', 'ProductController', 'index');
$router->add('GET', '/products/{id}', 'ProductController', 'show');


// Detail Barang -> ProductController@show
$router->add('GET', '/ProductDetails', 'ProductDetailController', 'DescView');
// edit Barang -> ProductController@edit
// tambah Barang -> ProductController@create
$router->add('GET', '/products-add', 'ProductCreateController', 'create');

// Login Page
$router->add('GET', '/login', 'LoginController', 'loginView');

// Profile Page
$router->add('GET', '/profile', 'ProfileController', 'index');
$router->add('GET', '/profile/{id}', 'ProfileController', 'index');





// Admin pages
$router->add('GET', '/admin/products', 'AdminProductController', 'index'); // Daftar produk di admin
$router->add('GET', '/admin/products/create', 'AdminProductController', 'create'); // Form tambah produk di admin
$router->add('GET', '/admin/products/{id}/edit', 'AdminProductController', 'edit'); // Form edit produk di admin
$router->add('GET', '/admin/products/{id}', 'AdminProductController', 'show'); // detail produk di admin






$router->run();
?>