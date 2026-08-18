@extends('layouts.app')

@section('title', 'Payment Cancelled - Food Hub')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    {{-- Cancel Banner --}}
    <div class="text-center space-y-4 mb-10">
        <span class="inline-flex items-center justify-center bg-slate-100 text-slate-500 w-16 h-16 rounded-full shadow-inner">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 serif-font">Payment Cancelled</h1>
        <p class="text-slate-500 max-w-md mx-auto text-sm">
            You cancelled the payment at PayHere. No money has been charged. Your order has been saved — you can try paying again or switch to Cash on Delivery.
        </p>
    </div>

    @if($order)
        {{-- Order Reference Card --}}
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-4">
            <div class="flex justify-between items-center">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Order Reference</span>
                    <h2 class="text-lg font-extrabold text-slate-950 mt-0.5">{{ $order->order_number }}</h2>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-slate-50 text-slate-600 border border-slate-200">
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span> Payment Cancelled
                </span>
            </div>

            <div class="border-t border-slate-100 pt-4 flex justify-between text-sm">
                <span class="text-slate-500">Order Total</span>
                <span class="font-extrabold text-slate-900">LKR {{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>
    @endif

    {{-- Action Buttons --}}
    <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
        @if($order)
            {{-- Retry payment with the same order --}}
            <a href="{{ route('payment.checkout', $order->id) }}"
               style="color: white !important;"
               class="inline-block bg-orange-500 hover:bg-orange-600 font-bold text-sm px-6 py-3.5 rounded-2xl shadow-lg shadow-orange-500/20 transition-all duration-200 hover:scale-105 text-center">
                Try Payment Again
            </a>
        @endif

        {{-- Back to cart to restart with Cash on Delivery --}}
        <a href="{{ route('cart.index') }}"
           class="inline-block bg-white hover:bg-slate-50 text-slate-700 font-bold text-sm px-6 py-3.5 rounded-2xl border border-slate-200 shadow-sm transition-all duration-200 hover:scale-105 text-center">
            Choose Cash on Delivery
        </a>

        <a href="{{ route('home') }}"
           class="inline-block bg-white hover:bg-slate-50 text-slate-700 font-bold text-sm px-6 py-3.5 rounded-2xl border border-slate-200 shadow-sm transition-all duration-200 hover:scale-105 text-center">
            Back to Home
        </a>
    </div>

</div>
@endsection
