<?php
/**
 * Tambah kolom users.profile_image jika belum ada (perbaiki error upload foto profil).
 * Buka sekali di browser: http://localhost/trade-coin/public/fix-profile-image-column.php
 * atau sesuai URL Laragon Anda.
 */

declare(strict_types=1);

require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/config/database.php';

header('Content-Type: text/html; charset=utf-8');

try {
    $pdo = db()->getPdo();
    $check = $pdo->query("SHOW COLUMNS FROM users LIKE 'profile_image'");
    if ($check && $check->rowCount() > 0) {
        echo '<p>✅ Kolom <code>users.profile_image</code> sudah ada. Tidak perlu diubah.</p>';
    } else {
        $pdo->exec('ALTER TABLE users ADD COLUMN profile_image VARCHAR(500) NULL DEFAULT NULL');
        echo '<p>✅ Kolom <code>users.profile_image</code> berhasil ditambahkan. Upload foto profil bisa dicoba lagi.</p>';
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo '<p>❌ Gagal: ' . htmlspecialchars($e->getMessage()) . '</p>';
}

echo '<p><a href="/profile">← Kembali ke profil</a></p>';
