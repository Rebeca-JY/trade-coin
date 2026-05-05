<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Crimson+Pro:wght@400;600&display=swap');
        body { font-family: 'Crimson Pro', serif; }
    </style>
</head>

<body class="bg-[#567f89] min-h-screen flex items-center justify-center p-4">

<div class="bg-[#dce6e9] w-full max-w-[400px] rounded-[30px] overflow-hidden shadow-2xl">
    
    <!-- Header -->
    <div class="bg-[#c2d1d6] py-8 px-6 text-center border-b border-[#b0c4c9] relative">
        <a href="/" class="absolute left-6 top-1/2 -translate-y-1/2 text-[#3a5a64] font-bold hover:-translate-x-1 transition">
            <i class="fa-solid fa-chevron-left"></i> Back
        </a>
        <h1 class="text-[#3a5a64] text-4xl">Sign Up</h1>
    </div>

    <!-- Card Body Start -->
    <div class="p-10">
        <form method="POST" class="space-y-6">

            <!-- Email -->
            <div>
                <label class="block font-bold text-[#3a5a64] mb-1">Email</label>
                <input type="email" name="email" placeholder="Enter your email"
                class="w-full bg-transparent border-b border-[#3a5a64] py-2 outline-none text-[#3a5a64]" required>
            </div>

            <!-- Password -->
            <div>
                <label class="block font-bold text-[#3a5a64] mb-1">Password</label>
                <input type="password" name="password" placeholder="Enter password"
                class="w-full bg-transparent border-b border-[#3a5a64] py-2 outline-none text-[#3a5a64]" required>
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block font-bold text-[#3a5a64] mb-1">Confirm Password</label>
                <input type="password" name="confirm_password" placeholder="Confirm password"
                class="w-full bg-transparent border-b border-[#3a5a64] py-2 outline-none text-[#3a5a64]" required>
            </div>

            <!-- Terms -->
            <label class="flex items-center gap-2 text-[#3a5a64] text-sm">
                <input type="checkbox" required class="accent-[#567f89]">
                I agree to the terms & conditions
            </label>

            <!-- Button -->
            <button type="submit"
                class="w-full bg-[#567f89] text-white py-3 rounded-2xl text-xl border border-[#3a5a64] shadow-md hover:bg-[#4a6d76] transition">
                Sign Up
            </button>

            <!-- Login link -->
            <div class="text-center text-[#3a5a64] text-sm pt-3">
                Already have an account? 
                <a href="/login" class="font-semibold hover:underline">Login</a>
            </div>

        </form>
    </div>
</div>

</body>
</html>