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
    $coins = 53;
    $onSaleItems = [
        [
            "title" => "Drawing Service",
            "desc" => "I draw everything you want, from living things until random things you want. Dm me for details!",
            "img" => "../../public/foto/scara.png" 
        ],
    ];
    ?>

    <div class="max-w-5xl mx-auto">
        <header class="flex justify-between items-start mb-8">
            <a href="profile.php" class="font-bold text-lg flex items-center hover:opacity-70 transition">
                <span class="mr-2">‹</span> Back
            </a>
            
            <div class="flex items-center gap-2 border border-gray-300 rounded-full px-4 py-1 font-bold shadow-sm">
                <img src="../../public/foto/coin.png" alt="coin" class="w-5 h-5">
                <span><?= $coins ?></span>
            </div>
        </header>

        <div class="bg-[#C9E1E9] p-6 md:p-10 rounded-[2.5rem] shadow-sm min-h-[400px]">
            <h1 class="text-center text-3xl font-serif text-gray-700 mb-8">On sale</h1>

            <div class="space-y-6">
                <?php foreach($onSaleItems as $item): ?>
                <div class="bg-white/40 backdrop-blur-sm border border-gray-400/30 p-6 rounded-[2rem] flex flex-col md:flex-row items-center gap-6">
                    
                    <img src="<?= $item['img'] ?>" alt="Item" class="w-32 h-32 md:w-40 md:h-40 rounded-3xl object-cover shadow-sm">
                    
                    <div class="flex-1 text-center md:text-left">
                        <h3 class="text-xl font-bold text-gray-800 mb-2"><?= $item['title'] ?></h3>
                        <p class="text-sm text-gray-600 leading-relaxed max-w-md">
                            <?= $item['desc'] ?>
                        </p>
                    </div>

                    <div class="flex flex-col gap-3">
                        <button class="border border-gray-400 rounded-full px-8 py-1.5 text-sm bg-white/80 hover:bg-white transition shadow-sm">
                            Edit
                        </button>
                        <button class="border border-gray-400 rounded-full px-8 py-1.5 text-sm bg-white/80 hover:bg-red-50 hover:text-red-600 transition shadow-sm">
                            Delete
                        </button>
                    </div>

                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</body>
</html>