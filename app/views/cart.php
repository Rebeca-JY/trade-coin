<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradeCoin - Shopping Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-[#f8fafc] text-[#334155] min-h-screen">

<?php include __DIR__ . '/component/navbar.php'; ?>

<main class="max-w-[1000px] mx-auto px-5 pt-10 pb-[150px]">
    <div class="mb-6 flex flex-col gap-2">
        <h1 class="text-3xl font-semibold text-[#0f172a]">Shopping Cart</h1>
        <p class="text-sm text-[#64748b]">Keranjang kamu, total poin, dan kontrol jumlah produk.</p>
    </div>

    <?php if (empty($cartItems)): ?>
        <div class="bg-white rounded-3xl p-8 shadow-sm text-center">
            <h2 class="text-2xl font-semibold text-[#0f172a] mb-3">Keranjang kosong</h2>
            <p class="text-sm text-[#64748b]">Belum ada produk di keranjang. Buka halaman produk dan tambahkan barang.</p>
            <a href="/products" class="inline-flex mt-5 px-6 py-3 rounded-xl bg-black text-white font-semibold hover:bg-[#111]">Lihat Produk</a>
        </div>
    <?php else: ?>
        <?php foreach ($cartItems as $item): ?>
            <div class="cart-item group flex items-center gap-5 bg-white p-5 rounded-[30px] mb-5 shadow-[0_15px_40px_rgba(15,23,42,0.08)] border border-[#f0f4f8] transition-all duration-200 hover:-translate-y-0.5 hover:shadow-[0_20px_50px_rgba(15,23,42,0.12)]">
                <div class="flex items-start">
                    <span class="flex items-center justify-center w-12 h-12 rounded-3xl bg-black text-white shadow-lg">
                        <i class="fas fa-check"></i>
                    </span>
                </div>

                <div class="product-info flex items-center gap-5 flex-1">
                    <img src="<?= htmlspecialchars($item['gambar'] ?: '/foto/default.png') ?>" alt="<?= htmlspecialchars($item['nama_produk']) ?>" class="w-[150px] h-[120px] object-cover rounded-[25px] border border-[#ebeff3] bg-[#f8fafc] p-2">

                    <div class="product-details flex-1">
                        <h3 class="text-2xl font-semibold text-[#0f172a] mb-1"><?= htmlspecialchars($item['nama_produk']) ?></h3>
                        <p class="shop-name text-[#64748b] text-sm mb-2"><?= htmlspecialchars($item['nama_penjual']) ?></p>
                        <p class="unit-price text-[#64748b] text-sm mb-4"><?= number_format($item['harga']) ?> Points</p>

                        <div class="controls flex items-center gap-3">
                            <form method="POST" action="/cart/update" class="flex items-center gap-2">
                                <input type="hidden" name="cart_item_id" value="<?= $item['cart_item_id'] ?>">
                                <input type="hidden" name="quantity" value="<?= max(1, $item['quantity'] - 1) ?>">
                                <button type="submit" class="qty-btn w-9 h-9 rounded-xl border border-[#e2e8f0] bg-white text-[#0f172a] flex items-center justify-center hover:bg-[#f1f5f9]">-</button>
                            </form>

                            <span class="qty-num text-base font-semibold min-w-[30px] text-center"><?= $item['quantity'] ?></span>

                            <form method="POST" action="/cart/update" class="flex items-center gap-2">
                                <input type="hidden" name="cart_item_id" value="<?= $item['cart_item_id'] ?>">
                                <input type="hidden" name="quantity" value="<?= $item['quantity'] + 1 ?>">
                                <button type="submit" class="qty-btn w-9 h-9 rounded-xl border border-[#e2e8f0] bg-white text-[#0f172a] flex items-center justify-center hover:bg-[#f1f5f9]">+</button>
                            </form>

                            <form method="POST" action="/cart/remove">
                                <input type="hidden" name="cart_item_id" value="<?= $item['cart_item_id'] ?>">
                                <button type="submit" class="delete-btn w-10 h-10 rounded-xl bg-[#ffe6e9] text-[#dc2626] flex items-center justify-center hover:bg-[#fecdd3] hover:text-[#b91c1c]">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="item-total text-2xl font-bold text-[#0f172a] text-right min-w-[140px]"><?= number_format($item['harga'] * $item['quantity']) ?> Points</div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</main>

<footer class="fixed bottom-5 left-1/2 -translate-x-1/2 w-[calc(100%-40px)] max-w-[1000px] bg-white px-10 py-5 flex items-center justify-between rounded-[30px] shadow-[0_25px_70px_rgba(15,23,42,0.1)] border border-[#e5e7eb]">
    <div>
        <p class="text-sm text-[#64748b]">Total : <span class="font-semibold text-[#0f172a]"><?= number_format($cartTotalPoints) ?> Coins</span></p>
    </div>
    <button class="btn-checkout bg-black text-white px-10 py-4 rounded-[25px] text-base font-semibold transition-all duration-300 hover:bg-[#111] hover:scale-[1.02] active:scale-95">Check out</button>
</footer>

</body>
</html>