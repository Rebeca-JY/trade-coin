<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradeCoin - Keranjang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-white text-[#334155] min-h-screen">
<?php
$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
include __DIR__ . '/component/navbar.php';

$cartItems = $cartItems ?? [];
$checkoutFlash = $checkoutFlash ?? null;

function cart_image_url(string $gambar, string $basePath): string
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
<main class="max-w-[920px] mx-auto px-5 pt-8 pb-[140px]">
    <div class="mb-6">
        <h1 class="text-3xl font-semibold text-[#0f172a]">Shopping Cart</h1>
    </div>

    <?php if (!empty($checkoutFlash['message'])): ?>
        <div class="mb-4 rounded-2xl px-4 py-3 text-sm font-medium <?= ($checkoutFlash['type'] ?? '') === 'success' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-red-50 text-red-800 border border-red-200' ?>">
            <?= htmlspecialchars((string) $checkoutFlash['message']) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($cartItems)): ?>
        <div class="bg-slate-50 rounded-3xl p-10 text-center border border-slate-100">
            <h2 class="text-2xl font-semibold text-[#0f172a] mb-2">Keranjang kosong</h2>
            <p class="text-sm text-[#64748b]">Tambahkan produk dari halaman <strong>/products/…</strong>.</p>
            <a href="<?= htmlspecialchars(($basePath ?: '') . '/products') ?>" class="inline-flex mt-6 px-6 py-3 rounded-full bg-black text-white text-sm font-semibold hover:bg-[#222] transition">Lihat produk</a>
        </div>
    <?php else: ?>

    <form id="checkout-form" method="POST" action="<?= htmlspecialchars(($basePath ?: '') . '/cart/checkout') ?>" class="hidden" aria-hidden="true"></form>

    <div class="space-y-4">
        <?php foreach ($cartItems as $item):
            $cid = (int) ($item['cart_item_id'] ?? 0);
            $qty = max(1, (int) ($item['quantity'] ?? 1));
            $harga = (float) ($item['harga'] ?? 0);
            $sub = (int) round($qty * $harga);
            $imgSrc = cart_image_url((string) ($item['gambar'] ?? ''), $basePath);
            ?>
            <article class="cart-row flex flex-wrap sm:flex-nowrap items-stretch gap-4 rounded-3xl border px-5 py-5 shadow-sm bg-white border-[#eaeaea]">
                <div class="flex items-start pt-1 shrink-0">
                    <label class="inline-flex cursor-pointer">
                        <input type="checkbox" form="checkout-form" name="selected_ids[]" value="<?= $cid ?>" checked
                            class="cart-line-cb sr-only peer" data-subtotal="<?= $sub ?>">
                        <span class="flex items-center justify-center w-7 h-7 rounded-lg border-2 border-black bg-white peer-checked:bg-black">
                            <i class="fa-solid fa-check text-[10px] text-white opacity-0 peer-checked:opacity-100"></i>
                        </span>
                    </label>
                </div>
                <div class="flex gap-4 flex-1 min-w-0 items-center">
                    <?php if ($imgSrc !== ''): ?>
                    <img src="<?= $imgSrc ?>" alt="" class="w-[100px] h-[100px] sm:w-[120px] sm:h-[120px] object-cover rounded-2xl border border-[#eaeaea] bg-[#fafafa] shrink-0">
                    <?php else: ?>
                    <div class="w-[100px] h-[100px] sm:w-[120px] sm:h-[120px] rounded-2xl border border-[#eaeaea] bg-[#fafafa] shrink-0 flex items-center justify-center text-slate-400"><i class="fa-solid fa-image text-3xl"></i></div>
                    <?php endif; ?>
                    <div class="min-w-0 flex-1">
                        <h2 class="text-lg font-bold text-[#0f172a] leading-tight"><?= htmlspecialchars((string) ($item['nama_produk'] ?? '-')) ?></h2>
                        <p class="text-sm text-[#64748b] mt-1"><?= htmlspecialchars((string) ($item['nama_penjual'] ?? '')) ?></p>
                        <p class="text-sm text-[#64748b] mt-2"><?= number_format($harga, 0, ',', '.') ?> Points / item</p>
                        <div class="flex items-center gap-2 mt-3 flex-wrap">
                            <form method="POST" action="<?= htmlspecialchars(($basePath ?: '') . '/cart/update') ?>" class="inline-flex items-center gap-2">
                                <input type="hidden" name="cart_item_id" value="<?= $cid ?>">
                                <button type="submit" name="quantity" value="<?= max(1, $qty - 1) ?>" class="w-9 h-9 rounded-xl border border-[#dcdcdc] bg-white hover:bg-[#f4f4f4] font-semibold" <?= $qty <= 1 ? 'disabled' : '' ?>>−</button>
                                <span class="qty text-[15px] font-semibold min-w-[26px] text-center"><?= $qty ?></span>
                                <button type="submit" name="quantity" value="<?= $qty + 1 ?>" class="w-9 h-9 rounded-xl border border-[#dcdcdc] bg-white hover:bg-[#f4f4f4] font-semibold">+</button>
                            </form>
                            <form method="POST" action="<?= htmlspecialchars(($basePath ?: '') . '/cart/remove') ?>" class="inline" onsubmit="return confirm('Hapus item ini dari keranjang?');">
                                <input type="hidden" name="cart_item_id" value="<?= $cid ?>">
                                <button type="submit" class="w-10 h-10 rounded-xl bg-[#fde8ea] text-[#dc2626] hover:bg-[#fcd4d9] ml-1" aria-label="Hapus"><i class="fa-solid fa-trash text-sm"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="w-full sm:w-auto sm:min-w-[100px] text-left sm:text-right sm:pt-8">
                    <p class="text-lg font-bold text-[#0f172a] line-subtotal"><?= number_format($sub, 0, ',', '.') ?> Points</p>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</main>

