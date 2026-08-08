<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        
        $subtotal = 0;
        foreach ($cart as $id => $details) {
            $subtotal += $details['price'] * $details['quantity'];
        }
        
        // Simple logic for tax/delivery fee if wanted, or just total = subtotal
        $deliveryFee = $subtotal > 0 ? 5.00 : 0.00; // Flat fee
        $total = $subtotal + $deliveryFee;

        return view('cart', compact('cart', 'subtotal', 'deliveryFee', 'total'));
    }

    /**
     * Add food to the shopping cart.
     */
    public function add($id)
    {
        $food = Food::findOrFail($id);
        $cart = session()->get('cart', []);

        // If cart already has the food item, increment quantity
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            // Add new food item to cart
            $cart[$id] = [
                "name" => $food->name,
                "quantity" => 1,
                "price" => $food->price,
                "image" => $food->image,
                "description" => $food->description
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', "{$food->name} added to cart successfully!");
    }

    /**
     * Update quantity of an item in the cart.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Cart updated successfully!');
        }

        return redirect()->back()->with('error', 'Item not found in cart.');
    }

    /**
     * Remove an item from the cart.
     */
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $name = $cart[$id]['name'];
            unset($cart[$id]);
            session()->put('cart', $cart);
            return redirect()->back()->with('success', "{$name} removed from cart.");
        }

        return redirect()->back()->with('error', 'Item not found in cart.');
    }
}
