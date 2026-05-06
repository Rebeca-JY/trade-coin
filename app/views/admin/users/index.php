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

<body class="bg-gray-50">
    <?php require __DIR__ . '/../component/navbar.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <!-- Header dengan tombol tambah -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Manajemen Users</h1>
                <p class="text-gray-600 mt-2">Total Users: <span class="font-semibold"><?php echo $totalUsers; ?></span>
                </p>
            </div>
            <a href="/admin/users/create"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah User
            </a>
        </div>

        <!-- Success Message -->
        <?php if (isset($_GET['success'])): ?>
            <div id="alert-success"
                class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center justify-between transition-opacity duration-500">
                <div class="flex items-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    <span><?php echo htmlspecialchars($_GET['success']); ?></span>
                </div>
                <button onclick="closeAlert()" class="text-xl">&times;</button>
            </div>
        <?php endif; ?>
        <!-- Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <?php if (!empty($users)): ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b-2 border-gray-300">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">ID</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Username</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Email</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Nama Lengkap</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Role</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Terdaftar</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm text-gray-900"><?php echo $user['id']; ?></td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        <?php echo htmlspecialchars($user['username']); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($user['email']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <?php echo htmlspecialchars($user['nama_lengkap']); ?></td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    <?php
                                    switch ($user['role']) {
                                        case 'admin':
                                            echo 'bg-red-100 text-red-800';
                                            break;
                                        case 'seller':
                                            echo 'bg-purple-100 text-purple-800';
                                            break;
                                        default:
                                            echo 'bg-blue-100 text-blue-800';
                                    }
                                    ?>
                                ">
                                            <?php echo ucfirst($user['role']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <?php echo date('d M Y', strtotime($user['created_at'])); ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="/admin/users/show?id=<?php echo $user['id']; ?>"
                                                class="text-blue-600 hover:text-blue-900 transition" title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="/admin/users/edit?id=<?php echo $user['id']; ?>"
                                                class="text-yellow-600 hover:text-yellow-900 transition" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="/admin/users/delete?id=<?php echo $user['id']; ?>"
                                                class="text-red-600 hover:text-red-900 transition" title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus user ini?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600 text-lg">Belum ada users terdaftar</p>
                    <a href="/admin/users/create"
                        class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
                        Tambah User Pertama
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>