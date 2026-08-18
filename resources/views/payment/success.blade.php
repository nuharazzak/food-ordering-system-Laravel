@extends('layouts.app')

@section('title', 'Payment Result - Food Hub')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    @if($order)

        {{-- ============================================================
             Payment Status Banner
             We read the ACTUAL payment_status from the database.
             We NEVER assume the payment was successful just because
             the customer reached this URL.
        ============================================================ --}}
        @if($order->payment_status === 'paid')
            {{-- SUCCESS --}}
            <div class="text-center space-y-4 mb-10">
                <span class="inline-flex items-center justify-center bg-emerald-100 text-emerald-600 w-16 h-16 rounded-full shadow-inner animate-bounce">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                </span>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 serif-font">Payment Successful!</h1>
                <p class="text-slate-500 max-w-md mx-auto text-sm">Your payment has been confirmed and your order is now being processed by the kitchen.</p>
            </div>

        @elseif($order->payment_status === 'pending')
            {{-- PENDING — notification not yet received from PayHere --}}
            <div class="text-center space-y-4 mb-10">
                <span class="inline-flex items-center justify-center bg-amber-100 text-amber-600 w-16 h-16 rounded-full shadow-inner">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 serif-font">Payment Pending</h1>
                <p class="text-slate-500 max-w-md mx-auto text-sm">
                    Your payment is currently being verified. This page shows your order details — the payment status will update automatically once confirmed.
                </p>
            </div>

        @elseif($order->payment_status === 'failed')
            {{-- FAILED --}}
            <div class="text-center space-y-4 mb-10">
                <span class="inline-flex items-center justify-center bg-red-100 text-red-500 w-16 h-16 rounded-full shadow-inner">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </span>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 serif-font">Payment Failed</h1>
                <p class="text-slate-500 max-w-md mx-auto text-sm">Your payment could not be completed. Please try again or choose Cash on Delivery.</p>
            </div>

        @else
            {{-- CANCELLED or other --}}
            <div class="text-center space-y-4 mb-10">
                <span class="inline-flex items-center justify-center bg-slate-100 text-slate-500 w-16 h-16 rounded-full shadow-inner">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </span>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 serif-font">Payment Not Completed</h1>
                <p class="text-slate-500 max-w-md mx-auto text-sm">Your payment was not completed. Your order has been recorded but no payment has been taken.</p>
            </div>
        @endif

        {{-- Order Receipt Card --}}
        <div class="bg-white rounded-3xl border border-slate-150 shadow-sm overflow-hidden">
            {{-- Card Header --}}
            <div class="p-6 sm:p-8 bg-slate-50 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Order Reference</span>
                    <h2 class="text-lg font-extrabold text-slate-950 mt-0.5">{{ $order->order_number }}</h2>
                </div>

                <div class="flex flex-col items-start sm:items-end gap-2">
                    {{-- Payment Status Badge --}}
                    @if($order->payment_status === 'paid')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Payment: Paid
                        </span>
                    @elseif($order->payment_status === 'pending')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span> Payment: Pending
                        </span>
                    @elseif($order->payment_status === 'failed')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> Payment: Failed
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-slate-50 text-slate-600 border border-slate-200">
                            <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span> Payment: {{ ucfirst($order->payment_status) }}
                        </span>
                    @endif

                    {{-- Order Status Badge --}}
                    @if($order->status === 'pending')
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            ⏳ Order: Pending Approval
                        </span>
                    @elseif($order->status === 'preparing')
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-orange-50 text-orange-700 border border-orange-200">
                            👨‍🍳 Order: Preparing
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            ✅ Order: {{ ucfirst($order->status) }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-6 text-sm">
                {{-- Delivery info --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-b border-slate-100 pb-6">
                    <div class="space-y-2">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Delivery Information</h3>
                        <p class="font-semibold text-slate-800">{{ $order->customer_name }}</p>
                        <p class="text-slate-600">{{ $order->phone_number }}</p>
                        <p class="text-slate-500 leading-relaxed">{{ $order->address }}</p>
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Payment Details</h3>
                        <p class="text-slate-600">Method: <span class="font-medium text-slate-800">
                            {{ $order->payment_method === 'payhere' ? 'Online Payment (PayHere)' : 'Cash on Delivery' }}
                        </span></p>
                        @if($order->payment_reference)
                            <p class="text-slate-600">Transaction ID: <span class="font-medium text-slate-800 font-mono text-xs">{{ $order->payment_reference }}</span></p>
                        @endif
                        <p class="text-slate-600">Date: <span class="font-medium text-slate-800">{{ $order->created_at->format('M d, Y h:i A') }}</span></p>
                    </div>
                </div>

                {{-- Order total --}}
                <div class="flex justify-between text-base font-extrabold text-slate-950 pt-2">
                    <span>Total Amount</span>
                    <span class="text-orange-500">LKR {{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
            @if($order->payment_status === 'failed' || $order->payment_status === 'cancelled')
                <a href="{{ route('payment.checkout', $order->id) }}"
                   style="color: white !important;"
                   class="inline-block bg-orange-500 hover:bg-orange-600 font-bold text-sm px-6 py-3.5 rounded-2xl shadow-lg shadow-orange-500/20 transition-all duration-200 hover:scale-105 text-center">
                    Try Payment Again
                </a>
            @endif
            <a href="{{ route('menu') }}"
               class="inline-block bg-white hover:bg-slate-50 text-slate-700 font-bold text-sm px-6 py-3.5 rounded-2xl border border-slate-200 shadow-sm transition-all duration-200 hover:scale-105 text-center">
                Back to Menu
            </a>
        </div>

    @else
        {{-- Order not found --}}
        <div class="text-center py-20">
            <span class="text-5xl">⚠️</span>
            <h1 class="text-2xl font-bold text-slate-800 mt-4">Order Not Found</h1>
            <p class="text-slate-500 mt-2 text-sm">We could not find your order. Please contact support.</p>
            <a href="{{ route('home') }}" class="inline-block mt-6 bg-orange-500 text-white font-bold px-6 py-3 rounded-2xl shadow transition-all hover:bg-orange-600">
                Back to Home
            </a>
        </div>
    @endif

</div>
@endsection
