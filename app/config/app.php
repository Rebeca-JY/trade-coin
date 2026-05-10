<?php

/**
 * Base URL path aplikasi (folder di mana index.php berada), tanpa trailing slash.
 * Contoh: '' untuk vhost docroot=public, atau '/trade-coin/public' jika diakses lewat subfolder.
 */
if (!function_exists('app_base_path')) {
    function app_base_path(): string
    {
        static $cached = null;
        if ($cached !== null) {
            return $cached;
        }

        $script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
        $dir = dirname($script);
        $dir = str_replace('\\', '/', $dir);
        $dir = rtrim($dir, '/');

        if ($dir === '/' || $dir === '.' || $dir === '') {
            $cached = '';
        } else {
            $cached = $dir;
        }

        return $cached;
    }
}

if (!function_exists('url_for')) {
    function url_for(string $path): string
    {
        $path = '/' . ltrim($path, '/');
        $base = app_base_path();

        return $base . $path;
    }
}

if (!function_exists('app_path_is_under_products')) {
    function app_path_is_under_products(string $path): bool
    {
        $path = trim($path);
        $base = app_base_path();
        if ($base !== '' && strpos($path, $base . '/products') === 0) {
            return true;
        }

        return strpos($path, '/products') === 0;
    }
}
