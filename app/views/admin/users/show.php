<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Detail User'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <?php require __DIR__ . '/../component/navbar.php'; ?>

    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <div class="bg-white rounded-lg shadow-md p-8">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Detail User</h1>
                    <p class="text-gray-600 mt-2">Informasi lengkap user</p>
                </div>
                <a href="/admin/users" class="text-blue-600 hover:text-blue-900">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="space-y-6">
                <!-- User Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- ID -->
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <i class="fas fa-hashtag mr-2"></i>ID User
                        </p>
                        <p class="text-lg font-semibold text-gray-900 mt-1"><?php echo $user['id']; ?></p>
                    </div>

                    <!-- Username -->
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <i class="fas fa-user mr-2"></i>Username
                        </p>
                        <p class="text-lg font-semibold text-gray-900 mt-1"><?php echo htmlspecialchars($user['username']); ?></p>
                    </div>

                    <!-- Email -->
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <i class="fas fa-envelope mr-2"></i>Email
                        </p>
                        <p class="text-lg font-semibold text-gray-900 mt-1"><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <i class="fas fa-id-card mr-2"></i>Nama Lengkap
                        </p>
                        <p class="text-lg font-semibold text-gray-900 mt-1"><?php echo htmlspecialchars($user['nama_lengkap'] ?? '-'); ?></p>
                    </div>

                    <!-- Role -->
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <i class="fas fa-briefcase mr-2"></i>Role
                        </p>
                        <p class="mt-2">
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
                        </p>
                    </div>

                    <!-- Nomor HP -->
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <i class="fas fa-phone mr-2"></i>Nomor HP
                        </p>
                        <p class="text-lg font-semibold text-gray-900 mt-1"><?php echo htmlspecialchars($user['nomor_hp'] ?? '-'); ?></p>
                    </div>

                    <!-- Terdaftar -->
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <i class="fas fa-calendar mr-2"></i>Terdaftar
                        </p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">
                            <?php echo date('d M Y H:i', strtotime($user['created_at'])); ?>
                        </p>
                    </div>

                    <!-- Toko Nama (jika seller) -->
                    <?php if ($user['role'] === 'seller'): ?>
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <i class="fas fa-store mr-2"></i>Nama Toko
                        </p>
                        <p class="text-lg font-semibold text-gray-900 mt-1"><?php echo htmlspecialchars($user['toko_nama'] ?? '-'); ?></p>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Alamat (Full width) -->
                <?php if (!empty($user['alamat'])): ?>
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <i class="fas fa-map-marker-alt mr-2"></i>Alamat
                    </p>
                    <p class="text-gray-900 mt-2"><?php echo htmlspecialchars($user['alamat']); ?></p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 pt-8 border-t border-gray-200">
                <a 
                    href="/admin/users/edit?id=<?php echo $user['id']; ?>" 
                    class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 px-6 rounded-lg transition text-center flex items-center justify-center gap-2"
                >
                    <i class="fas fa-edit"></i> Edit User
                </a>
                <a 
                    href="/admin/users/delete?id=<?php echo $user['id']; ?>" 
                    class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition text-center flex items-center justify-center gap-2"
                    onclick="return confirm('Yakin ingin menghapus user ini?')"
                >
                    <i class="fas fa-trash"></i> Hapus User
                </a>
                <a 
                    href="/admin/users" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-6 rounded-lg transition text-center"
                >
                    Kembali
                </a>
            </div>
        </div>
    </div>
</body>
</html>
