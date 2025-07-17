<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\Admin;
use App\Models\Rating;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Product;
use App\Models\Category;
use App\Models\Reseller;
use Illuminate\Support\Str;
use App\Models\ProductVariant;
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

        // 1. Shops
        // 1. Buat Toko
        $shop = Shop::create([
            'name' => 'Toko Andalan',
            'description' => 'Toko terpercaya dengan berbagai produk unggulan.',
        ]);

        // 2. Produk tanpa harga langsung
        $productData = [['name' => 'Baju Katun', 'variants' => [['name' => 'M', 'price' => 120000], ['name' => 'L', 'price' => 125000]]], ['name' => 'Celana Jeans', 'variants' => [['name' => '32', 'price' => 180000], ['name' => '34', 'price' => 185000]]], ['name' => 'Sepatu Sneakers', 'variants' => [['name' => '40', 'price' => 250000], ['name' => '42', 'price' => 255000]]], ['name' => 'Topi Keren', 'variants' => [['name' => 'Hitam', 'price' => 80000]]], ['name' => 'Tas Ransel', 'variants' => [['name' => 'Biru', 'price' => 200000]]]];

        $products = collect($productData)->map(function ($item) use ($shop) {
            $product = Product::create([
                'name' => $item['name'],
                'description' => 'Deskripsi produk ' . $item['name'],
                'shop_id' => $shop->id,
            ]);

            // Buat Variannya
            foreach ($item['variants'] as $variant) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => $variant['name'],
                    'price' => $variant['price'],
                ]);
            }

            return $product;
        });

        // 3. Kategori
        $categories = collect(['Fashion', 'Aksesoris', 'Sepatu', 'Tas', 'Promo'])->map(function ($name) {
            return Category::create(['name' => $name]);
        });

        // 4. Hubungkan kategori (pivot)
        $products->each(function ($product, $index) use ($categories) {
            $product->categories()->attach($categories[$index % $categories->count()]);
        });

        // 5. Buat Rating Dummy
        foreach ($products as $product) {
            Rating::create([
                'product_id' => $product->id,
                'reseller_id' => 1,
                'rating' => rand(4, 5),
                'comment' => 'Produk bagus dan sesuai harapan!',
            ]);
        }
    }
}
