<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradeCoin - Top Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#D1E9F0] text-[#333] font-sans min-h-screen">
    <?php require __DIR__ . '/component/navbar.php'; ?>

    <main class="px-[5%] py-6">
        <div class="max-w-3xl mx-auto">
            <?php if (!empty($topupNotice)): ?>
                <div class="mb-4 rounded-2xl border border-amber-300 bg-amber-50 text-amber-950 px-4 py-3 text-sm">
                    <?= htmlspecialchars($topupNotice['message'] ?? '') ?>
                    <div class="mt-2">
                        <a href="/cart" class="font-semibold underline">Kembali ke keranjang</a>
                    </div>
                </div>
            <?php endif; ?>
            <section class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm mb-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold">Top Up Page</h1>
                    <div class="flex items-center gap-2 rounded-full border px-4 py-1 font-semibold">
                        <i class="fa-solid fa-coins text-yellow-500"></i>
                        <span><?= number_format((int)$userCoins) ?> Coins</span>
                    </div>
                </div>
                <p class="text-sm text-slate-600 mt-2">Pilih nominal top up sesuai kebutuhan.</p>
            </section>

            <?php if (!empty($errors)): ?>
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 text-red-700 px-4 py-3 text-sm">
                    <?php foreach ($errors as $error): ?>
                        <div>• <?= htmlspecialchars($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="mb-4 rounded-xl border border-green-200 bg-green-50 text-green-700 px-4 py-3 text-sm">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <section class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="grid grid-cols-12 gap-2 bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700">
                    <div class="col-span-2">No</div>
                    <div class="col-span-4">Coin</div>
                    <div class="col-span-3">Total</div>
                    <div class="col-span-3 text-right">Aksi</div>
                </div>

                <?php foreach ($packages as $idx => $coin): ?>
                    <?php $price = $coin * 1000; ?>
                    <div class="grid grid-cols-12 gap-2 px-4 py-3 border-t border-slate-100 items-center">
                        <div class="col-span-2 text-sm"><?= $idx + 1 ?></div>
                        <div class="col-span-4 text-sm font-semibold text-[#3e6f7d]">
                            <i class="fa-solid fa-coins text-yellow-500 mr-1"></i> <?= $coin ?>
                        </div>
                        <div class="col-span-3 text-sm">Rp <?= number_format($price, 0, ',', '.') ?></div>
                        <div class="col-span-3 text-right">
                            <form method="POST" action="/topup">
                                <input type="hidden" name="coin" value="<?= $coin ?>">
                                <button type="submit" class="bg-[#6B9CAA] hover:bg-[#5a8d9c] text-white text-sm px-4 py-1.5 rounded-lg transition">
                                    Top up
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>
        </div>
    </main>
</body>
</html>

