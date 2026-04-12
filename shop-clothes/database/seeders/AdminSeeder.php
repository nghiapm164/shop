<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminEmail = env('ADMIN_EMAIL', 'admin@sportswear.shop');
        $adminPassword = env('ADMIN_PASSWORD', 'password123');
        $adminPhone = env('ADMIN_PHONE', '+8412345678900');

        // Check if admin already exists
        if (User::where('email', $adminEmail)->exists()) {
            $this->command->info('Admin user already exists.');
            return;
        }

        // Create super_admin user
        $admin = User::create([
            'name' => 'Super Administrator',
            'email' => $adminEmail,
            'phone' => $adminPhone,
            'password' => Hash::make($adminPassword),
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);

        // Assign super_admin role
        $admin->assignRole('super_admin');

        $this->command->info("Super admin user created with email: {$adminEmail}");
    }
}
