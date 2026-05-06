<?php
// Admin Navbar Component
?>
<nav class="bg-gray-900 text-white shadow-lg">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <!-- Logo/Brand -->
            <div class="flex items-center gap-2">
                <i class="fas fa-cogs text-2xl text-blue-400"></i>
                <span class="font-bold text-xl">Admin Panel</span>
            </div>

            <!-- Navigation Links -->
            <div class="flex items-center gap-8">
                <a href="/admin/products" class="hover:text-blue-400 transition flex items-center gap-2">
                    <i class="fas fa-box"></i>
                    Produk
                </a>
                <a href="/admin/users" class="hover:text-blue-400 transition flex items-center gap-2">
                    <i class="fas fa-users"></i>
                    Users
                </a>
                <a href="/products" class="hover:text-blue-400 transition flex items-center gap-2">
                    <i class="fas fa-home"></i>
                    Kembali ke Home
                </a>
            </div>

            <!-- User Profile Dropdown -->
            <div class="relative">
                <button onclick="toggleAdminDropdown()" class="flex items-center gap-2 hover:text-blue-400 transition">
                    <i class="fas fa-user-circle text-2xl"></i>
                    <span class="hidden md:inline">Admin</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
                <div id="adminDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white text-gray-900 rounded-lg shadow-lg py-2 z-50">
                    <a href="/profile" class="block px-4 py-2 hover:bg-gray-100">
                        <i class="fas fa-user-circle mr-2"></i> Profil
                    </a>
                    <a href="/login" class="block px-4 py-2 hover:bg-gray-100 text-red-600">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
function toggleAdminDropdown() {
    const dropdown = document.getElementById('adminDropdown');
    dropdown.classList.toggle('hidden');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('adminDropdown');
    const button = event.target.closest('button');
    if (!button && !dropdown.contains(event.target)) {
        dropdown.classList.add('hidden');
    }
});
</script>
