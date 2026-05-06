<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Tambah User'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <?php require __DIR__ . '/../component/navbar.php'; ?>

    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <div class="bg-white rounded-lg shadow-md p-8">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Tambah User Baru</h1>
                <p class="text-gray-600 mt-2">Isi form di bawah untuk menambahkan user baru ke sistem</p>
            </div>

            <!-- Error Messages -->
            <?php if (!empty($errors)): ?>
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-start gap-3 mb-2">
                    <i class="fas fa-exclamation-circle text-red-600 mt-1"></i>
                    <div>
                        <h3 class="font-semibold text-red-800">Terdapat kesalahan:</h3>
                        <ul class="text-red-700 text-sm mt-2 space-y-1">
                            <?php foreach ($errors as $error): ?>
                            <li>• <?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <!-- Username & Email Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user mr-2"></i>Username <span class="text-red-600">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="username" 
                            value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>"
                            placeholder="Masukkan username"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2"></i>Email <span class="text-red-600">*</span>
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"
                            placeholder="Masukkan email"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2"></i>Password <span class="text-red-600">*</span>
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        placeholder="Masukkan password"
                        required
                        minlength="6"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                    <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter</p>
                </div>

                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-id-card mr-2"></i>Nama Lengkap <span class="text-red-600">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="nama_lengkap" 
                        value="<?php echo htmlspecialchars($user['nama_lengkap'] ?? ''); ?>"
                        placeholder="Masukkan nama lengkap"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                </div>

                <!-- Role & Nomor HP -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-briefcase mr-2"></i>Role <span class="text-red-600">*</span>
                        </label>
                        <select 
                            name="role" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required
                        >
                            <option value="buyer" <?php echo ($user['role'] ?? 'buyer') === 'buyer' ? 'selected' : ''; ?>>Buyer (Pembeli)</option>
                            <option value="seller" <?php echo ($user['role'] ?? '') === 'seller' ? 'selected' : ''; ?>>Seller (Penjual)</option>
                            <option value="admin" <?php echo ($user['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-phone mr-2"></i>Nomor HP
                        </label>
                        <input 
                            type="tel" 
                            name="nomor_hp" 
                            value="<?php echo htmlspecialchars($user['nomor_hp'] ?? ''); ?>"
                            placeholder="Masukkan nomor HP"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                    </div>
                </div>

                <!-- Alamat & Toko Nama -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>Alamat
                        </label>
                        <textarea 
                            name="alamat" 
                            placeholder="Masukkan alamat"
                            rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        ><?php echo htmlspecialchars($user['alamat'] ?? ''); ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-store mr-2"></i>Nama Toko (Untuk Seller)
                        </label>
                        <input 
                            type="text" 
                            name="toko_nama" 
                            value="<?php echo htmlspecialchars($user['toko_nama'] ?? ''); ?>"
                            placeholder="Masukkan nama toko"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 pt-6 border-t">
                    <button 
                        type="submit" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-save"></i> Simpan User
                    </button>
                    <a 
                        href="/admin/users" 
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-6 rounded-lg transition text-center"
                    >
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
