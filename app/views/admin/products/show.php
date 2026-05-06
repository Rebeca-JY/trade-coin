<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-slate-100 min-h-screen">
    <div class="max-w-5xl mx-auto py-10 px-4">
        <header class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Detail Produk</h1>
                <p class="text-slate-600">Informasi lengkap produk.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="/admin/products" class="inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-5 py-3 text-slate-700 hover:bg-slate-50 transition">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
                <a href="/admin/products/<?= htmlspecialchars($product['id'] ?? '') ?>/edit" class="inline-flex items-center gap-2 rounded-full bg-sky-600 px-5 py-3 text-white hover:bg-sky-700 transition">
                    <i class="fa-solid fa-pen-to-square"></i> Edit
                </a>
            </div>
        </header>

        <div class="grid gap-8 lg:grid-cols-[320px_1fr] items-start">
            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
                <div class="overflow-hidden rounded-3xl bg-slate-100 h-[320px]">
                    <?php if (!empty($product['gambar'])): ?>
                        <img src="/<?= htmlspecialchars($product['gambar']) ?>" alt="<?= htmlspecialchars($product['nama_produk'] ?? 'Gambar Produk') ?>" class="h-full w-full object-cover">
                    <?php else: ?>
                        <div class="flex h-full items-center justify-center text-slate-400">Tidak ada gambar</div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-8 shadow-sm border border-slate-200">
                <h2 class="text-2xl font-bold text-slate-900 mb-3"><?= htmlspecialchars($product['nama_produk'] ?? '-') ?></h2>
                <p class="text-slate-700 mb-4">Penjual: <strong><?= htmlspecialchars($product['nama_penjual'] ?? '-') ?></strong></p>
                <p class="text-slate-700 mb-6">Harga: <strong>Rp <?= number_format($product['harga'] ?? 0, 0, ',', '.') ?></strong></p>
                <div class="mb-6 rounded-3xl bg-slate-50 p-5 text-slate-700">
                    <h3 class="text-sm uppercase text-slate-500 tracking-[0.15em] mb-3">Deskripsi</h3>
                    <p class="whitespace-pre-line"><?= htmlspecialchars($product['deskripsi'] ?? '-') ?></p>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div class="rounded-3xl bg-slate-50 p-5">
                        <p class="text-slate-500 text-sm">Status</p>
                        <p class="mt-2 font-semibold <?= ($product['status'] ?? 'inactive') === 'active' ? 'text-emerald-700' : 'text-amber-700' ?>"><?= htmlspecialchars($product['status'] ?? 'inactive') ?></p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-5">
                        <p class="text-slate-500 text-sm">Dibuat</p>
                        <p class="mt-2 text-slate-700"><?= htmlspecialchars($product['created_at'] ?? '-') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
