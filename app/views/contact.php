<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradeCoin - Contact Us</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-white text-[#333] font-sans min-h-screen">
    <?php include __DIR__ . '/component/navbar.php'; ?>

    <!-- Hero Section -->
    <section class="relative h-96 bg-cover bg-center flex items-center justify-center" style="background-image: url('<?= htmlspecialchars(url_for('/foto/contact us bg.png')) ?>'); background-position: center;">
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="relative z-10 text-center">
            <h1 class="text-5xl md:text-6xl font-serif font-bold text-white drop-shadow-lg">Contact Us</h1>
        </div>
    </section>

    <!-- Contact Information -->
    <main class="px-[5%] py-16">
        <div class="max-w-6xl mx-auto">
            <!-- Three Column Layout -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-16">
                
                <!-- Social Media Column -->
                <div class="text-center">
                    <h2 class="text-2xl font-serif font-semibold mb-8">Sosial Media</h2>
                    <div class="flex justify-center gap-6">
                        <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="text-4xl text-gray-800 hover:text-pink-500 transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://x.com" target="_blank" rel="noopener noreferrer" class="text-4xl text-gray-800 hover:text-black transition-colors">
                            <i class="fab fa-x-twitter"></i>
                        </a>
                        <a href="https://threads.net" target="_blank" rel="noopener noreferrer" class="text-4xl text-gray-800 hover:text-gray-600 transition-colors">
                            <i class="fab fa-threads"></i>
                        </a>
                    </div>
                </div>

                <!-- Phone Column -->
                <div class="text-center">
                    <h2 class="text-2xl font-serif font-semibold mb-8">Phone</h2>
                    <a href="tel:+6281234507890" class="text-xl text-gray-700 hover:text-blue-600 transition-colors font-medium">
                        +62 812-3450-7890
                    </a>
                </div>

                <!-- Email Column -->
                <div class="text-center">
                    <h2 class="text-2xl font-serif font-semibold mb-8">Email</h2>
                    <a href="mailto:Sapphirekevinstitute@gmail.com" class="text-xl text-gray-700 hover:text-blue-600 transition-colors font-medium break-all">
                        Sapphirekevinstitute@gmail.com
                    </a>
                </div>

            </div>

            <!-- Hours of Operation -->
            <div class="border-t border-gray-300 pt-12">
                <h2 class="text-2xl font-serif font-semibold mb-6">Hours of Operation</h2>
                <div class="space-y-3 text-lg text-gray-700">
                    <p><span class="font-semibold">Monday - Friday:</span> 07:00 - 15:00</p>
                    <p><span class="font-semibold">Saturday & Sunday:</span> 07:00 - 11:00</p>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer (Optional) -->
    <footer class="bg-gray-100 mt-12 py-8 px-[5%]">
        <div class="max-w-6xl mx-auto text-center text-gray-600 text-sm">
            <p>&copy; 2026 TradeCoin. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
