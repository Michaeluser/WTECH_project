<?php

namespace Database\Factories;

use App\Models\CatalogItem;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CatalogItem>
 */
class CatalogItemFactory extends Factory
{
    protected $model = CatalogItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'target_category_id' => Category::factory(),
            'kind' => fake()->randomElement(['slide', 'card']),
            'title' => fake()->words(2, true),
            'image_path' => 'images/banner-main.jpg',
            'alt_text' => fake()->sentence(3),
            'sort_order' => fake()->numberBetween(1, 8),
        ];
    }
}
