<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\Admin;
use App\Models\Product;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Reseller;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Contoh Reseller',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);
        Reseller::create([
            'name' => 'Contoh Reseller',
            'email' => 'reseller@gmail.com',
            'password' => bcrypt('password123'),
            'pfp_path' => 'default.png',
            'phone' => '081234567890',
            'email_verified_at' => now(),
        ]);

        Shop::create([
            'name' => 'Toko Sakura',
            'description' => 'Minuman rasa matcha manis dan creamy',
        ]);
        Product::create([
            'name' => 'Matcha Latte',
            'description' => 'Minuman rasa matcha manis dan creamy',
            'price' => 20000,
            'shop_id' => 1,
        ]);
        Product::create([
            'name' => 'Kue Mochi',
            'description' => 'Minuman rasa matcha manis dan creamy',
            'price' => 15000,
            'shop_id' => 1,
        ]);
        Product::create([
            'name' => 'Onigiri',
            'description' => 'Minuman rasa matcha manis dan creamy',
            'price' => 12000,
            'shop_id' => 1,
        ]);
    }
}
