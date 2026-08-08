<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Food;
use App\Models\Order;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display the home page with hero section and categories.
     */
    public function home()
    {
        $categories = Category::all();
        $featuredFoods = Food::where('is_available', true)
            ->with('category')
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact('categories', 'featuredFoods'));
    }

    /**
     * Display the food menu with search and category filtering.
     */
    public function menu(Request $request)
    {
        $categories = Category::all();
        $query = Food::where('is_available', true)->with('category');

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Search by name
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $foods = $query->latest()->get();
        $selectedCategory = $request->category;

        return view('menu', compact('categories', 'foods', 'selectedCategory'));
    }

    /**
     * Show the order confirmation page.
     */
    public function orderConfirmation($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return view('confirmation', compact('order'));
    }
}
