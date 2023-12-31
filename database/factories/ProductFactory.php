<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'SKU' => 'KN-' . fake()->numberBetween(0, 10000),
            'size' => fake()->randomElement(['2XL','M','3XL','XS','L','S','XL']),
            'photo_url' => fake()->imageUrl()
        ];
    }
}
