<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function catalog(Category $category): View|RedirectResponse
    {
        if ($category->catalog_mode !== 'landing') {
            return redirect()->route('categories.show', $category);
        }

        $category->load('catalogItems.targetCategory');

        return view('shop.catalog', [
            'categories' => Category::query()->orderBy('nav_order')->orderBy('id')->get(),
            'category' => $category,
            'slides' => $category->catalogItems->where('kind', 'slide')->values(),
            'cards' => $category->catalogItems->where('kind', 'card')->values(),
        ]);
    }

    public function indexByCategory(Request $request, Category $category): View
    {
        $selectedBrands = $request->input('brands', []);
        $selectedColors = $request->input('colors', []);
        $selectedRam = $request->input('ram', []);
        $sort = $request->input('sort', '');

        if (! is_array($selectedBrands)) {
            $selectedBrands = [];
        }

        if (! is_array($selectedColors)) {
            $selectedColors = [];
        }

        if (! is_array($selectedRam)) {
            $selectedRam = [];
        }

        $selectedBrands = array_values(array_filter($selectedBrands));
        $selectedColors = array_values(array_filter($selectedColors));
        $selectedRam = array_values(array_filter(array_map('intval', $selectedRam)));

        $query = Product::query()
            ->with(['brand', 'category'])
            ->whereBelongsTo($category);

        if ($request->filled('price_from')) {
            $query->where('price', '>=', (float) $request->input('price_from'));
        }

        if ($request->filled('price_to')) {
            $query->where('price', '<=', (float) $request->input('price_to'));
        }

        if ($selectedBrands !== []) {
            $query->whereHas('brand', function ($brandQuery) use ($selectedBrands) {
                $brandQuery->whereIn('slug', $selectedBrands);
            });
        }

        if ($selectedColors !== []) {
            $query->whereIn('color', $selectedColors);
        }

        if ($selectedRam !== []) {
            $query->whereIn('ram_gb', $selectedRam);
        }

        if ($sort === 'price_asc') {
            $query->orderBy('price');
        } elseif ($sort === 'price_desc') {
            $query->orderByDesc('price');
        } else {
            $query->orderByDesc('is_featured');
            $query->orderBy('name');
        }

        $products = $query
            ->paginate(6)
            ->withQueryString();

        $allCategoryProducts = Product::query()
            ->whereBelongsTo($category)
            ->with('brand')
            ->get();

        $availableBrands = $allCategoryProducts->pluck('brand')->unique('id')->sortBy('name')->values();
        $availableColors = $allCategoryProducts->pluck('color')->filter()->unique()->sort()->values();
        $availableRam = $allCategoryProducts->pluck('ram_gb')->filter()->unique()->sort()->values();

        return view('shop.category', [
            'category' => $category,
            'categories' => Category::query()->orderBy('nav_order')->orderBy('id')->get(),
            'products' => $products,
            'availableBrands' => $availableBrands,
            'availableColors' => $availableColors,
            'availableRam' => $availableRam,
            'filters' => [
                'price_from' => $request->input('price_from'),
                'price_to' => $request->input('price_to'),
                'brands' => $selectedBrands,
                'colors' => $selectedColors,
                'ram' => $selectedRam,
                'sort' => $sort,
            ],
        ]);
    }
}
