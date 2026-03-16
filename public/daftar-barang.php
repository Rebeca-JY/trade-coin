<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradeCoin - Cari Barang</title>
    <link rel="stylesheet" href="./css/daftar-barang.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include '../public/navbar.php'; ?>

    <main>
        <div class="search-container">
            <button class="back-btn"><i class="fas fa-arrow-left"></i></button>
            <input type="text" id="searchInput" placeholder="Cari di sini...">
        </div>

        <section class="item-grid" id="itemGrid">
            <div class="item-card">
                <div class="image-box"><img src="https://via.placeholder.com/150" alt="Wadah pen"></div>
                <h3>Wadah pen</h3>
                <p>Posted by: Louis</p>
                <p class="price">Price: Bisa di hubungi...</p>
            </div>
            <div class="item-card">
                <div class="image-box"><img src="https://via.placeholder.com/150" alt="Stabilo"></div>
                <h3>Stabilo</h3>
                <p>Posted by: Louis</p>
                <p class="price">Price: Bisa di hubungi...</p>
            </div>
            <div class="item-card">
                <div class="image-box"><img src="https://via.placeholder.com/150" alt="Headphone"></div>
                <h3>Headphone second</h3>
                <p>Posted by: Louis</p>
                <p class="price">Price: Bisa di hubungi...</p>
            </div>
            </section>
    </main>

    <script src="script.js"></script>
</body>
</html>