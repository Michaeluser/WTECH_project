<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\CatalogItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $categories = $this->createCategories();
        $brands = $this->createBrands();

        $this->createProducts($categories, $brands);
        $this->createCatalogItems($categories);
    }

    private function createCategories(): array
    {
        $categories = [];

        foreach ($this->categoryData() as $categoryData) {
            $category = Category::create($categoryData);
            $categories[$category->name] = $category;
        }

        return $categories;
    }

    private function createBrands(): array
    {
        $brands = [];

        foreach ($this->brandNames() as $brandName) {
            $brand = Brand::create([
                'name' => $brandName,
                'slug' => Str::slug($brandName),
            ]);

            $brands[$brand->name] = $brand;
        }

        return $brands;
    }

    private function createProducts(array $categories, array $brands): void
    {
        foreach ($this->productData() as $productData) {
            Product::create([
                'category_id' => $categories[$productData['category']]->id,
                'brand_id' => $brands[$productData['brand']]->id,
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']),
                'description' => $productData['name'] . ' is a curated demo product for the first kontrolny bod. It gives us real data for category browsing, search, sorting, filtering, and cart scenarios.',
                'price' => $productData['price'],
                'color' => $productData['color'],
                'ram_gb' => $productData['ram_gb'],
                'stock' => $productData['stock'],
                'image_path' => $productData['image_path'],
                'is_featured' => $productData['featured'],
            ]);
        }
    }

    private function createCatalogItems(array $categories): void
    {
        foreach ($this->catalogItemData() as $categoryName => $groups) {
            $category = $categories[$categoryName];

            foreach ($groups['slides'] as $index => $slide) {
                CatalogItem::create([
                    'category_id' => $category->id,
                    'target_category_id' => $categories[$slide['target']]->id,
                    'kind' => 'slide',
                    'title' => null,
                    'image_path' => $slide['image'],
                    'alt_text' => $slide['alt'],
                    'sort_order' => $index + 1,
                ]);
            }

            foreach ($groups['cards'] as $index => $card) {
                CatalogItem::create([
                    'category_id' => $category->id,
                    'target_category_id' => $categories[$card['target']]->id,
                    'kind' => 'card',
                    'title' => $card['title'],
                    'image_path' => $card['image'],
                    'alt_text' => $card['alt'],
                    'sort_order' => $index + 1,
                ]);
            }
        }
    }

    private function categoryData(): array
    {
        return [
            [
                'name' => 'Laptops',
                'slug' => 'laptops',
                'description' => 'Portable computers for study, business work, creative tasks, and everyday use.',
                'catalog_mode' => 'landing',
                'catalog_slider_caption' => 'The customers favorite',
                'catalog_advice_title' => 'A piece of advice from technodom.sk',
                'catalog_advice_subtitle' => 'What to choose - a personal computer or a laptop?',
                'catalog_advice_text' => 'Personal computers and laptops have become indispensable assistants in everyday life, without which it is impossible to imagine work, study, or entertainment. Today, they are found in almost every home and office - they are a modern means of communication, a tool for searching, processing, and storing information, as well as a great way to spend your free time. Each device has its own advantages, which have led to their well-deserved popularity and demand.',
                'nav_order' => 1,
            ],
            [
                'name' => 'Phones',
                'slug' => 'phones',
                'description' => 'Smartphones with flagship cameras, long battery life, and modern connectivity.',
                'catalog_mode' => 'landing',
                'catalog_slider_caption' => 'Flagships and everyday winners',
                'catalog_advice_title' => 'A piece of advice from technodom.sk',
                'catalog_advice_subtitle' => 'How to choose the right smartphone for daily use?',
                'catalog_advice_text' => 'A modern smartphone should match how you actually live: battery life, camera quality, storage, and display size matter more than marketing labels. If you travel, create content, or rely on your phone for work and communication, choose a model that gives you enough performance headroom and the accessories to support it.',
                'nav_order' => 2,
            ],
            [
                'name' => 'PC Components',
                'slug' => 'pc-components',
                'description' => 'Core hardware for building, upgrading, and tuning desktop computers.',
                'catalog_mode' => 'landing',
                'catalog_slider_caption' => 'Upgrade your setup',
                'catalog_advice_title' => 'A piece of advice from technodom.sk',
                'catalog_advice_subtitle' => 'Which component should you upgrade first?',
                'catalog_advice_text' => 'The best upgrade depends on your bottleneck. Slow boot and load times usually point to storage, frame-rate issues often point to the GPU, and poor multitasking usually points to RAM or CPU limits. Start with the part that improves your real daily workload the most.',
                'nav_order' => 3,
            ],
            [
                'name' => 'Monitors',
                'slug' => 'monitors',
                'description' => 'Displays for workstations, home offices, and gaming setups.',
                'catalog_mode' => 'landing',
                'catalog_slider_caption' => 'Sharper work and play',
                'catalog_advice_title' => 'A piece of advice from technodom.sk',
                'catalog_advice_subtitle' => 'What matters most when picking a monitor?',
                'catalog_advice_text' => 'Resolution, panel quality, refresh rate, and ergonomics all shape the experience. Work-focused setups benefit from sharp text and comfortable positioning, while gaming setups benefit from higher refresh rates and faster response times.',
                'nav_order' => 4,
            ],
            [
                'name' => 'TV&Audio',
                'slug' => 'tv-audio',
                'description' => 'Televisions, sound systems, and home entertainment devices.',
                'catalog_mode' => 'landing',
                'catalog_slider_caption' => 'Home entertainment favorites',
                'catalog_advice_title' => 'A piece of advice from technodom.sk',
                'catalog_advice_subtitle' => 'How to build a better home cinema setup?',
                'catalog_advice_text' => 'Picture size alone is not enough. A balanced setup combines a display that fits the room, audio that fills the space clearly, and the right cables and placement so the whole system feels cohesive rather than oversized or underpowered.',
                'nav_order' => 5,
            ],
            [
                'name' => 'Gaming',
                'slug' => 'gaming',
                'description' => 'Gaming-focused hardware and accessories for performance and immersion.',
                'catalog_mode' => 'landing',
                'catalog_slider_caption' => 'Built for performance',
                'catalog_advice_title' => 'A piece of advice from technodom.sk',
                'catalog_advice_subtitle' => 'How to choose gaming hardware without overspending?',
                'catalog_advice_text' => 'Start from the games you actually play and the resolution you want to target. It is better to build a balanced setup with a sensible GPU, enough RAM, and a good display than to overspend on one top-end part while the rest of the system holds it back.',
                'nav_order' => 6,
            ],
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'description' => 'Useful add-ons and peripherals that complete a workstation or setup.',
                'catalog_mode' => 'landing',
                'catalog_slider_caption' => 'Small gear, big difference',
                'catalog_advice_title' => 'A piece of advice from technodom.sk',
                'catalog_advice_subtitle' => 'Which accessories are actually worth buying?',
                'catalog_advice_text' => 'The best accessories solve friction you feel every day: charging, connectivity, comfort, and desk organization. Prioritize items that improve how your main devices work rather than collecting add-ons you rarely use.',
                'nav_order' => 7,
            ],
            [
                'name' => 'Sale',
                'slug' => 'sale',
                'description' => 'Discounted products and limited-time offers across the catalog.',
                'catalog_mode' => 'list',
                'catalog_slider_caption' => null,
                'catalog_advice_title' => null,
                'catalog_advice_subtitle' => null,
                'catalog_advice_text' => null,
                'nav_order' => 8,
            ],
        ];
    }

    private function brandNames(): array
    {
        return [
            'Lenovo',
            'HP',
            'Dell',
            'ASUS',
            'Apple',
            'Samsung',
            'LG',
            'Sony',
            'MSI',
            'Logitech',
        ];
    }

    private function productData(): array
    {
        return [
            ['category' => 'Laptops', 'brand' => 'Lenovo', 'name' => 'Lenovo ThinkBook 14 G6', 'price' => 749.00, 'color' => 'Gray', 'ram_gb' => 16, 'stock' => 9, 'image_path' => 'images/product-laptop1.jpg', 'featured' => true],
            ['category' => 'Laptops', 'brand' => 'HP', 'name' => 'HP ProBook 450', 'price' => 899.00, 'color' => 'Silver', 'ram_gb' => 16, 'stock' => 6, 'image_path' => 'images/product-laptop2.jpg', 'featured' => false],
            ['category' => 'Laptops', 'brand' => 'Dell', 'name' => 'Dell Latitude 5450', 'price' => 1049.00, 'color' => 'Black', 'ram_gb' => 16, 'stock' => 5, 'image_path' => 'images/product-laptop3.jpg', 'featured' => false],
            ['category' => 'Laptops', 'brand' => 'ASUS', 'name' => 'ASUS ExpertBook B1', 'price' => 639.00, 'color' => 'Blue', 'ram_gb' => 8, 'stock' => 12, 'image_path' => 'images/product-laptop4.jpg', 'featured' => false],
            ['category' => 'Laptops', 'brand' => 'Apple', 'name' => 'MacBook Air 13 M4', 'price' => 1199.00, 'color' => 'Silver', 'ram_gb' => 16, 'stock' => 8, 'image_path' => 'images/product-5.jpg', 'featured' => true],
            ['category' => 'Laptops', 'brand' => 'Lenovo', 'name' => 'Lenovo IdeaPad Slim 5', 'price' => 829.00, 'color' => 'Blue', 'ram_gb' => 16, 'stock' => 10, 'image_path' => 'images/product-laptop5.jpg', 'featured' => false],
            ['category' => 'Laptops', 'brand' => 'HP', 'name' => 'HP EliteBook 840', 'price' => 1149.00, 'color' => 'Silver', 'ram_gb' => 32, 'stock' => 4, 'image_path' => 'images/product-laptop6.jpg', 'featured' => false],
            ['category' => 'Laptops', 'brand' => 'Dell', 'name' => 'Dell Inspiron 14 Business', 'price' => 789.00, 'color' => 'Gray', 'ram_gb' => 8, 'stock' => 11, 'image_path' => 'images/product-laptop7.jpg', 'featured' => false],
            ['category' => 'Laptops', 'brand' => 'ASUS', 'name' => 'ASUS VivoBook 15', 'price' => 699.00, 'color' => 'Black', 'ram_gb' => 16, 'stock' => 13, 'image_path' => 'images/product-laptop1.jpg', 'featured' => false],
            ['category' => 'Laptops', 'brand' => 'Lenovo', 'name' => 'Lenovo Yoga 7', 'price' => 1249.00, 'color' => 'Gray', 'ram_gb' => 16, 'stock' => 5, 'image_path' => 'images/product-laptop2.jpg', 'featured' => false],
            ['category' => 'Laptops', 'brand' => 'HP', 'name' => 'HP Pavilion 15', 'price' => 759.00, 'color' => 'Blue', 'ram_gb' => 8, 'stock' => 9, 'image_path' => 'images/product-laptop3.jpg', 'featured' => false],
            ['category' => 'Laptops', 'brand' => 'Dell', 'name' => 'Dell XPS 13', 'price' => 1399.00, 'color' => 'Silver', 'ram_gb' => 16, 'stock' => 3, 'image_path' => 'images/product-laptop4.jpg', 'featured' => true],
            ['category' => 'Laptops', 'brand' => 'ASUS', 'name' => 'ASUS Zenbook 14 OLED', 'price' => 1099.00, 'color' => 'Black', 'ram_gb' => 16, 'stock' => 7, 'image_path' => 'images/product-laptop5.jpg', 'featured' => false],
            ['category' => 'Laptops', 'brand' => 'Apple', 'name' => 'MacBook Pro 14 M4', 'price' => 1999.00, 'color' => 'Gray', 'ram_gb' => 32, 'stock' => 4, 'image_path' => 'images/product-laptop6.jpg', 'featured' => true],
            ['category' => 'Laptops', 'brand' => 'Lenovo', 'name' => 'Lenovo V15 G4', 'price' => 719.00, 'color' => 'Black', 'ram_gb' => 16, 'stock' => 12, 'image_path' => 'images/product-laptop7.jpg', 'featured' => false],
            ['category' => 'Phones', 'brand' => 'Apple', 'name' => 'iPhone 17 Pro 256 GB', 'price' => 1339.00, 'color' => 'Black', 'ram_gb' => 8, 'stock' => 10, 'image_path' => 'images/product-1.jpg', 'featured' => true],
            ['category' => 'Phones', 'brand' => 'Apple', 'name' => 'iPhone 17 Pro Max 256 GB', 'price' => 1489.00, 'color' => 'Silver', 'ram_gb' => 8, 'stock' => 7, 'image_path' => 'images/product-2.jpg', 'featured' => true],
            ['category' => 'Phones', 'brand' => 'Samsung', 'name' => 'Samsung Galaxy S26 Ultra', 'price' => 1399.00, 'color' => 'Gray', 'ram_gb' => 12, 'stock' => 9, 'image_path' => 'images/banner-phone.jpg', 'featured' => false],
            ['category' => 'Phones', 'brand' => 'Samsung', 'name' => 'Samsung Galaxy A58', 'price' => 479.00, 'color' => 'Blue', 'ram_gb' => 8, 'stock' => 14, 'image_path' => 'images/banner-phone.jpg', 'featured' => false],
            ['category' => 'PC Components', 'brand' => 'ASUS', 'name' => 'ASUS GeForce RTX 5070 Dual', 'price' => 729.00, 'color' => 'Black', 'ram_gb' => 12, 'stock' => 6, 'image_path' => 'images/promo-gpu.jpg', 'featured' => true],
            ['category' => 'PC Components', 'brand' => 'MSI', 'name' => 'MSI MAG B760 Tomahawk', 'price' => 229.00, 'color' => 'Black', 'ram_gb' => 16, 'stock' => 12, 'image_path' => 'images/promo-gpu.jpg', 'featured' => false],
            ['category' => 'PC Components', 'brand' => 'Dell', 'name' => 'Dell 1 TB NVMe SSD', 'price' => 119.00, 'color' => 'Black', 'ram_gb' => 8, 'stock' => 18, 'image_path' => 'images/promo-gpu.jpg', 'featured' => false],
            ['category' => 'Monitors', 'brand' => 'Dell', 'name' => 'Dell UltraSharp 27', 'price' => 449.00, 'color' => 'Black', 'ram_gb' => 8, 'stock' => 15, 'image_path' => 'images/promo-monitor.jpg', 'featured' => true],
            ['category' => 'Monitors', 'brand' => 'HP', 'name' => 'HP E24 G5 Monitor', 'price' => 219.00, 'color' => 'Black', 'ram_gb' => 8, 'stock' => 18, 'image_path' => 'images/promo-monitor.jpg', 'featured' => false],
            ['category' => 'Monitors', 'brand' => 'ASUS', 'name' => 'ASUS ProArt Display 27', 'price' => 589.00, 'color' => 'Black', 'ram_gb' => 16, 'stock' => 7, 'image_path' => 'images/promo-monitor.jpg', 'featured' => false],
            ['category' => 'Monitors', 'brand' => 'Lenovo', 'name' => 'Lenovo ThinkVision T24', 'price' => 199.00, 'color' => 'Gray', 'ram_gb' => 8, 'stock' => 20, 'image_path' => 'images/promo-monitor.jpg', 'featured' => false],
            ['category' => 'TV&Audio', 'brand' => 'LG', 'name' => 'LG OLED C6 55 TV', 'price' => 1299.00, 'color' => 'Black', 'ram_gb' => 8, 'stock' => 6, 'image_path' => 'images/promo-tablet.jpg', 'featured' => true],
            ['category' => 'TV&Audio', 'brand' => 'Sony', 'name' => 'Sony Bravia 50 Smart TV', 'price' => 899.00, 'color' => 'Black', 'ram_gb' => 8, 'stock' => 7, 'image_path' => 'images/promo-tablet.jpg', 'featured' => false],
            ['category' => 'TV&Audio', 'brand' => 'Samsung', 'name' => 'Samsung Soundbar Q Series', 'price' => 499.00, 'color' => 'Black', 'ram_gb' => 8, 'stock' => 10, 'image_path' => 'images/promo-tablet.jpg', 'featured' => false],
            ['category' => 'Gaming', 'brand' => 'ASUS', 'name' => 'ASUS ROG Strix G16', 'price' => 1799.00, 'color' => 'Black', 'ram_gb' => 32, 'stock' => 5, 'image_path' => 'images/cat-gaming.jpg', 'featured' => true],
            ['category' => 'Gaming', 'brand' => 'Lenovo', 'name' => 'Lenovo Legion 5 Pro', 'price' => 1699.00, 'color' => 'Gray', 'ram_gb' => 32, 'stock' => 4, 'image_path' => 'images/product-laptop2.jpg', 'featured' => false],
            ['category' => 'Gaming', 'brand' => 'HP', 'name' => 'HP Omen 16', 'price' => 1599.00, 'color' => 'Black', 'ram_gb' => 16, 'stock' => 6, 'image_path' => 'images/product-laptop3.jpg', 'featured' => false],
            ['category' => 'Gaming', 'brand' => 'Sony', 'name' => 'Sony PlayStation 5 Slim', 'price' => 549.00, 'color' => 'White', 'ram_gb' => 16, 'stock' => 11, 'image_path' => 'images/cat-gaming.jpg', 'featured' => true],
            ['category' => 'Accessories', 'brand' => 'Logitech', 'name' => 'Logitech MX Master 4', 'price' => 119.00, 'color' => 'Black', 'ram_gb' => 8, 'stock' => 25, 'image_path' => 'images/cat-acc.jpg', 'featured' => false],
            ['category' => 'Accessories', 'brand' => 'Apple', 'name' => 'Apple USB-C Charger 70W', 'price' => 69.00, 'color' => 'White', 'ram_gb' => 8, 'stock' => 30, 'image_path' => 'images/cat-chargers.jpg', 'featured' => false],
            ['category' => 'Accessories', 'brand' => 'Samsung', 'name' => 'Samsung USB-C Cable 2 m', 'price' => 19.00, 'color' => 'Black', 'ram_gb' => 8, 'stock' => 40, 'image_path' => 'images/cat-cables.jpg', 'featured' => false],
            ['category' => 'Accessories', 'brand' => 'Dell', 'name' => 'Dell Monitor Arm', 'price' => 149.00, 'color' => 'Silver', 'ram_gb' => 8, 'stock' => 9, 'image_path' => 'images/cat-arm.jpg', 'featured' => false],
            ['category' => 'Sale', 'brand' => 'Apple', 'name' => 'iPad Pro M5 13 256 GB', 'price' => 1249.00, 'color' => 'Silver', 'ram_gb' => 16, 'stock' => 5, 'image_path' => 'images/product-3.jpg', 'featured' => true],
            ['category' => 'Sale', 'brand' => 'Apple', 'name' => 'iPad Pro M5 11 256 GB', 'price' => 999.00, 'color' => 'Gray', 'ram_gb' => 16, 'stock' => 6, 'image_path' => 'images/product-4.jpg', 'featured' => true],
            ['category' => 'Sale', 'brand' => 'ASUS', 'name' => 'ASUS TUF Gaming VG27', 'price' => 329.00, 'color' => 'Black', 'ram_gb' => 16, 'stock' => 13, 'image_path' => 'images/promo-monitor.jpg', 'featured' => false],
        ];
    }

    private function catalogItemData(): array
    {
        return [
            'Laptops' => [
                'slides' => [
                    ['target' => 'Laptops', 'image' => 'images/slider_img1.webp', 'alt' => 'Office laptops'],
                    ['target' => 'Gaming', 'image' => 'images/slider_img2.webp', 'alt' => 'Gaming laptops'],
                    ['target' => 'Laptops', 'image' => 'images/slider_img3.webp', 'alt' => 'MacBook style laptops'],
                ],
                'cards' => [
                    ['target' => 'Laptops', 'image' => 'images/cat-office.webp', 'title' => 'Office laptops', 'alt' => 'Office laptops'],
                    ['target' => 'Gaming', 'image' => 'images/cat-gaming.jpg', 'title' => 'Gaming laptops', 'alt' => 'Gaming laptops'],
                    ['target' => 'Laptops', 'image' => 'images/cat-arm.jpg', 'title' => 'ARM laptops', 'alt' => 'ARM laptops'],
                    ['target' => 'Laptops', 'image' => 'images/slider_img3.webp', 'title' => 'MacBook', 'alt' => 'MacBook'],
                    ['target' => 'Monitors', 'image' => 'images/cat-imac.jpg', 'title' => 'iMac', 'alt' => 'iMac style desktop displays'],
                    ['target' => 'Accessories', 'image' => 'images/cat-acc.jpg', 'title' => 'Accessories', 'alt' => 'Accessories'],
                    ['target' => 'Accessories', 'image' => 'images/cat-cables.jpg', 'title' => 'Cables', 'alt' => 'Cables'],
                    ['target' => 'Accessories', 'image' => 'images/cat-chargers.jpg', 'title' => 'Chargers', 'alt' => 'Chargers'],
                ],
            ],
            'Phones' => [
                'slides' => [
                    ['target' => 'Phones', 'image' => 'images/banner-phone.jpg', 'alt' => 'Smartphones'],
                    ['target' => 'Sale', 'image' => 'images/product-1.jpg', 'alt' => 'Premium phones on sale'],
                    ['target' => 'Accessories', 'image' => 'images/cat-chargers.jpg', 'alt' => 'Phone chargers and cables'],
                ],
                'cards' => [
                    ['target' => 'Phones', 'image' => 'images/product-1.jpg', 'title' => 'Flagship phones', 'alt' => 'Flagship phones'],
                    ['target' => 'Phones', 'image' => 'images/product-2.jpg', 'title' => 'Large-screen phones', 'alt' => 'Large-screen phones'],
                    ['target' => 'Sale', 'image' => 'images/banner-phone.jpg', 'title' => 'Special offers', 'alt' => 'Special offers'],
                    ['target' => 'Accessories', 'image' => 'images/cat-chargers.jpg', 'title' => 'Chargers', 'alt' => 'Chargers'],
                    ['target' => 'Accessories', 'image' => 'images/cat-cables.jpg', 'title' => 'Cables', 'alt' => 'Cables'],
                    ['target' => 'Accessories', 'image' => 'images/cat-acc.jpg', 'title' => 'Cases and add-ons', 'alt' => 'Cases and add-ons'],
                ],
            ],
            'PC Components' => [
                'slides' => [
                    ['target' => 'PC Components', 'image' => 'images/promo-gpu.jpg', 'alt' => 'Graphics cards and components'],
                    ['target' => 'Gaming', 'image' => 'images/cat-gaming.jpg', 'alt' => 'Gaming hardware'],
                    ['target' => 'Sale', 'image' => 'images/promo-monitor.jpg', 'alt' => 'Discounted hardware'],
                ],
                'cards' => [
                    ['target' => 'PC Components', 'image' => 'images/promo-gpu.jpg', 'title' => 'Graphics cards', 'alt' => 'Graphics cards'],
                    ['target' => 'PC Components', 'image' => 'images/cat-arm.jpg', 'title' => 'Processors and boards', 'alt' => 'Processors and boards'],
                    ['target' => 'PC Components', 'image' => 'images/cat-office.webp', 'title' => 'Storage and memory', 'alt' => 'Storage and memory'],
                    ['target' => 'Gaming', 'image' => 'images/cat-gaming.jpg', 'title' => 'Gaming upgrades', 'alt' => 'Gaming upgrades'],
                    ['target' => 'Accessories', 'image' => 'images/cat-cables.jpg', 'title' => 'Cables and adapters', 'alt' => 'Cables and adapters'],
                    ['target' => 'Sale', 'image' => 'images/promo-monitor.jpg', 'title' => 'Deals', 'alt' => 'Deals'],
                ],
            ],
            'Monitors' => [
                'slides' => [
                    ['target' => 'Monitors', 'image' => 'images/promo-monitor.jpg', 'alt' => 'Monitors'],
                    ['target' => 'Gaming', 'image' => 'images/cat-gaming.jpg', 'alt' => 'Gaming displays'],
                    ['target' => 'Sale', 'image' => 'images/cat-imac.jpg', 'alt' => 'Premium displays'],
                ],
                'cards' => [
                    ['target' => 'Monitors', 'image' => 'images/promo-monitor.jpg', 'title' => 'Office monitors', 'alt' => 'Office monitors'],
                    ['target' => 'Gaming', 'image' => 'images/cat-gaming.jpg', 'title' => 'Gaming monitors', 'alt' => 'Gaming monitors'],
                    ['target' => 'Monitors', 'image' => 'images/cat-imac.jpg', 'title' => 'Creative displays', 'alt' => 'Creative displays'],
                    ['target' => 'Accessories', 'image' => 'images/cat-arm.jpg', 'title' => 'Monitor arms', 'alt' => 'Monitor arms'],
                ],
            ],
            'TV&Audio' => [
                'slides' => [
                    ['target' => 'TV&Audio', 'image' => 'images/promo-tablet.jpg', 'alt' => 'TV and audio devices'],
                    ['target' => 'Sale', 'image' => 'images/banner-main.jpg', 'alt' => 'Entertainment deals'],
                    ['target' => 'Accessories', 'image' => 'images/cat-cables.jpg', 'alt' => 'Audio and TV cables'],
                ],
                'cards' => [
                    ['target' => 'TV&Audio', 'image' => 'images/promo-tablet.jpg', 'title' => 'Smart TVs', 'alt' => 'Smart TVs'],
                    ['target' => 'TV&Audio', 'image' => 'images/banner-main.jpg', 'title' => 'Sound systems', 'alt' => 'Sound systems'],
                    ['target' => 'Accessories', 'image' => 'images/cat-cables.jpg', 'title' => 'HDMI and audio cables', 'alt' => 'HDMI and audio cables'],
                    ['target' => 'Sale', 'image' => 'images/promo-monitor.jpg', 'title' => 'Best deals', 'alt' => 'Best deals'],
                ],
            ],
            'Gaming' => [
                'slides' => [
                    ['target' => 'Gaming', 'image' => 'images/cat-gaming.jpg', 'alt' => 'Gaming hardware'],
                    ['target' => 'PC Components', 'image' => 'images/promo-gpu.jpg', 'alt' => 'PC gaming components'],
                    ['target' => 'Monitors', 'image' => 'images/promo-monitor.jpg', 'alt' => 'Gaming displays'],
                ],
                'cards' => [
                    ['target' => 'Gaming', 'image' => 'images/cat-gaming.jpg', 'title' => 'Gaming laptops', 'alt' => 'Gaming laptops'],
                    ['target' => 'PC Components', 'image' => 'images/promo-gpu.jpg', 'title' => 'Graphics power', 'alt' => 'Graphics power'],
                    ['target' => 'Monitors', 'image' => 'images/promo-monitor.jpg', 'title' => 'High refresh displays', 'alt' => 'High refresh displays'],
                    ['target' => 'Accessories', 'image' => 'images/cat-acc.jpg', 'title' => 'Gaming accessories', 'alt' => 'Gaming accessories'],
                ],
            ],
            'Accessories' => [
                'slides' => [
                    ['target' => 'Accessories', 'image' => 'images/cat-acc.jpg', 'alt' => 'Accessories'],
                    ['target' => 'Accessories', 'image' => 'images/cat-cables.jpg', 'alt' => 'Cables'],
                    ['target' => 'Accessories', 'image' => 'images/cat-chargers.jpg', 'alt' => 'Chargers'],
                ],
                'cards' => [
                    ['target' => 'Accessories', 'image' => 'images/cat-acc.jpg', 'title' => 'Everyday accessories', 'alt' => 'Everyday accessories'],
                    ['target' => 'Accessories', 'image' => 'images/cat-cables.jpg', 'title' => 'Cables', 'alt' => 'Cables'],
                    ['target' => 'Accessories', 'image' => 'images/cat-chargers.jpg', 'title' => 'Chargers', 'alt' => 'Chargers'],
                    ['target' => 'Accessories', 'image' => 'images/cat-arm.jpg', 'title' => 'Workspace gear', 'alt' => 'Workspace gear'],
                ],
            ],
        ];
    }
}
