<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\TourPackage;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            "email" => "admin@adaut.com",
            "password" => bcrypt('password'),
            "role" => "admin"
        ]);
        $user = User::factory()->create([
            "email" => "user@gmail.com",
            "password" => bcrypt('password'),
        ]);
        Category::create([
            'name' => 'Makanan',
            'icon' => asset("storage/icon/kategori/cutlery.png")
        ]);
        Category::create([
            'name' => 'Kerajinan',
            'icon' => asset("storage/icon/kategori/craft.png")
        ]);
        Product::factory(1000)->create([
            'category_id' => Category::all()->random()->id,
            'image' => "https://themewagon.github.io/FoodMart/images/product-thumb-1.png"
        ]);
        Review::factory(20)->create();
    }
}