@extends('layouts.admin')

@section('title', 'Edit Food Item - Food Hub')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Breadcrumbs / Header -->
    <div class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('admin.foods.index') }}" class="hover:text-slate-900 font-medium transition-colors">Menu Items</a>
        <span>/</span>
        <span class="text-slate-800">Edit Menu Item</span>
    </div>
    
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Edit Menu Item</h1>
        <p class="text-sm text-slate-500 mt-1">Modify dish details, price, availability, or upload a new photo.</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl border border-slate-200/60 p-8 shadow-sm">
        <form action="{{ route('admin.foods.update', $food->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Dish Name -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Dish Name</label>
                    <input type="text" name="name" id="name" required value="{{ old('name', $food->name) }}"
                           class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 text-sm transition-all"
                           placeholder="e.g. Pepperoni Pizza">
                    @error('name')
                        <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Category Selector -->
                <div>
                    <label for="category_id" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Category</label>
                    <select name="category_id" id="category_id" required
                            class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 text-sm transition-all cursor-pointer">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $food->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Price -->
                <div>
                    <label for="price" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Price ($ USD)</label>
                    <input type="number" step="0.01" name="price" id="price" required value="{{ old('price', $food->price) }}"
                           class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 text-sm transition-all"
                           placeholder="e.g. 14.99">
                    @error('price')
                        <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Availability -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Availability Status</label>
                    <div class="flex items-center mt-3">
                        <input type="checkbox" name="is_available" id="is_available" value="1" {{ old('is_available', $food->is_available) ? 'checked' : '' }}
                               class="w-4 h-4 rounded text-orange-500 focus:ring-orange-500 border-slate-300 bg-slate-50">
                        <label for="is_available" class="ml-2.5 text-sm text-slate-700 font-semibold cursor-pointer">Available for order</label>
                    </div>
                    @error('is_available')
                        <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Description</label>
                <textarea name="description" id="description" rows="3" required
                          class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 text-sm transition-all resize-none"
                          placeholder="Describe ingredients, taste profile, and portions...">{{ old('description', $food->description) }}</textarea>
                @error('description')
                    <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Current Image Preview -->
            @if($food->image)
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Current Photo</label>
                    <div class="w-48 h-36 rounded-2xl overflow-hidden bg-slate-100 border border-slate-200">
                        <img src="{{ asset($food->image) }}" alt="{{ $food->name }}" class="w-full h-full object-cover">
                    </div>
                </div>
            @endif

            <!-- Replace Image Upload -->
            <div>
                <label for="image" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Replace Image</label>
                <input type="file" name="image" id="image" accept="image/*"
                       class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 cursor-pointer">
                <p class="text-xs text-slate-400 mt-2">Recommended format: JPG, PNG, or WebP. Max file size: 2MB. Leave blank to keep current photo.</p>
                @error('image')
                    <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="border-t border-slate-100 pt-6 flex items-center justify-end gap-3">
                <a href="{{ route('admin.foods.index') }}" class="px-5 py-3 rounded-2xl border border-slate-200 text-slate-700 font-semibold text-sm hover:bg-slate-50 transition-all duration-200">
                    Cancel
                </a>
                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm px-6 py-3 rounded-2xl shadow-md shadow-orange-500/10 active:scale-95 transition-all duration-200">
                    Update Menu Item
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
