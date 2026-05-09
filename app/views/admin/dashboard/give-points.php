<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berikan Poin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <?php require __DIR__ . '/../component/navbar.php'; ?>

    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <div class="bg-white rounded-lg shadow-md p-8">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Berikan Poin</h1>
                <p class="text-gray-600 mt-2">Tambahkan poin kepada user</p>
            </div>

            <!-- Error & Success Messages -->
            <?php if (!empty($errors)): ?>
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <ul class="text-red-700 text-sm space-y-1">
                    <?php foreach ($errors as $error): ?>
                    <li>• <?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <?php if ($success): ?>
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-green-700 font-semibold flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                </p>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <!-- User Selection -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user mr-2"></i>Pilih User <span class="text-red-600">*</span>
                    </label>
                    <select 
                        name="user_id" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                    >
                        <option value="">-- Pilih User --</option>
                        <?php foreach ($allUsers as $u): ?>
                        <option value="<?php echo $u['id']; ?>" <?php echo (isset($user['id']) && (int)$user['id'] === (int)$u['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($u['username']); ?> (<?php echo htmlspecialchars($u['email']); ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Points Input -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-star mr-2"></i>Jumlah Poin <span class="text-red-600">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="points" 
                        min="1"
                        max="999999"
                        placeholder="Masukkan jumlah poin"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                    />
                </div>

                <!-- Reason -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-comment mr-2"></i>Alasan <span class="text-red-600">*</span>
                    </label>
                    <textarea 
                        name="reason" 
                        placeholder="Alasan pemberian poin (misal: Bonus pembelian, Referral, dll)"
                        required
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                    ></textarea>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 pt-6 border-t">
                    <button 
                        type="submit" 
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-plus"></i> Berikan Poin
                    </button>
                    <a 
                        href="/admin/manage-points" 
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
