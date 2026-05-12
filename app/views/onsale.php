<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>On Sale - Trade Coin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white p-6 md:p-10 font-sans text-gray-800">

    <?php
    $coins = $_SESSION['user']['coins'] ?? 53;
    // $items is passed from the controller
    if (!isset($items)) $items = [];
    ?>

    <div class="max-w-5xl mx-auto">
        <header class="flex justify-between items-start mb-8">
            <a href="profile.php" class="font-bold text-lg flex items-center hover:opacity-70 transition">
                <a href="/products"><span class="mr-2">‹</span> Back</a>
            </a>
            
            <div class="flex items-center gap-2 border border-gray-300 rounded-full px-4 py-1 font-bold shadow-sm">
                <img src="../../public/foto/coin.png" alt="coin" class="w-5 h-5">
                <span><?= $coins ?></span>
            </div>
        </header>

        <div class="bg-[#C9E1E9] p-6 md:p-10 rounded-[2.5rem] shadow-sm min-h-[400px]">
            <div class="flex justify-between items-center mb-8 px-4">
                <h1 class="text-3xl font-serif text-gray-700">On sale</h1>
                <a href="/onsale/create" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-full font-bold shadow transition">
                    + Add New
                </a>
            </div>

            <div class="space-y-6">
                <?php if(empty($items)): ?>
                    <p class="text-center text-gray-500 py-10">Belum ada jasa/produk yang dijual.</p>
                <?php else: ?>
                <?php foreach($items as $item): ?>
                <div class="bg-white/40 backdrop-blur-sm border border-gray-400/30 p-6 rounded-[2rem] flex flex-col md:flex-row items-center gap-6">
                    
                    <img src="<?= htmlspecialchars(!empty($item['gambar']) ? $item['gambar'] : '/public/foto/scara.png') ?>" alt="Item" class="w-32 h-32 md:w-40 md:h-40 rounded-3xl object-cover shadow-sm">
                    
                    <div class="flex-1 text-center md:text-left">
                        <h3 class="text-xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($item['nama_produk'] ?? '') ?></h3>
                        <p class="text-sm text-gray-600 leading-relaxed max-w-md">
                            <?= htmlspecialchars($item['deskripsi'] ?? '') ?>
                        </p>
                    </div>

                    <div class="flex flex-col gap-3">
                        <a href="/onsale/edit/<?= $item['id'] ?>" class="border border-gray-400 rounded-full px-8 py-1.5 text-sm bg-white/80 hover:bg-white transition shadow-sm text-center">
                            Edit
                        </a>
                        <form action="/onsale/delete/<?= $item['id'] ?>" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ini?');">
                            <button type="submit" class="w-full border border-gray-400 rounded-full px-8 py-1.5 text-sm bg-white/80 hover:bg-red-50 hover:text-red-600 transition shadow-sm">
                                Delete
                            </button>
                        </form>
                    </div>

                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>
</html>