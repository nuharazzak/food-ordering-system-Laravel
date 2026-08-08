@extends('layouts.admin')

@section('title', 'Manage Categories - Food Hub')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Food Categories</h1>
            <p class="text-sm text-slate-500 mt-1">Manage food menu sections and categories.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm px-5 py-3 rounded-2xl shadow-md shadow-orange-500/10 flex items-center gap-2 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            Add New Category
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200/60 overflow-hidden shadow-sm">
        @if($categories->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-400">
                            <th class="py-4 px-6">Image</th>
                            <th class="py-4 px-6">Category Name</th>
                            <th class="py-4 px-6">Slug identifier</th>
                            <th class="py-4 px-6 text-center">Items Count</th>
                            <th class="py-4 px-6 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach($categories as $category)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <!-- Image Preview -->
                                <td class="py-4 px-6">
                                    <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 flex-shrink-0 flex items-center justify-center">
                                        @if($category->image)
                                            <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-lg">🍽️</span>
                                        @endif
                                    </div>
                                </td>
                                
                                <!-- Name -->
                                <td class="py-4 px-6 font-bold text-slate-900">
                                    {{ $category->name }}
                                </td>

                                <!-- Slug -->
                                <td class="py-4 px-6 font-mono text-xs text-slate-500">
                                    {{ $category->slug }}
                                </td>

                                <!-- Food Items Count -->
                                <td class="py-4 px-6 text-center font-extrabold text-orange-500">
                                    {{ $category->foods_count }}
                                </td>

                                <!-- CRUD actions -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Edit -->
                                        <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                           class="bg-blue-50 hover:bg-blue-100 text-blue-600 p-2.5 rounded-xl transition-all"
                                           title="Edit category">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M18.364 4.364a9 9 0 00-12.728 0l1.272 1.272m12.728-1.272l-1.272 1.272m0 0L8 14H6v-2l8-8m2 2l-2 2"></path></svg>
                                        </a>

                                        <!-- Delete -->
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete category \'{{ $category->name }}\'? This will also delete ALL related food items.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 p-2.5 rounded-xl transition-all" title="Delete category">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-20 bg-slate-50/50">
                <span class="text-4xl">🍽️</span>
                <h3 class="text-lg font-bold text-slate-800 mt-4">No categories created yet</h3>
                <p class="text-slate-500 mt-1 text-sm">Create food categories to start setting up your restaurant menu.</p>
                <a href="{{ route('admin.categories.create') }}" class="inline-block bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow mt-4">
                    Create First Category
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
