<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Favourite;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function search(Request $request): View
    {
        $query = trim($request->input('q', ''));

        $products = collect();
        $total = 0;

        if ($query !== '') {
            $result = Product::query()
                ->with(['brand', 'category'])
                ->where(function ($q) use ($query) {
                    $q->where('name', 'ilike', '%'.$query.'%')
                        ->orWhere('description', 'ilike', '%'.$query.'%')
                        ->orWhereHas('brand', fn ($b) => $b->where('name', 'ilike', '%'.$query.'%'));
                })
                ->orderByDesc('is_featured')
                ->orderBy('name')
                ->paginate(3)
                ->withQueryString();

            $products = $result;
            $total = $result->total();
        }

        return view('shop.search', [
            'categories'   => Category::query()->orderBy('nav_order')->orderBy('id')->get(),
            'query'        => $query,
            'products'     => $products,
            'total'        => $total,
            'favouriteIds' => Favourite::getIds(),
        ]);
    }

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
        $line = $this->resolveLine($request, $category);
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

        $query = Product::query()->with(['brand', 'category']);

        if ($line !== null) {
            $this->applyLineFilter($query, $line);
        } else {
            $query->whereBelongsTo($category);
        }

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

        $allCategoryProductsQuery = Product::query()->with('brand');

        if ($line !== null) {
            $this->applyLineFilter($allCategoryProductsQuery, $line);
        } else {
            $allCategoryProductsQuery->whereBelongsTo($category);
        }

        $allCategoryProducts = $allCategoryProductsQuery->get();
        $lineConfig = $line !== null ? Product::lineConfig($line) : null;

        $currentLineTitle = $lineConfig['title'] ?? null;
        $currentLineDescription = $lineConfig['description'] ?? $category->description;

        $availableBrands = $allCategoryProducts->pluck('brand')->unique('id')->sortBy('name')->values();
        $availableColors = $allCategoryProducts->pluck('color')->filter()->unique()->sort()->values();
        $availableRam = $allCategoryProducts->pluck('ram_gb')->filter()->unique()->sort()->values();

        return view('shop.category', [
            'category'        => $category,
            'categories'      => Category::query()->orderBy('nav_order')->orderBy('id')->get(),
            'products'        => $products,
            'availableBrands' => $availableBrands,
            'availableColors' => $availableColors,
            'availableRam'    => $availableRam,
            'favouriteIds'    => Favourite::getIds(),
            'currentLine'     => $line,
            'currentLineTitle' => $currentLineTitle,
            'currentLineDescription' => $currentLineDescription,
            'filters' => [
                'price_from' => $request->input('price_from'),
                'price_to' => $request->input('price_to'),
                'brands' => $selectedBrands,
                'colors' => $selectedColors,
                'ram' => $selectedRam,
                'sort' => $sort,
                'line' => $line,
            ],
        ]);
    }

    public function show(Product $product): View
    {
        $product->load(['brand', 'category', 'images']);

        return view('shop.product', [
            'product'      => $product,
            'categories'   => Category::query()->orderBy('nav_order')->orderBy('id')->get(),
            'favouriteIds' => Favourite::getIds(),
            'galleryImages' => $product->galleryImages(),
        ]);
    }

    private function resolveLine(Request $request, Category $category): ?string
    {
        $line = $request->input('line');

        if (! is_string($line) || $line === '') {
            return null;
        }

        $allowedLineKeys = Product::allowedLineKeysForCategorySlug($category->slug);

        if (! in_array($line, $allowedLineKeys, true)) {
            return null;
        }

        return $line;
    }

    private function applyLineFilter($query, string $lineKey): void
    {
        $lineConfig = Product::lineConfig($lineKey);

        if ($lineConfig === null) {
            return;
        }

        $query
            ->where(function ($lineQuery) use ($lineConfig, $lineKey) {
                $lineQuery
                    ->where(function ($namedProductsQuery) use ($lineConfig) {
                        $namedProductsQuery
                            ->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('name', $lineConfig['category_name']))
                            ->whereIn('name', $lineConfig['product_names']);
                    })
                    ->orWhere('line_key', $lineKey);
            });
    }
}
