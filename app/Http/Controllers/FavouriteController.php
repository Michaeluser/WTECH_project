<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Favourite;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FavouriteController extends Controller
{
    // Определяем кто делает запрос — залогинен или гость
    // Возвращает массив с нужным идентификатором для запросов к БД
    private function identifier(): array
    {
        if (auth()->check()) {
            return ['user_id' => auth()->id()];      // залогинен → используем user_id
        }
        return ['session_id' => session()->getId()]; // гость → используем session_id
    }

    // Показать страницу избранного
    public function index(): View
    {
        $favourites = Favourite::where($this->identifier())
            ->with('product')   // подгружаем данные товара к каждой записи
            ->get();

        return view('account.favourites', [
            'categories' => Category::orderBy('nav_order')->get(),
            'favourites' => $favourites,
        ]);
    }

    // Добавить товар в избранное
    public function store(Product $product): RedirectResponse
    {
        // Объединяем идентификатор (user_id или session_id) с id товара
        // firstOrCreate — не создаст дубликат если товар уже в избранном
        Favourite::firstOrCreate(
            array_merge($this->identifier(), ['product_id' => $product->id])
        );

        return back();
    }

    // Убрать товар из избранного
    public function destroy(Product $product): RedirectResponse
    {
        Favourite::where($this->identifier())
            ->where('product_id', $product->id)
            ->delete();

        return back();
    }
}
