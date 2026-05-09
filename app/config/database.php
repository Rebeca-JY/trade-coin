<?php

// Konfigurasi koneksi database global untuk aplikasi.
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'tradecoin';

if (!function_exists('db')) {
    function db()
    {
        static $instance = null;

        if ($instance === null) {
            $instance = \App\Core\Database::getInstance(
                $GLOBALS['DB_HOST'] ?? 'localhost',
                $GLOBALS['DB_USER'] ?? 'root',
                $GLOBALS['DB_PASS'] ?? '',
                $GLOBALS['DB_NAME'] ?? 'tradecoin'
            );
        }

        return $instance;
    }
}

$db = db();