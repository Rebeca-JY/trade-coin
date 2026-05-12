<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white p-6 md:p-10 font-sans text-gray-800">

    <?php

    // Jika data tidak dikirim dari controller, ambil dari database manual

    if (!isset($user)) {
        echo '<p class="text-center text-red-600 mt-8">Silakan <a href="/login" class="underline">login</a> untuk melihat profil.</p>';
        exit;
    }

    // Default empty arrays jika tidak ada data
    if (!isset($purchases)) $purchases = [];
    if (!isset($sales)) $sales = [];
    
    $userName = $user['username'] ?? 'User';
    $userEmail = $user['email'] ?? '';
    $coins = $user['coins'] ?? 0;
    $profileImage = $user['profile_image'] ?? '/public/foto/default.png';
    
    // Fix path foto jika masih relative
    if (strpos($profileImage, '../../') === 0) {
        $profileImage = str_replace('../../', '/', $profileImage);
    }
    ?>

    <div class="max-w-5xl mx-auto">
        <header class="flex justify-between items-start mb-8">
            <a href="/products"><button class="font-bold text-lg">‹ Back</button></a>
            <div class="flex flex-col items-end gap-2">
                <div class="flex items-center gap-2 border border-gray-300 rounded-full px-4 py-1 font-bold">
                    <img src="<?= htmlspecialchars(url_for('/foto/Coin.png')) ?>" alt="coin" class="w-5 h-5">
                    <span><?= $coins ?></span>
                </div>
                <a href="/onsale"><button class="border border-gray-300 rounded-full px-5 py-1 text-sm bg-white">On sale</button></a>
            </div>
        </header>

        <?php if (!empty($flash)): ?>
            <div class="mb-6 rounded-xl px-4 py-3 text-sm <?= ($flash['type'] ?? '') === 'success' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' ?>">
                <?= htmlspecialchars($flash['message'] ?? '') ?>
            </div>
        <?php endif; ?>

        <section class="flex flex-col items-center text-center mb-12">
            <div class="w-32 h-32 rounded-full overflow-hidden border-2 border-gray-100 mb-4">
                <img src="<?= $profileImage ?>" alt="Profile" class="w-full h-full object-cover">
            </div>
            <h1 class="text-2xl font-serif text-gray-700"><?= $userName ?></h1>
            <p class="text-gray-500 text-sm"><?= $userEmail ?></p>
            <form action="/profile/upload-photo" method="POST" enctype="multipart/form-data" class="mt-4 flex items-center gap-2">
                <input type="file" name="profile_image" accept=".jpg,.jpeg,.png,.webp" class="text-xs border border-gray-300 rounded-lg px-2 py-1 bg-white" required>
                <button type="submit" class="text-xs bg-[#6B9CAA] text-white px-3 py-1.5 rounded-lg hover:bg-[#5a8d9c] transition">Upload Foto</button>
            </form>
        </section>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <div class="bg-[#C9E1E9] p-6 rounded-[2.5rem]">
                <div class="flex justify-between items-center mb-5 px-2">
                    <h2 class="font-semibold text-lg text-gray-700">Recent Purchase History</h2>
                    <a href="#" class="text-[10px] underline text-gray-600">see more</a>
                </div>
                <div class="space-y-4">
                    <?php if (count($purchases) > 0): ?>
                        <?php foreach($purchases as $item): 
                            $itemImg = $item['img'] ?? '/public/foto/default.png';
                            if (strpos($itemImg, '../../') === 0) {
                                $itemImg = str_replace('../../', '/', $itemImg);
                            }
                        ?>
                        <div class="bg-white/50 backdrop-blur-sm p-4 rounded-3xl flex gap-4">
                            <img src="<?= $itemImg ?>" class="w-20 h-20 rounded-xl object-cover" alt="product">
                            <div class="flex flex-col justify-center">
                                <h3 class="font-bold text-sm"><?= $item['title'] ?></h3>
                                <p class="text-[10px] text-gray-500">From: <?= $item['seller_name'] ?? 'Unknown' ?></p>
                                <p class="text-[10px] text-gray-700 mt-1 line-clamp-2 italic"><?= $item['description'] ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-gray-500 text-sm">Tidak ada riwayat pembelian</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="bg-[#C9E1E9] p-6 rounded-[2.5rem]">
                <div class="flex justify-between items-center mb-5 px-2">
                    <h2 class="font-semibold text-lg text-gray-700">Recent Sales History</h2>
                    <a href="#" class="text-[10px] underline text-gray-600">see more</a>
                </div>
                <div class="space-y-4">
                    <?php if (count($sales) > 0): ?>
                        <?php foreach($sales as $item): 
                            $itemImg = $item['img'] ?? '/public/foto/default.png';
                            if (strpos($itemImg, '../../') === 0) {
                                $itemImg = str_replace('../../', '/', $itemImg);
                            }
                        ?>
                        <div class="bg-white/50 backdrop-blur-sm p-4 rounded-3xl flex gap-4">
                            <img src="<?= $itemImg ?>" class="w-20 h-20 rounded-xl object-cover" alt="service">
                            <div class="flex flex-col justify-center">
                                <h3 class="font-bold text-sm"><?= $item['title'] ?></h3>
                                <p class="text-[10px] text-gray-500">From: <?= $userName ?></p>
                                <p class="text-[10px] text-gray-700 mt-1 line-clamp-2"><?= $item['description'] ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-gray-500 text-sm">Tidak ada riwayat penjualan</p>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

</body>
</html>