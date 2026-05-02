<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item - Trade Coin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white p-6 md:p-10 font-sans text-gray-800">

    <div class="max-w-5xl mx-auto">
        <header class="flex justify-between items-start mb-8">
            <a href="onsale.php" class="font-bold text-lg flex items-center hover:opacity-70 transition">
                <span class="mr-2">‹</span> Back
            </a>
            
            <div class="flex items-center gap-2 border border-gray-300 rounded-full px-4 py-1 font-bold shadow-sm">
                <img src="../../public/foto/coin.png" alt="coin" class="w-5 h-5">
                <span>53</span>
            </div>
        </header>

        <div class="bg-[#C9E1E9] p-6 md:p-12 rounded-[2.5rem] shadow-sm">
            <h1 class="text-center text-4xl font-serif text-gray-700 mb-10">On sale</h1>

            <div class="bg-white/40 backdrop-blur-sm border border-gray-400/40 p-8 md:p-12 rounded-[2rem] flex flex-col md:flex-row items-center gap-10">
                
                <div class="relative group">
                    <img src="../../public/foto/scara.png" alt="Item Preview" class="w-48 h-48 md:w-64 md:h-64 rounded-3xl object-cover shadow-md border-2 border-white/50">
                    <div class="absolute inset-0 bg-black/10 rounded-3xl opacity-0 group-hover:opacity-100 transition flex items-center justify-center cursor-pointer">
                        <span class="text-white text-xs font-bold bg-black/40 px-2 py-1 rounded">Change Photo</span>
                    </div>
                </div>

                <form action="#" method="POST" class="flex-1 w-full space-y-4">
                    <input type="text" placeholder="Name" 
                        class="w-full bg-white/60 border border-gray-400 rounded-xl px-4 py-2 focus:outline-none focus:bg-white transition placeholder-gray-500 text-lg font-medium">
                    
                    <textarea placeholder="Description" rows="3"
                        class="w-full bg-white/60 border border-gray-400 rounded-xl px-4 py-2 focus:outline-none focus:bg-white transition placeholder-gray-500"></textarea>
                    
                    <input type="text" placeholder="Price (optional)" 
                        class="w-full bg-white/60 border border-gray-400 rounded-xl px-4 py-2 focus:outline-none focus:bg-white transition placeholder-gray-500">
                    
                    <input type="text" placeholder="Category (optional)" 
                        class="w-full bg-white/60 border border-gray-400 rounded-xl px-4 py-2 focus:outline-none focus:bg-white transition placeholder-gray-500">
                    
                    <input type="text" placeholder="Material (optional)" 
                        class="w-full bg-white/60 border border-gray-400 rounded-xl px-4 py-2 focus:outline-none focus:bg-white transition placeholder-gray-500">

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="bg-white/80 border border-gray-400 rounded-full px-10 py-2 font-medium hover:bg-white transition shadow-sm">
                            save
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</body>
</html>