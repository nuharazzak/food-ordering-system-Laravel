@extends('layouts.app')

@section('title', 'Food Hub - Shopping Cart')

@section('content')
<section class="py-12 bg-stone-50 border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 serif-font text-center">Your Shopping Cart</h1>
        <p class="text-slate-500 text-center mt-2 text-sm sm:text-base">Review your selected items and fill out the delivery details to complete your order.</p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if(count($cart) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Cart Items List (8 Columns) -->
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-white rounded-3xl border border-slate-150 overflow-hidden shadow-sm">
                    <div class="p-6 sm:p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h2 class="text-lg font-bold text-slate-900">Cart Items</h2>
                        <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Scroll right on mobile</span>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-400">
                                    <th class="py-4 px-6 sm:px-8">Item</th>
                                    <th class="py-4 px-4 text-center">Quantity</th>
                                    <th class="py-4 px-4 text-right">Price</th>
                                    <th class="py-4 px-4 text-right">Subtotal</th>
                                    <th class="py-4 px-6 sm:px-8 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @foreach($cart as $id => $details)
                                    <tr class="hover:bg-slate-50/30 transition-colors">
                                        <!-- Item image and details -->
                                        <td class="py-6 px-6 sm:px-8 flex items-center gap-4 min-w-[280px]">
                                            <div class="w-16 h-16 rounded-2xl overflow-hidden bg-slate-100 flex-shrink-0">
                                                <img src="{{ asset($details['image']) }}" alt="{{ $details['name'] }}" class="w-full h-full object-cover">
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-slate-800 leading-tight">{{ $details['name'] }}</h3>
                                                <p class="text-xs text-slate-400 mt-1 line-clamp-1 max-w-[200px]">{{ $details['description'] }}</p>
                                            </div>
                                        </td>
                                        
                                        <!-- Quantity update form -->
                                        <td class="py-6 px-4 text-center">
                                            <form action="{{ route('cart.update', $id) }}" method="POST" class="inline-flex items-center bg-slate-50 border border-slate-200 rounded-xl p-1">
                                                @csrf
                                                <!-- We use a number input for simple cross-platform standard editing -->
                                                <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" max="99" 
                                                       class="w-12 bg-transparent text-center text-sm font-bold focus:outline-none"
                                                       onchange="this.form.submit()">
                                                <button type="submit" class="text-xs text-orange-500 hover:text-orange-600 font-bold px-1.5" title="Update">
                                                    ✓
                                                </button>
                                            </form>
                                        </td>
                                        
                                        <!-- Price -->
                                        <td class="py-6 px-4 text-right font-semibold text-slate-600">
                                            ${{ number_format($details['price'], 2) }}
                                        </td>
                                        
                                        <!-- Subtotal -->
                                        <td class="py-6 px-4 text-right font-extrabold text-slate-900">
                                            ${{ number_format($details['price'] * $details['quantity'], 2) }}
                                        </td>
                                        
                                        <!-- Delete action -->
                                        <td class="py-6 px-6 sm:px-8 text-center">
                                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-xl transition-all duration-200" title="Remove item">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Order Total & Checkout Details (4 Columns) -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Receipt Summary -->
                <div class="bg-white rounded-3xl border border-slate-150 p-6 sm:p-8 shadow-sm space-y-4">
                    <h2 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4">Bill Details</h2>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-slate-500">
                            <span>Subtotal</span>
                            <span class="font-semibold text-slate-800">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-500">
                            <span>Flat Delivery Fee</span>
                            <span class="font-semibold text-slate-800">${{ number_format($deliveryFee, 2) }}</span>
                        </div>
                        <div class="border-t border-slate-100 pt-3 flex justify-between text-base font-extrabold text-slate-950">
                            <span>Total Price</span>
                            <span class="text-orange-500">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Checkout Billing Form -->
                <div class="bg-white rounded-3xl border border-slate-150 p-6 sm:p-8 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4 mb-6">Delivery Details</h2>
                    
                    <form action="{{ route('checkout.process') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <!-- Customer Name -->
                        <div>
                            <label for="customer_name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Customer Name</label>
                            <input type="text" name="customer_name" id="customer_name" required value="{{ old('customer_name') }}"
                                   class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 text-sm transition-all"
                                   placeholder="Enter your full name">
                            @error('customer_name')
                                <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label for="phone_number" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Phone Number</label>
                            <input type="tel" name="phone_number" id="phone_number" required value="{{ old('phone_number') }}"
                                   class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 text-sm transition-all"
                                   placeholder="Enter your phone number">
                            @error('phone_number')
                                <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div>
                            <label for="address" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Delivery Address</label>
                            <textarea name="address" id="address" rows="3" required
                                      class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 text-sm transition-all resize-none"
                                      placeholder="Enter complete delivery street address, apartment, city"></textarea>
                            @error('address')
                                <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Payment Method --}}
                        <div class="pt-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Payment Method</label>

                            <div class="space-y-2">
                                {{-- Cash on Delivery option --}}
                                <label for="payment_cod" class="flex items-center gap-3 p-4 rounded-2xl border border-slate-200 bg-slate-50 cursor-pointer transition-all duration-200 hover:border-orange-300 hover:bg-orange-50/30 peer-checked:border-orange-500 has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/40">
                                    <input type="radio" id="payment_cod" name="payment_method" value="cash_on_delivery" checked
                                           class="w-4 h-4 accent-orange-500 flex-shrink-0">
                                    <div class="flex items-center gap-3">
                                        <span class="text-xl">💵</span>
                                        <div>
                                            <p class="text-sm font-bold text-slate-800">Cash on Delivery</p>
                                            <p class="text-xs text-slate-400 mt-0.5">Pay in cash when your order arrives</p>
                                        </div>
                                    </div>
                                </label>

                                {{-- Pay Online (PayHere) option --}}
                                <label for="payment_payhere" class="flex items-center gap-3 p-4 rounded-2xl border border-slate-200 bg-slate-50 cursor-pointer transition-all duration-200 hover:border-orange-300 hover:bg-orange-50/30 has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/40">
                                    <input type="radio" id="payment_payhere" name="payment_method" value="payhere"
                                           class="w-4 h-4 accent-orange-500 flex-shrink-0">
                                    <div class="flex items-center gap-3">
                                        <span class="text-xl">💳</span>
                                        <div>
                                            <p class="text-sm font-bold text-slate-800">Pay Online</p>
                                            <p class="text-xs text-slate-400 mt-0.5">Secure payment powered by PayHere</p>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            @error('payment_method')
                                <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Order Total Display --}}
                        <div class="flex justify-between items-center py-3 border-t border-slate-100 mt-2">
                            <span class="text-sm font-bold text-slate-600">Order Total</span>
                            <span class="text-base font-extrabold text-orange-500">Rs. {{ number_format($total, 2) }}</span>
                        </div>

                        {{-- Submit Order Button --}}
                        <button type="submit"
                            style="color: white !important;"
                            class="w-full mt-2
                                bg-orange-500 hover:bg-orange-600
                                font-bold text-lg
                                py-4 px-6
                                rounded-2xl
                                shadow-lg shadow-orange-500/30
                                hover:shadow-xl
                                hover:-translate-y-0.5
                                active:translate-y-0
                                transition-all duration-300">
                            Confirm &amp; Place Order
                        </button>
                    </form>
                </div>
            </div>
            
        </div>
    @else
        <!-- Empty Cart State -->
        <div class="text-center py-20 bg-slate-50 rounded-3xl border border-dashed border-slate-200 max-w-xl mx-auto shadow-sm">
            <span class="text-5xl">🛒</span>
            <h3 class="text-lg font-bold text-slate-800 mt-4">Your cart is empty</h3>
            <p class="text-slate-500 mt-2 text-sm max-w-xs mx-auto">Looks like you haven't added any of our delicious meals to your cart yet. Go to our menu to get started!</p>
            <a href="{{ route('menu') }}"
    style="color: white !important;"
    class="inline-block mt-6
           bg-orange-500 hover:bg-orange-600
           font-bold text-sm
           px-6 py-3.5
           rounded-2xl
           shadow-lg shadow-orange-500/30
           hover:shadow-xl
           hover:-translate-y-0.5
           active:translate-y-0
           transition-all duration-300">
    Go To Food Menu
</a>
        </div>
    @endif
</div>
@endsection
