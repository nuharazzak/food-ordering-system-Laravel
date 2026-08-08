<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Handle the checkout and save order to database.
     */
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your shopping cart is empty.');
        }

        // Validate checkout form
        $request->validate([
            'customer_name' => 'required|string|max:100',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        // Calculate total amount
        $subtotal = 0;
        foreach ($cart as $id => $details) {
            $subtotal += $details['price'] * $details['quantity'];
        }
        $deliveryFee = 5.00;
        $totalAmount = $subtotal + $deliveryFee;

        // Generate unique order number (e.g. FH-20260807-ABCDE)
        $orderNumber = 'FH-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        // Use database transaction to guarantee data integrity
        DB::beginTransaction();

        try {
            // Create the Order
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => auth()->check() ? auth()->id() : null,
                'customer_name' => $request->customer_name,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'total_amount' => $totalAmount,
                'status' => 'pending',
            ]);

            // Save Order Items
            foreach ($cart as $foodId => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'food_id' => $foodId,
                    'name' => $details['name'],
                    'price' => $details['price'],
                    'quantity' => $details['quantity'],
                ]);
            }

            // Clear Cart Session
            session()->forget('cart');

            DB::commit();

            return redirect()->route('order.confirmation', $order->id)
                ->with('success', 'Thank you! Your order has been placed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong while processing your order. Please try again: ' . $e->getMessage());
        }
    }
}
