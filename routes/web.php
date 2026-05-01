<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\ProductController;
use App\Models\Category;
use App\Models\Favourite;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.index', [
        'categories'       => Category::query()->orderBy('nav_order')->orderBy('id')->get(),
        'featuredProducts' => Product::query()
            ->with('category')
            ->where('is_featured', true)
            ->latest('id')
            ->take(5)
            ->get(),
        'favouriteIds'     => Favourite::getIds(), // id товаров в избранном текущего юзера/гостя
    ]);
})->name('home');

Route::get('/search', [ProductController::class, 'search'])
    ->name('search');

Route::get('/catalog/{category:slug}', [ProductController::class, 'catalog'])
    ->name('catalog.show');

Route::get('/products/{product:slug}', [ProductController::class, 'show'])
    ->name('products.show');

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

// Избранное — доступно всем (и залогиненным и гостям)
Route::get('/favourites', [FavouriteController::class, 'index'])->name('favourites');
Route::post('/favourites/{product}', [FavouriteController::class, 'store'])->name('favourites.store');
Route::delete('/favourites/{product}', [FavouriteController::class, 'destroy'])->name('favourites.destroy');

// Cart routes (accessible to both authenticated and guest users)
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
Route::put('/cart/item/{cartItem}', [CartController::class, 'updateQuantity'])->name('cart.update');
Route::delete('/cart/item/{cartItem}', [CartController::class, 'removeItem'])->name('cart.remove');
