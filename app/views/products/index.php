<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradeCoin - Cari Barang</title>

    <link rel="stylesheet" href="../css/daftar-barang.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

<?php include '../app/views/component/navbar.php'; ?>

<main>

    <div class="search-container">
        <button class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </button>

        <input type="text" id="searchInput" placeholder="Cari barang atau jasa...">
    </div>


    <section class="item-grid" id="itemGrid">

        <div class="item-card">
            <div class="image-box">
                <img src="https://via.placeholder.com/150" alt="Wadah Pen">
            </div>
            <h3>Wadah Pen</h3>
            <p>Posted by : Louis</p>
            <p class="price">Price : Bisa di hubungi...</p>
        </div>

        <div class="item-card">
            <div class="image-box">
                <img src="https://via.placeholder.com/150" alt="Gantungan Jepang">
            </div>
            <h3>Gantungan Jpan</h3>
            <p>Posted by : Louis</p>
            <p class="price">Price : Bisa di hubungi...</p>
        </div>

        <div class="item-card">
            <div class="image-box">
                <img src="https://via.placeholder.com/150" alt="Penyangga Buku">
            </div>
            <h3>Penyangga Buku</h3>
            <p>Posted by : Louis</p>
            <p class="price">Price : Bisa di hubungi...</p>
        </div>

        <div class="item-card">
            <div class="image-box">
                <img src="https://via.placeholder.com/150" alt="Stabilo">
            </div>
            <h3>Stabilo</h3>
            <p>Posted by : Louis</p>
            <p class="price">Price : Bisa di hubungi...</p>
        </div>

        <div class="item-card">
            <div class="image-box">
                <img src="https://via.placeholder.com/150" alt="Penggaris">
            </div>
            <h3>Penggaris</h3>
            <p>Posted by : Louis</p>
            <p class="price">Price : Bisa di hubungi...</p>
        </div>

        <div class="item-card">
            <div class="image-box">
                <img src="https://via.placeholder.com/150" alt="Jasa Tutor">
            </div>
            <h3>Jasa Tutor</h3>
            <p>Posted by : Louis</p>
            <p class="price">Price : Bisa di hubungi...</p>
        </div>

        <div class="item-card">
            <div class="image-box">
                <img src="https://via.placeholder.com/150" alt="Pulpen">
            </div>
            <h3>Pulpen</h3>
            <p>Posted by : Louis</p>
            <p class="price">Price : Bisa di hubungi...</p>
        </div>

        <div class="item-card">
            <div class="image-box">
                <img src="https://via.placeholder.com/150" alt="Pulpen Hitam">
            </div>
            <h3>Pulpen</h3>
            <p>Posted by : Louis</p>
            <p class="price">Price : Bisa di hubungi...</p>
        </div>

        <div class="item-card">
            <div class="image-box">
                <img src="https://via.placeholder.com/150" alt="Headphone">
            </div>
            <h3>Headphone Second</h3>
            <p>Posted by : Louis</p>
            <p class="price">Price : Bisa di hubungi...</p>
        </div>

        <div class="item-card">
            <div class="image-box">
                <img src="https://via.placeholder.com/150" alt="Jasa Nyatat">
            </div>
            <h3>Jasa Nyatat</h3>
            <p>Posted by : Louis</p>
            <p class="price">Price : Bisa di hubungi...</p>
        </div>

    </section>

</main>

<script src="../js/daftar-barang.js"></script>

</body>
</html>