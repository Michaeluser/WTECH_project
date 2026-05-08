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
    private const PRODUCT_GALLERY_EXTRAS = [
        'asus-expertbook-b1' => [
            'images/laptop_asus_expertbook_2angle.png',
            'images/laptop_asus_expertbook_3angle.png',
        ],
        'lenovo-thinkbook-14-g6' => [
            'images/laptop_lenovo_thinkbook_2angle.png',
            'images/laptop_lenovo_thinkbook_3angle.png',
        ],
    ];

    private const LAPTOP_LINES = [
        'office' => [
            'category' => 'Laptops',
            'title' => 'Office laptops',
            'description' => 'Reliable laptops for study, office tasks, business travel, and everyday productivity.',
            'product_names' => [
                'Lenovo ThinkBook 14 G6',
                'HP ProBook 450',
                'Dell Latitude 5450',
                'ASUS ExpertBook B1',
                'Dell Inspiron 14 Business',
                'Lenovo V15 G4',
                'Dell Vostro 15',
                'ASUS ExpertBook P1',
                'HP 250 G10',
                'Lenovo ThinkPad E14',
            ],
        ],
        'gaming' => [
            'category' => 'Gaming',
            'title' => 'Gaming laptops',
            'description' => 'High-performance laptops with stronger graphics, cooling, and displays for modern games.',
            'product_names' => [
                'ASUS ROG Strix G16',
                'Lenovo Legion 5 Pro',
                'HP Omen 16',
            ],
        ],
        'arm' => [
            'category' => 'Laptops',
            'title' => 'ARM laptops',
            'description' => 'Modern ARM-based laptops focused on battery life, quiet operation, and lightweight design.',
            'product_names' => [
                'Lenovo IdeaPad Slim 5x',
                'ASUS Vivobook S 15 Snapdragon',
                'HP OmniBook X 14',
            ],
        ],
        'macbook' => [
            'category' => 'Laptops',
            'title' => 'MacBook',
            'description' => 'Apple laptops with efficient chips, premium displays, and strong battery life.',
            'product_names' => [
                'MacBook Air 13 M4',
                'MacBook Air 15 M4',
                'MacBook Pro 14 M4',
            ],
        ],
    ];

    private const PC_COMPONENT_LINES = [
        'graphics' => [
            'category' => 'PC Components',
            'title' => 'Graphics cards',
            'description' => 'Graphics solutions for smoother gaming, creative workloads, and higher display resolutions.',
            'product_names' => [
                'ASUS GeForce RTX 5070 Dual',
                'MSI GeForce RTX 5060 Ventus 2X',
                'ASUS Radeon RX 7800 XT Dual',
            ],
        ],
        'processors-boards' => [
            'category' => 'PC Components',
            'title' => 'Processors and boards',
            'description' => 'Core platform parts for building or upgrading a desktop system.',
            'product_names' => [
                'MSI MAG B760 Tomahawk',
                'Intel Core i7-14700K',
                'AMD Ryzen 7 9700X',
                'ASUS Prime B650-Plus',
            ],
        ],
        'storage-memory' => [
            'category' => 'PC Components',
            'title' => 'Storage and memory',
            'description' => 'Fast SSD storage and memory kits for responsive everyday work and gaming.',
            'product_names' => [
                'Dell 1 TB NVMe SSD',
                'Samsung 990 EVO 1 TB',
                'Kingston Fury Beast 32 GB DDR5',
            ],
        ],
        'gaming-upgrades' => [
            'category' => 'PC Components',
            'title' => 'Gaming upgrades',
            'description' => 'Supporting parts and upgrades that help round out a gaming-focused desktop build.',
            'product_names' => [
                'Corsair RM850e Power Supply',
                'Logitech G502 X Plus',
                'MSI MAG CoreLiquid 240R V2',
            ],
        ],
    ];

    private const GAMING_LINES = [
        'gaming-laptops' => [
            'category' => 'Gaming',
            'title' => 'Gaming laptops',
            'description' => 'Portable gaming machines with stronger cooling, dedicated graphics, and fast displays.',
            'product_names' => [
                'ASUS ROG Strix G16',
                'Lenovo Legion 5 Pro',
                'HP Omen 16',
            ],
        ],
        'graphics-power' => [
            'category' => 'Gaming',
            'title' => 'Graphics power',
            'description' => 'Performance-focused gaming systems and consoles built for stronger visuals and smoother gameplay.',
            'product_names' => [
                'Sony PlayStation 5 Slim',
                'ASUS ROG NUC',
                'MSI Aegis RS2',
            ],
        ],
        'high-refresh-displays' => [
            'category' => 'Monitors',
            'title' => 'High refresh displays',
            'description' => 'Gaming monitors with smoother motion and quick response times for competitive play.',
            'product_names' => [
                'Samsung Odyssey G5 27',
                'LG UltraGear 27GR75Q',
                'ASUS TUF VG27AQ3A',
            ],
        ],
        'portable-performance' => [
            'category' => 'Gaming',
            'title' => 'Portable performance',
            'description' => 'Compact gaming devices built for strong performance away from a full desk setup.',
            'product_names' => [
                'ASUS ROG Ally X',
                'Lenovo Legion Go',
                'MSI Claw 8 AI+',
            ],
        ],
    ];

    private const MONITOR_LINES = [
        'office-monitors' => [
            'category' => 'Monitors',
            'title' => 'Office monitors',
            'description' => 'Comfortable, practical displays for documents, spreadsheets, meetings, and everyday desk work.',
            'product_names' => [
                'Dell UltraSharp 27',
                'HP E24 G5 Monitor',
                'Lenovo ThinkVision T24',
            ],
        ],
        'gaming-monitors' => [
            'category' => 'Monitors',
            'title' => 'Gaming monitors',
            'description' => 'Faster gaming displays built for smoother motion, lower blur, and more responsive gameplay.',
            'product_names' => [
                'Samsung Odyssey G5 27',
                'LG UltraGear 27GR75Q',
                'ASUS TUF VG27AQ3A',
            ],
        ],
        'creative-displays' => [
            'category' => 'Monitors',
            'title' => 'Creative displays',
            'description' => 'Color-focused displays for design work, editing, and accurate visual content creation.',
            'product_names' => [
                'ASUS ProArt Display 27',
                'Dell UltraSharp U3223QE',
                'LG Ergo 32UN880',
            ],
        ],
    ];

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
        $line = $this->resolveLaptopLine($request, $category);
        $pcLine = $this->resolvePcComponentLine($request, $category);
        $gamingLine = $this->resolveGamingLine($request, $category);
        $monitorLine = $this->resolveMonitorLine($request, $category);
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
            $lineConfig = self::LAPTOP_LINES[$line];
            $query
                ->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('name', $lineConfig['category']))
                ->whereIn('name', $lineConfig['product_names']);
        } elseif ($pcLine !== null) {
            $lineConfig = self::PC_COMPONENT_LINES[$pcLine];
            $query
                ->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('name', $lineConfig['category']))
                ->whereIn('name', $lineConfig['product_names']);
        } elseif ($gamingLine !== null) {
            $lineConfig = self::GAMING_LINES[$gamingLine];
            $query
                ->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('name', $lineConfig['category']))
                ->whereIn('name', $lineConfig['product_names']);
        } elseif ($monitorLine !== null) {
            $lineConfig = self::MONITOR_LINES[$monitorLine];
            $query
                ->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('name', $lineConfig['category']))
                ->whereIn('name', $lineConfig['product_names']);
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
            $lineConfig = self::LAPTOP_LINES[$line];
            $allCategoryProductsQuery
                ->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('name', $lineConfig['category']))
                ->whereIn('name', $lineConfig['product_names']);
        } elseif ($pcLine !== null) {
            $lineConfig = self::PC_COMPONENT_LINES[$pcLine];
            $allCategoryProductsQuery
                ->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('name', $lineConfig['category']))
                ->whereIn('name', $lineConfig['product_names']);
        } elseif ($gamingLine !== null) {
            $lineConfig = self::GAMING_LINES[$gamingLine];
            $allCategoryProductsQuery
                ->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('name', $lineConfig['category']))
                ->whereIn('name', $lineConfig['product_names']);
        } elseif ($monitorLine !== null) {
            $lineConfig = self::MONITOR_LINES[$monitorLine];
            $allCategoryProductsQuery
                ->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('name', $lineConfig['category']))
                ->whereIn('name', $lineConfig['product_names']);
        } else {
            $allCategoryProductsQuery->whereBelongsTo($category);
        }

        $allCategoryProducts = $allCategoryProductsQuery->get();

        $currentLineTitle = $line !== null
            ? self::LAPTOP_LINES[$line]['title']
            : ($pcLine !== null ? self::PC_COMPONENT_LINES[$pcLine]['title'] : ($gamingLine !== null ? self::GAMING_LINES[$gamingLine]['title'] : ($monitorLine !== null ? self::MONITOR_LINES[$monitorLine]['title'] : null)));

        $currentLineDescription = $line !== null
            ? self::LAPTOP_LINES[$line]['description']
            : ($pcLine !== null ? self::PC_COMPONENT_LINES[$pcLine]['description'] : ($gamingLine !== null ? self::GAMING_LINES[$gamingLine]['description'] : ($monitorLine !== null ? self::MONITOR_LINES[$monitorLine]['description'] : $category->description)));

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
            'currentLine'     => $line ?? $pcLine ?? $gamingLine ?? $monitorLine,
            'currentLineTitle' => $currentLineTitle,
            'currentLineDescription' => $currentLineDescription,
            'filters' => [
                'price_from' => $request->input('price_from'),
                'price_to' => $request->input('price_to'),
                'brands' => $selectedBrands,
                'colors' => $selectedColors,
                'ram' => $selectedRam,
                'sort' => $sort,
                'line' => $line ?? $pcLine ?? $gamingLine ?? $monitorLine,
            ],
        ]);
    }

    public function show(Product $product): View
    {
        $product->load(['brand', 'category']);

        $extraGalleryImages = self::PRODUCT_GALLERY_EXTRAS[$product->slug] ?? [
            'images/default_laptop.png',
            'images/default_laptop_2.png',
        ];

        $galleryImages = array_values(array_unique(array_filter([
            $product->image_path,
            ...$extraGalleryImages,
        ])));

        return view('shop.product', [
            'product'      => $product,
            'categories'   => Category::query()->orderBy('nav_order')->orderBy('id')->get(),
            'favouriteIds' => Favourite::getIds(),
            'galleryImages' => $galleryImages,
        ]);
    }

    private function resolveLaptopLine(Request $request, Category $category): ?string
    {
        $line = $request->input('line');

        if ($category->slug !== 'laptops' || ! is_string($line) || ! array_key_exists($line, self::LAPTOP_LINES)) {
            return null;
        }

        return $line;
    }

    private function resolvePcComponentLine(Request $request, Category $category): ?string
    {
        $line = $request->input('line');

        if ($category->slug !== 'pc-components' || ! is_string($line) || ! array_key_exists($line, self::PC_COMPONENT_LINES)) {
            return null;
        }

        return $line;
    }

    private function resolveGamingLine(Request $request, Category $category): ?string
    {
        $line = $request->input('line');

        if ($category->slug !== 'gaming' || ! is_string($line) || ! array_key_exists($line, self::GAMING_LINES)) {
            return null;
        }

        return $line;
    }

    private function resolveMonitorLine(Request $request, Category $category): ?string
    {
        $line = $request->input('line');

        if ($category->slug !== 'monitors' || ! is_string($line) || ! array_key_exists($line, self::MONITOR_LINES)) {
            return null;
        }

        return $line;
    }
}
