<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Food Hub</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col justify-center items-center p-6 font-sans antialiased">

    <div class="w-full max-w-md space-y-6">
        <!-- Logo -->
        <div class="text-center space-y-2">
            <span class="inline-flex items-center justify-center bg-orange-500 text-white p-3 rounded-2xl shadow-lg shadow-orange-500/30">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-11.314l.707.707m11.314 11.314l.707-.707M4 5a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 10a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2zm0-10a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1v-2z"></path></svg>
            </span>
            <h1 class="text-3xl font-extrabold tracking-tight">FoodHub <span class="text-orange-500 text-2xl font-semibold bg-orange-500/10 px-2.5 py-0.5 rounded border border-orange-500/20">Admin</span></h1>
            <p class="text-slate-400 text-sm">Please log in to manage store settings and kitchen orders.</p>
        </div>

        <!-- Login Form Card -->
        <div class="bg-slate-800 rounded-3xl border border-slate-700/50 p-8 shadow-2xl relative overflow-hidden">
            <!-- Decorative gradient blur -->
            <div class="absolute -top-20 -right-20 w-40 h-40 bg-orange-500/10 rounded-full blur-3xl"></div>
            
            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5 relative z-10">
                @csrf
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Admin Email</label>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}"
                           class="w-full px-4 py-3 rounded-2xl bg-slate-900 border border-slate-700 focus:outline-none focus:border-orange-500 text-sm text-white transition-all"
                           placeholder="admin@foodhub.com">
                    @error('email')
                        <span class="text-xs text-red-400 font-medium mt-1.5 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Password</label>
                    <input type="password" name="password" id="password" required
                           class="w-full px-4 py-3 rounded-2xl bg-slate-900 border border-slate-700 focus:outline-none focus:border-orange-500 text-sm text-white transition-all"
                           placeholder="••••••••">
                    @error('password')
                        <span class="text-xs text-red-400 font-medium mt-1.5 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded text-orange-500 focus:ring-orange-500 border-slate-700 bg-slate-950">
                    <label for="remember" class="ml-2.5 text-sm text-slate-300 font-medium cursor-pointer">Remember my session</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full mt-4 bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white font-extrabold py-3.5 rounded-2xl shadow-lg shadow-orange-500/20 active:scale-95 transition-all">
                    Sign In to Dashboard
                </button>
            </form>
        </div>
        
        <!-- Default Account Hint Alert -->
        <div class="bg-orange-500/10 border border-orange-500/20 rounded-2xl p-4 text-sm text-orange-300 flex items-start gap-3">
            <span class="text-lg">💡</span>
            <div>
                <p class="font-bold text-orange-400">Demo Login Details</p>
                <p class="text-xs text-orange-300/80 mt-1">Use the following seeded credentials to log in as administrator:</p>
                <p class="text-xs font-mono mt-1 text-white">Email: admin@foodhub.com</p>
                <p class="text-xs font-mono text-white">Password: admin12345</p>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('home') }}" class="text-xs text-slate-500 hover:text-slate-400 transition-colors">
                ← Go back to Customer Site
            </a>
        </div>
    </div>

</body>
</html>
