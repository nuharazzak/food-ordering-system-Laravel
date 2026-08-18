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
     * Handle the checkout form submission.
     *
     * - Validates customer details and payment method choice.
     * - Creates the order and order items inside a DB transaction.
     * - Cash on Delivery: redirects straight to the confirmation page.
     * - PayHere:         redirects to PaymentController::checkout() which
     *                    builds and auto-submits the PayHere sandbox form.
     */
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your shopping cart is empty.');
        }

        // Validate checkout form including the payment method selection
        $request->validate([
            'customer_name'  => 'required|string|max:100',
            'phone_number'   => 'required|string|max:20',
            'address'        => 'required|string|max:500',
            'payment_method' => 'required|in:cash_on_delivery,payhere',
        ]);

        // Calculate total amount server-side — never trust the frontend amount
        $subtotal = 0;
        foreach ($cart as $id => $details) {
            $subtotal += $details['price'] * $details['quantity'];
        }
        $deliveryFee  = 5.00;
        $totalAmount  = $subtotal + $deliveryFee;

        // Generate unique order number (e.g. FH-20260815-ABCDE)
        $orderNumber = 'FH-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        // Use a database transaction to guarantee data integrity
        DB::beginTransaction();

        try {
            // Create the Order record
            $order = Order::create([
                'order_number'   => $orderNumber,
                'user_id'        => auth()->check() ? auth()->id() : null,
                'customer_name'  => $request->customer_name,
                'phone_number'   => $request->phone_number,
                'address'        => $request->address,
                'total_amount'   => $totalAmount,
                'status'         => 'pending',
                // Payment fields — payment_status starts as pending for both methods;
                // for PayHere it will be updated authoritatively via the notify endpoint.
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
            ]);

            // Save Order Items
            foreach ($cart as $foodId => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'food_id'  => $foodId,
                    'name'     => $details['name'],
                    'price'    => $details['price'],
                    'quantity' => $details['quantity'],
                ]);
            }

            // Clear the cart session
            session()->forget('cart');

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong while processing your order. Please try again: ' . $e->getMessage());
        }

        // Route to the appropriate next step based on payment method
        if ($request->payment_method === 'payhere') {
            // Redirect to PaymentController which will build and submit the PayHere form
            return redirect()->route('payment.checkout', $order->id);
        }

        // Cash on Delivery — go straight to the existing confirmation page
        return redirect()->route('order.confirmation', $order->id)
            ->with('success', 'Thank you! Your order has been placed successfully.');
    }
}