<?php if (!empty($cartItems)): ?>
<footer class="fixed bottom-5 left-1/2 -translate-x-1/2 w-[calc(100%-32px)] max-w-[920px] bg-white px-8 py-4 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 rounded-[28px] shadow-[0_20px_50px_rgba(15,23,42,0.08)] border border-[#eaeaea]">
    <p class="text-sm text-[#64748b]">
        Total : <span class="font-semibold text-[#0f172a]"><span id="cart-total-coins">0</span> Coins</span>
        <span class="text-xs text-slate-400 block sm:inline sm:ml-2">(item tercentang)</span>
    </p>
    <button type="submit" form="checkout-form" id="btn-checkout" class="w-full sm:w-auto bg-black text-white px-10 py-3.5 rounded-[22px] text-sm font-semibold hover:bg-[#222] transition">
        Check out
    </button>
</footer>

<script>
(function () {
    function nf(n) {
        return new Intl.NumberFormat('id-ID').format(Math.round(Number(n) || 0));
    }
    function refreshTotal() {
        var sum = 0;
        document.querySelectorAll('.cart-line-cb:checked').forEach(function (cb) {
            sum += parseInt(cb.getAttribute('data-subtotal') || '0', 10) || 0;
        });
        var el = document.getElementById('cart-total-coins');
        if (el) el.textContent = nf(sum);
    }
    document.querySelectorAll('.cart-line-cb').forEach(function (cb) {
        cb.addEventListener('change', function () {
            var row = cb.closest('.cart-row');
            if (row) {
                if (cb.checked) {
                    row.classList.remove('opacity-55', 'grayscale', 'bg-slate-100', 'border-slate-200');
                    row.classList.add('bg-white', 'border-[#eaeaea]');
                } else {
                    row.classList.add('opacity-55', 'grayscale', 'bg-slate-100', 'border-slate-200');
                    row.classList.remove('bg-white', 'border-[#eaeaea]');
                }
            }
            refreshTotal();
        });
        cb.dispatchEvent(new Event('change'));
    });
    document.getElementById('btn-checkout').addEventListener('click', function (e) {
        var any = document.querySelector('.cart-line-cb:checked');
        if (!any) {
            e.preventDefault();
            alert('Centang minimal satu produk untuk checkout.');
        }
    });
    refreshTotal();
})();
</script>
<?php endif; ?>

</body>
</html>
