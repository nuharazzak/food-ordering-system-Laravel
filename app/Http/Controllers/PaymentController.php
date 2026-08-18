<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    // -------------------------------------------------------------------------
    // PayHere sandbox endpoint. Change to live URL in production by setting
    // PAYHERE_SANDBOX=false (the URL is chosen below based on config).
    // -------------------------------------------------------------------------
    private function payhereCheckoutUrl(): string
    {
        return config('services.payhere.sandbox')
            ? 'https://sandbox.payhere.lk/pay/checkout'
            : 'https://www.payhere.lk/pay/checkout';
    }

    // -------------------------------------------------------------------------
    // Generate the PayHere MD5 checksum/hash.
    //
    // Official PayHere formula (case-sensitive):
    //   MD5(
    //     merchant_id +
    //     order_id +
    //     amount (2 decimal places, e.g. "1550.00") +
    //     currency +
    //     strtoupper(MD5(merchant_secret))
    //   )
    //   — then convert the whole result to uppercase.
    //
    // The merchant_secret NEVER leaves the server.
    // -------------------------------------------------------------------------
    private function generateHash(string $merchantId, string $orderId, string $amount, string $currency): string
    {
        $merchantSecret    = config('services.payhere.merchant_secret');
        $hashedSecret      = strtoupper(md5($merchantSecret));
        $rawString         = $merchantId . $orderId . $amount . $currency . $hashedSecret;

        return strtoupper(md5($rawString));
    }

    // =========================================================================
    // checkout()
    //
    // The customer is redirected here from OrderController after choosing
    // "Pay Online". This method:
    //   1. Loads and validates the order.
    //   2. Builds the PayHere payment request.
    //   3. Renders a self-submitting form to the PayHere sandbox endpoint.
    // =========================================================================
    public function checkout(Order $order)
    {
        // Guard: only process orders that are still awaiting PayHere payment
        if ($order->payment_method !== 'payhere' || $order->payment_status === 'paid') {
            return redirect()->route('order.confirmation', $order->id)
                ->with('info', 'This order has already been processed.');
        }

        $merchantId = config('services.payhere.merchant_id');

        // Amount must be formatted to exactly 2 decimal places per PayHere spec
        $amount   = number_format((float) $order->total_amount, 2, '.', '');
        $currency = 'LKR';

        // Split customer name into first/last for the PayHere form fields
        $nameParts = explode(' ', trim($order->customer_name), 2);
        $firstName = $nameParts[0] ?? $order->customer_name;
        $lastName  = $nameParts[1] ?? '';

        // Generate the hash for the payment request
        $hash = $this->generateHash($merchantId, $order->order_number, $amount, $currency);

        // Build the complete PayHere payment data array
        $paymentData = [
            'merchant_id'  => $merchantId,
            'return_url'   => route('payment.success') . '?order_id=' . $order->id,
            'cancel_url'   => route('payment.cancel')  . '?order_id=' . $order->id,
            'notify_url'   => route('payment.notify'),
            'order_id'     => $order->order_number,     // Use human-readable order number
            'items'        => 'FoodHub Order #' . $order->order_number,
            'currency'     => $currency,
            'amount'       => $amount,
            'first_name'   => $firstName,
            'last_name'    => $lastName,
            'email'        => 'customer@foodhub.lk',    // PayHere requires email; customers don't have accounts
            'phone'        => $order->phone_number,
            'address'      => $order->address,
            'city'         => 'Colombo',                 // Default city for demo
            'country'      => 'Sri Lanka',
            'hash'         => $hash,
        ];

        return view('payment.checkout', [
            'order'       => $order,
            'paymentData' => $paymentData,
            'checkoutUrl' => $this->payhereCheckoutUrl(),
        ]);
    }

    // =========================================================================
    // notify()
    //
    // This is the server-to-server notification endpoint called by PayHere.
    // The browser is NOT involved — this is called directly from PayHere servers.
    //
    // SECURITY CRITICAL:
    //   - Verifies the PayHere MD5 checksum.
    //   - Verifies the payment amount matches the order.
    //   - Verifies the currency.
    //   - Only then updates the order payment_status.
    //   - Idempotent: safe to receive duplicate notifications.
    //
    // PayHere status_code values:
    //   2  = Success
    //   0  = Pending
    //  -1  = Cancelled
    //  -2  = Failed
    //  -3  = Chargedback (treat as failed)
    // =========================================================================
    public function notify(Request $request)
    {
        Log::info('PayHere notification received', $request->all());

        // --- Step 1: Locate the order by order_id (our order_number) ----------
        $orderNumber = $request->input('order_id');
        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            Log::warning('PayHere notify: Order not found', ['order_number' => $orderNumber]);
            return response('Order not found', 404);
        }

        // --- Step 2: Verify the PayHere checksum/hash -------------------------
        //
        // PayHere sends a hash in the notification. We re-compute it using
        // the same formula:
        //   MD5(merchant_id + order_id + amount + currency + strtoupper(MD5(merchant_secret)))
        //
        $hashedSecret  = strtoupper(md5(config('services.payhere.merchant_secret')));
        $rawHashString = $request->input('merchant_id')
            . $orderNumber
            . $request->input('payhere_amount')
            . $request->input('payhere_currency')
            . $request->input('status_code')
            . $hashedSecret;

        $expectedHash = strtoupper(md5($rawHashString));
        $receivedHash = strtoupper($request->input('md5sig', ''));

        if ($expectedHash !== $receivedHash) {
            Log::warning('PayHere notify: Checksum MISMATCH — possible tampered request', [
                'order_number'  => $orderNumber,
                'expected_hash' => $expectedHash,
                'received_hash' => $receivedHash,
            ]);
            // Return 200 so PayHere stops retrying, but do NOT update the order
            return response('Checksum mismatch', 200);
        }

        // --- Step 3: Verify the payment amount matches our order --------------
        $expectedAmount  = number_format((float) $order->total_amount, 2, '.', '');
        $receivedAmount  = $request->input('payhere_amount');
        $receivedCurrency = $request->input('payhere_currency');

        if ($receivedAmount !== $expectedAmount || $receivedCurrency !== 'LKR') {
            Log::warning('PayHere notify: Amount or currency mismatch', [
                'order_number'      => $orderNumber,
                'expected_amount'   => $expectedAmount,
                'received_amount'   => $receivedAmount,
                'received_currency' => $receivedCurrency,
            ]);
            return response('Amount/currency mismatch', 200);
        }

        // --- Step 4: Map PayHere status code to our payment_status -----------
        $statusCode    = (int) $request->input('status_code');
        $paymentStatus = match ($statusCode) {
            2       => 'paid',
            0       => 'pending',
            -1      => 'cancelled',
            default => 'failed',     // -2 failed, -3 chargedback
        };

        // --- Step 5: Update the order (idempotent — skip if already paid) ----
        if ($order->payment_status === 'paid' && $paymentStatus === 'paid') {
            Log::info('PayHere notify: Duplicate success notification — ignoring', ['order_number' => $orderNumber]);
            return response('OK', 200);
        }

        $order->update([
            'payment_status'    => $paymentStatus,
            'payment_reference' => $request->input('payment_id'), // PayHere's transaction ID
        ]);

        Log::info('PayHere notify: Order payment status updated', [
            'order_number'   => $orderNumber,
            'payment_status' => $paymentStatus,
            'payment_id'     => $request->input('payment_id'),
        ]);

        // PayHere expects a 200 OK response
        return response('OK', 200);
    }

    // =========================================================================
    // success()
    //
    // Customer return URL — called when the customer returns from PayHere.
    // IMPORTANT: Do NOT mark payment as paid here. Payment status is set
    // authoritatively only by notify(). This page just reads the DB status.
    // =========================================================================
    public function success(Request $request)
    {
        $orderId = $request->input('order_id');
        $order   = Order::find($orderId);

        if (!$order) {
            return redirect()->route('home')->with('error', 'Order not found.');
        }

        return view('payment.success', compact('order'));
    }

    // =========================================================================
    // cancel()
    //
    // Customer cancel URL — called when the customer cancels at PayHere.
    // Updates payment_status to 'cancelled' if it hasn't been updated already.
    // =========================================================================
    public function cancel(Request $request)
    {
        $orderId = $request->input('order_id');
        $order   = Order::find($orderId);

        if ($order && $order->payment_status === 'pending') {
            $order->update(['payment_status' => 'cancelled']);
        }

        return view('payment.cancel', compact('order'));
    }
}
