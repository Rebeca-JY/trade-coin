<?php

namespace App\controllers;

trait AdminAuthTrait
{
    private function requireAdmin(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $role = isset($_SESSION['user']['role']) ? (string) $_SESSION['user']['role'] : null;

        if ($role !== 'admin') {
            http_response_code(403);
            echo '<h1>403 - Forbidden</h1>';
            exit;
        }
    }
}
