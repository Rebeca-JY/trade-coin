<?php
namespace App\controllers;
class AdminProductController
{
    public function index()
    {
        require_once '../app/views/admin/products/index.php';
    }

    public function create()
    {
        require_once '../app/views/admin/products/create.php';
    }

    public function edit($id)
    {
        require_once '../app/views/admin/products/edit.php';
    }

    public function show($id)
    {
        require_once '../app/views/admin/products/show.php';
    }

    public function createview()
    {
        require_once '../app/views/products/create.php';
    }
}


?>      