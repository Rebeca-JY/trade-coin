<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['nama_produk'] ?? 'Detail Produk') ?> - TradeCoin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col">
    <?php $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/'); ?>
    <!-- Header -->
    <header class="sticky top-0 bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between shadow-sm z-50">
        <a href="<?= ($basePath ?: '') . '/products' ?>" class="flex items-center gap-2 text-slate-700 hover:text-slate-900 font-semibold transition">
            <i class="fa-solid fa-chevron-left"></i> Back
        </a>
        <div class="flex-1 text-center">
            <h1 class="text-xl font-bold">TradeCoin</h1>
        </div>
        <div class="w-8"></div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Product Image -->
                <div class="flex items-center justify-center">
                    <div class="bg-white rounded-3xl border-2 border-slate-300 overflow-hidden w-full aspect-square flex items-center justify-center">
                        <?php if (!empty($product['gambar'])): ?>
                            <img src="/<?= htmlspecialchars($product['gambar']) ?>" alt="<?= htmlspecialchars($product['nama_produk']) ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="text-center text-slate-400">
                                <i class="fa-solid fa-image text-8xl mb-4"></i>
                                <p>Tidak ada gambar</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="bg-sky-100 rounded-3xl border-2 border-sky-300 p-8 flex flex-col justify-between">
                    <!-- Title -->
                    <h1 class="text-4xl font-serif font-bold text-slate-900 mb-6">
                        <?= htmlspecialchars($product['nama_produk'] ?? '-') ?>
                    </h1>

                    <!-- Description -->
                    <div class="bg-white rounded-2xl px-6 py-4 mb-5">
                        <p class="text-sm text-slate-700 leading-relaxed">
                            <strong>Description:</strong> <?= htmlspecialchars($product['deskripsi'] ?? 'Tidak ada deskripsi') ?>
                        </p>
                    </div>

                    <!-- Price -->
                    <div class="bg-yellow-200 rounded-full px-6 py-3 mb-4 inline-block">
                        <p class="font-bold text-lg text-slate-800">
                            Price : <?= number_format($product['harga'] ?? 0) ?> Coins
                        </p>
                    </div>

                    <!-- Category -->
                    <div class="bg-white rounded-full px-6 py-3 mb-4">
                        <p class="text-base text-slate-700">
                            <strong>Category :</strong> Stationery
                        </p>
                    </div>

                    <!-- Stock Info -->
                    <div class="bg-white rounded-full px-6 py-3 mb-6">
                        <p class="text-base text-slate-700">
                            <strong>Stock :</strong> <?= htmlspecialchars($product['stock'] ?? 0) ?> items
                        </p>
                    </div>

                    <!-- Seller Info -->
                    <div class="mt-auto pt-6 border-t-2 border-sky-200">
                        <p class="text-2xl font-serif font-bold text-slate-900 mb-4">
                            Username: <?= htmlspecialchars($product['nama_penjual'] ?? 'Unknown') ?>
                        </p>
                        <button class="bg-white px-6 py-2.5 rounded-full font-bold border-none shadow-md text-slate-600 hover:shadow-lg hover:bg-slate-50 transition cursor-pointer">
                            <i class="fa-solid fa-envelope mr-2"></i> Message me!
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bottom Action Bar -->
    <footer class="sticky bottom-0 bg-sky-200 border-t-2 border-sky-300 px-4 py-4">
        <div class="max-w-4xl mx-auto flex gap-4 justify-end">
            <form action="<?= ($basePath ?: '') . '/cart/add' ?>" method="POST" class="m-0">
                <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id'] ?? '0') ?>">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="bg-white border-2 border-slate-800 text-slate-900 font-bold px-12 py-3 rounded-2xl hover:bg-slate-50 transition cursor-pointer">
                    <i class="fa-solid fa-shopping-cart mr-2"></i> Add to cart
                </button>
            </form>
            <button type="button" class="bg-sky-500 text-white font-bold px-12 py-3 rounded-2xl hover:bg-sky-600 transition cursor-pointer" id="buyNowBtn">
                Buy it now
            </button>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stock = <?= htmlspecialchars($product['stock'] ?? 0) ?>;
            const basePath = '<?= $basePath ?: '' ?>';
            const buyNowBtn = document.getElementById('buyNowBtn');

            buyNowBtn.addEventListener('click', function() {
                if (stock <= 0) {
                    alert('Produk tidak tersedia');
                    return;
                }
                window.location.href = basePath + '/cart';
            });
        });
    </script>
