<?php

namespace Database\Seeders;

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
            "password" => bcrypt('^6Adaut9^'),
            "role" => "admin"
        ]);
        $user = User::factory()->create([
            "email" => "user@gmail.com",
            "password" => bcrypt('^6Adaut9^'),
        ]);
    }
}
