<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Food;
use Illuminate\Support\Str;

class FoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all()->keyBy('slug');

        $foodItems = [
            // Pizza
            [
                'category_slug' => 'pizza',
                'name' => 'Margherita Pizza',
                'description' => 'Classic tomatoes, fresh mozzarella cheese, fresh basil leaves, and extra virgin olive oil on a thin crust.',
                'price' => 12.99,
                'image' => 'images/foods/margherita-pizza.jpg',
            ],
            [
                'category_slug' => 'pizza',
                'name' => 'Pepperoni Pizza',
                'description' => 'Spicy pepperoni slices, melted mozzarella cheese, and rich tomato sauce baked to perfection.',
                'price' => 14.99,
                'image' => 'images/foods/pepperoni-pizza.jpg',
            ],
            [
                'category_slug' => 'pizza',
                'name' => 'BBQ Chicken Pizza',
                'description' => 'Tender grilled chicken, smoky BBQ sauce, red onions, and fresh cilantro with mozzarella cheese.',
                'price' => 15.99,
                'image' => 'images/foods/bbq-chicken-pizza.jpg',
            ],

            // Burgers
            [
                'category_slug' => 'burgers',
                'name' => 'Classic Cheeseburger',
                'description' => 'Juicy flame-grilled beef patty, cheddar cheese, lettuce, tomato, pickles, and our signature house sauce.',
                'price' => 9.99,
                'image' => 'images/foods/classic-cheeseburger.jpg',
            ],
            [
                'category_slug' => 'burgers',
                'name' => 'Bacon Double Burger',
                'description' => 'Two flame-grilled beef patties, crispy smoked bacon, melted cheddar, caramelized onions, and BBQ sauce.',
                'price' => 13.99,
                'image' => 'images/foods/bacon-double-burger.jpg',
            ],
            [
                'category_slug' => 'burgers',
                'name' => 'Spicy Veggie Burger',
                'description' => 'Spicy black bean and quinoa patty, sliced avocado, butter lettuce, tomato, and chipotle mayo.',
                'price' => 10.99,
                'image' => 'images/foods/spicy-veggie-burger.jpg',
            ],

            // Drinks
            [
                'category_slug' => 'drinks',
                'name' => 'Craft Cola',
                'description' => 'Artisanal organic recipe craft cola served with fresh lime and ice.',
                'price' => 2.99,
                'image' => 'images/foods/craft-cola.jpg',
            ],
            [
                'category_slug' => 'drinks',
                'name' => 'Fresh Lemonade',
                'description' => 'Freshly squeezed organic lemons, aromatic mint leaves, and pure cane sugar.',
                'price' => 3.49,
                'image' => 'images/foods/fresh-lemonade.jpg',
            ],
            [
                'category_slug' => 'drinks',
                'name' => 'Iced Matcha Latte',
                'description' => 'Premium Japanese green tea matcha whisked with cold milk and a touch of honey.',
                'price' => 4.99,
                'image' => 'images/foods/iced-matcha-latte.jpg',
            ],

            // Desserts
            [
                'category_slug' => 'desserts',
                'name' => 'Chocolate Lava Cake',
                'description' => 'Warm, decadent chocolate cake with a liquid chocolate core, served with vanilla bean ice cream.',
                'price' => 6.99,
                'image' => 'images/foods/chocolate-lava-cake.jpg',
            ],
            [
                'category_slug' => 'desserts',
                'name' => 'Classic Tiramisu',
                'description' => 'Espresso-soaked ladyfingers layered with whipped mascarpone cheese cream and rich cocoa powder.',
                'price' => 7.99,
                'image' => 'images/foods/classic-tiramisu.jpg',
            ],
            [
                'category_slug' => 'desserts',
                'name' => 'New York Cheesecake',
                'description' => 'Rich and creamy classic cheesecake on a graham cracker crust, topped with fresh strawberry compote.',
                'price' => 6.49,
                'image' => 'images/foods/new-york-cheesecake.jpg',
            ],

            // Pasta
            [
                'category_slug' => 'pasta',
                'name' => 'Fettuccine Alfredo',
                'description' => 'Creamy aged parmesan alfredo sauce tossed with fettuccine pasta and sliced grilled chicken breast.',
                'price' => 14.49,
                'image' => 'images/foods/fettuccine-alfredo.jpg',
            ],
            [
                'category_slug' => 'pasta',
                'name' => 'Spaghetti Bolognese',
                'description' => 'Slow-simmered beef, pork, and herb ragu sauce over spaghetti, topped with shaved parmesan cheese.',
                'price' => 13.99,
                'image' => 'images/foods/spaghetti-bolognese.jpg',
            ],
            [
                'category_slug' => 'pasta',
                'name' => 'Pesto Penne Pasta',
                'description' => 'Penne pasta tossed in house-made sweet basil pesto, cherry tomatoes, and toasted pine nuts.',
                'price' => 12.49,
                'image' => 'images/foods/pesto-penne-pasta.jpg',
            ],
        ];

        foreach ($foodItems as $item) {
            $category = $categories[$item['category_slug']] ?? null;
            if ($category) {
                Food::create([
                    'category_id' => $category->id,
                    'name' => $item['name'],
                    'slug' => Str::slug($item['name']),
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'image' => $item['image'],
                    'is_available' => true,
                ]);
            }
        }
    }
}
