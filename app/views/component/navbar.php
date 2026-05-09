<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$messageCount = 0;
$cartCount = 0;
$userCoins = 0;
$loggedIn = isset($_SESSION['user']['id']);

if ($loggedIn) {
    try {
        $pointModel = new \App\models\UserPoint();
        $userCoins = (int) $pointModel->getUserPoints((int) $_SESSION['user']['id']);
    } catch (\Throwable $e) {
        $userCoins = (int) ($_SESSION['user']['coins'] ?? 0);
    }
}
?>

<nav class="bg-white mx-[5%] my-4 px-8 py-3 flex items-center justify-between shadow-sm rounded-full border border-gray-200 relative z-[1000]">
    <div class="flex items-center">
        <img src="/foto/logo.png" alt="TradeCoin Logo" class="h-8 object-contain">
    </div>

    <ul class="flex items-center gap-8 list-none m-0 p-0">
        <li><a href="/products" class="text-gray-700 hover:text-black font-medium transition-colors">Search Item & Service</a></li>
        <li><a href="#" class="text-gray-700 hover:text-black font-medium transition-colors">Sell Item & Service</a></li>
        <li><a href="#" class="text-gray-700 hover:text-black font-medium transition-colors">Guide</a></li>
        <li><a href="#" class="text-gray-700 hover:text-black font-medium transition-colors">Contact Us</a></li>
    </ul>

    <div class="flex items-center gap-8">
        <a href="/messages" class="icon-link relative text-2xl text-[#333] transition-all duration-200 block">
            <i class="fas fa-comment-dots"></i>
            <?php if ($messageCount > 0): ?>
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full border border-white"><?= $messageCount ?></span>
            <?php endif; ?>
        </a>

        <a href="/cart" class="icon-link relative text-2xl text-[#333] transition-all duration-200 block">
            <i class="fas fa-shopping-cart"></i>
            <?php if ($cartCount > 0): ?>
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full border border-white"><?= $cartCount ?></span>
            <?php endif; ?>
        </a>

        <div class="relative inline-block">
            <div id="tcUserIcon" class="text-3xl text-[#333] cursor-pointer transition-all duration-200 hover:text-gray-600">
                <i class="fas fa-user-circle"></i>
            </div>
            
            <div id="tcDropdown" class="absolute right-0 mt-4 w-[220px] bg-white border border-gray-200 rounded-2xl shadow-xl overflow-hidden py-2 transition-all duration-200 opacity-0 scale-95 origin-top-right pointer-events-none">
                <p class="px-5 py-3 text-sm text-[#333] bg-gray-50 m-0 border-b border-gray-100">
                    Coins : <b class="text-[#567f89]"><?= number_format($userCoins) ?></b>
                    <?php if (!$loggedIn): ?>
                        <span class="block text-[11px] font-normal text-gray-500 mt-1">Login untuk saldo akun</span>
                    <?php endif; ?>
                </p>
                <a href="<?= $loggedIn ? '/profile' : '/login' ?>" class="flex items-center gap-3 px-5 py-2.5 text-sm text-[#333] no-underline hover:bg-[#f4f7f8] transition-colors"><i class="fa-regular fa-user w-5 text-center text-gray-500"></i> Account Profile</a>
                <a href="<?= $loggedIn ? '/profile' : '/login' ?>" class="flex items-center gap-3 px-5 py-2.5 text-sm text-[#333] no-underline hover:bg-[#f4f7f8] transition-colors"><i class="fa-solid fa-gear w-5 text-center text-gray-500"></i> Account Setting</a>
                <a href="<?= $loggedIn ? '/profile' : '/login' ?>" class="flex items-center gap-3 px-5 py-2.5 text-sm text-[#333] no-underline hover:bg-[#f4f7f8] transition-colors"><i class="fa-solid fa-clock-rotate-left w-5 text-center text-gray-500"></i> History</a>
                <hr class="border-gray-100 mx-2">
                <?php if ($loggedIn): ?>
                <a href="/logout" class="flex items-center gap-3 px-5 py-2.5 text-sm text-red-600 font-bold no-underline hover:bg-red-50 transition-colors"><i class="fa-solid fa-right-from-bracket w-5 text-center"></i> Logout</a>
                <?php else: ?>
                <a href="/login" class="flex items-center gap-3 px-5 py-2.5 text-sm text-[#567f89] font-semibold no-underline hover:bg-[#f4f7f8] transition-colors"><i class="fa-solid fa-right-to-bracket w-5 text-center"></i> Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const userIcon = document.getElementById("tcUserIcon");
    const dropdown = document.getElementById("tcDropdown");

    userIcon.addEventListener("click", (e) => {
        e.stopPropagation();
        const isOpen = !dropdown.classList.contains("opacity-0");
        
        if (isOpen) {
            dropdown.classList.add("opacity-0", "scale-95", "pointer-events-none");
            dropdown.classList.remove("opacity-100", "scale-100");
        } else {
            dropdown.classList.remove("opacity-0", "scale-95", "pointer-events-none");
            dropdown.classList.add("opacity-100", "scale-100");
        }
    });

    document.addEventListener("click", (e) => {
        if (!dropdown.contains(e.target) && !userIcon.contains(e.target)) {
            dropdown.classList.add("opacity-0", "scale-95", "pointer-events-none");
            dropdown.classList.remove("opacity-100", "scale-100");
        }
    });
});
</script>