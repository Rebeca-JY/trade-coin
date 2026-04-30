<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Crimson+Pro:wght@400;600&display=swap');
        body { font-family: 'Crimson Pro', serif; }
    </style>
</head>
<body class="bg-[#567f89] min-h-screen flex items-center justify-center p-4">

    <div class="bg-[#dce6e9] w-full max-w-[400px] rounded-[30px] overflow-hidden shadow-2xl">
        
        <div class="bg-[#c2d1d6] py-8 text-center border-b border-[#b0c4c9]">
            <h1 class="text-[#3a5a64] text-4xl">Log in</h1>
        </div>

        <div class="p-10">
            <form class="space-y-8">
                
                <div class="relative">
                    <input type="text" 
                        placeholder="Enter your email/username" 
                        class="w-full bg-transparent border-b border-[#3a5a64] py-2 outline-none text-[#3a5a64] placeholder-[#567f89]">
                </div>

                <div class="relative">
                    <input type="password" 
                        placeholder="Enter your Password" 
                        class="w-full bg-transparent border-b border-[#3a5a64] py-2 outline-none text-[#3a5a64] placeholder-[#567f89]">
                </div>

                <div class="flex items-center justify-between text-[#3a5a64] text-sm">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 rounded border-[#3a5a64] accent-[#567f89]">
                        <span>Remember me</span>
                    </label>
                    <a href="#" class="hover:underline">Forgot password?</a>
                </div>

                <button type="submit" 
                    class="w-full bg-[#567f89] text-white py-3 rounded-2xl text-2xl border border-[#3a5a64] shadow-md transition-all hover:bg-[#4a6d76]">
                    Log in
                </button>

                <div class="text-center text-[#3a5a64] text-sm pt-4">
                    <p>Don't have an account? <a href="#" class="font-semibold hover:underline">Register</a></p>
                </div>

            </form>
        </div>
    </div>

</body>
</html>