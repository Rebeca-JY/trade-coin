<?php

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


// Detail Barang -> ProductController@show
// edit Barang -> ProductController@edit
// tambah Barang -> ProductController@create



$router->run();
?>