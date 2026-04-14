<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $productName = fake()->unique()->words(rand(3, 5), true);

        return [
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'name' => Str::title($productName),
            'slug' => Str::slug($productName) . '-' . fake()->unique()->numberBetween(100, 999),
            'description' => fake()->paragraphs(2, true),
            'price' => fake()->randomFloat(2, 399, 2499),
            'color' => fake()->randomElement(['Black', 'Silver', 'Gray', 'Blue', 'White']),
            'ram_gb' => fake()->randomElement([8, 16, 32]),
            'stock' => fake()->numberBetween(3, 40),
            'image_path' => fake()->randomElement([
                'images/product-laptop1.jpg',
                'images/product-laptop2.jpg',
                'images/product-laptop3.jpg',
                'images/product-laptop4.jpg',
                'images/product-laptop5.jpg',
                'images/product-laptop6.jpg',
                'images/product-laptop7.jpg',
            ]),
            'is_featured' => false,
        ];
    }
}
