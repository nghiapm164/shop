<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            [
                'name' => 'Admin Tổng',
                'email' => 'admin@shop.local',
                'phone' => '0909000001',
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Nhân sự Kho',
                'email' => 'staff.kho@shop.local',
                'phone' => '0909000002',
                'role' => 'staff',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Nhân sự CSKH',
                'email' => 'staff.cskh@shop.local',
                'phone' => '0909000003',
                'role' => 'staff',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Khách hàng A',
                'email' => 'customer.a@shop.local',
                'phone' => '0909000010',
                'role' => 'customer',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Khách hàng B',
                'email' => 'customer.b@shop.local',
                'phone' => '0909000011',
                'role' => 'customer',
                'is_active' => true,
                'email_verified_at' => null,
            ],
        ];

        foreach ($accounts as $account) {
            $user = User::updateOrCreate(
                ['email' => $account['email']],
                array_merge($account, ['password' => Hash::make('12345678')])
            );

            if (method_exists($user, 'syncRoles')) {
                $user->syncRoles([$account['role']]);
            }
        }
    }
}
