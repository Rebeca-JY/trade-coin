<?php
$messageCount = 0;
$cartCount = 0;
$userPoints = 500;
?>

<nav class="bg-white mx-[5%] my-4 px-8 py-3 flex items-center justify-between shadow-sm rounded-full border border-gray-200 relative z-[1000]">
    <div class="flex items-center">
        <img src="/foto/logo.png" alt="TradeCoin Logo" class="h-8 object-contain">
    </div>

    <ul class="hidden md:flex gap-10 items-center list-none">
        <li><a href="/products" class="text-[#333] text-sm font-medium no-underline transition-colors hover:text-[#567f89]">Search Item & Service</a></li>
        <li><a href="/products-add" class="text-[#333] text-sm font-medium no-underline transition-colors hover:text-[#567f89]">Sell Item & Service</a></li>
        <li><a href="#" class="text-[#333] text-sm font-medium no-underline transition-colors hover:text-[#567f89]">Guide</a></li>
        <li><a href="#" class="text-[#333] text-sm font-medium no-underline transition-colors hover:text-[#567f89]">Contact Us</a></li>
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
            <div id="tcUserIcon" class="text-3xl text-[#333] cursor-pointer transition-all duration-200">
                <i class="fas fa-user-circle"></i>
            </div>
            
            <div id="tcDropdown" class="hidden absolute right-0 mt-4 w-[220px] bg-white border border-gray-200 rounded-2xl shadow-xl overflow-hidden py-2 transition-all opacity-0 scale-95 origin-top-right">
                <p class="px-5 py-3 text-sm text-[#333] bg-gray-50 m-0 border-b border-gray-100">Points : <b class="text-[#567f89]"><?= number_format($userPoints) ?></b></p>
                <a href="#" class="flex items-center gap-3 px-5 py-2.5 text-sm text-[#333] no-underline hover:bg-[#f4f7f8] transition-colors"><i class="fa-regular fa-user w-5 text-center text-gray-500"></i> Account Profile</a>
                <a href="#" class="flex items-center gap-3 px-5 py-2.5 text-sm text-[#333] no-underline hover:bg-[#f4f7f8] transition-colors"><i class="fa-solid fa-gear w-5 text-center text-gray-500"></i> Account Setting</a>
                <a href="#" class="flex items-center gap-3 px-5 py-2.5 text-sm text-[#333] no-underline hover:bg-[#f4f7f8] transition-colors"><i class="fa-solid fa-clock-rotate-left w-5 text-center text-gray-500"></i> History</a>
                <hr class="border-gray-100 mx-2">
                <a href="#" class="flex items-center gap-3 px-5 py-2.5 text-sm text-red-600 font-bold no-underline hover:bg-red-50 transition-colors"><i class="fa-solid fa-right-from-bracket w-5 text-center"></i> Logout</a>
            </div>
        </div>
    </div>
</nav>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const userIcon = document.getElementById("tcUserIcon");
    const dropdown = document.getElementById("tcDropdown");
    const iconLinks = document.querySelectorAll(".icon-link");

    userIcon.addEventListener("click", (e) => {
        e.stopPropagation();
        const isHidden = dropdown.classList.contains("hidden");
        
        if (!isHidden) {
            dropdown.classList.add("hidden", "opacity-0", "scale-95");
            dropdown.classList.remove("block", "opacity-100", "scale-100");
            userIcon.style.transform = "scale(1.1)";
        } else {
            dropdown.classList.remove("hidden", "opacity-0", "scale-95");
            dropdown.classList.add("block", "opacity-100", "scale-100");
            userIcon.style.transform = "scale(0.9)";
        }
        
        setTimeout(() => {
            userIcon.style.transform = "";
        }, 200);
    });

    document.addEventListener("click", (e) => {
        if (!dropdown.contains(e.target) && !userIcon.contains(e.target)) {
            dropdown.classList.add("hidden", "opacity-0", "scale-95");
            dropdown.classList.remove("block", "opacity-100", "scale-100");
        }
    });

    iconLinks.forEach(icon => {
        icon.addEventListener("mousedown", () => {
            icon.style.transform = "scale(0.85)";
        });
        icon.addEventListener("mouseup", () => {
            icon.style.transform = "translateY(-2px) scale(1.1)";
        });
        icon.addEventListener("mouseleave", () => {
            icon.style.transform = "";
        });
    });
});
</script>