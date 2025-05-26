<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

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
        $productImage = [
            'gambar/produk/kain tenun anteng.png',
            'gambar/produk/kain tenun bima.png',
            'gambar/produk/kain tenun khas lombok.png',
            'gambar/produk/kain tenun rang rang.png',
        ];

        return [
            'name' => $this->faker->name,
            'image' => Arr::random($productImage),
            'price' => $this->faker->randomNumber(5, true),
            'description' => $this->faker->paragraph(),
            'stock' => $this->faker->randomNumber(2, true),
            'weight' => 1000,
            'category_id' => Category::all()->random()->id
        ];
    }
}