<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Favourite;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FavouriteController extends Controller
{
    private function identifier(): array
    {
        if (auth()->check()) {
            return ['user_id' => auth()->id()];
        }
        return ['session_id' => session()->getId()];
    }

    public function index(): View
    {
        $favourites = Favourite::where($this->identifier())
            ->with('product')
            ->get();

        return view('account.favourites', [
            'categories' => Category::orderBy('nav_order')->get(),
            'favourites' => $favourites,
        ]);
    }

    public function store(Product $product): RedirectResponse
    {
        Favourite::firstOrCreate(
            array_merge($this->identifier(), ['product_id' => $product->id])
        );

        return back();
    }

    public function destroy(Product $product): RedirectResponse
    {
        Favourite::where($this->identifier())
            ->where('product_id', $product->id)
            ->delete();

        return back();
    }
}
