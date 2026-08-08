@extends('layouts.app')

@section('title', 'Food Hub - Explore Our Menu')

@section('content')
<section class="py-12 bg-stone-50 border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 serif-font text-center">Gourmet Food Menu</h1>
        <p class="text-slate-500 text-center mt-2 max-w-md mx-auto text-sm sm:text-base">Browse through our menu, search by name, or select a category to find your favorite food.</p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Search & Filter Controls -->
    <div class="flex flex-col lg:flex-row gap-6 justify-between items-stretch lg:items-center mb-10">
        <!-- Search Form -->
        <form action="{{ route('menu') }}" method="GET" class="w-full lg:max-w-md">
            <!-- Retain current category filter if searching -->
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <div class="relative flex items-center">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search food by name..."
                       class="w-full pl-12 pr-4 py-3.5 rounded-2xl bg-white border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 text-sm transition-all duration-200">
                <span class="absolute left-4 text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <button type="submit" class="absolute right-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold text-xs px-4 py-2.5 rounded-xl shadow-sm transition-colors duration-200">
                    Search
                </button>
            </div>
        </form>

        <!-- Category Filters -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 lg:pb-0 scrollbar-none scroll-smooth">
            <!-- All Categories -->
            <a href="{{ route('menu', request()->only('search')) }}" 
               class="px-5 py-3 rounded-2xl text-sm font-semibold whitespace-nowrap transition-all duration-200 {{ !$selectedCategory ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/10' : 'bg-white text-slate-600 hover:bg-orange-50 hover:text-orange-500 border border-slate-100' }}">
                All Menu
            </a>
            
            @foreach($categories as $category)
                <a href="{{ route('menu', array_merge(request()->only('search'), ['category' => $category->slug])) }}"
                   class="px-5 py-3 rounded-2xl text-sm font-semibold whitespace-nowrap transition-all duration-200 {{ $selectedCategory === $category->slug ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/10' : 'bg-white text-slate-600 hover:bg-orange-50 hover:text-orange-500 border border-slate-100' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Active Filters Summary -->
    @if(request('search') || request('category'))
        <div class="flex items-center justify-between bg-slate-50 border border-slate-200/50 p-4 rounded-2xl mb-8 text-sm">
            <div class="flex items-center flex-wrap gap-2 text-slate-600">
                <span>Active Filters:</span>
                @if(request('category'))
                    <span class="bg-white border border-slate-200 px-3 py-1 rounded-xl font-medium text-orange-500">
                        Category: {{ ucfirst(request('category')) }}
                    </span>
                @endif
                @if(request('search'))
                    <span class="bg-white border border-slate-200 px-3 py-1 rounded-xl font-medium text-orange-500">
                        Search: "{{ request('search') }}"
                    </span>
                @endif
            </div>
            <a href="{{ route('menu') }}" class="text-xs font-bold text-red-500 hover:text-red-600 underline">Clear All</a>
        </div>
    @endif

    <!-- Menu Items Grid -->
    @if($foods->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($foods as $food)
                <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-xl hover:border-slate-200/60 transition-all duration-300 flex flex-col group">
                    <!-- Image -->
                    <div class="relative h-56 w-full overflow-hidden bg-slate-100">
                        <img src="{{ asset($food->image) }}" alt="{{ $food->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-4 left-4 bg-white/95 backdrop-blur-md text-orange-600 text-xs font-extrabold px-3 py-1.5 rounded-full shadow-sm border border-slate-100">
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
    @else
        <!-- Empty State -->
        <div class="text-center py-20 bg-slate-50 rounded-3xl border border-dashed border-slate-200 max-w-xl mx-auto">
            <span class="text-5xl">🔍</span>
            <h3 class="text-lg font-bold text-slate-800 mt-4">No menu items found</h3>
            <p class="text-slate-500 mt-2 text-sm">We couldn't find any dishes matching your current search criteria. Try a different filter or search term.</p>
            <a href="{{ route('menu') }}" class="inline-block mt-6 bg-orange-500 hover:bg-orange-600 text-white font-bold px-6 py-3 rounded-2xl shadow-sm text-sm transition-colors duration-200">
                Clear Filters & Reset
            </a>
        </div>
    @endif
</div>
@endsection
