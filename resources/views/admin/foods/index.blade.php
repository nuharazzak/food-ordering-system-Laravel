@extends('layouts.admin')

@section('title', 'Manage Food Menu - Food Hub')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Food Menu Items</h1>
            <p class="text-sm text-slate-500 mt-1">Manage dishes, descriptions, pricing, and availability status.</p>
        </div>
        <a href="{{ route('admin.foods.create') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm px-5 py-3 rounded-2xl shadow-md shadow-orange-500/10 flex items-center gap-2 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            Add Menu Item
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200/60 overflow-hidden shadow-sm">
        @if($foods->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-400">
                            <th class="py-4 px-6">Image</th>
                            <th class="py-4 px-6">Dish Name</th>
                            <th class="py-4 px-6">Category</th>
                            <th class="py-4 px-6">Description</th>
                            <th class="py-4 px-6 text-right">Price</th>
                            <th class="py-4 px-6 text-center">Status</th>
                            <th class="py-4 px-6 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach($foods as $food)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <!-- Image Preview -->
                                <td class="py-4 px-6">
                                    <div class="w-16 h-12 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 flex-shrink-0 flex items-center justify-center">
                                        @if($food->image)
                                            <img src="{{ asset($food->image) }}" alt="{{ $food->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-lg">🍔</span>
                                        @endif
                                    </div>
                                </td>
                                
                                <!-- Name -->
                                <td class="py-4 px-6 font-bold text-slate-900 min-w-[150px]">
                                    {{ $food->name }}
                                </td>

                                <!-- Category Tag -->
                                <td class="py-4 px-6 min-w-[120px]">
                                    <span class="bg-slate-100 text-slate-700 text-xs font-bold px-2.5 py-1 rounded-lg">
                                        {{ $food->category->name }}
                                    </span>
                                </td>

                                <!-- Description -->
                                <td class="py-4 px-6 text-slate-500 text-xs leading-relaxed max-w-[250px] truncate" title="{{ $food->description }}">
                                    {{ $food->description }}
                                </td>

                                <!-- Price -->
                                <td class="py-4 px-6 text-right font-extrabold text-slate-905">
                                    ${{ number_format($food->price, 2) }}
                                </td>

                                <!-- Availability Status -->
                                <td class="py-4 px-6 text-center min-w-[120px]">
                                    @if($food->is_available)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Available
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            Unavailable
                                        </span>
                                    @endif
                                </td>

                                <!-- CRUD actions -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Edit -->
                                        <a href="{{ route('admin.foods.edit', $food->id) }}" 
                                           class="bg-blue-50 hover:bg-blue-100 text-blue-600 p-2.5 rounded-xl transition-all"
                                           title="Edit dish">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M18.364 4.364a9 9 0 00-12.728 0l1.272 1.272m12.728-1.272l-1.272 1.272m0 0L8 14H6v-2l8-8m2 2l-2 2"></path></svg>
                                        </a>

                                        <!-- Delete -->
                                        <form action="{{ route('admin.foods.destroy', $food->id) }}" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete dish \'{{ $food->name }}\'?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 p-2.5 rounded-xl transition-all" title="Delete dish">
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
                <span class="text-4xl">🍔</span>
                <h3 class="text-lg font-bold text-slate-800 mt-4">No food items created yet</h3>
                <p class="text-slate-500 mt-1 text-sm">Add dishes to the menu with description, price, and photos.</p>
                <a href="{{ route('admin.foods.create') }}" class="inline-block bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow mt-4">
                    Create First Menu Item
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
