@extends('layouts.admin')

@section('title', 'Admin Dashboard - Food Hub')

@section('content')
<div class="space-y-8">
    <!-- Page Title -->
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Orders Manager</h1>
        <p class="text-sm text-slate-500 mt-1">Monitor sales performance and process kitchen orders.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Completed Revenue -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/60 shadow-sm flex items-center gap-4">
            <span class="bg-emerald-100 text-emerald-600 p-4 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </span>
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Completed Sales</span>
                <h3 class="text-xl font-black text-slate-950 mt-0.5">${{ number_format($completedRevenue, 2) }}</h3>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/60 shadow-sm flex items-center gap-4">
            <span class="bg-indigo-100 text-indigo-600 p-4 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </span>
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Orders</span>
                <h3 class="text-xl font-black text-slate-950 mt-0.5">{{ $totalOrdersCount }}</h3>
            </div>
        </div>

        <!-- Pending / Kitchen Wait -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/60 shadow-sm flex items-center gap-4">
            <span class="bg-amber-100 text-amber-600 p-4 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </span>
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pending Approval</span>
                <h3 class="text-xl font-black text-slate-950 mt-0.5">{{ $pendingCount }}</h3>
            </div>
        </div>

        <!-- Preparing/Cooking -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/60 shadow-sm flex items-center gap-4">
            <span class="bg-orange-100 text-orange-600 p-4 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.172V5L8 4z"></path></svg>
            </span>
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">In Kitchen</span>
                <h3 class="text-xl font-black text-slate-950 mt-0.5">{{ $preparingCount }}</h3>
            </div>
        </div>
    </div>

    <!-- Filters and Order List -->
    <div class="bg-white rounded-3xl border border-slate-200/60 overflow-hidden shadow-sm">
        <!-- List Header & Status Tabs -->
        <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-slate-50/50">
            <h2 class="text-lg font-bold text-slate-900">Customer Orders</h2>
            
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0">
                <a href="{{ route('admin.dashboard') }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap {{ !$statusFilter ? 'bg-slate-800 text-white shadow' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                    All Orders
                </a>
                <a href="{{ route('admin.dashboard', ['status' => 'pending']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap {{ $statusFilter === 'pending' ? 'bg-amber-500 text-white shadow' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                    ⏳ Pending ({{ $pendingCount }})
                </a>
                <a href="{{ route('admin.dashboard', ['status' => 'preparing']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap {{ $statusFilter === 'preparing' ? 'bg-orange-500 text-white shadow' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                    👨‍🍳 Preparing ({{ $preparingCount }})
                </a>
                <a href="{{ route('admin.dashboard', ['status' => 'completed']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap {{ $statusFilter === 'completed' ? 'bg-emerald-500 text-white shadow' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                    ✅ Completed ({{ $completedCount }})
                </a>
            </div>
        </div>

        <!-- Orders Table -->
        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-400">
                            <th class="py-4 px-6">Order Info</th>
                            <th class="py-4 px-6">Customer & Address</th>
                            <th class="py-4 px-6">Order Items</th>
                            <th class="py-4 px-6 text-right">Amount</th>
                            <th class="py-4 px-6 text-center">Status Update</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach($orders as $order)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <!-- Order Info -->
                                <td class="py-5 px-6 space-y-1 vertical-align-top min-w-[150px]">
                                    <p class="font-extrabold text-slate-900">{{ $order->order_number }}</p>
                                    <p class="text-xs text-slate-400">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                                </td>

                                <!-- Customer Details -->
                                <td class="py-5 px-6 space-y-1 min-w-[220px]">
                                    <p class="font-bold text-slate-800">{{ $order->customer_name }}</p>
                                    <p class="text-xs text-slate-500">{{ $order->phone_number }}</p>
                                    <p class="text-xs text-slate-400 line-clamp-2 max-w-[200px] leading-relaxed">{{ $order->address }}</p>
                                </td>

                                <!-- Order Items List -->
                                <td class="py-5 px-6 min-w-[220px]">
                                    <ul class="space-y-1 text-xs">
                                        @foreach($order->items as $item)
                                            <li class="flex items-center gap-1.5">
                                                <span class="font-extrabold text-orange-500">{{ $item->quantity }}x</span>
                                                <span class="text-slate-600">{{ $item->name }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>

                                <!-- Total amount -->
                                <td class="py-5 px-6 text-right font-extrabold text-slate-900 min-w-[100px]">
                                    ${{ number_format($order->total_amount, 2) }}
                                </td>

                                <!-- Status Modifier Dropdown -->
                                <td class="py-5 px-6 text-center min-w-[160px]">
                                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="inline-block w-full">
                                        @csrf
                                        <select name="status" onchange="this.form.submit()" 
                                                class="w-full text-xs font-bold rounded-xl px-3 py-2 bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 cursor-pointer
                                                @if($order->status === 'pending') text-amber-600 border-amber-200 bg-amber-50/50 @elseif($order->status === 'preparing') text-orange-600 border-orange-200 bg-orange-50/50 @elseif($order->status === 'completed') text-emerald-600 border-emerald-200 bg-emerald-50/50 @endif">
                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                            <option value="preparing" {{ $order->status === 'preparing' ? 'selected' : '' }}>👨‍🍳 Preparing</option>
                                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>✅ Completed</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="p-6 border-t border-slate-100 bg-slate-50/50">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <!-- Empty state -->
            <div class="text-center py-20 bg-slate-50/50">
                <span class="text-4xl">📭</span>
                <h3 class="text-lg font-bold text-slate-800 mt-4">No orders found</h3>
                <p class="text-slate-500 mt-1 text-sm">There are no orders registered under this filter.</p>
            </div>
        @endif
    </div>
</div>
@endsection
