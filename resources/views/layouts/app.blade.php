<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Food Hub - Delicious Restaurant Ordering')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- AlpineJS for interactive UI -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind CSS (via Vite with Fallback CDN for instant preview) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    colors: {
                        primary: {
                            50: '#fdf3f2',
                            100: '#fbe4e2',
                            500: '#ea580c', // Orange-600 equivalent
                            600: '#dc2626', // Red-600 equivalent
                            700: '#b91c1c',
                        },
                        secondary: '#1e293b',
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        .serif-font {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>
<body class="bg-stone-50 text-slate-800 min-h-screen flex flex-col antialiased">

    <!-- Header / Navbar -->
    <header class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-100 shadow-sm" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                        <span class="bg-gradient-to-r from-orange-500 to-red-600 text-white p-2.5 rounded-2xl shadow-md shadow-orange-500/20 group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-11.314l.707.707m11.314 11.314l.707-.707M4 5a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 10a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2zm0-10a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1v-2z"></path>
                            </svg>
                        </span>
                        <span class="text-2xl font-extrabold bg-gradient-to-r from-slate-900 to-slate-800 bg-clip-text text-transparent tracking-tight">Food<span class="text-orange-500">Hub</span></span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-base font-semibold transition-colors duration-200 {{ Route::is('home') ? 'text-orange-500' : 'text-slate-600 hover:text-slate-900' }}">Home</a>
                    <a href="{{ route('menu') }}" class="text-base font-semibold transition-colors duration-200 {{ Route::is('menu') ? 'text-orange-500' : 'text-slate-600 hover:text-slate-900' }}">Menu</a>
                    
                    @auth
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="text-base font-semibold text-red-600 hover:text-red-700 bg-red-50 px-3 py-1.5 rounded-xl transition-colors duration-200">Admin Dashboard</a>
                        @endif
                    @endauth
                </nav>

                <!-- Cart and Quick Actions -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('cart.index') }}" class="relative p-2.5 text-slate-600 hover:text-orange-500 bg-slate-50 hover:bg-orange-50 rounded-2xl transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        @php
                            $cartCount = 0;
                            if (session()->has('cart')) {
                                foreach(session('cart') as $item) {
                                    $cartCount += $item['quantity'];
                                }
                            }
                        @endphp
                        @if($cartCount > 0)
                            <span class="absolute -top-1 -right-1 bg-orange-500 text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full ring-2 ring-white animate-pulse">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                    
                    <a href="{{ route('menu') }}" class="bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white font-bold px-6 py-3 rounded-2xl shadow-lg shadow-orange-500/20 hover:shadow-orange-500/30 hover:-translate-y-0.5 transition-all duration-300">
                        Order Now
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center gap-4">
                    <a href="{{ route('cart.index') }}" class="relative p-2.5 text-slate-600 hover:text-orange-500 bg-slate-50 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        @if($cartCount > 0)
                            <span class="absolute -top-1 -right-1 bg-orange-500 text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full ring-2 ring-white">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                    
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-slate-600 hover:text-slate-900 focus:outline-none p-2 bg-slate-50 rounded-xl" aria-label="Toggle Menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" x-show="!mobileMenuOpen">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" x-show="mobileMenuOpen" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden border-t border-slate-100 bg-white" x-show="mobileMenuOpen" x-transition style="display: none;">
            <div class="px-4 pt-2 pb-6 space-y-2">
                <a href="{{ route('home') }}" class="block px-4 py-3 rounded-xl text-base font-semibold text-slate-700 hover:bg-slate-50 hover:text-orange-500">Home</a>
                <a href="{{ route('menu') }}" class="block px-4 py-3 rounded-xl text-base font-semibold text-slate-700 hover:bg-slate-50 hover:text-orange-500">Menu</a>
                
                @auth
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-xl text-base font-semibold text-red-600 hover:bg-red-50">Admin Dashboard</a>
                    @endif
                @endauth
                
                <div class="pt-4 px-4">
                    <a href="{{ route('menu') }}" class="w-full bg-gradient-to-r from-orange-500 to-red-600 text-white font-bold py-3 rounded-xl flex items-center justify-center shadow-md">
                        Order Now
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Success & Error Session Toast notifications -->
    @if(session('success') || session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            @if(session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-xl flex items-start justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm font-semibold">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </button>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl flex items-start justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm font-semibold">{{ session('error') }}</p>
                    </div>
                    <button @click="show = false" class="text-rose-500 hover:text-rose-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </button>
                </div>
            @endif
        </div>
    @endif

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 border-t border-slate-800 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About / Logo -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="bg-orange-500 text-white p-2 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-11.314l.707.707m11.314 11.314l.707-.707M4 5a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 10a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2zm0-10a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1v-2z"></path></svg>
                        </span>
                        <span class="text-xl font-bold text-white tracking-tight">Food<span class="text-orange-500">Hub</span></span>
                    </div>
                    <p class="text-sm leading-relaxed">Serving the finest gourmet pizzas, juicy flame-grilled burgers, fresh drinks, and delicious desserts. Order hot and fresh food directly to your doorstep.</p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-sm font-semibold text-slate-200 uppercase tracking-wider mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors duration-200">Home</a></li>
                        <li><a href="{{ route('menu') }}" class="hover:text-white transition-colors duration-200">Food Menu</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-white transition-colors duration-200">Shopping Cart</a></li>
                        <li><a href="{{ route('admin.login') }}" class="hover:text-white transition-colors duration-200">Admin Login</a></li>
                    </ul>
                </div>

                <!-- Opening Hours -->
                <div>
                    <h3 class="text-sm font-semibold text-slate-200 uppercase tracking-wider mb-4">Opening Hours</h3>
                    <ul class="space-y-2 text-sm">
                        <li class="flex justify-between"><span>Monday - Friday</span><span class="text-slate-300">11:00 AM - 10:00 PM</span></li>
                        <li class="flex justify-between"><span>Saturday</span><span class="text-slate-300">12:00 PM - 11:00 PM</span></li>
                        <li class="flex justify-between"><span>Sunday</span><span class="text-orange-400">12:00 PM - 09:00 PM</span></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-sm font-semibold text-slate-200 uppercase tracking-wider mb-4">Get In Touch</h3>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>123 Culinary Boulevard, Food Town</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span>+1 (555) 123-4567</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span>contact@foodhub.com</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Line -->
            <div class="border-t border-slate-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs">
                <p>&copy; {{ date('Y') }} FoodHub Restaurant. All rights reserved. Designed for Developer Portfolio.</p>
                <div class="flex gap-4">
                    <a href="{{ route('admin.login') }}" class="hover:text-white transition-colors duration-200">Admin Section</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
