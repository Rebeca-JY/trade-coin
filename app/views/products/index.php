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

               <a href="/ProductDetails"><img class="gambar" src="../../../foto/tape.jpg" alt="tipe x"></a>
            </div>
            <h3>tipe x</h3>
            <p>Posted by : Ameng</p>
            <p class="price">Price : 5 Points</p>
        </div>

        <div class="item-card">
            <div class="image-box">
                <img class="gambar" src="../../../foto/gantungan.png" alt="Gantungan">
            </div>
            <h3>Gantungan Jpan</h3>
            <p>Posted by : Aming</p>
            <p class="price">Price : 10 Points</p>
        </div>

        <div class="item-card">
            <div class="image-box">
                <img class="gambar" src="../../../foto/penyangga.png" alt="Penyangga">
            </div>
            <h3>Penyangga Buku</h3>
            <p>Posted by : Ahau</p>
            <p class="price">Price : 30 Points</p>
        </div>

        <div class="item-card">
            <div class="image-box">
                <img class="gambar"src="../../../foto/stabilo.png" alt="Stabilo">
            </div>
            <h3>Stabilo</h3>
            <p>Posted by : Akien</p>
            <p class="price">Price : 25 Points</p>
        </div>

        <div class="item-card">
            <div class="image-box">
                <img class="gambar" src="../../../foto/tutor.png" alt="Jasa Tutor">
            </div>
            <h3>Jasa Tutor</h3>
            <p>Posted by : Anam</p>
            <p class="price">Price : Bisa di hubungi...</p>
        </div>

        <div class="item-card">
            <div class="image-box">
                <img class="gambar"src="../../../foto/pulpen.png" alt="Pulpen">
            </div>
            <h3>Pulpen</h3>
            <p>Posted by : Aphiau </p>
            <p class="price">Price : 5 Points</p>
        </div>

        <div class="item-card">
            <div class="image-box">
                <img class="gambar" src="../../../foto/penhitam.png" alt="Pulpen Hitam">
            </div>
            <h3>Pulpen Hitam</h3>
            <p>Posted by : Aju </p>
            <p class="price">Price : 7 Points</p>
        </div>

        <div class="item-card">
            <div class="image-box">
                <img class="gambar" src="../../../foto/headset.png" alt="Headset">
            </div>
            <h3>Headset</h3>
            <p>Posted by : Yanto</p>
            <p class="price">Price : 90 Points</p>
        </div>
    

    </section>

</main>

<script src="../js/daftar-barang.js"></script>

</body>
</html>