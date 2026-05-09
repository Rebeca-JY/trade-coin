<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Poin - <?php echo htmlspecialchars($user['username']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <?php require __DIR__ . '/../component/navbar.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Riwayat Poin</h1>
                <p class="text-gray-600 mt-2">User: <span class="font-semibold"><?php echo htmlspecialchars($user['username']); ?></span></p>
            </div>
            <a href="/admin/manage-points" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg transition flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- User Info Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-xs font-semibold text-gray-600 uppercase">Username</p>
                    <p class="text-lg font-semibold text-gray-900 mt-1"><?php echo htmlspecialchars($user['username']); ?></p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-xs font-semibold text-gray-600 uppercase">Email</p>
                    <p class="text-lg font-semibold text-gray-900 mt-1"><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-xs font-semibold text-gray-600 uppercase">Role</p>
                    <p class="text-lg font-semibold text-gray-900 mt-1"><?php echo ucfirst($user['role']); ?></p>
                </div>
                <div class="p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                    <p class="text-xs font-semibold text-gray-600 uppercase">Total Poin</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-1">
                        <i class="fas fa-star text-yellow-500 mr-1"></i><?php echo number_format($user['total_points']); ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- History Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <?php if (!empty($history)): ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b-2 border-gray-300">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">No</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Aksi</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Jumlah</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Alasan</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($history as $record): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900"><?php echo $no; ?></td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    <?php 
                                    switch($record['action']) {
                                        case 'add':
                                            echo 'bg-green-100 text-green-800';
                                            break;
                                        case 'deduct':
                                            echo 'bg-red-100 text-red-800';
                                            break;
                                        case 'set':
                                            echo 'bg-blue-100 text-blue-800';
                                            break;
                                        default:
                                            echo 'bg-gray-100 text-gray-800';
                                    }
                                    ?>
                                ">
                                    <?php echo ucfirst($record['action']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold
                                <?php
                                if ($record['action'] === 'add') {
                                    echo 'text-green-600';
                                } elseif ($record['action'] === 'deduct') {
                                    echo 'text-red-600';
                                } else {
                                    echo 'text-blue-600';
                                }
                                ?>
                            ">
                                <?php
                                if ($record['action'] === 'add') {
                                    echo '+' . number_format($record['amount']);
                                } elseif ($record['action'] === 'deduct') {
                                    echo '-' . number_format($record['amount']);
                                } else {
                                    echo '→ ' . number_format($record['amount']);
                                }
                                ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <?php echo htmlspecialchars($record['reason']); ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <?php echo date('d M Y H:i', strtotime($record['created_at'])); ?>
                            </td>
                        </tr>
                        <?php $no++; endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-12">
                <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-600 text-lg">Belum ada riwayat poin</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
