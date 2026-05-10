<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\CatalogItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
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
        $roles = $this->createRoles();

        User::query()->updateOrCreate(['email' => 'admin@technodom.sk'], User::factory()->raw([
            'first_name' => 'Admin',
            'last_name' => 'Staff',
            'email' => 'admin@technodom.sk',
        ]));

        User::query()->updateOrCreate(['email' => 'test@example.com'], User::factory()->raw([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
        ]));

        $this->assignUserRoles($roles);

        $categories = $this->createCategories();
        $brands = $this->createBrands();

        $this->createProducts($categories, $brands);
        $this->createCatalogItems($categories);
    }

    private function createRoles(): array
    {
        $roles = [];

        foreach ([
            'admin' => 'Administrator with access to the admin dashboard.',
            'customer' => 'Regular customer account.',
        ] as $roleName => $description) {
            $role = Role::query()->updateOrCreate(
                ['role' => $roleName],
                ['description' => $description]
            );

            $roles[$roleName] = $role;
        }

        return $roles;
    }

    private function assignUserRoles(array $roles): void
    {
        $adminUser = User::query()->where('email', 'admin@technodom.sk')->first();
        $testUser = User::query()->where('email', 'test@example.com')->first();

        if ($adminUser !== null) {
            $adminUser->roles()->sync([
                $roles['customer']->id,
                $roles['admin']->id,
            ]);
        }

        if ($testUser !== null) {
            $testUser->roles()->sync([$roles['customer']->id]);
        }
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
                'description' => $productData['name'].' is a curated demo product for the first kontrolny bod. It gives us real data for category browsing, search, sorting, filtering, and cart scenarios.',
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
                'name' => 'PC Components',
                'slug' => 'pc-components',
                'description' => 'Core hardware for building, upgrading, and tuning desktop computers.',
                'catalog_mode' => 'landing',
                'catalog_slider_caption' => 'Upgrade your setup',
                'catalog_advice_title' => 'A piece of advice from technodom.sk',
                'catalog_advice_subtitle' => 'Which component should you upgrade first?',
                'catalog_advice_text' => 'The best upgrade depends on your bottleneck. Slow boot and load times usually point to storage, frame-rate issues often point to the GPU, and poor multitasking usually points to RAM or CPU limits. Start with the part that improves your real daily workload the most.',
                'nav_order' => 2,
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
                'name' => 'Gaming',
                'slug' => 'gaming',
                'description' => 'Gaming-focused hardware and accessories for performance and immersion.',
                'catalog_mode' => 'landing',
                'catalog_slider_caption' => 'Built for performance',
                'catalog_advice_title' => 'A piece of advice from technodom.sk',
                'catalog_advice_subtitle' => 'How to choose gaming hardware without overspending?',
                'catalog_advice_text' => 'Start from the games you actually play and the resolution you want to target. It is better to build a balanced setup with a sensible GPU, enough RAM, and a good display than to overspend on one top-end part while the rest of the system holds it back.',
                'nav_order' => 3,
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
            'Intel',
            'AMD',
            'Kingston',
            'Corsair',
        ];
    }

    private function productData(): array
    {
        return [
            ['category' => 'Laptops', 'brand' => 'Lenovo', 'name' => 'Lenovo ThinkBook 14 G6', 'price' => 749.00, 'color' => 'Gray', 'ram_gb' => 16, 'stock' => 9, 'image_path' => 'images/product-laptop1.jpg', 'featured' => true],
            ['category' => 'Laptops', 'brand' => 'HP', 'name' => 'HP ProBook 450', 'price' => 899.00, 'color' => 'Silver', 'ram_gb' => 16, 'stock' => 6, 'image_path' => 'images/laptop_hp_elitebook_silver.png', 'featured' => false],
            ['category' => 'Laptops', 'brand' => 'Dell', 'name' => 'Dell Latitude 5450', 'price' => 1049.00, 'color' => 'Black', 'ram_gb' => 16, 'stock' => 5, 'image_path' => 'images/laptop_dell_latitude_black.png', 'featured' => false],
            ['category' => 'Laptops', 'brand' => 'ASUS', 'name' => 'ASUS ExpertBook B1', 'price' => 639.00, 'color' => 'Blue', 'ram_gb' => 8, 'stock' => 12, 'image_path' => 'images/laptop_asus_expertbook_blue.png', 'featured' => false],
            ['category' => 'Laptops', 'brand' => 'Apple', 'name' => 'MacBook Air 13 M4', 'price' => 1199.00, 'color' => 'Silver', 'ram_gb' => 16, 'stock' => 8, 'image_path' => 'images/product-5.jpg', 'featured' => true],
            ['category' => 'Laptops', 'brand' => 'Lenovo', 'name' => 'Lenovo IdeaPad Slim 5', 'price' => 829.00, 'color' => 'Blue', 'ram_gb' => 16, 'stock' => 10, 'image_path' => 'images/laptop_lenovo_ideapad_slim.png', 'featured' => false],
            ['category' => 'Laptops', 'brand' => 'HP', 'name' => 'HP EliteBook 840', 'price' => 1149.00, 'color' => 'Silver', 'ram_gb' => 32, 'stock' => 4, 'image_path' => 'images/laptop_hp_elitebook_silver.png', 'featured' => false],
            ['category' => 'Laptops', 'brand' => 'Dell', 'name' => 'Dell Inspiron 14 Business', 'price' => 789.00, 'color' => 'Gray', 'ram_gb' => 8, 'stock' => 11, 'image_path' => 'images/laptop_dell_inspiron_gray.png', 'featured' => false],
            ['category' => 'Laptops', 'brand' => 'ASUS', 'name' => 'ASUS VivoBook 15', 'price' => 699.00, 'color' => 'Black', 'ram_gb' => 16, 'stock' => 13, 'image_path' => 'images/laptop_asus_vivobook_black.png', 'featured' => false],
            ['category' => 'Laptops', 'brand' => 'Lenovo', 'name' => 'Lenovo Yoga 7', 'price' => 1249.00, 'color' => 'Gray', 'ram_gb' => 16, 'stock' => 5, 'image_path' => 'images/laptop_lenovo_yoga_7.png', 'featured' => false],
            ['category' => 'Laptops', 'brand' => 'HP', 'name' => 'HP Pavilion 15', 'price' => 759.00, 'color' => 'Blue', 'ram_gb' => 8, 'stock' => 9, 'image_path' => 'images/laptop_hp_pavilion_blue.png', 'featured' => false],
            ['category' => 'Laptops', 'brand' => 'Dell', 'name' => 'Dell Vostro 15', 'price' => 839.00, 'color' => 'Gray', 'ram_gb' => 16, 'stock' => 8, 'image_path' => 'images/laptop_dell_inspiron_gray.png', 'featured' => false],
            ['category' => 'Laptops', 'brand' => 'ASUS', 'name' => 'ASUS ExpertBook P1', 'price' => 779.00, 'color' => 'Blue', 'ram_gb' => 16, 'stock' => 10, 'image_path' => 'images/laptop_asus_expertbook_blue.png', 'featured' => false],
            ['category' => 'Laptops', 'brand' => 'HP', 'name' => 'HP 250 G10', 'price' => 729.00, 'color' => 'Silver', 'ram_gb' => 8, 'stock' => 9, 'image_path' => 'images/laptop_hp_elitebook_silver.png', 'featured' => false],
            ['category' => 'Laptops', 'brand' => 'Lenovo', 'name' => 'Lenovo ThinkPad E14', 'price' => 929.00, 'color' => 'Black', 'ram_gb' => 16, 'stock' => 7, 'image_path' => 'images/laptop_lenovo_v15_g4.png', 'featured' => false],
            ['category' => 'Laptops', 'brand' => 'Dell', 'name' => 'Dell XPS 13', 'price' => 1399.00, 'color' => 'Silver', 'ram_gb' => 16, 'stock' => 3, 'image_path' => 'images/product-laptop4.jpg', 'featured' => true],
            ['category' => 'Laptops', 'brand' => 'ASUS', 'name' => 'ASUS Zenbook 14 OLED', 'price' => 1099.00, 'color' => 'Black', 'ram_gb' => 16, 'stock' => 7, 'image_path' => 'images/laptop_asus_zenbook_black.png', 'featured' => false],
            ['category' => 'Laptops', 'brand' => 'Apple', 'name' => 'MacBook Pro 14 M4', 'price' => 1999.00, 'color' => 'Gray', 'ram_gb' => 32, 'stock' => 4, 'image_path' => 'images/product-laptop6.jpg', 'featured' => true],
            ['category' => 'Laptops', 'brand' => 'Lenovo', 'name' => 'Lenovo V15 G4', 'price' => 719.00, 'color' => 'Black', 'ram_gb' => 16, 'stock' => 12, 'image_path' => 'images/laptop_lenovo_v15_g4.png', 'featured' => false],
            ['category' => 'Laptops', 'brand' => 'Apple', 'name' => 'MacBook Air 15 M4', 'price' => 1499.00, 'color' => 'Silver', 'ram_gb' => 16, 'stock' => 6, 'image_path' => 'images/product-5.jpg', 'featured' => false],
            ['category' => 'Laptops', 'brand' => 'Lenovo', 'name' => 'Lenovo IdeaPad Slim 5x', 'price' => 999.00, 'color' => 'Gray', 'ram_gb' => 16, 'stock' => 7, 'image_path' => 'images/laptop_lenovo_ideapad_slim.png', 'featured' => false],
            ['category' => 'Laptops', 'brand' => 'ASUS', 'name' => 'ASUS Vivobook S 15 Snapdragon', 'price' => 1099.00, 'color' => 'Black', 'ram_gb' => 16, 'stock' => 5, 'image_path' => 'images/laptop_asus_vivobook_black.png', 'featured' => false],
            ['category' => 'Laptops', 'brand' => 'HP', 'name' => 'HP OmniBook X 14', 'price' => 1199.00, 'color' => 'Blue', 'ram_gb' => 16, 'stock' => 4, 'image_path' => 'images/laptop_hp_pavilion_blue.png', 'featured' => false],
            ['category' => 'PC Components', 'brand' => 'ASUS', 'name' => 'ASUS GeForce RTX 5070 Dual', 'price' => 729.00, 'color' => 'Black', 'ram_gb' => 12, 'stock' => 6, 'image_path' => 'images/asus_geforce_rtx_5070dual.png', 'featured' => true],
            ['category' => 'PC Components', 'brand' => 'MSI', 'name' => 'MSI GeForce RTX 5060 Ventus 2X', 'price' => 599.00, 'color' => 'Black', 'ram_gb' => 8, 'stock' => 9, 'image_path' => 'images/msi_geforce_rtx_560_ventus2x.png', 'featured' => false],
            ['category' => 'PC Components', 'brand' => 'ASUS', 'name' => 'ASUS Radeon RX 7800 XT Dual', 'price' => 649.00, 'color' => 'Black', 'ram_gb' => 16, 'stock' => 5, 'image_path' => 'images/asus_radeon_rx_7800xt_dual.png', 'featured' => false],
            ['category' => 'PC Components', 'brand' => 'MSI', 'name' => 'MSI MAG B760 Tomahawk', 'price' => 229.00, 'color' => 'Black', 'ram_gb' => 16, 'stock' => 12, 'image_path' => 'images/msi_mag_b760_tomahawk.png', 'featured' => false],
            ['category' => 'PC Components', 'brand' => 'Intel', 'name' => 'Intel Core i7-14700K', 'price' => 429.00, 'color' => 'Silver', 'ram_gb' => 20, 'stock' => 7, 'image_path' => 'images/intelcore_i7_14700k.png', 'featured' => false],
            ['category' => 'PC Components', 'brand' => 'AMD', 'name' => 'AMD Ryzen 7 9700X', 'price' => 379.00, 'color' => 'Silver', 'ram_gb' => 16, 'stock' => 8, 'image_path' => 'images/amd_ryzen_7_9700X.png', 'featured' => false],
            ['category' => 'PC Components', 'brand' => 'ASUS', 'name' => 'ASUS Prime B650-Plus', 'price' => 199.00, 'color' => 'Black', 'ram_gb' => 16, 'stock' => 10, 'image_path' => 'images/asus_prime_b650_plus.png', 'featured' => false],
            ['category' => 'PC Components', 'brand' => 'Dell', 'name' => 'Dell 1 TB NVMe SSD', 'price' => 119.00, 'color' => 'Black', 'ram_gb' => 8, 'stock' => 18, 'image_path' => 'images/dell_1b_nvme_ssd.png', 'featured' => false],
            ['category' => 'PC Components', 'brand' => 'Samsung', 'name' => 'Samsung 990 EVO 1 TB', 'price' => 139.00, 'color' => 'Black', 'ram_gb' => 8, 'stock' => 14, 'image_path' => 'images/samsung990_evo_1tb.png', 'featured' => false],
            ['category' => 'PC Components', 'brand' => 'Kingston', 'name' => 'Kingston Fury Beast 32 GB DDR5', 'price' => 129.00, 'color' => 'Black', 'ram_gb' => 32, 'stock' => 16, 'image_path' => 'images/kingston_fury_beats_32gb_ddr5.png', 'featured' => false],
            ['category' => 'PC Components', 'brand' => 'Corsair', 'name' => 'Corsair RM850e Power Supply', 'price' => 159.00, 'color' => 'Black', 'ram_gb' => 8, 'stock' => 10, 'image_path' => 'images/corsair_rm850e_powersupply.png', 'featured' => false],
            ['category' => 'PC Components', 'brand' => 'Logitech', 'name' => 'Logitech G502 X Plus', 'price' => 139.00, 'color' => 'Black', 'ram_gb' => 8, 'stock' => 20, 'image_path' => 'images/logitech_g502x_plus.png', 'featured' => false],
            ['category' => 'PC Components', 'brand' => 'MSI', 'name' => 'MSI MAG CoreLiquid 240R V2', 'price' => 109.00, 'color' => 'Black', 'ram_gb' => 8, 'stock' => 11, 'image_path' => 'images/msi_mag_coreliquid_240R_v2.png', 'featured' => false],
            ['category' => 'Monitors', 'brand' => 'Dell', 'name' => 'Dell UltraSharp 27', 'price' => 449.00, 'color' => 'Black', 'ram_gb' => 8, 'stock' => 15, 'image_path' => 'images/dell_ultrasharp_27.png', 'featured' => true],
            ['category' => 'Monitors', 'brand' => 'HP', 'name' => 'HP E24 G5 Monitor', 'price' => 219.00, 'color' => 'Black', 'ram_gb' => 8, 'stock' => 18, 'image_path' => 'images/hp_e24_g5_monitor.png', 'featured' => false],
            ['category' => 'Monitors', 'brand' => 'ASUS', 'name' => 'ASUS ProArt Display 27', 'price' => 589.00, 'color' => 'Black', 'ram_gb' => 16, 'stock' => 7, 'image_path' => 'images/asus_proart_display_27.png', 'featured' => false],
            ['category' => 'Monitors', 'brand' => 'Lenovo', 'name' => 'Lenovo ThinkVision T24', 'price' => 199.00, 'color' => 'Gray', 'ram_gb' => 8, 'stock' => 20, 'image_path' => 'images/lenovo_thinkvision_t24.png', 'featured' => false],
            ['category' => 'Monitors', 'brand' => 'Samsung', 'name' => 'Samsung Odyssey G5 27', 'price' => 319.00, 'color' => 'Black', 'ram_gb' => 8, 'stock' => 10, 'image_path' => 'images/samsung_odyssey_g5_27.png', 'featured' => false],
            ['category' => 'Monitors', 'brand' => 'LG', 'name' => 'LG UltraGear 27GR75Q', 'price' => 359.00, 'color' => 'Black', 'ram_gb' => 8, 'stock' => 8, 'image_path' => 'images/lg_ultragear_27gr75q.png', 'featured' => false],
            ['category' => 'Monitors', 'brand' => 'ASUS', 'name' => 'ASUS TUF VG27AQ3A', 'price' => 339.00, 'color' => 'Black', 'ram_gb' => 8, 'stock' => 9, 'image_path' => 'images/asus_tuf_vg27aq3a.png', 'featured' => false],
            ['category' => 'Monitors', 'brand' => 'Dell', 'name' => 'Dell UltraSharp U3223QE', 'price' => 899.00, 'color' => 'Black', 'ram_gb' => 16, 'stock' => 5, 'image_path' => 'images/dell_ultrasharp_27.png', 'featured' => false],
            ['category' => 'Monitors', 'brand' => 'LG', 'name' => 'LG Ergo 32UN880', 'price' => 699.00, 'color' => 'Black', 'ram_gb' => 16, 'stock' => 6, 'image_path' => 'images/lg_ergo_32un880.png', 'featured' => false],
            ['category' => 'Gaming', 'brand' => 'ASUS', 'name' => 'ASUS ROG Strix G16', 'price' => 1799.00, 'color' => 'Black', 'ram_gb' => 32, 'stock' => 5, 'image_path' => 'images/laptop_asus_rog_strix.png', 'featured' => true],
            ['category' => 'Gaming', 'brand' => 'Lenovo', 'name' => 'Lenovo Legion 5 Pro', 'price' => 1699.00, 'color' => 'Gray', 'ram_gb' => 32, 'stock' => 4, 'image_path' => 'images/laptop_lenovo_legion.png', 'featured' => false],
            ['category' => 'Gaming', 'brand' => 'HP', 'name' => 'HP Omen 16', 'price' => 1599.00, 'color' => 'Black', 'ram_gb' => 16, 'stock' => 6, 'image_path' => 'images/laptop_hp_omen_16.png', 'featured' => false],
            ['category' => 'Gaming', 'brand' => 'Sony', 'name' => 'Sony PlayStation 5 Slim', 'price' => 549.00, 'color' => 'White', 'ram_gb' => 16, 'stock' => 11, 'image_path' => 'images/sony_playstation_5_slim.png', 'featured' => true],
            ['category' => 'Gaming', 'brand' => 'ASUS', 'name' => 'ASUS ROG NUC', 'price' => 2199.00, 'color' => 'Black', 'ram_gb' => 32, 'stock' => 3, 'image_path' => 'images/asus_rog_nuc.png', 'featured' => false],
            ['category' => 'Gaming', 'brand' => 'MSI', 'name' => 'MSI Aegis RS2', 'price' => 1999.00, 'color' => 'Black', 'ram_gb' => 32, 'stock' => 4, 'image_path' => 'images/msi_aegis_rs2.png', 'featured' => false],
            ['category' => 'Gaming', 'brand' => 'Samsung', 'name' => 'Samsung Odyssey G5 27 Gaming', 'price' => 319.00, 'color' => 'Black', 'ram_gb' => 8, 'stock' => 10, 'image_path' => 'images/samsung_odyssey_g5_27.png', 'featured' => false],
            ['category' => 'Gaming', 'brand' => 'LG', 'name' => 'LG UltraGear 27GR75Q Gaming', 'price' => 359.00, 'color' => 'Black', 'ram_gb' => 8, 'stock' => 8, 'image_path' => 'images/lg_ultragear_27gr75q.png', 'featured' => false],
            ['category' => 'Gaming', 'brand' => 'ASUS', 'name' => 'ASUS TUF VG27AQ3A Gaming', 'price' => 339.00, 'color' => 'Black', 'ram_gb' => 8, 'stock' => 9, 'image_path' => 'images/asus_tuf_vg27aq3a.png', 'featured' => false],
            ['category' => 'Gaming', 'brand' => 'ASUS', 'name' => 'ASUS ROG Ally X', 'price' => 899.00, 'color' => 'Black', 'ram_gb' => 24, 'stock' => 7, 'image_path' => 'images/asus_rog_ally_x.png', 'featured' => false],
            ['category' => 'Gaming', 'brand' => 'Lenovo', 'name' => 'Lenovo Legion Go', 'price' => 799.00, 'color' => 'White', 'ram_gb' => 16, 'stock' => 6, 'image_path' => 'images/laptop_lenovo_legion.png', 'featured' => false],
            ['category' => 'Gaming', 'brand' => 'MSI', 'name' => 'MSI Claw 8 AI+', 'price' => 949.00, 'color' => 'Black', 'ram_gb' => 16, 'stock' => 5, 'image_path' => 'images/msi_claw_8_ai.png', 'featured' => false],
        ];
    }

    private function catalogItemData(): array
    {
        return [
            'Laptops' => [
                'slides' => [
                    ['target' => 'Laptops', 'image' => 'images/category-office_laptops.jpg', 'alt' => 'Office laptops'],
                    ['target' => 'Laptops', 'image' => 'images/laptop_asus_vivobook_black.png', 'alt' => 'ARM laptops'],
                    ['target' => 'Laptops', 'image' => 'images/product-5.jpg', 'alt' => 'MacBook'],
                ],
                'cards' => [
                    ['target' => 'Laptops', 'image' => 'images/category-office_laptops.jpg', 'title' => 'Office laptops', 'alt' => 'Office laptops'],
                    ['target' => 'Gaming', 'image' => 'images/promo_gaming_laptops.png', 'title' => 'Gaming laptops', 'alt' => 'Gaming laptops'],
                    ['target' => 'Laptops', 'image' => 'images/laptop_asus_vivobook_black.png', 'title' => 'ARM laptops', 'alt' => 'ARM laptops'],
                    ['target' => 'Laptops', 'image' => 'images/product-5.jpg', 'title' => 'MacBook', 'alt' => 'MacBook'],
                ],
            ],
            'PC Components' => [
                'slides' => [
                    ['target' => 'PC Components', 'image' => 'images/promo_graphic_cards.png', 'alt' => 'Graphics cards and components'],
                    ['target' => 'PC Components', 'image' => 'images/promo_graphic_cards.png', 'alt' => 'Storage and memory'],
                    ['target' => 'PC Components', 'image' => 'images/promo_graphic_cards.png', 'alt' => 'Gaming upgrades'],
                ],
                'cards' => [
                    ['target' => 'PC Components', 'image' => 'images/asus_geforce_rtx_5070dual.png', 'title' => 'Graphics cards', 'alt' => 'Graphics cards'],
                    ['target' => 'PC Components', 'image' => 'images/msi_mag_b760_tomahawk.png', 'title' => 'Processors and boards', 'alt' => 'Processors and boards'],
                    ['target' => 'PC Components', 'image' => 'images/samsung990_evo_1tb.png', 'title' => 'Storage and memory', 'alt' => 'Storage and memory'],
                    ['target' => 'PC Components', 'image' => 'images/logitech_g502x_plus.png', 'title' => 'Gaming upgrades', 'alt' => 'Gaming upgrades'],
                ],
            ],
            'Monitors' => [
                'slides' => [
                    ['target' => 'Monitors', 'image' => 'images/dell_ultrasharp_27.png', 'alt' => 'Office monitors'],
                    ['target' => 'Monitors', 'image' => 'images/promo_monitors.png', 'alt' => 'Gaming monitors'],
                    ['target' => 'Monitors', 'image' => 'images/promo_monitors.png', 'alt' => 'Creative displays'],
                ],
                'cards' => [
                    ['target' => 'Monitors', 'image' => 'images/dell_ultrasharp_27.png', 'title' => 'Office monitors', 'alt' => 'Office monitors'],
                    ['target' => 'Monitors', 'image' => 'images/samsung_odyssey_g5_27.png', 'title' => 'Gaming monitors', 'alt' => 'Gaming monitors'],
                    ['target' => 'Monitors', 'image' => 'images/asus_proart_display_27.png', 'title' => 'Creative displays', 'alt' => 'Creative displays'],
                ],
            ],
            'Gaming' => [
                'slides' => [
                    ['target' => 'Gaming', 'image' => 'images/laptop_asus_rog_strix.png', 'alt' => 'Gaming hardware'],
                    ['target' => 'PC Components', 'image' => 'images/promo_graphic_cards.png', 'alt' => 'PC gaming components'],
                    ['target' => 'Monitors', 'image' => 'images/promo_monitors.png', 'alt' => 'Gaming displays'],
                ],
                'cards' => [
                    ['target' => 'Gaming', 'image' => 'images/laptop_asus_rog_strix.png', 'title' => 'Gaming laptops', 'alt' => 'Gaming laptops'],
                    ['target' => 'Gaming', 'image' => 'images/sony_playstation_5_slim.png', 'title' => 'Graphics power', 'alt' => 'Graphics power'],
                    ['target' => 'Gaming', 'image' => 'images/samsung_odyssey_g5_27.png', 'title' => 'High refresh displays', 'alt' => 'High refresh displays'],
                    ['target' => 'Gaming', 'image' => 'images/asus_rog_ally_x.png', 'title' => 'Portable performance', 'alt' => 'Portable performance'],
                ],
            ],
        ];
    }
}
