<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradeCoin - Cari Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-[#D1E9F0] text-[#333] font-sans">

<?php include '../app/views/component/navbar.php'; ?>

<main class="px-[5%] py-5">

    <div class="flex items-center gap-[15px] mb-[30px]">
        <button class="bg-white border border-black rounded-full w-[35px] h-[35px] flex items-center justify-center cursor-pointer transition-colors duration-200 hover:bg-[#f0f0f0]">
            <i class="fas fa-arrow-left"></i>
        </button>

        <input type="text" id="searchInput" placeholder="Cari barang atau jasa..." class="px-5 py-2 rounded-[25px] border border-black w-[250px] outline-none">
    </div>

    <section class="grid grid-cols-4 gap-10 justify-items-center" id="itemGrid">

        <div class="w-full max-w-[250px] transition-transform duration-200 ease-in-out hover:-translate-y-[5px]">
            <div class="bg-white border border-black rounded-[15px] aspect-[1/0.9] w-full flex justify-center items-center overflow-hidden mb-3">
               <a href="/ProductDetails" class="w-full h-full">
                   <img src="../../../foto/tape.jpg" alt="tipe x" class="w-full h-full object-cover">
               </a>
            </div>
            <h3 class="text-[1.1rem] mb-1 font-semibold text-[#333]">tipe x</h3>
            <p class="text-[0.9rem] text-[#444] leading-[1.4]">Posted by : Ameng</p>
            <p class="text-[0.9rem] text-[#444] leading-[1.4] font-medium">Price : 5 Points</p>
        </div>

        <div class="w-full max-w-[250px] transition-transform duration-200 ease-in-out hover:-translate-y-[5px]">
            <div class="bg-white border border-black rounded-[15px] aspect-[1/0.9] w-full flex justify-center items-center overflow-hidden mb-3">
                <img src="../../../foto/gantungan.png" alt="Gantungan" class="w-full h-full object-cover">
            </div>
            <h3 class="text-[1.1rem] mb-1 font-semibold text-[#333]">Gantungan Jpan</h3>
            <p class="text-[0.9rem] text-[#444] leading-[1.4]">Posted by : Aming</p>
            <p class="text-[0.9rem] text-[#444] leading-[1.4] font-medium">Price : 10 Points</p>
        </div>

        <div class="w-full max-w-[250px] transition-transform duration-200 ease-in-out hover:-translate-y-[5px]">
            <div class="bg-white border border-black rounded-[15px] aspect-[1/0.9] w-full flex justify-center items-center overflow-hidden mb-3">
                <img src="../../../foto/penyangga.png" alt="Penyangga" class="w-full h-full object-cover">
            </div>
            <h3 class="text-[1.1rem] mb-1 font-semibold text-[#333]">Penyangga Buku</h3>
            <p class="text-[0.9rem] text-[#444] leading-[1.4]">Posted by : Ahau</p>
            <p class="text-[0.9rem] text-[#444] leading-[1.4] font-medium">Price : 30 Points</p>
        </div>

        <div class="w-full max-w-[250px] transition-transform duration-200 ease-in-out hover:-translate-y-[5px]">
            <div class="bg-white border border-black rounded-[15px] aspect-[1/0.9] w-full flex justify-center items-center overflow-hidden mb-3">
                <img src="../../../foto/stabilo.png" alt="Stabilo" class="w-full h-full object-cover">
            </div>
            <h3 class="text-[1.1rem] mb-1 font-semibold text-[#333]">Stabilo</h3>
            <p class="text-[0.9rem] text-[#444] leading-[1.4]">Posted by : Akien</p>
            <p class="text-[0.9rem] text-[#444] leading-[1.4] font-medium">Price : 25 Points</p>
        </div>

        <div class="w-full max-w-[250px] transition-transform duration-200 ease-in-out hover:-translate-y-[5px]">
            <div class="bg-white border border-black rounded-[15px] aspect-[1/0.9] w-full flex justify-center items-center overflow-hidden mb-3">
                <img src="../../../foto/tutor.png" alt="Jasa Tutor" class="w-full h-full object-cover">
            </div>
            <h3 class="text-[1.1rem] mb-1 font-semibold text-[#333]">Jasa Tutor</h3>
            <p class="text-[0.9rem] text-[#444] leading-[1.4]">Posted by : Anam</p>
            <p class="text-[0.9rem] text-[#444] leading-[1.4] font-medium">Price : Bisa di hubungi...</p>
        </div>

        <div class="w-full max-w-[250px] transition-transform duration-200 ease-in-out hover:-translate-y-[5px]">
            <div class="bg-white border border-black rounded-[15px] aspect-[1/0.9] w-full flex justify-center items-center overflow-hidden mb-3">
                <img src="../../../foto/pulpen.png" alt="Pulpen" class="w-full h-full object-cover">
            </div>
            <h3 class="text-[1.1rem] mb-1 font-semibold text-[#333]">Pulpen</h3>
            <p class="text-[0.9rem] text-[#444] leading-[1.4]">Posted by : Aphiau </p>
            <p class="text-[0.9rem] text-[#444] leading-[1.4] font-medium">Price : 5 Points</p>
        </div>

        <div class="w-full max-w-[250px] transition-transform duration-200 ease-in-out hover:-translate-y-[5px]">
            <div class="bg-white border border-black rounded-[15px] aspect-[1/0.9] w-full flex justify-center items-center overflow-hidden mb-3">
                <img src="../../../foto/penhitam.png" alt="Pulpen Hitam" class="w-full h-full object-cover">
            </div>
            <h3 class="text-[1.1rem] mb-1 font-semibold text-[#333]">Pulpen Hitam</h3>
            <p class="text-[0.9rem] text-[#444] leading-[1.4]">Posted by : Aju </p>
            <p class="text-[0.9rem] text-[#444] leading-[1.4] font-medium">Price : 7 Points</p>
        </div>

        <div class="w-full max-w-[250px] transition-transform duration-200 ease-in-out hover:-translate-y-[5px]">
            <div class="bg-white border border-black rounded-[15px] aspect-[1/0.9] w-full flex justify-center items-center overflow-hidden mb-3">
                <img src="../../../foto/headset.png" alt="Headset" class="w-full h-full object-cover">
            </div>
            <h3 class="text-[1.1rem] mb-1 font-semibold text-[#333]">Headset</h3>
            <p class="text-[0.9rem] text-[#444] leading-[1.4]">Posted by : Yanto</p>
            <p class="text-[0.9rem] text-[#444] leading-[1.4] font-medium">Price : 90 Points</p>
        </div>

    </section>

</main>

<script src="../js/daftar-barang.js"></script>

</body>
</html>