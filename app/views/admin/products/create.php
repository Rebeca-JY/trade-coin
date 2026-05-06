<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-slate-100 min-h-screen">
    <div class="max-w-4xl mx-auto py-10 px-4">
        <header class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Tambah Produk Baru</h1>
                <p class="text-slate-600">Masukkan data produk sesuai database.</p>
            </div>
            <a href="/admin/products" class="inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-5 py-3 text-slate-700 hover:bg-slate-50 transition">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </header>

        <?php if (!empty($errors)): ?>
            <div class="mb-6 rounded-3xl border border-rose-200 bg-rose-50 p-5 text-rose-700">
                <ul class="list-disc pl-5">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="/admin/products/create" method="POST" class="space-y-6 rounded-3xl bg-white p-8 shadow-sm border border-slate-200">
            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Nama Produk</label>
                    <input type="text" name="nama_produk" value="<?= htmlspecialchars($product['nama_produk'] ?? '') ?>" class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-800 outline-none focus:border-sky-500" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Harga</label>
                    <input type="number" name="harga" step="0.01" min="0" max="99999999.99" value="<?= htmlspecialchars($product['harga'] ?? '') ?>" class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-800 outline-none focus:border-sky-500" required>
                    <p class="mt-1 text-xs text-slate-500">Maksimal: 99999999.99</p>
                </div>
            </div>
            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Stok</label>
                    <input type="number" name="stock" min="0" value="<?= htmlspecialchars($product['stock'] ?? '') ?>" class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-800 outline-none focus:border-sky-500" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Nama Penjual</label>
                    <input type="text" name="nama_penjual" value="<?= htmlspecialchars($product['nama_penjual'] ?? '') ?>" class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-800 outline-none focus:border-sky-500" required>
                </div>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Nama Penjual</label>
                    <input type="text" name="nama_penjual" value="<?= htmlspecialchars($product['nama_penjual'] ?? '') ?>" class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-800 outline-none focus:border-sky-500" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Status</label>
                    <select name="status" class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-800 outline-none focus:border-sky-500">
                        <option value="active" <?= (isset($product['status']) && $product['status'] === 'active') ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= (isset($product['status']) && $product['status'] === 'inactive') ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Gambar (path)</label>
                <input type="text" name="gambar" value="<?= htmlspecialchars($product['gambar'] ?? '') ?>" class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-800 outline-none focus:border-sky-500" placeholder="Contoh: foto/produk.jpg">
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Deskripsi</label>
                <textarea name="deskripsi" rows="5" class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-800 outline-none focus:border-sky-500"><?= htmlspecialchars($product['deskripsi'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="inline-flex items-center justify-center rounded-full bg-sky-600 px-6 py-3 text-sm font-semibold text-white hover:bg-sky-700 transition">
                <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Produk
            </button>
        </form>
    </div>
</body>
</html>
