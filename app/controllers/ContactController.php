<?php
namespace App\controllers;

class ContactController
{
    public function index()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        require_once '../app/views/contact.php';
    }
}
?>
