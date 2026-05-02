<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradeCoin - Shopping Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-[#f8fafc] text-[#334155] min-h-screen">

    <?php include '../app/views/component/navbar.php'; ?>
    <div class="bg-[#C1E1E9] min-h-screen flex flex-col font-serif">
        <main
            class="flex-grow container mx-auto px-6 py-10 flex flex-col md:flex-row gap-12 items-start justify-center">
            <div
                class="w-full md:w-[400px] aspect-square bg-white rounded-3xl border border-gray-400 flex items-center justify-center relative shadow-sm cursor-pointer overflow-hidden">
                <div class="flex flex-col items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-32 h-32 text-black" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    <span class="absolute top-[41%] right-[38%] text-3xl font-bold">+</span>
                </div>
            </div>

            <div class="w-full max-w-xl space-y-4">
                <div>
                    <label class="block font-bold mb-1 text-black">Product Name</label>
                    <input type="text"
                        class="w-full bg-white border border-gray-400 rounded-full px-5 py-2 focus:ring-1 focus:ring-gray-500 outline-none shadow-sm">
                </div>
                <div>
                    <label class="block font-bold mb-1 text-black">Product Description</label>
                    <textarea rows="4"
                        class="w-full bg-white border border-gray-400 rounded-2xl px-5 py-2 focus:ring-1 focus:ring-gray-500 outline-none shadow-sm"></textarea>
                </div>
                <div>
                    <label class="block font-bold mb-1 text-black">Price</label>
                    <input type="text"
                        class="w-full bg-white border border-gray-400 rounded-full px-5 py-2 focus:ring-1 focus:ring-gray-500 outline-none shadow-sm">
                </div>
                <div>
                    <label class="block font-bold mb-1 text-black">Category</label>
                    <input type="text"
                        class="w-full bg-white border border-gray-400 rounded-full px-5 py-2 focus:ring-1 focus:ring-gray-500 outline-none shadow-sm">
                </div>
                <div>
                    <label class="block font-bold mb-1 text-black">Material</label>
                    <input type="text"
                        class="w-full bg-white border border-gray-400 rounded-full px-5 py-2 focus:ring-1 focus:ring-gray-500 outline-none shadow-sm">
                </div>
            </div>
        </main>

        <footer class="bg-white p-5 border-t border-gray-300 mt-auto">
            <div class="max-w-7xl mx-auto flex justify-end gap-4 px-6 md:px-12">
                <button
                    class="px-14 py-2 border border-black rounded-xl font-bold hover:bg-gray-50 transition-all active:scale-95">
                    Draft
                </button>
                <button
                    class="px-14 py-2 bg-[#8DBCCB] border border-black rounded-xl font-bold hover:bg-[#7ba9b8] transition-all active:scale-95">
                    Post
                </button>
            </div>
        </footer>
    </div>