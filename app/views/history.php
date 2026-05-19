<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradeCoin - Riwayat Pembelian</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-white text-[#334155] min-h-screen">
<?php
$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
include __DIR__ . '/component/navbar.php';

$checkoutItems = $checkoutItems ?? [];
$checkoutFlash = $checkoutFlash ?? null;

function history_image_url(string $gambar, string $basePath): string
{
    $g = trim($gambar);
    if ($g === '') {
        return '';
    }
    if ($g[0] === '/') {
        return htmlspecialchars($g);
    }

    return htmlspecialchars(($basePath ?: '') . '/' . ltrim($g, '/'));
}
?>
<main class="max-w-[920px] mx-auto px-5 pt-8 pb-[100px]">
    <div class="mb-6">
        <h1 class="text-3xl font-semibold text-[#0f172a]">Riwayat Pembelian</h1>
        <p class="text-sm text-[#64748b] mt-2">Daftar item yang sudah Anda beli</p>
    </div>

    <?php if (!empty($checkoutFlash['message'])): ?>
        <div class="mb-4 rounded-2xl px-4 py-3 text-sm font-medium <?= ($checkoutFlash['type'] ?? '') === 'success' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-red-50 text-red-800 border border-red-200' ?>">
            <?= htmlspecialchars((string) $checkoutFlash['message']) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($checkoutItems)): ?>
        <div class="bg-slate-50 rounded-3xl p-10 text-center border border-slate-100">
            <div class="mb-4">
                <i class="fa-solid fa-box-open text-5xl text-slate-300"></i>
            </div>
            <h2 class="text-2xl font-semibold text-[#0f172a] mb-2">Belum ada pembelian</h2>
            <p class="text-sm text-[#64748b] mb-6">Anda belum melakukan checkout atau membeli item apapun.</p>
            <a href="<?= htmlspecialchars(($basePath ?: '') . '/products') ?>" class="inline-flex px-6 py-3 rounded-full bg-black text-white text-sm font-semibold hover:bg-[#222] transition">
                <i class="fa-solid fa-shopping-bag mr-2"></i>
                Lihat Produk
            </a>
        </div>
    <?php else: ?>

    <div class="space-y-3">
        <?php 
        $totalSpent = 0;
        foreach ($checkoutItems as $item):
            $ciId = (int) ($item['checkout_item_id'] ?? 0);
            $qty = max(1, (int) ($item['quantity'] ?? 1));
            $harga = (float) ($item['harga'] ?? 0);
            $sub = (int) round($qty * $harga);
            $totalSpent += $sub;
            $imgSrc = history_image_url((string) ($item['gambar'] ?? ''), $basePath);
            $checkedOutAt = !empty($item['checked_out_at']) 
                ? date('d M Y - H:i', strtotime($item['checked_out_at']))
                : 'Tanpa tanggal';
            ?>
            <div class="border border-[#eaeaea] rounded-3xl px-6 py-4 bg-white">
                <div class="flex items-center gap-4">
                    <!-- Image -->
                    <div class="flex-shrink-0">
                        <?php if ($imgSrc !== ''): ?>
                        <img src="<?= $imgSrc ?>" alt="" class="w-[100px] h-[100px] object-cover rounded-xl border border-[#eaeaea] bg-[#fafafa]">
                        <?php else: ?>
                        <div class="w-[100px] h-[100px] rounded-xl border border-[#eaeaea] bg-[#fafafa] flex items-center justify-center text-slate-400"><i class="fa-solid fa-image text-2xl"></i></div>
                        <?php endif; ?>
                    </div>

                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base font-bold text-[#0f172a]"><?= htmlspecialchars((string) ($item['nama_produk'] ?? '-')) ?></h3>
                        <p class="text-sm text-[#64748b] mt-1"><?= htmlspecialchars((string) ($item['nama_penjual'] ?? '')) ?></p>
                        <div class="flex items-center gap-2 mt-2 flex-wrap">
                            <p class="text-xs text-slate-500">
                                <i class="fa-solid fa-calendar mr-1"></i>
                                <?= htmlspecialchars($checkedOutAt) ?>
                            </p>
                            <span class="text-xs bg-emerald-50 text-emerald-700 px-2 py-1 rounded-full">✓ Selesai</span>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="flex-shrink-0 text-right">
                        <p class="text-lg font-bold text-[#0f172a]"><?= number_format($sub, 0, ',', '.') ?> Points</p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php endif; ?>
</main>

</body>
</html>
