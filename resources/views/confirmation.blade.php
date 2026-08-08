@extends('layouts.app')

@section('title', 'Order Confirmation - Food Hub')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <!-- Success Banner -->
    <div class="text-center space-y-4 mb-12">
        <span class="inline-flex items-center justify-center bg-emerald-100 text-emerald-600 w-16 h-16 rounded-full shadow-inner animate-bounce">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 serif-font">Order Placed Successfully!</h1>
        <p class="text-slate-500 max-w-md mx-auto text-sm">Thank you for ordering from Food Hub! Your order has been successfully saved in our system and sent to the kitchen.</p>
    </div>

    <!-- Receipt Card -->
    <div class="bg-white rounded-3xl border border-slate-150 shadow-sm overflow-hidden">
        <!-- Receipt Header -->
        <div class="p-6 sm:p-8 bg-slate-50 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Order Reference</span>
                <h2 class="text-lg font-extrabold text-slate-950 mt-0.5">{{ $order->order_number }}</h2>
            </div>
            
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block text-left sm:text-right">Status</span>
                <div>
                    @if($order->status === 'pending')
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            ⏳ Pending Approval
                        </span>
                    @elseif($order->status === 'preparing')
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-orange-50 text-orange-700 border border-orange-200">
                            👨‍🍳 Preparing Food
                        </span>
                    @elseif($order->status === 'completed')
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            ✅ Order Completed
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-6 sm:p-8 space-y-8">
            <!-- Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-b border-slate-100 pb-8 text-sm">
                <!-- Delivery info -->
                <div class="space-y-3">
                    <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider text-slate-400">Delivery Information</h3>
                    <div class="space-y-1 text-slate-600">
                        <p class="font-semibold text-slate-800">{{ $order->customer_name }}</p>
                        <p>{{ $order->phone_number }}</p>
                        <p class="leading-relaxed">{{ $order->address }}</p>
                    </div>
                </div>

                <!-- Timing and summary -->
                <div class="space-y-3">
                    <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider text-slate-400">Order Details</h3>
                    <div class="space-y-1 text-slate-600">
                        <p>Date: <span class="font-medium text-slate-800">{{ $order->created_at->format('M d, Y h:i A') }}</span></p>
                        <p>Estimated prep: <span class="font-medium text-slate-800">20-30 minutes</span></p>
                        <p>Payment: <span class="font-medium text-slate-800">Cash on Delivery</span></p>
                    </div>
                </div>
            </div>

            <!-- Items list -->
            <div>
                <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider text-slate-400 mb-4">Ordered Items</h3>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex justify-between items-center text-sm">
                            <div class="flex items-center gap-2">
                                <span class="font-extrabold text-orange-500 w-6">{{ $item->quantity }}x</span>
                                <span class="font-bold text-slate-800">{{ $item->name }}</span>
                            </div>
                            <span class="font-semibold text-slate-600">${{ number_format($item->price * $item->quantity, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Bill totals -->
            <div class="border-t border-slate-100 pt-6 space-y-3 text-sm">
                <div class="flex justify-between text-slate-500">
                    <span>Subtotal</span>
                    <span class="font-semibold text-slate-800">${{ number_format($order->total_amount - 5.00, 2) }}</span>
                </div>
                <div class="flex justify-between text-slate-500">
                    <span>Flat Delivery Fee</span>
                    <span class="font-semibold text-slate-800">$5.00</span>
                </div>
                <div class="border-t border-slate-100 pt-3 flex justify-between text-base font-extrabold text-slate-950">
                    <span>Total Paid (COD)</span>
                    <span class="text-orange-500">${{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Menu button -->
    <div class="text-center mt-10">
        <a href="{{ route('menu') }}" class="inline-block bg-orange-500 hover:bg-orange-600 text-white font-extrabold px-8 py-4 rounded-2xl shadow-lg shadow-orange-500/20 transition-all duration-200 hover:scale-105">
            Order More Food
        </a>
    </div>
</div>
@endsection
