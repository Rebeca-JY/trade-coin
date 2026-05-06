<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradeCoin - Cari Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-[#D1E9F0] text-[#333] font-sans">

<?php include '../app/views/component/navbar.php'; ?>

<main class="px-[5%] py-5">

    <div class="flex items-center gap-[15px] mb-[30px]">
        <button onclick="history.back()" class="bg-white border border-black rounded-full w-[35px] h-[35px] flex items-center justify-center cursor-pointer transition-colors duration-200 hover:bg-[#f0f0f0]">
            <i class="fas fa-arrow-left"></i>
        </button>

        <input type="text" id="searchInput" placeholder="Cari barang atau jasa..." class="px-5 py-2 rounded-[25px] border border-black w-[250px] outline-none">
    </div>

    <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-10 justify-items-center" id="itemGrid">
        <?php if (!empty($products) && is_array($products)): ?>
            <?php foreach ($products as $item): 
                $itemImg = $item['gambar'] ?? '/public/foto/default.png';
                if (strpos($itemImg, '../') === 0) {
                    $itemImg = str_replace('../', '/', $itemImg);
                }
                $itemName = htmlspecialchars($item['nama_produk'] ?? 'Unnamed Product');
                $itemSeller = htmlspecialchars($item['nama_penjual'] ?? 'Unknown');
                $itemPrice = isset($item['harga']) ? number_format($item['harga'], 0, ',', '.') : '0';
            ?>
                <div class="w-full max-w-[250px] transition-transform duration-200 ease-in-out hover:-translate-y-[5px]">
                    <div class="bg-white border border-black rounded-[15px] aspect-[1/0.9] w-full flex justify-center items-center overflow-hidden mb-3">
                        <?php $productIdentifier = isset($item['id']) ? $item['id'] : urlencode($item['nama_produk'] ?? ''); ?>
                        <a href="/products/<?= htmlspecialchars($productIdentifier) ?>" class="w-full h-full block">
                            <img src="<?= htmlspecialchars($itemImg) ?>" alt="<?= $itemName ?>" class="w-full h-full object-cover">
                        </a>
                    </div>
                    <h3 class="text-[1.1rem] mb-1 font-semibold text-[#333]"><?= $itemName ?></h3>
                    <p class="text-[0.9rem] text-[#444] leading-[1.4]">Posted by : <?= $itemSeller ?></p>
                    <p class="text-[0.9rem] text-[#444] leading-[1.4] font-medium">Price : <?= $itemPrice ?> Points</p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-full text-center text-[#444]">
                <p class="text-lg font-semibold">Tidak ada produk untuk ditampilkan.</p>
                <p class="text-sm text-[#666] mt-2">Pastikan database sudah terhubung dan tabel produk tersedia.</p>
            </div>
        <?php endif; ?>
    </section>

</main>

<script src="../js/daftar-barang.js"></script>

</body>
</html>