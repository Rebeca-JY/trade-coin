<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradeCoin - Homepage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-white text-[#333] font-sans min-h-screen">
    <?php include __DIR__ . '/component/navbar.php'; ?>

    <main class="px-[5%] py-4">
        <section class="max-w-2xl mx-auto bg-[#4f8fa0] text-white rounded-2xl px-6 py-6 text-center shadow-sm mb-10">
            <h1 class="text-xl font-semibold">Hello, Welcome to TradeCoin!</h1>
            <p class="text-sm mt-1 opacity-95">Trade and sell your item or service with coin</p>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
            <a href="<?= htmlspecialchars(url_for('/topup')) ?>" class="bg-[#cbeef8] rounded-xl h-40 border border-slate-200 shadow-sm flex items-center justify-center">
                <div class="relative">
                    <img src="<?= htmlspecialchars(url_for('/foto/Coin.png')) ?>" alt="coin" class="w-10 h-10">
                    <span class="absolute -right-3 -bottom-3 text-white bg-yellow-500 w-6 h-6 text-sm rounded-full flex items-center justify-center font-bold">+</span>
                </div>
            </a>
            <a href="<?= htmlspecialchars(url_for('/topup')) ?>" class="bg-[#cbeef8] rounded-xl h-40 border border-slate-200 shadow-sm flex items-center justify-center">
                <div class="text-center">
                    <img src="<?= htmlspecialchars(url_for('/foto/Coin.png')) ?>" alt="coin" class="w-10 h-10 mx-auto">
                    <p class="text-xs mt-3 font-semibold"><?= number_format((int)($userCoins ?? 0)) ?> Coin</p>
                </div>
            </a>
            <a href="/guide" class="bg-[#cbeef8] rounded-xl h-40 border border-slate-200 shadow-sm flex items-center justify-center">
                <div class="bg-white rounded-full w-10 h-10 flex items-center justify-center text-slate-500">
                    <i class="fa-solid fa-info"></i>
                </div>
            </a>
        </section>

        <section>
            <div class="flex items-center justify-between mb-3 border-b border-black pb-1">
                <h2 class="text-2xl font-serif font-semibold">Newest</h2>
                <a href="/products" class="text-sm underline">See all</a>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $item): ?>
                        <?php
                        $itemImg = $item['gambar'] ?? '/public/foto/default.png';
                        if (strpos($itemImg, '../') === 0) {
                            $itemImg = str_replace('../', '/', $itemImg);
                        }
                        $pid = isset($item['id']) ? $item['id'] : urlencode($item['nama_produk'] ?? '');
                        ?>
                        <a href="/products/<?= htmlspecialchars((string)$pid) ?>" class="block">
                            <div class="aspect-square bg-white border border-black rounded-xl overflow-hidden">
                                <img src="<?= htmlspecialchars($itemImg) ?>" alt="<?= htmlspecialchars($item['nama_produk'] ?? 'Produk') ?>" class="w-full h-full object-cover">
                            </div>
                            <div class="pt-2">
                                <p class="font-semibold text-sm line-clamp-1"><?= htmlspecialchars($item['nama_produk'] ?? '-') ?></p>
                                <p class="text-xs text-slate-600 line-clamp-1"><?= htmlspecialchars($item['deskripsi'] ?? '') ?></p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-sm text-slate-600">Belum ada produk untuk ditampilkan.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
</body>
</html>