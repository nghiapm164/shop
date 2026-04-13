<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call seeders
        $this->call([
            SizeSeeder::class,
            ColorSeeder::class,
            RolePermissionSeeder::class,
            AdminSeeder::class,
            AccountSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            CouponSeeder::class,
            BannerSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
