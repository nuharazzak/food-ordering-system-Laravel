<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@foodhub.com',
            'password' => Hash::make('admin12345'),
            'is_admin' => true,
        ]);

        // Create a test Customer User
        User::create([
            'name' => 'John Doe',
            'email' => 'customer@foodhub.com',
            'password' => Hash::make('customer123'),
            'is_admin' => false,
        ]);
    }
}
