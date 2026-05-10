<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradeCoin — Tambah Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-[#e8f2f6] text-slate-700 min-h-screen font-sans antialiased">

    <?php include '../app/views/component/navbar.php'; ?>

    <div class="px-4 sm:px-6 pb-16 pt-2">
        <?php if (!empty($errors ?? [])): ?>
            <div class="max-w-4xl mx-auto mb-6">
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4">
                    <ul class="list-disc pl-5 space-y-1 text-sm">
                        <?php foreach (($errors ?? []) as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($success ?? null)): ?>
            <div class="max-w-4xl mx-auto mb-6">
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-2xl p-4 text-sm font-semibold">
                    <?= htmlspecialchars($success) ?>
                </div>
            </div>
        <?php endif; ?>

        <form method="POST" action="/products-add" enctype="multipart/form-data"
            class="max-w-4xl mx-auto bg-[#c5dfe9] rounded-[2rem] shadow-[0_8px_30px_rgba(70,120,140,0.12)] border border-white/40 p-6 sm:p-10 md:p-12">

            <div class="flex flex-col lg:flex-row gap-10 lg:gap-14 items-stretch">
                <!-- Kolom gambar -->
                <div class="w-full lg:w-[min(100%,380px)] lg:flex-shrink-0">
                    <label for="product_image" id="drop-zone"
                        class="group relative flex aspect-square w-full cursor-pointer flex-col items-center justify-center overflow-hidden rounded-[1.75rem] border-2 border-dashed border-[#5b9bd4] bg-white/70 transition hover:bg-white/90 hover:border-[#4a8bc9]">
                        <input type="file" name="product_image" id="product_image" accept="image/jpeg,image/png,image/webp"
                            class="sr-only">

                        <img id="image-preview" src="" alt=""
                            class="absolute inset-0 hidden h-full w-full object-cover" />

                        <div id="upload-placeholder" class="flex flex-col items-center gap-3 px-6 text-center pointer-events-none">
                            <i class="fa-regular fa-image text-5xl text-[#5b9bd4]/80"></i>
                            <p class="text-sm font-bold text-slate-600">Klik atau seret gambar ke sini</p>
                            <p class="text-xs text-slate-500">JPG, PNG, atau WEBP · maks. 5MB</p>
                        </div>
                    </label>
                </div>

                <!-- Kolom form -->
                <div class="flex min-w-0 flex-1 flex-col gap-6">
                    <div>
                        <label for="nama_produk" class="mb-2 block text-sm font-bold text-slate-800">Product Name</label>
                        <input type="text" name="nama_produk" id="nama_produk"
                            value="<?= htmlspecialchars($form['nama_produk'] ?? '') ?>"
                            class="w-full rounded-2xl border-0 bg-white px-5 py-3.5 text-slate-800 shadow-sm outline-none ring-1 ring-slate-200/80 transition placeholder:text-slate-400 focus:ring-2 focus:ring-[#7aa8bc]"
                            autocomplete="off">
                    </div>

                    <div class="flex-1 min-h-[140px] flex flex-col">
                        <label for="deskripsi" class="mb-2 block text-sm font-bold text-slate-800">Product Description</label>
                        <textarea name="deskripsi" id="deskripsi" rows="6"
                            class="min-h-[160px] w-full flex-1 resize-y rounded-2xl border-0 bg-white px-5 py-3.5 text-slate-800 shadow-sm outline-none ring-1 ring-slate-200/80 transition placeholder:text-slate-400 focus:ring-2 focus:ring-[#7aa8bc]"><?= htmlspecialchars($form['deskripsi'] ?? '') ?></textarea>
                    </div>

                    <div>
                        <label for="harga" class="mb-2 block text-sm font-bold text-slate-800">Price (Rp)</label>
                        <input type="text" name="harga" id="harga" inputmode="numeric"
                            value="<?= htmlspecialchars($form['harga'] ?? '') ?>"
                            placeholder="Contoh: 50000"
                            class="w-full rounded-2xl border-0 bg-white px-5 py-3.5 text-slate-800 shadow-sm outline-none ring-1 ring-slate-200/80 transition placeholder:text-slate-400 focus:ring-2 focus:ring-[#7aa8bc]"
                            autocomplete="off">
                    </div>

                    <div class="mt-2 flex justify-end pt-2">
                        <button type="submit"
                            class="rounded-2xl bg-[#8aa8b5] px-12 py-3.5 text-base font-bold text-slate-800 shadow-[0_4px_14px_rgba(60,90,105,0.25)] transition hover:bg-[#7d9aa8] active:scale-[0.98]">
                            Post
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        (function () {
            const input = document.getElementById('product_image');
            const zone = document.getElementById('drop-zone');
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('upload-placeholder');

            function showPreview(file) {
                if (!file || !file.type.startsWith('image/')) return;
                const url = URL.createObjectURL(file);
                preview.src = url;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }

            input.addEventListener('change', function () {
                const f = this.files && this.files[0];
                if (f) showPreview(f);
            });

            ['dragenter', 'dragover'].forEach(function (ev) {
                zone.addEventListener(ev, function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    zone.classList.add('bg-white', 'ring-2', 'ring-[#5b9bd4]');
                });
            });
            ['dragleave', 'drop'].forEach(function (ev) {
                zone.addEventListener(ev, function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    zone.classList.remove('bg-white', 'ring-2', 'ring-[#5b9bd4]');
                });
            });
            zone.addEventListener('drop', function (e) {
                const dt = e.dataTransfer;
                if (!dt || !dt.files || !dt.files[0]) return;
                const file = dt.files[0];
                try {
                    const list = new DataTransfer();
                    list.items.add(file);
                    input.files = list.files;
                } catch (err) {
                    input.files = dt.files;
                }
                showPreview(file);
            });
        })();
    </script>
</body>

</html>
