@extends('layouts.app')

@section('title', 'Food Hub - Delicious Restaurant Ordering')

@section('content')
<!-- Hero Section -->
<section class="relative bg-slate-900 text-white overflow-hidden py-24 lg:py-32">
    <!-- Background Decor -->
    <div class="absolute inset-0 bg-cover bg-center opacity-75 filter blur-[2px]" style="background-image: url('{{ asset('images/food_hero_bg.jpg') }}');"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-slate-990 via-slate-900/90 to-transparent"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-2xl lg:max-w-3xl space-y-6">
            <span class="inline-flex items-center gap-1.5 py-1.5 px-4 rounded-full text-xs font-bold bg-orange-500/10 text-orange-400 border border-orange-500/20 uppercase tracking-widest animate-pulse">
                🍕 Freshly Baked & Cooked
            </span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-none text-white serif-font">
                Savor the Best Flavors, <br>
                <span class="text-orange-500 bg-gradient-to-r from-orange-400 to-red-500 bg-clip-text text-transparent">Delivered to You</span>
            </h1>
            <p class="text-lg text-slate-300 leading-relaxed max-w-xl">
                Experience gourmet cooking from our master chefs. Order your favorite burgers, pizzas, pastas, drinks, and desserts with simple checkout and blazing-fast kitchen preparation.
            </p>
            <div class="pt-4 flex flex-wrap gap-4">
                <a href="{{ route('menu') }}" class="bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white font-bold px-8 py-4 rounded-2xl shadow-lg shadow-orange-500/25 hover:shadow-orange-500/35 hover:-translate-y-0.5 transition-all duration-300">
                    Explore Full Menu
                </a>
                <a href="#categories" class="bg-slate-800 hover:bg-slate-700 text-white font-semibold px-8 py-4 rounded-2xl border border-slate-700 transition-all duration-300">
                    Browse Categories
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section id="categories" class="py-16 sm:py-24 bg-stone-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-xl mx-auto mb-12">
            <h2 class="text-3xl font-bold tracking-tight text-slate-900 serif-font">Explore Categories</h2>
            <p class="text-slate-500 mt-2 text-sm sm:text-base">Filter our gourmet selection and find exactly what you are craving right now.</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
            @foreach($categories as $category)
                <a href="{{ route('menu', ['category' => $category->slug]) }}" 
                   class="group relative bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col items-center text-center transition-all duration-300 hover:shadow-md hover:border-orange-500/30 hover:-translate-y-1">
                    
                    <!-- Category Image / Icon Container -->
                    <div class="w-16 h-16 rounded-2xl overflow-hidden mb-4 bg-orange-50 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        @if($category->image)
                            <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-2xl">🍽️</span>
                        @endif
                    </div>
                    
                    <h3 class="font-bold text-slate-800 group-hover:text-orange-500 transition-colors duration-200">{{ $category->name }}</h3>
                    <span class="text-xs text-slate-400 mt-1">View items</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Food Items Section -->
<section class="py-16 sm:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-12 gap-4">
            <div class="text-center sm:text-left">
                <h2 class="text-3xl font-bold tracking-tight text-slate-900 serif-font">Chef's Specialties</h2>
                <p class="text-slate-500 mt-1 text-sm sm:text-base">Our most popular and freshly prepared food items, highly recommended.</p>
            </div>
            <a href="{{ route('menu') }}" class="text-orange-500 hover:text-orange-600 font-bold flex items-center gap-1 group">
                View Full Menu 
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <!-- Food Items Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($featuredFoods as $food)
                <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-xl hover:border-slate-200/60 transition-all duration-300 flex flex-col group">
                    <!-- Image -->
                    <div class="relative h-60 w-full overflow-hidden bg-slate-100">
                        <img src="{{ asset($food->image) }}" alt="{{ $food->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-4 left-4 bg-white/90 backdrop-blur-md text-orange-600 text-xs font-extrabold px-3 py-1.5 rounded-full shadow-sm">
                            {{ $food->category->name }}
                        </span>
                    </div>
                    
                    <!-- Details -->
                    <div class="p-6 flex-grow flex flex-col justify-between space-y-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">{{ $food->name }}</h3>
                            <p class="text-slate-500 text-sm mt-2 line-clamp-2 leading-relaxed">{{ $food->description }}</p>
                        </div>
                        
                        <div class="flex items-center justify-between pt-2">
                            <span class="text-2xl font-extrabold text-slate-950">${{ number_format($food->price, 2) }}</span>
                            
                            <!-- Add to Cart Form -->
                            <form action="{{ route('cart.add', $food->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-orange-50 hover:bg-orange-500 text-orange-500 hover:text-white p-3 rounded-2xl hover:scale-105 shadow-sm active:scale-95 transition-all duration-200 flex items-center justify-center" aria-label="Add to cart">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                    <span class="text-sm font-bold ml-1.5 hidden md:inline">Add to Cart</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
