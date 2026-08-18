@extends('layouts.app')

@section('title', 'Redirecting to PayHere - Food Hub')

@section('content')
<div class="min-h-screen bg-stone-50 flex items-center justify-center px-4 py-16">
    <div class="max-w-md w-full text-center space-y-8">

        {{-- PayHere Redirect Card --}}
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-10 space-y-6">

            {{-- Spinner --}}
            <div class="flex justify-center">
                <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-orange-50 border-4 border-orange-500 border-t-transparent animate-spin"></span>
            </div>

            <div class="space-y-2">
                <h1 class="text-2xl font-extrabold text-slate-900 serif-font">Redirecting to PayHere...</h1>
                <p class="text-sm text-slate-500">You are being securely redirected to the PayHere payment page. Please do not close this window.</p>
            </div>

            {{-- Order Summary --}}
            <div class="bg-slate-50 rounded-2xl p-4 text-sm text-left border border-slate-100">
                <div class="flex justify-between text-slate-600 mb-1">
                    <span>Order</span>
                    <span class="font-bold text-slate-900">{{ $order->order_number }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Amount</span>
                    <span class="font-extrabold text-orange-500">LKR {{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>

            {{-- Security note --}}
            <p class="text-xs text-slate-400 flex items-center justify-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Secure payment powered by PayHere · Sandbox Mode
            </p>

            {{-- Manual submit fallback --}}
            <p class="text-xs text-slate-400">
                Not redirected automatically?
                <button type="button" onclick="document.getElementById('payhere-form').submit()"
                    class="text-orange-500 hover:text-orange-600 font-semibold underline cursor-pointer">
                    Click here
                </button>
            </p>
        </div>
    </div>
</div>

{{--
    Auto-submitting hidden form that POSTs all required PayHere fields.
    The form submits itself automatically via JavaScript on page load.
    All values are generated server-side — the merchant_secret is NEVER
    included here, only the pre-computed hash.
--}}
<form id="payhere-form" action="{{ $checkoutUrl }}" method="POST" style="display:none;">
    @foreach($paymentData as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach
</form>

<script>
    // Auto-submit the PayHere form 1.5 seconds after page load
    // to give the customer a moment to see the redirect message.
    window.addEventListener('DOMContentLoaded', function () {
        setTimeout(function () {
            document.getElementById('payhere-form').submit();
        }, 1500);
    });
</script>
@endsection
