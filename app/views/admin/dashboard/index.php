<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <?php require __DIR__ . '/../component/navbar.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Admin Dashboard</h1>
            <p class="text-gray-600 mt-2">Selamat datang di panel admin TradeCoin</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <!-- Total Users -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Total Users</p>
                        <p class="text-3xl font-bold text-gray-900"><?php echo $totalUsers; ?></p>
                    </div>
                    <i class="fas fa-users text-4xl text-blue-500 opacity-20"></i>
                </div>
            </div>

            <!-- Admins -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Admin</p>
                        <p class="text-3xl font-bold text-gray-900"><?php echo $totalAdmins; ?></p>
                    </div>
                    <i class="fas fa-shield text-4xl text-red-500 opacity-20"></i>
                </div>
            </div>

            <!-- Sellers -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Sellers</p>
                        <p class="text-3xl font-bold text-gray-900"><?php echo $totalSellers; ?></p>
                    </div>
                    <i class="fas fa-store text-4xl text-purple-500 opacity-20"></i>
                </div>
            </div>

            <!-- Buyers -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Buyers</p>
                        <p class="text-3xl font-bold text-gray-900"><?php echo $totalBuyers; ?></p>
                    </div>
                    <i class="fas fa-shopping-bag text-4xl text-green-500 opacity-20"></i>
                </div>
            </div>

            <!-- Total Points -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Total Points</p>
                        <p class="text-3xl font-bold text-gray-900"><?php echo number_format($totalPointsDistributed); ?></p>
                    </div>
                    <i class="fas fa-star text-4xl text-yellow-500 opacity-20"></i>
                </div>
            </div>
        </div>

        <!-- Main Menu -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Manage Products -->
            <a href="/admin/products" class="bg-white rounded-lg shadow hover:shadow-lg transition p-6 border-t-4 border-indigo-500">
                <div class="flex items-center gap-4">
                    <div class="bg-indigo-100 rounded-full p-4">
                        <i class="fas fa-box text-2xl text-indigo-600"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-900">Kelola Produk</h3>
                        <p class="text-gray-600 text-sm">Manage semua produk</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 ml-auto"></i>
                </div>
            </a>

            <!-- Manage Users -->
            <a href="/admin/users" class="bg-white rounded-lg shadow hover:shadow-lg transition p-6 border-t-4 border-blue-500">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 rounded-full p-4">
                        <i class="fas fa-users text-2xl text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-900">Kelola Users</h3>
                        <p class="text-gray-600 text-sm">Manage akun pengguna</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 ml-auto"></i>
                </div>
            </a>

            <!-- Manage Points -->
            <a href="/admin/manage-points" class="bg-white rounded-lg shadow hover:shadow-lg transition p-6 border-t-4 border-yellow-500">
                <div class="flex items-center gap-4">
                    <div class="bg-yellow-100 rounded-full p-4">
                        <i class="fas fa-star text-2xl text-yellow-600"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-900">Kelola Poin</h3>
                        <p class="text-gray-600 text-sm">Manage poin pengguna</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 ml-auto"></i>
                </div>
            </a>
        </div>

        <!-- Top Users by Points -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">
                <i class="fas fa-trophy text-yellow-500 mr-2"></i>Top 5 Users by Points
            </h2>

            <?php if (!empty($topUsers)): ?>
            <div class="space-y-4">
                <?php $rank = 1; foreach ($topUsers as $user): ?>
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full font-bold text-white
                        <?php echo match($rank) {
                            1 => 'bg-yellow-500',
                            2 => 'bg-gray-400',
                            3 => 'bg-orange-600',
                            default => 'bg-blue-500'
                        }; ?>
                    ">
                        <?php if ($rank === 1): ?>
                            <i class="fas fa-crown"></i>
                        <?php else: ?>
                            <?php echo $rank; ?>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($user['username']); ?></p>
                        <p class="text-sm text-gray-600"><?php echo ucfirst($user['role']); ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-yellow-600"><?php echo number_format($user['total_points']); ?></p>
                        <p class="text-xs text-gray-600">Poin</p>
                    </div>
                </div>
                <?php $rank++; endforeach; ?>
            </div>
            <?php else: ?>
            <p class="text-gray-600 text-center py-8">Belum ada data poin</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
