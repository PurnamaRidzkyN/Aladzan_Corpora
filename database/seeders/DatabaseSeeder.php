<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Shop;
use App\Models\Admin;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Rating;
use App\Models\Address;
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
         
        Plan::create([
            'name' => 'Standard',
            'description' => 'Dapat belanja saja',
            'price' => 0, 
            'currency' => 'IDR',
            'duration_days' => 0,
        ]);
        Plan::create([
            'name' => 'Pro',
            'description' => 'Dapat mempunyai link komunitas, belanja, dan video pembelajaran cara berjualan',
            'price' => 150000, 
            'currency' => 'IDR',
            'duration_days' => 00, 
        ]);

        Admin::create([
            'name' => 'adzani ksw',
            'email' => 'adzanikusumantapraja.92@gmail.com',
            'password' => bcrypt('adzanikusumantapraja'),
            'email_verified_at' => now(),
        ]);
        Admin::create([
            'name' => 'Purnama',
            'email' => 'purnamanugraha492@gmail.com',
            'password' => bcrypt('purnamanugraha'),
            'email_verified_at' => now(),
        ]);
        Reseller::create([
            'name' => 'Contoh Reseller',
            'email' => 'himadatsuki@gmail.com',
            'password' => bcrypt('password123'),
            'pfp_path' => 'default_wxli5k.jpg',
            'phone' => '081234567890',
            'email_verified_at' => now(),
            'plan_id' => 1,

        ]);

        // 1. Buat Toko
        $shop = Shop::create([
            'name' => 'Toko Andalan',
            'description' => 'Toko terpercaya dengan berbagai produk unggulan.',
            'zipcode' => '40973',
            'city' => 'Bandung',
            'sub_district_id' => '5087',
        ]);

        // 2. Produk tanpa harga langsung
        $productData = [
            [
                'name' => 'Baju Katun',
                'variants' => [['name' => 'M', 'price' => 120000], ['name' => 'L', 'price' => 125000]],
                'weight' => 12,
            ],
            [
                'name' => 'Celana Jeans',
                'variants' => [['name' => '32', 'price' => 180000], ['name' => '34', 'price' => 185000]],
                'weight' => 12,
            ],
            [
                'name' => 'Sepatu Sneakers',
                'variants' => [['name' => '40', 'price' => 250000], ['name' => '42', 'price' => 255000]],
                'weight' => 12,
            ],
            [
                'name' => 'Topi Keren',
                'variants' => [['name' => 'Hitam', 'price' => 80000]],
                'weight' => 12,
            ],
            [
                'name' => 'Tas Ransel',
                'variants' => [['name' => 'Biru', 'price' => 200000]],
                'weight' => 12,
            ],
        ];

        $products = collect($productData)->map(function ($item) use ($shop) {
            $product = Product::create([
                'name' => $item['name'],
                'description' => 'Deskripsi produk ' . $item['name'],
                'weight' => $item['weight'],
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
        Address::create([
            'reseller_id' => 1,
            'recipient_name' => 'Andi Wijaya',
            'phone_number' => '081234567890',
            'province' => 'Jawa Barat',
            'city' => 'Bandung',
            'district' => 'Ciwidey',
            'sub_district' => 'Ciwidey',
            'neighborhood' => 'RT 02',
            'hamlet' => 'RW 01',
            'village' => 'Kampung Hijau',
            'zipcode' => '40973',
            'address_detail' => 'Jl. kehutanan',
            'sub_district_id' => '5087',
        ]);
    }
}
