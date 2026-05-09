<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Poin User</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <?php require __DIR__ . '/../component/navbar.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Kelola Poin User</h1>
                <p class="text-gray-600 mt-2">Total Users: <span class="font-semibold"><?php echo $totalUsers; ?></span></p>
            </div>
            <div class="flex gap-3">
                <a href="/admin/give-points" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-plus"></i> Berikan Poin
                </a>
                <a href="/admin/deduct-points" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-minus"></i> Kurangi Poin
                </a>
                <a href="/admin/set-points" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-edit"></i> Set Poin
                </a>
                <a href="/admin" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <?php if (!empty($users)): ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b-2 border-gray-300">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">No</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Username</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Email</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Role</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Poin</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Update Terakhir</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($users as $user): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900"><?php echo $no; ?></td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900"><?php echo htmlspecialchars($user['username']); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($user['email']); ?></td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    <?php 
                                    switch($user['role']) {
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
                            <td class="px-6 py-4 text-right text-lg font-bold text-yellow-600">
                                <i class="fas fa-star text-yellow-500 mr-1"></i><?php echo number_format($user['total_points']); ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <?php
                                $lastUpdated = $user['last_updated'] ?? null;
                                echo $lastUpdated ? date('d M Y H:i', strtotime($lastUpdated)) : '-';
                                ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="/admin/give-points?id=<?php echo $user['id']; ?>" class="text-green-600 hover:text-green-900 transition" title="Berikan Poin">
                                        <i class="fas fa-plus-circle"></i>
                                    </a>
                                    <a href="/admin/deduct-points?id=<?php echo $user['id']; ?>" class="text-red-600 hover:text-red-900 transition" title="Kurangi Poin">
                                        <i class="fas fa-minus-circle"></i>
                                    </a>
                                    <a href="/admin/point-history?id=<?php echo $user['id']; ?>" class="text-blue-600 hover:text-blue-900 transition" title="Riwayat Poin">
                                        <i class="fas fa-history"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php $no++; endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-12">
                <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-600 text-lg">Belum ada users</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
