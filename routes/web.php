<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FoodController;

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [CustomerController::class, 'home'])->name('home');
Route::get('/menu', [CustomerController::class, 'menu'])->name('menu');
Route::get('/order-confirmation/{id}', [CustomerController::class, 'orderConfirmation'])->name('order.confirmation');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// Checkout Process Route
Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout.process');

/*
|--------------------------------------------------------------------------
| Admin Auth & Panel Routes
|--------------------------------------------------------------------------
*/

// Admin Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'login'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'loginSubmit'])->name('admin.login.submit');
});

// Admin Authenticated Routes
Route::middleware('admin')->group(function () {
    // Logout
    Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');
    Route::get('/admin/logout', [AuthController::class, 'logout']); // Fallback GET

    // Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/orders/{order}/status', [DashboardController::class, 'updateStatus'])->name('admin.orders.update-status');

    // Resources CRUD
    Route::resource('/admin/categories', CategoryController::class)->names([
        'index' => 'admin.categories.index',
        'create' => 'admin.categories.create',
        'store' => 'admin.categories.store',
        'edit' => 'admin.categories.edit',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy',
    ]);
    Route::resource('/admin/foods', FoodController::class)->names([
        'index' => 'admin.foods.index',
        'create' => 'admin.foods.create',
        'store' => 'admin.foods.store',
        'edit' => 'admin.foods.edit',
        'update' => 'admin.foods.update',
        'destroy' => 'admin.foods.destroy',
    ]);
});
