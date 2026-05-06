<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Hapus User'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <?php require __DIR__ . '/../component/navbar.php'; ?>

    <div class="container mx-auto px-4 py-8 max-w-md">
        <div class="bg-white rounded-lg shadow-md p-8">
            <div class="text-center mb-6">
                <div class="inline-block bg-red-100 rounded-full p-4 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-4xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Hapus User</h1>
                <p class="text-gray-600 mt-2">Tindakan ini tidak dapat dibatalkan</p>
            </div>

            <!-- Error Message -->
            <?php if (!empty($error)): ?>
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-red-700 text-sm"><?php echo htmlspecialchars($error); ?></p>
            </div>
            <?php endif; ?>

            <!-- User Information -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6 border border-gray-200">
                <div class="space-y-3">
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase">Username</p>
                        <p class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($user['username']); ?></p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase">Email</p>
                        <p class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase">Nama Lengkap</p>
                        <p class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($user['nama_lengkap']); ?></p>
                    </div>
                </div>
            </div>

            <!-- Warning Message -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <p class="text-yellow-800 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    Menghapus user akan menghapus semua data yang terkait dengan user ini dari sistem.
                </p>
            </div>

            <!-- Confirm Form -->
            <form method="POST" class="space-y-4">
                <div class="flex gap-4">
                    <button 
                        type="submit" 
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-trash"></i> Ya, Hapus User
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
