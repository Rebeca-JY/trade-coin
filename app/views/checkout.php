<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradeCoin - Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-white text-[#334155] min-h-screen">
<?php
$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
include __DIR__ . '/component/navbar.php';

$selectedItems = $selectedItems ?? [];
$userCoins = $userCoins ?? 0;

function checkout_image_url(string $gambar, string $basePath): string
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
<main class="max-w-[920px] mx-auto px-5 pt-8 pb-[280px]">
    <?php if (empty($selectedItems)): ?>
        <div class="bg-slate-50 rounded-3xl p-10 text-center border border-slate-100">
            <div class="mb-4">
                <i class="fa-solid fa-exclamation-circle text-5xl text-slate-300"></i>
            </div>
            <h2 class="text-2xl font-semibold text-[#0f172a] mb-2">Tidak ada item yang dipilih</h2>
            <p class="text-sm text-[#64748b] mb-6">Kembali ke keranjang dan centang produk yang ingin dibeli.</p>
            <a href="<?= htmlspecialchars(($basePath ?: '') . '/cart') ?>" class="inline-flex px-6 py-3 rounded-full bg-black text-white text-sm font-semibold hover:bg-[#222] transition">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Kembali ke Cart
            </a>
        </div>
    <?php else: ?>

    <!-- Products Section -->
    <div class="space-y-3">
        <?php 
        $totalBayar = 0;
        $totalItem = 0;
        foreach ($selectedItems as $item):
            $qty = max(1, (int) ($item['quantity'] ?? 1));
            $harga = (float) ($item['harga'] ?? 0);
            $sub = (int) round($qty * $harga);
            $totalBayar += $sub;
            $totalItem += $qty;
            $imgSrc = checkout_image_url((string) ($item['gambar'] ?? ''), $basePath);
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
                        <p class="text-sm font-semibold text-[#0f172a] mt-2">
                            <?= number_format($harga, 0, ',', '.') ?> Points
                            <?php if ($qty > 1): ?>
                                <span class="text-xs text-slate-500 font-normal ml-1">× <?= $qty ?></span>
                            <?php endif; ?>
                        </p>
                    </div>

                    <!-- Price -->
                    <div class="flex-shrink-0 text-right">
                        <p class="text-lg font-bold text-[#0f172a]"><?= number_format($sub, 0, ',', '.') ?> Points</p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Summary Section - Match Design -->
    <div class="mt-8 border border-[#eaeaea] rounded-3xl px-6 py-6 bg-white">
        <!-- Product Row -->
        <div class="flex justify-between items-center mb-4 pb-4 border-b border-[#eaeaea]">
            <span class="font-medium text-[#0f172a]">Product</span>
            <span class="font-medium text-[#0f172a]"><?= $totalItem ?></span>
        </div>

        <!-- Shipping Row -->
        <div class="flex justify-between items-center mb-6 pb-6 border-b border-[#eaeaea]">
            <span class="font-medium text-[#0f172a]">Shipping</span>
            <span class="font-medium text-emerald-600">Free</span>
        </div>

        <!-- Total Row -->
        <div class="flex justify-between items-end">
            <div>
                <p class="text-sm text-slate-500 mb-1">Total :</p>
                <p class="text-3xl font-bold text-[#0f172a]"><?= number_format($totalBayar, 0, ',', '.') ?> <span class="text-lg">Coins</span></p>
            </div>
            <div>
                <?php 
                $canPay = $userCoins >= $totalBayar;
                if ($canPay):
                ?>
                <span class="inline-block px-4 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold">✓ Saldo Cukup</span>
                <?php else: ?>
                <span class="inline-block px-4 py-1 rounded-full bg-red-50 text-red-700 text-xs font-semibold">⚠ Saldo Kurang</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php endif; ?>
</main>

<!-- Fixed Footer -->
<?php if (!empty($selectedItems)): ?>
<footer class="fixed bottom-0 left-0 right-0 bg-white border-t border-[#eaeaea] px-5 py-5">
    <div class="max-w-[920px] mx-auto flex gap-3">
        <a href="<?= htmlspecialchars(($basePath ?: '') . '/cart') ?>" class="flex-1 inline-flex items-center justify-center px-6 py-4 rounded-2xl bg-slate-100 text-[#0f172a] text-sm font-semibold hover:bg-slate-200 transition">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Back
        </a>
        
        <?php 
        $totalBayar = 0;
        foreach ($selectedItems as $item) {
            $qty = max(1, (int) ($item['quantity'] ?? 1));
            $harga = (float) ($item['harga'] ?? 0);
            $totalBayar += (int) round($qty * $harga);
        }
        $canPay = $userCoins >= $totalBayar;
        ?>
        
        <button 
            onclick="document.getElementById('confirm-payment-form').submit()" 
            class="flex-1 inline-flex items-center justify-center px-8 py-4 rounded-2xl font-semibold text-sm transition <?= $canPay ? 'bg-[#7FB3D5] text-white hover:bg-[#5A96B5]' : 'bg-slate-300 text-slate-500 cursor-not-allowed' ?>"
            <?= !$canPay ? 'disabled' : '' ?>
        >
            <i class="fa-solid fa-check-circle mr-2"></i>
            Check out
        </button>
    </div>
</footer>

<!-- Hidden form untuk confirm payment -->
<form id="confirm-payment-form" method="POST" action="<?= htmlspecialchars(($basePath ?: '') . '/cart/checkout-confirm') ?>" style="display: none;">
    <?php foreach ($selectedItems as $item): ?>
        <input type="hidden" name="selected_ids[]" value="<?= htmlspecialchars((string) $item['cart_item_id']) ?>">
    <?php endforeach; ?>
</form>
<?php endif; ?>

</body>
</html>
