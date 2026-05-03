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
        $db = new \App\Core\Database();
        
        // Get user (default user ID = 1)
        $userId = $_GET['id'] ?? 1;
        $user = $db->selectOne("SELECT id, username, email, coins, profile_image FROM users WHERE id = ?", [$userId]);
        
        if (!$user) {
            die('<h1>User tidak ditemukan</h1>');
        }
        
        // Get purchases
        $purchases = $db->select("SELECT id, product_title as title, seller_name, product_desc as description, product_image as img FROM purchases WHERE user_id = ? ORDER BY id DESC LIMIT 10", [$userId]);
        if (!is_array($purchases)) $purchases = [];
        
        // Get sales
        $sales = $db->select("SELECT id, product_title as title, seller_id, product_desc as description, product_image as img FROM sales WHERE seller_id = ? ORDER BY id DESC LIMIT 10", [$userId]);
        if (!is_array($sales)) $sales = [];
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
            <button class="font-bold text-lg">‹ Back</button>
            <div class="flex flex-col items-end gap-2">
                <div class="flex items-center gap-2 border border-gray-300 rounded-full px-4 py-1 font-bold">
                    <img src="/public/foto/coin.png" alt="coin" class="w-5 h-5">
                    <span><?= $coins ?></span>
                </div>
                <button class="border border-gray-300 rounded-full px-5 py-1 text-sm bg-white">On sale</button>
            </div>
        </header>

        <section class="flex flex-col items-center text-center mb-12">
            <div class="w-32 h-32 rounded-full overflow-hidden border-2 border-gray-100 mb-4">
                <img src="<?= $profileImage ?>" alt="Profile" class="w-full h-full object-cover">
            </div>
            <h1 class="text-2xl font-serif text-gray-700"><?= $userName ?></h1>
            <p class="text-gray-500 text-sm"><?= $userEmail ?></p>
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