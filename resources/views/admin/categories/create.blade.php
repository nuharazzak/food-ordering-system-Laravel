@extends('layouts.admin')

@section('title', 'Add Category - Food Hub')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Breadcrumbs / Header -->
    <div class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('admin.categories.index') }}" class="hover:text-slate-900 font-medium transition-colors">Categories</a>
        <span>/</span>
        <span class="text-slate-800">Add New Category</span>
    </div>
    
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Create Food Category</h1>
        <p class="text-sm text-slate-500 mt-1">Specify a unique category title and upload an icon image.</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl border border-slate-200/60 p-8 shadow-sm">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Category Name -->
            <div>
                <label for="name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Category Name</label>
                <input type="text" name="name" id="name" required value="{{ old('name') }}"
                       class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 text-sm transition-all"
                       placeholder="e.g. Pizza, Burgers, Drinks, etc.">
                @error('name')
                    <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Image upload -->
            <div>
                <label for="image" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Category Image</label>
                <input type="file" name="image" id="image" accept="image/*"
                       class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 cursor-pointer">
                <p class="text-xs text-slate-400 mt-2">Recommended format: JPG, PNG, or WebP. Max file size: 2MB.</p>
                @error('image')
                    <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="border-t border-slate-100 pt-6 flex items-center justify-end gap-3">
                <a href="{{ route('admin.categories.index') }}" class="px-5 py-3 rounded-2xl border border-slate-200 text-slate-700 font-semibold text-sm hover:bg-slate-50 transition-all duration-200">
                    Cancel
                </a>
                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm px-6 py-3 rounded-2xl shadow-md shadow-orange-500/10 active:scale-95 transition-all duration-200">
                    Save Category
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
