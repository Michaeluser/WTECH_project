<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.index', [
        'categories' => Category::query()->orderBy('nav_order')->orderBy('id')->get(),
        'featuredProducts' => Product::query()
            ->with('category')
            ->where('is_featured', true)
            ->latest('id')
            ->take(5)
            ->get(),
    ]);
})->name('home');

Route::get('/catalog/{category:slug}', [ProductController::class, 'catalog'])
    ->name('catalog.show');

Route::get('/categories/{category:slug}', [ProductController::class, 'indexByCategory'])
    ->name('categories.show');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/account', function () {
        return view('account.account');
    })->name('account');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
