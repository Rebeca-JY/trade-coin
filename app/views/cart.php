<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradeCoin - Shopping Cart</title>
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-[#f8fafc] text-[#334155] min-h-screen">

<?php include '../app/views/component/navbar.php'; ?>

<main class="max-w-[1000px] mx-auto px-5 pt-10 pb-[150px]">

    <!-- Cart Item 1 -->
    <div class="cart-item group flex items-center gap-5 bg-white p-5 rounded-2xl mb-5 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg">
        <input type="checkbox" class="item-check w-5 h-5 cursor-pointer accent-black" checked>

        <div class="product-info flex items-center gap-5 flex-1">
            <img src="./foto/pulpen.png" alt="Pulpen Elitis" class="w-[100px] h-[100px] object-cover rounded-xl bg-[#f1f5f9]">

            <div class="product-details">
                <h3 class="text-lg font-semibold text-[#1e293b] mb-1">Pulpen Elitis</h3>
                <p class="shop-name text-[#64748b] text-sm flex items-center gap-1.5">toko bahagia</p>
                <p class="unit-price text-[#94a3b8] text-sm mt-1">27 Points</p>

                <div class="controls flex items-center gap-4 mt-4">
                    <button class="qty-btn w-8 h-8 rounded-lg border border-[#e2e8f0] bg-white text-[#0f172a] flex items-center justify-center transition-all hover:bg-[#f1f5f9] hover:border-[#cbd5e1] active:scale-90">-</button>
                    <span class="qty-num text-base font-semibold min-w-[20px] text-center">3</span>
                    <button class="qty-btn w-8 h-8 rounded-lg border border-[#e2e8f0] bg-white text-[#0f172a] flex items-center justify-center transition-all hover:bg-[#f1f5f9] hover:border-[#cbd5e1] active:scale-90">+</button>
                    
                    <button class="delete-btn ml-2.5 w-8 h-8 rounded-lg bg-[#fff1f2] text-[#e11d48] flex items-center justify-center transition-all hover:bg-[#ffe4e6] hover:text-[#be123c]">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="item-total text-lg font-bold text-[#0f172a] text-right min-w-[130px]">81 Points</div>
    </div>

    <!-- Cart Item 2 -->
    <div class="cart-item group flex items-center gap-5 bg-white p-5 rounded-2xl mb-5 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg">
        <input type="checkbox" class="item-check w-5 h-5 cursor-pointer accent-black" checked>

        <div class="product-info flex items-center gap-5 flex-1">
            <img src="./foto/penpink.png" alt="Pulpen Clingy" class="w-[100px] h-[100px] object-cover rounded-xl bg-[#f1f5f9]">

            <div class="product-details">
                <h3 class="text-lg font-semibold text-[#1e293b] mb-1">Pulpen Clingy</h3>
                <p class="shop-name text-[#64748b] text-sm flex items-center gap-1.5">toko barbie</p>
                <p class="unit-price text-[#94a3b8] text-sm mt-1">99 Points</p>

                <div class="controls flex items-center gap-4 mt-4">
                    <button class="qty-btn w-8 h-8 rounded-lg border border-[#e2e8f0] bg-white text-[#0f172a] flex items-center justify-center transition-all hover:bg-[#f1f5f9] hover:border-[#cbd5e1] active:scale-90">-</button>
                    <span class="qty-num text-base font-semibold min-w-[20px] text-center">1</span>
                    <button class="qty-btn w-8 h-8 rounded-lg border border-[#e2e8f0] bg-white text-[#0f172a] flex items-center justify-center transition-all hover:bg-[#f1f5f9] hover:border-[#cbd5e1] active:scale-90">+</button>
                    
                    <button class="delete-btn ml-2.5 w-8 h-8 rounded-lg bg-[#fff1f2] text-[#e11d48] flex items-center justify-center transition-all hover:bg-[#ffe4e6] hover:text-[#be123c]">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="item-total text-lg font-bold text-[#0f172a] text-right min-w-[130px]">99 Points</div>
    </div>

    <!-- Cart Item 3 -->
    <div class="cart-item group flex items-center gap-5 bg-white p-5 rounded-2xl mb-5 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg">
        <input type="checkbox" class="item-check w-5 h-5 cursor-pointer accent-black" checked>

        <div class="product-info flex items-center gap-5 flex-1">
            <img src="./foto/cutter.png" alt="Cater Cat" class="w-[100px] h-[100px] object-cover rounded-xl bg-[#f1f5f9]">

            <div class="product-details">
                <h3 class="text-lg font-semibold text-[#1e293b] mb-1">Cater Cat</h3>
                <p class="shop-name text-[#64748b] text-sm flex items-center gap-1.5">toko kucing</p>
                <p class="unit-price text-[#94a3b8] text-sm mt-1">20 Points</p>

                <div class="controls flex items-center gap-4 mt-4">
                    <button class="qty-btn w-8 h-8 rounded-lg border border-[#e2e8f0] bg-white text-[#0f172a] flex items-center justify-center transition-all hover:bg-[#f1f5f9] hover:border-[#cbd5e1] active:scale-90">-</button>
                    <span class="qty-num text-base font-semibold min-w-[20px] text-center">1</span>
                    <button class="qty-btn w-8 h-8 rounded-lg border border-[#e2e8f0] bg-white text-[#0f172a] flex items-center justify-center transition-all hover:bg-[#f1f5f9] hover:border-[#cbd5e1] active:scale-90">+</button>
                    
                    <button class="delete-btn ml-2.5 w-8 h-8 rounded-lg bg-[#fff1f2] text-[#e11d48] flex items-center justify-center transition-all hover:bg-[#ffe4e6] hover:text-[#be123c]">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="item-total text-lg font-bold text-[#0f172a] text-right min-w-[130px]">20 Points</div>
    </div>

</main>

<!-- Checkout Footer (Floating) -->
<footer class="fixed bottom-5 left-1/2 -translate-x-1/2 w-[calc(100%-40px)] max-w-[1000px] bg-white/90 backdrop-blur-md px-10 py-5 flex justify-between items-center rounded-[20px] shadow-xl border border-black/5">
    <h2 class="text-2xl text-[#1e293b] font-medium grand-total">
        Total : <span class="font-extrabold text-black">0 Points</span>
    </h2>

    <button class="btn-checkout bg-black text-white px-[45px] py-3.5 rounded-xl text-base font-semibold transition-all duration-300 hover:bg-[#333] hover:scale-[1.03] active:scale-95">
        Check out
    </button>
</footer>

<script src="js/cart.js"></script>

</body>
</html>