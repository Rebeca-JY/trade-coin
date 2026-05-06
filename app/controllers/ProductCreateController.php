<?php
namespace App\controllers;

class ProductCreateController
{
   public function index()
    {
        require_once '../app/views/products/index.php';
    }

    public function create()
    {
        require_once '../app/views/products/ProductCreate.php';
    }
}
