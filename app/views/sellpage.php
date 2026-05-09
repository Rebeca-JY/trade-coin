<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sell Item - TradeCoin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#D1E9F0]">

<?php include 'component/navbar.php'; ?>

<main class="min-h-screen py-10 px-4 flex justify-center items-start">
    <div class="w-full max-w-5xl bg-white/30 backdrop-blur-sm rounded-3xl p-8 shadow-sm">
        
        <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
            <div class="mb-6 rounded-xl bg-green-100 border border-green-400 p-4 text-green-700">
                Produk berhasil diposting!
            </div>
        <?php endif; ?>

        <form action="../controllers/UserSellController.php" method="POST" enctype="multipart/form-data">
            <div class="flex flex-col md:flex-row gap-10">
                
                <div class="w-full md:w-1/3">
                    <label for="image-upload" class="cursor-pointer group">
                        <div class="aspect-square bg-white rounded-3xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center hover:border-blue-400 transition-all overflow-hidden relative">
                            <img id="preview-img" class="hidden absolute inset-0 w-full h-full object-cover" />
                            <div id="placeholder-content" class="flex flex-col items-center justify-center text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">Tambah Foto</p>
                            </div>
                            <input id="image-upload" name="product_image" type="file" class="hidden" accept="image/*" required onchange="previewImage(event)" />
                        </div>
                    </label>
                </div>

                <div class="w-full md:w-2/3 space-y-6">
                    <div>
                        <label class="block text-lg font-bold text-gray-800">Product Name</label>
                        <input type="text" name="product_name" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-300 outline-none">
                    </div>

                    <div>
                        <label class="block text-lg font-bold text-gray-800">Product Description</label>
                        <textarea name="description" rows="4" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-300 outline-none resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-lg font-bold text-gray-800">Price (Rp)</label>
                         <input type="number" name="price" required placeholder="Contoh: 50000" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-300 outline-none">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-[#90C2D1] hover:bg-[#7AADBC] py-3 px-12 rounded-xl font-bold shadow-md transition-all">
                            Post
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const output = document.getElementById('preview-img');
        const placeholder = document.getElementById('placeholder-content');
        output.src = reader.result;
        output.classList.remove('hidden');
        placeholder.classList.add('hidden');
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>
</body>
</html>