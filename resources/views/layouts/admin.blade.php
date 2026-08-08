<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard - Food Hub')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind CSS (via Fallback CDN + Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
<body class="bg-slate-100 text-slate-800 min-h-screen flex flex-col font-sans antialiased" x-data="{ sidebarOpen: false }">

    <!-- Top Admin Header -->
    <header class="bg-slate-900 text-white h-16 flex items-center justify-between px-6 z-20 sticky top-0 shadow-md">
        <div class="flex items-center gap-4">
            <!-- Mobile Menu Toggle Button -->
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-300 hover:text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                <span class="bg-orange-500 text-white p-1.5 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-11.314l.707.707m11.314 11.314l.707-.707M4 5a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 10a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2zm0-10a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1v-2z"></path></svg>
                </span>
                <span class="text-lg font-bold tracking-tight text-white">FoodHub <span class="text-orange-500 font-medium text-sm bg-orange-500/10 px-2 py-0.5 rounded border border-orange-500/20">Admin</span></span>
            </a>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="hidden md:flex flex-col text-right">
                <span class="text-sm font-semibold">{{ Auth::user()->name ?? 'Administrator' }}</span>
                <span class="text-xs text-orange-400">Main Kitchen Admin</span>
            </div>
            
            <!-- Log out Button (Standard Form) -->
            <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </form>
        </div>
    </header>

    <div class="flex flex-1 relative">
        <!-- Sidebar Navigation -->
        <aside class="bg-slate-800 text-slate-300 w-64 lg:block flex-shrink-0 z-10 transition-all duration-300 absolute inset-y-0 left-0 transform -translate-x-full lg:relative lg:translate-x-0"
               :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
            <div class="p-6">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4">Management</p>
                <nav class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-200 {{ Route::is('admin.dashboard') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/10' : 'hover:bg-slate-700 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Customer Orders
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-200 {{ Route::is('admin.categories.*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/10' : 'hover:bg-slate-700 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                        Food Categories
                    </a>
                    <a href="{{ route('admin.foods.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-200 {{ Route::is('admin.foods.*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/10' : 'hover:bg-slate-700 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        Food Menu Items
                    </a>
                </nav>
                
                <div class="mt-8 border-t border-slate-700 pt-6">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4">Storefront</p>
                    <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-slate-400 hover:bg-slate-700 hover:text-white transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back to Website
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Workspace Area -->
        <main class="flex-grow p-6 lg:p-8 overflow-y-auto max-w-7xl mx-auto w-full">
            <!-- Toast messages inside admin panel -->
            @if(session('success') || session('error'))
                <div class="mb-6 w-full" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                    @if(session('success'))
                        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-xl flex items-center justify-between shadow-sm">
                            <span class="text-sm font-semibold flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ session('success') }}
                            </span>
                            <button @click="show = false" class="text-emerald-500 hover:text-emerald-800"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl flex items-center justify-between shadow-sm">
                            <span class="text-sm font-semibold flex items-center gap-2">
                                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ session('error') }}
                            </span>
                            <button @click="show = false" class="text-rose-500 hover:text-rose-800"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></button>
                        </div>
                    @endif
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>
