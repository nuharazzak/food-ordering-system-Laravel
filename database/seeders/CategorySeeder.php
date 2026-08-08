<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Pizza', 'image' => 'images/categories/pizza.jpg'],
            ['name' => 'Burgers', 'image' => 'images/categories/burgers.jpg'],
            ['name' => 'Drinks', 'image' => 'images/categories/drinks.jpg'],
            ['name' => 'Desserts', 'image' => 'images/categories/desserts.jpg'],
            ['name' => 'Pasta', 'image' => 'images/categories/pasta.jpg'],
        ];

        foreach ($categories as $cat) {
            Category::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'image' => $cat['image'],
            ]);
        }
    }
}
