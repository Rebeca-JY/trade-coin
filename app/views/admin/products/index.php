<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-slate-100 min-h-screen">
    <?php require __DIR__ . '/../component/navbar.php'; ?>
    <div class="max-w-7xl mx-auto py-10 px-4">
        <header class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Admin Dashboard</h1>
                <p class="text-slate-600">Kelola data produk dari database.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="/admin/products/create"
                    class="inline-flex items-center gap-2 rounded-full bg-sky-600 px-5 py-3 text-white font-semibold hover:bg-sky-700 transition">
                    <i class="fa-solid fa-plus"></i> Tambah Produk
                </a>
                <a href="/products"
                    class="inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-5 py-3 text-slate-700 hover:bg-slate-50 transition">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Toko
                </a>
            </div>
        </header>

        <section class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4 mb-8">
            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
                <h2 class="text-lg font-semibold text-slate-700">Total Produk</h2>
                <p class="mt-3 text-4xl font-bold text-sky-600"><?= number_format($totalProducts) ?></p>
            </div>
            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
                <h2 class="text-lg font-semibold text-slate-700">Status Produk</h2>
                <p class="mt-3 text-base text-slate-600">Data produk dapat diedit, dilihat detail, atau dihapus.</p>
            </div>
        </section>

        <div class="overflow-x-auto bg-white rounded-3xl shadow-sm border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-slate-600 uppercase tracking-[0.08em] text-left">
                    <tr>
                        <th class="px-5 py-4">Nama Produk</th>
                        <th class="px-5 py-4">Harga</th>
                        <th class="px-5 py-4">Penjual</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Dibuat</th>
                        <th class="px-5 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td class="px-5 py-4 font-semibold text-slate-800">
                                    <?= htmlspecialchars($product['nama_produk'] ?? '-') ?></td>
                                <td class="px-5 py-4 text-slate-600">Rp
                                    <?= number_format($product['harga'] ?? 0, 0, ',', '.') ?></td>
                                <td class="px-5 py-4 text-slate-600"><?= htmlspecialchars($product['nama_penjual'] ?? '-') ?>
                                </td>
                                <td class="px-5 py-4">
                                    <span
                                        class="rounded-full px-3 py-1 text-xs font-semibold <?= ($product['status'] ?? 'inactive') === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' ?>">
                                        <?= htmlspecialchars($product['status'] ?? 'inactive') ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-slate-500"><?= htmlspecialchars($product['created_at'] ?? '-') ?></td>
                                <td class="px-5 py-4 space-x-2">
                                    <a href="/admin/products/<?= htmlspecialchars($product['id'] ?? '') ?>"
                                        class="inline-flex items-center rounded-full border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">View</a>
                                    <a href="/admin/products/<?= htmlspecialchars($product['id'] ?? '') ?>/edit"
                                        class="inline-flex items-center rounded-full border border-sky-600 bg-sky-50 px-3 py-2 text-xs font-semibold text-sky-700 hover:bg-sky-100">Edit</a>
                                    <form action="/admin/products/<?= htmlspecialchars($product['id'] ?? '') ?>/delete"
                                        method="POST" class="inline-block" onsubmit="return confirm('Hapus produk ini?');">
                                        <button type="submit"
                                            class="inline-flex items-center rounded-full bg-rose-600 px-3 py-2 text-xs font-semibold text-white hover:bg-rose-700">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td class="px-5 py-10 text-center text-slate-500" colspan="6">Belum ada produk di database.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>