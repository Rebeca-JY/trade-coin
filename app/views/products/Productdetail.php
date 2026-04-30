<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradeCoin - Detail Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        h1 { font-family: 'Georgia', serif; }
    </style>
</head>

<body class="bg-[#f4f7f8] text-[#333] min-h-screen flex flex-col">

    <a href="/products" class="inline-block mt-5 ml-[50px] no-underline text-black font-bold transition-all duration-300 hover:-translate-x-1">
        ← Back
    </a>

    <main class="container mx-auto flex flex-wrap justify-center items-start gap-[30px] px-5 pt-10 pb-[120px] flex-1">
        
        <div class="flex-1 max-w-[500px] min-w-[300px]">
            <img src="foto/tape.jpg" alt="Produk" class="w-full h-auto rounded-[25px] border border-[#999] shadow-[0_10px_20px_rgba(0,0,0,0.05)]">
        </div>

        <div class="flex-1 max-w-[450px] min-w-[300px] bg-[#bcd3d8] p-10 rounded-[25px] border border-[#999] flex flex-col shadow-[0_10px_20px_rgba(0,0,0,0.05)]">
            <h1 class="text-center text-[2.5rem] mb-[25px]">Correction Tape</h1>

            <div class="bg-white px-[25px] py-[15px] rounded-[20px] mb-[15px] text-base leading-[1.4] transition-all duration-300 hover:scale-[1.02]">
                Description: You can ask anything about the product with me.
                Just open my profile and dm me or just click “Message me!”
                beside my username below.
            </div>

            <div class="bg-[#fce8a4] px-5 py-3 rounded-[50px] mb-[15px] text-base font-bold transition-all duration-300 hover:scale-[1.02]">
                Price : 5 Coins
            </div>

            <div class="bg-white px-5 py-3 rounded-[50px] mb-[15px] text-base leading-[1.4] transition-all duration-300 hover:scale-[1.02]">
                Category : Stationery
            </div>
            <div class="bg-white px-5 py-3 rounded-[50px] mb-[15px] text-base leading-[1.4] transition-all duration-300 hover:scale-[1.02]">
                Material : Plastic
            </div>

            <div class="mt-[30px]">
                <h2 class="text-2xl mb-[15px] font-semibold">Username: James Luther</h2>
                <button class="bg-white px-[25px] py-2.5 border-none rounded-[50px] font-bold shadow-[0_2px_5px_rgba(0,0,0,0.1)] transition-all duration-300 hover:opacity-90 hover:-translate-y-0.5 active:translate-y-0">
                    Message me!
                </button>
            </div>
        </div>

    </main>

    <div class="fixed bottom-0 w-full bg-[#bcd3d8] py-5 flex justify-center gap-5 border-t border-[#999] z-[100]">
        <button class="bg-white px-[60px] py-3 rounded-xl text-base font-bold border-2 border-black transition-all duration-300 hover:opacity-90 hover:-translate-y-0.5 active:translate-y-0">
            Add to cart
        </button>
        <button class="bg-[#6fa8b3] text-white px-[60px] py-3 rounded-xl text-base font-bold border-2 border-transparent transition-all duration-300 hover:opacity-90 hover:-translate-y-0.5 active:translate-y-0">
            Buy it now
        </button>
    </div>

    <script src="js/detail.js"></script>
</body>
</html>