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
    $userName = "Cathrine Abigail Sabrina";
    $userEmail = "Cathrine.002@ski.sch.id";
    $coins = 53;

    $purchases = [
        [
            "title" => "The Alchemyst Book Series",
            "from" => "Elenore Sophie",
            "desc" => "Full series from the book \"The Alchemyst\" by Michael Scott Eng ver...",
            "img" => "../../public/foto/book.png"
        ],
        [
            "title" => "Correction Tape",
            "from" => "James Luther",
            "desc" => "Good condi, still new, DM for color.",
            "img" => "../../public/foto/tape.png"
        ]
    ];

    $sales = [
        [
            "title" => "Drawing Service",
            "from" => $userName,
            "desc" => "I draw everything you want, from living things until random things...",
            "img" => "../../public/foto/scara.png"
        ],
        [
            "title" => "Infographic Service",
            "from" => $userName,
            "desc" => "I can make any infographic you want just dm me the detail!",
            "img" => "../../public/foto/service.png"
        ]
    ];
    ?>

    <div class="max-w-5xl mx-auto">
        <header class="flex justify-between items-start mb-8">
            <button class="font-bold text-lg">‹ Back</button>
            <div class="flex flex-col items-end gap-2">
                <div class="flex items-center gap-2 border border-gray-300 rounded-full px-4 py-1 font-bold">
                    <img src="../../public/foto/coin.png" alt="coin" class="w-5 h-5">
                    <span><?= $coins ?></span>
                </div>
                <button class="border border-gray-300 rounded-full px-5 py-1 text-sm bg-white">On sale</button>
            </div>
        </header>

        <section class="flex flex-col items-center text-center mb-12">
            <div class="w-32 h-32 rounded-full overflow-hidden border-2 border-gray-100 mb-4">
                <img src="../../public/foto/cath.png" alt="Profile" class="w-full h-full object-cover">
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
                    <?php foreach($purchases as $item): ?>
                    <div class="bg-white/50 backdrop-blur-sm p-4 rounded-3xl flex gap-4">
                        <img src="<?= $item['img'] ?>" class="w-20 h-20 rounded-xl object-cover">
                        <div class="flex flex-col justify-center">
                            <h3 class="font-bold text-sm"><?= $item['title'] ?></h3>
                            <p class="text-[10px] text-gray-500">From: <?= $item['from'] ?></p>
                            <p class="text-[10px] text-gray-700 mt-1 line-clamp-2 italic"><?= $item['desc'] ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="bg-[#C9E1E9] p-6 rounded-[2.5rem]">
                <div class="flex justify-between items-center mb-5 px-2">
                    <h2 class="font-semibold text-lg text-gray-700">Recent Sales History</h2>
                    <a href="#" class="text-[10px] underline text-gray-600">see more</a>
                </div>
                <div class="space-y-4">
                    <?php foreach($sales as $item): ?>
                    <div class="bg-white/50 backdrop-blur-sm p-4 rounded-3xl flex gap-4">
                        <img src="<?= $item['img'] ?>" class="w-20 h-20 rounded-xl object-cover">
                        <div class="flex flex-col justify-center">
                            <h3 class="font-bold text-sm"><?= $item['title'] ?></h3>
                            <p class="text-[10px] text-gray-500">From: <?= $item['from'] ?></p>
                            <p class="text-[10px] text-gray-700 mt-1 line-clamp-2"><?= $item['desc'] ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>

</body>
</html>