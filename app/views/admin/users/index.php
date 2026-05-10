<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Manajemen Users'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<script>
    function closeAlert() {
        const alert = document.getElementById('alert-success');
        if (alert) {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }

    // Auto close setelah 3 detik
    setTimeout(() => {
        closeAlert();
    }, 3000);
</script>

<body class="bg-slate-100 min-h-screen font-sans text-slate-800">
    <?php require __DIR__ . '/../component/navbar.php'; ?>
    
    <div class="max-w-7xl mx-auto py-10 px-4">
        
        <header class="mb-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Manajemen Users</h1>
                <p class="text-slate-500 mt-1 text-sm">Kelola akses, peran, dan data pengguna sistem.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="/products"
                    class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 hover:border-slate-300 hover:bg-slate-50 transition shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
                <a href="/admin/users/create"
                    class="inline-flex items-center gap-2 rounded-full bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-sky-700 hover:shadow-md transition shadow-sm border border-transparent">
                    <i class="fa-solid fa-user-plus"></i> Tambah User Baru
                </a>
            </div>
        </header>

        <?php if (isset($_GET['success'])): ?>
            <div id="alert-success"
                class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl flex items-center justify-between transition-all duration-500 shadow-sm">
                <div class="flex items-center gap-3 font-medium text-sm">
                    <div class="bg-emerald-100 p-1.5 rounded-full text-emerald-600">
                        <i class="fas fa-check"></i>
                    </div>
                    <span><?php echo htmlspecialchars($_GET['success']); ?></span>
                </div>
                <button onclick="closeAlert()" class="text-xl text-emerald-500 hover:text-emerald-700 transition-colors">&times;</button>
            </div>
        <?php endif; ?>

        <section class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3 mb-8">
            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200 flex items-center gap-5 transition hover:shadow-md">
                <div class="w-14 h-14 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-2xl">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Total Users</h2>
                    <p class="mt-1 text-3xl font-bold text-slate-800"><?php echo number_format($totalUsers ?? 0); ?></p>
                </div>
            </div>
            
            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200 flex items-center gap-5 transition hover:shadow-md">
                <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Admin dashboard</h2>
                    <p class="mt-1 text-sm text-slate-700 font-medium">Admin dashboard di peruntukan untuk pengurus website saja</p>
                </div>
            </div>
        </section>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            
            <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                <h3 class="font-bold text-slate-800 text-lg">Daftar Pengguna</h3>
                <div class="relative w-full sm:w-72">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </div>
                    <input type="text" class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-full focus:ring-sky-500 focus:border-sky-500 block w-full pl-10 p-2.5 transition" placeholder="Cari nama atau email...">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm whitespace-nowrap">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-left text-xs font-semibold">
                        <tr>
                            <th class="px-6 py-4">User Info</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4">Tanggal Daftar</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr class="hover:bg-slate-50/70 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['nama_lengkap']); ?>&background=f0f9ff&color=0284c7&bold=true&rounded=true" 
                                                 alt="Avatar" 
                                                 class="w-10 h-10 rounded-full shadow-sm border border-slate-200">
                                            <div>
                                                <div class="font-bold text-slate-800"><?php echo htmlspecialchars($user['nama_lengkap']); ?></div>
                                                <div class="text-slate-500 text-xs flex items-center gap-2 mt-0.5">
                                                    <span>@<?php echo htmlspecialchars($user['username']); ?></span>
                                                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                                    <span><?php echo htmlspecialchars($user['email']); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold
                                            <?php
                                            switch (strtolower($user['role'])) {
                                                case 'admin':
                                                    echo 'bg-rose-50 text-rose-600 border border-rose-100';
                                                    $icon = 'fa-user-gear';
                                                    break;
                                                case 'seller':
                                                    echo 'bg-purple-50 text-purple-600 border border-purple-100';
                                                    $icon = 'fa-store';
                                                    break;
                                                default:
                                                    echo 'bg-sky-50 text-sky-600 border border-sky-100';
                                                    $icon = 'fa-user';
                                            }
                                            ?>
                                        ">
                                            <i class="fa-solid <?php echo $icon; ?> text-[10px]"></i>
                                            <?php echo ucfirst(htmlspecialchars($user['role'])); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 font-medium">
                                        <?php echo date('d M Y', strtotime($user['created_at'])); ?>
                                    </td>
                                    <td class="px-6 py-4 text-center space-x-2">
                                        <a href="/admin/users/show?id=<?php echo $user['id']; ?>"
                                            class="inline-flex items-center rounded-full border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">View</a>
                                        <a href="/admin/users/edit?id=<?php echo $user['id']; ?>"
                                            class="inline-flex items-center rounded-full border border-sky-600 bg-sky-50 px-3 py-2 text-xs font-semibold text-sky-700 hover:bg-sky-100">Edit</a>
                                        <a href="/admin/users/delete?id=<?php echo $user['id']; ?>"
                                            onclick="return confirm('Peringatan: Yakin ingin menghapus user ini secara permanen?');"
                                            class="inline-flex items-center rounded-full bg-rose-600 px-3 py-2 text-xs font-semibold text-white hover:bg-rose-700">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td class="px-6 py-16 text-center" colspan="4">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                            <i class="fa-solid fa-folder-open text-2xl text-slate-300"></i>
                                        </div>
                                        <p class="text-slate-500 font-medium">Belum ada users yang terdaftar.</p>
                                        <p class="text-slate-400 text-xs mt-1">Tambahkan user baru untuk mulai mengelola.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>