<?php
/**
 * Form login — pemrosesan hanya lewat LoginController (POST /login).
 */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$error = $error ?? '';
$loginId = $loginId ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $loginId === '') {
    $loginId = trim((string)($_POST['login_id'] ?? ''));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - My App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Crimson+Pro:wght@400;600&display=swap');
        body { font-family: 'Crimson Pro', serif; }
    </style>
</head>

<body class="bg-[#567f89] min-h-screen flex items-center justify-center p-4">

    <div class="bg-[#dce6e9] w-full max-w-[400px] rounded-[30px] overflow-hidden shadow-2xl">

        <!-- HEADER -->
        <div class="bg-[#c2d1d6] py-8 px-6 text-center border-b border-[#b0c4c9] relative">
            <a href="/" class="absolute left-6 top-1/2 -translate-y-1/2 text-[#3a5a64] font-bold no-underline transition-all duration-300 hover:-translate-x-1">
                <i class="fa-solid fa-chevron-left"></i> Back
            </a>
            <h1 class="text-[#3a5a64] text-4xl">Log in</h1>
        </div>

        <!-- BODY -->
        <div class="p-10">

            <!-- PESAN ERROR -->
            <?php if (!empty($error)): ?>
                <div class="mb-6 p-3 bg-red-100 border border-red-300 text-red-700 rounded-lg text-sm text-center animate-pulse">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="/login" method="POST" class="space-y-8">

                <!-- EMAIL / USERNAME -->
                <div class="relative">
                    <label class="block font-bold text-[#3a5a64] mb-1" for="login_id">
                        Email/Username
                    </label>
                    <input
                        type="text"
                        id="login_id"
                        name="login_id"
                        value="<?= htmlspecialchars($loginId) ?>"
                        placeholder="Enter your email/username"
                        class="w-full bg-transparent border-b border-[#3a5a64] py-2 outline-none text-[#3a5a64] placeholder-[#567f89]/50"
                        required>
                </div>

                <!-- PASSWORD -->
                <div class="relative">
                    <label class="block font-bold text-[#3a5a64] mb-1" for="password">
                        Password
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your Password"
                        class="w-full bg-transparent border-b border-[#3a5a64] py-2 outline-none text-[#3a5a64] placeholder-[#567f89]/50"
                        required>
                </div>

                <!-- REMEMBER & FORGOT -->
                <div class="flex items-center justify-between text-[#3a5a64] text-sm">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input
                            type="checkbox"
                            name="remember_me"
                            class="w-4 h-4 rounded border-[#3a5a64] accent-[#567f89]">
                        <span>Remember me</span>
                    </label>
                    <a href="#" class="hover:underline">Forgot password?</a>
                </div>

                <!-- BUTTON -->
                <button
                    type="submit"
                    class="w-full bg-[#567f89] text-white py-3 rounded-2xl text-2xl border border-[#3a5a64] shadow-md transition-all hover:bg-[#4a6d76] active:scale-95">
                    Log in
                </button>

                <!-- REGISTER LINK -->
                <div class="text-center text-[#3a5a64] text-sm pt-4">
                    <p>
                        Don't have an account?
                        <a href="/register" class="font-semibold hover:underline">Register</a>
                    </p>
                </div>

            </form>
        </div>
    </div>

</body>
</html>