<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    private const LINE_DEFINITIONS = [
        'office' => [
            'category_slug' => 'laptops',
            'category_name' => 'Laptops',
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
            'category_slug' => 'laptops',
            'category_name' => 'Gaming',
            'title' => 'Gaming laptops',
            'description' => 'High-performance laptops with stronger graphics, cooling, and displays for modern games.',
            'product_names' => [
                'ASUS ROG Strix G16',
                'Lenovo Legion 5 Pro',
                'HP Omen 16',
            ],
        ],
        'arm' => [
            'category_slug' => 'laptops',
            'category_name' => 'Laptops',
            'title' => 'ARM laptops',
            'description' => 'Modern ARM-based laptops focused on battery life, quiet operation, and lightweight design.',
            'product_names' => [
                'Lenovo IdeaPad Slim 5x',
                'ASUS Vivobook S 15 Snapdragon',
                'HP OmniBook X 14',
            ],
        ],
        'macbook' => [
            'category_slug' => 'laptops',
            'category_name' => 'Laptops',
            'title' => 'MacBook',
            'description' => 'Apple laptops with efficient chips, premium displays, and strong battery life.',
            'product_names' => [
                'MacBook Air 13 M4',
                'MacBook Air 15 M4',
                'MacBook Pro 14 M4',
            ],
        ],
        'graphics' => [
            'category_slug' => 'pc-components',
            'category_name' => 'PC Components',
            'title' => 'Graphics cards',
            'description' => 'Graphics solutions for smoother gaming, creative workloads, and higher display resolutions.',
            'product_names' => [
                'ASUS GeForce RTX 5070 Dual',
                'MSI GeForce RTX 5060 Ventus 2X',
                'ASUS Radeon RX 7800 XT Dual',
            ],
        ],
        'processors-boards' => [
            'category_slug' => 'pc-components',
            'category_name' => 'PC Components',
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
            'category_slug' => 'pc-components',
            'category_name' => 'PC Components',
            'title' => 'Storage and memory',
            'description' => 'Fast SSD storage and memory kits for responsive everyday work and gaming.',
            'product_names' => [
                'Dell 1 TB NVMe SSD',
                'Samsung 990 EVO 1 TB',
                'Kingston Fury Beast 32 GB DDR5',
            ],
        ],
        'gaming-upgrades' => [
            'category_slug' => 'pc-components',
            'category_name' => 'PC Components',
            'title' => 'Gaming upgrades',
            'description' => 'Supporting parts and upgrades that help round out a gaming-focused desktop build.',
            'product_names' => [
                'Corsair RM850e Power Supply',
                'Logitech G502 X Plus',
                'MSI MAG CoreLiquid 240R V2',
            ],
        ],
        'gaming-laptops' => [
            'category_slug' => 'gaming',
            'category_name' => 'Gaming',
            'title' => 'Gaming laptops',
            'description' => 'Portable gaming machines with stronger cooling, dedicated graphics, and fast displays.',
            'product_names' => [
                'ASUS ROG Strix G16',
                'Lenovo Legion 5 Pro',
                'HP Omen 16',
            ],
        ],
        'graphics-power' => [
            'category_slug' => 'gaming',
            'category_name' => 'Gaming',
            'title' => 'Graphics power',
            'description' => 'Performance-focused gaming systems and consoles built for stronger visuals and smoother gameplay.',
            'product_names' => [
                'Sony PlayStation 5 Slim',
                'ASUS ROG NUC',
                'MSI Aegis RS2',
            ],
        ],
        'high-refresh-displays' => [
            'category_slug' => 'gaming',
            'category_name' => 'Monitors',
            'title' => 'High refresh displays',
            'description' => 'Gaming monitors with smoother motion and quick response times for competitive play.',
            'product_names' => [
                'Samsung Odyssey G5 27',
                'LG UltraGear 27GR75Q',
                'ASUS TUF VG27AQ3A',
            ],
        ],
        'portable-performance' => [
            'category_slug' => 'gaming',
            'category_name' => 'Gaming',
            'title' => 'Portable performance',
            'description' => 'Compact gaming devices built for strong performance away from a full desk setup.',
            'product_names' => [
                'ASUS ROG Ally X',
                'Lenovo Legion Go',
                'MSI Claw 8 AI+',
            ],
        ],
        'office-monitors' => [
            'category_slug' => 'monitors',
            'category_name' => 'Monitors',
            'title' => 'Office monitors',
            'description' => 'Comfortable, practical displays for documents, spreadsheets, meetings, and everyday desk work.',
            'product_names' => [
                'Dell UltraSharp 27',
                'HP E24 G5 Monitor',
                'Lenovo ThinkVision T24',
            ],
        ],
        'gaming-monitors' => [
            'category_slug' => 'monitors',
            'category_name' => 'Monitors',
            'title' => 'Gaming monitors',
            'description' => 'Faster gaming displays built for smoother motion, lower blur, and more responsive gameplay.',
            'product_names' => [
                'Samsung Odyssey G5 27',
                'LG UltraGear 27GR75Q',
                'ASUS TUF VG27AQ3A',
            ],
        ],
        'creative-displays' => [
            'category_slug' => 'monitors',
            'category_name' => 'Monitors',
            'title' => 'Creative displays',
            'description' => 'Color-focused displays for design work, editing, and accurate visual content creation.',
            'product_names' => [
                'ASUS ProArt Display 27',
                'Dell UltraSharp U3223QE',
                'LG Ergo 32UN880',
            ],
        ],
    ];

    private const GALLERY_EXTRAS = [
        'asus-expertbook-b1' => [
            'images/laptop_asus_expertbook_2angle.png',
            'images/laptop_asus_expertbook_3angle.png',
        ],
        'lenovo-thinkbook-14-g6' => [
            'images/laptop_lenovo_thinkbook_2angle.png',
            'images/laptop_lenovo_thinkbook_3angle.png',
        ],
    ];

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'description',
        'price',
        'color',
        'ram_gb',
        'stock',
        'image_path',
        'line_key',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public static function lineDefinitions(): array
    {
        return self::LINE_DEFINITIONS;
    }

    public static function lineConfig(string $lineKey): ?array
    {
        return self::LINE_DEFINITIONS[$lineKey] ?? null;
    }

    public static function lineOptionsByCategory(): array
    {
        $options = [];

        foreach (self::LINE_DEFINITIONS as $lineKey => $lineConfig) {
            $options[$lineConfig['category_slug']][$lineKey] = $lineConfig['title'];
        }

        return $options;
    }

    public static function allowedLineKeysForCategorySlug(string $categorySlug): array
    {
        return array_keys(self::lineOptionsByCategory()[$categorySlug] ?? []);
    }

    public function resolvedLineKey(): ?string
    {
        if (is_string($this->line_key) && $this->line_key !== '') {
            return $this->line_key;
        }

        foreach (self::LINE_DEFINITIONS as $lineKey => $lineConfig) {
            if (in_array($this->name, $lineConfig['product_names'], true)) {
                return $lineKey;
            }
        }

        return null;
    }

    public function galleryImages(): array
    {
        $savedGalleryImages = $this->relationLoaded('images')
            ? $this->images->pluck('image_path')->filter()->values()->all()
            : $this->images()->pluck('image_path')->filter()->values()->all();

        if ($savedGalleryImages !== []) {
            return array_values(array_unique(array_filter([
                $this->image_path,
                ...$savedGalleryImages,
            ])));
        }

        $extraGalleryImages = self::GALLERY_EXTRAS[$this->slug] ?? [
            'images/default_laptop.png',
            'images/default_laptop_2.png',
        ];

        return array_values(array_unique(array_filter([
            $this->image_path,
            ...$extraGalleryImages,
        ])));
    }
}
