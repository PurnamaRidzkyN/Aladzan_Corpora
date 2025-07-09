<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Reseller;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'email_verified_at' => now(),
        ]);
    }
}
