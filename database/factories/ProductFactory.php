<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
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
            'name' => $this->faker->name,
            'image' => $this->faker->imageUrl(),
            'price' => $this->faker->randomNumber(5, true),
            'description' => $this->faker->paragraph(),
            'stock' => $this->faker->randomNumber(2, true),
            'category_id' => Category::factory()
        ];
    }
}
