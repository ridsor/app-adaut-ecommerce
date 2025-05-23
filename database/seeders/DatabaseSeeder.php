<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
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
            'icon' => "ikon/kategori/cutlery.png"
        ]);
        Category::create([
            'name' => 'Kerajinan',
            'icon' => "ikon/kategori/craft.png"
        ]);

        Product::factory(1000)->create();
        Review::factory(20)->create();
    }
}
