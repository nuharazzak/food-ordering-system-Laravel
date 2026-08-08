<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $foods = Food::with('category')->latest()->get();
        return view('admin.foods.index', compact('foods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.foods.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:100|unique:foods,name',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0.01',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_available' => 'nullable|boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '-' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/foods'), $imageName);
            $imagePath = 'images/foods/' . $imageName;
        }

        Food::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
            'is_available' => $request->has('is_available') ? (bool)$request->is_available : true,
        ]);

        return redirect()->route('admin.foods.index')->with('success', 'Food item created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Food $food)
    {
        $categories = Category::all();
        return view('admin.foods.edit', compact('food', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Food $food)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:100|unique:foods,name,' . $food->id,
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0.01',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_available' => 'nullable|boolean',
        ]);

        $imagePath = $food->image;
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($imagePath && File::exists(public_path($imagePath))) {
                File::delete(public_path($imagePath));
            }

            $image = $request->file('image');
            $imageName = time() . '-' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/foods'), $imageName);
            $imagePath = 'images/foods/' . $imageName;
        }

        $food->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
            'is_available' => $request->has('is_available') ? (bool)$request->is_available : true,
        ]);

        return redirect()->route('admin.foods.index')->with('success', 'Food item updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Food $food)
    {
        // Delete image file if exists
        if ($food->image && File::exists(public_path($food->image))) {
            File::delete(public_path($food->image));
        }

        $food->delete();

        return redirect()->route('admin.foods.index')->with('success', 'Food item deleted successfully!');
    }
}
