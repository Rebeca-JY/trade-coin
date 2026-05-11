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
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/config/app.php';
use App\Core\Router;

$router = new Router();

// Register Route

// Landing page
$router->add('GET', '/', 'LandingController', 'landingView');
$router->add('GET', '/topup', 'TopUpController', 'index');
$router->add('POST', '/topup', 'TopUpController', 'index');

// Keranjang
$router->add('GET', '/cart', 'CartController', 'cartView');


// Daftar Barang
$router->add('GET', '/products', 'ProductController', 'index');
$router->add('GET', '/products/{id}', 'ProductController', 'show');


// Detail Barang -> ProductController@show
// NOTE: ProductDetailController tidak ada, gunakan route produk detail yang sudah ada di /products/{id}
// $router->add('GET', '/ProductDetails', 'ProductDetailController', 'DescView');
// edit Barang -> ProductController@edit
// tambah Barang -> ProductController@create    
$router->add('GET', '/products-add', 'ProductCreateController', 'create');
$router->add('POST', '/products-add', 'ProductCreateController', 'create');

// Sellpage
$router->add('GET', '/sellpage', 'SellPageController', 'index');
$router->add('POST', '/sellpage', 'SellPageController', 'submit');

// Login Page
$router->add('GET', '/login', 'LoginController', 'loginView');
$router->add('POST', '/login', 'LoginController', 'loginSubmit');
$router->add('GET', '/logout', 'LoginController', 'logout');

// Register Page
$router->add('GET', '/register', 'SignupController', 'signupView');
$router->add('POST', '/register', 'SignupController', 'signupSubmit');


// Cart actions
$router->add('GET', '/cart', 'CartController', 'cartView');
$router->add('POST', '/cart/add', 'CartController', 'addItem');
$router->add('POST', '/cart/update', 'CartController', 'updateItem');
$router->add('POST', '/cart/remove', 'CartController', 'removeItem');
$router->add('POST', '/cart/checkout', 'CartController', 'checkout');

// Profile Page
$router->add('GET', '/profile', 'ProfileController', 'index');
$router->add('POST', '/profile/upload-photo', 'ProfileController', 'uploadPhoto');
$router->add('GET', '/profile/{id}', 'ProfileController', 'index');


// Admin pages
$router->add('GET', '/admin', 'AdminDashboardController', 'index'); // Alias dashboard
$router->add('GET', '/admin/dashboard', 'AdminDashboardController', 'index'); // Dashboard utama admin
$router->add('GET', '/admin/manage-points', 'AdminDashboardController', 'managePoints'); // Kelola poin user di admin
$router->add('GET', '/admin/give-points', 'AdminDashboardController', 'givePoints');
$router->add('POST', '/admin/give-points', 'AdminDashboardController', 'givePoints');
$router->add('GET', '/admin/deduct-points', 'AdminDashboardController', 'deductPoints');
$router->add('POST', '/admin/deduct-points', 'AdminDashboardController', 'deductPoints');
$router->add('GET', '/admin/set-points', 'AdminDashboardController', 'setPoints');
$router->add('POST', '/admin/set-points', 'AdminDashboardController', 'setPoints');
$router->add('GET', '/admin/point-history', 'AdminDashboardController', 'pointHistory');


// Admin Products
$router->add('GET', '/admin/products', 'AdminProductController', 'index'); // Daftar produk di admin
$router->add('GET', '/admin/products/create', 'AdminProductController', 'create'); // Form tambah produk di admin
$router->add('POST', '/admin/products/create', 'AdminProductController', 'create'); // Proses tambah produk
$router->add('GET', '/admin/products/{id}/edit', 'AdminProductController', 'edit'); // Form edit produk di admin
$router->add('POST', '/admin/products/{id}/edit', 'AdminProductController', 'edit'); // Proses edit produk
$router->add('POST', '/admin/products/{id}/delete', 'AdminProductController', 'delete'); // Hapus produk di admin
$router->add('GET', '/admin/products/{id}', 'AdminProductController', 'show'); // detail produk di admin

// Admin Users - CRUD Operations
$router->add('GET', '/admin/users', 'AdminUserController', 'index'); // Daftar semua users
$router->add('GET', '/admin/users/create', 'AdminUserController', 'create'); // Form tambah user baru
$router->add('POST', '/admin/users/create', 'AdminUserController', 'create'); // Proses tambah user
$router->add('GET', '/admin/users/show', 'AdminUserController', 'show'); // Lihat detail user
$router->add('GET', '/admin/users/edit', 'AdminUserController', 'edit'); // Form edit user
$router->add('POST', '/admin/users/edit', 'AdminUserController', 'edit'); // Proses edit user
$router->add('GET', '/admin/users/delete', 'AdminUserController', 'delete'); // Form delete user
$router->add('POST', '/admin/users/delete', 'AdminUserController', 'delete'); // Proses delete user




// Jasa / Services CRUD (On sale & Edit)
$router->add('GET', '/onsale', 'UserJasaController', 'index');
$router->add('GET', '/onsale/create', 'UserJasaController', 'create');
$router->add('POST', '/onsale/store', 'UserJasaController', 'store');
$router->add('GET', '/onsale/edit/{id}', 'UserJasaController', 'edit');
$router->add('POST', '/onsale/update/{id}', 'UserJasaController', 'update');
$router->add('POST', '/onsale/delete/{id}', 'UserJasaController', 'delete');

$router->run();
?>