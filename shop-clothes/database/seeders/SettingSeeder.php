<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'store_name', 'label' => 'Tên cửa hàng', 'group' => 'general', 'type' => 'text', 'value' => 'SportWear Shop', 'sort_order' => 1],
            ['key' => 'store_email', 'label' => 'Email liên hệ', 'group' => 'general', 'type' => 'text', 'value' => 'support@sportwear.shop', 'sort_order' => 2],
            ['key' => 'store_phone', 'label' => 'Số điện thoại', 'group' => 'general', 'type' => 'text', 'value' => '0123 456 789', 'sort_order' => 3],
            ['key' => 'store_address', 'label' => 'Địa chỉ cửa hàng', 'group' => 'general', 'type' => 'textarea', 'value' => '123 Đường Thể Thao, Quận 1, TP.HCM', 'sort_order' => 4],

            ['key' => 'shipping_free_min', 'label' => 'Ngưỡng miễn phí vận chuyển', 'group' => 'shipping', 'type' => 'text', 'value' => '299000', 'sort_order' => 1],
            ['key' => 'shipping_default_fee', 'label' => 'Phí vận chuyển mặc định', 'group' => 'shipping', 'type' => 'text', 'value' => '30000', 'sort_order' => 2],

            ['key' => 'maintenance_mode', 'label' => 'Chế độ bảo trì', 'group' => 'system', 'type' => 'boolean', 'value' => '0', 'sort_order' => 1],
            ['key' => 'enable_reviews', 'label' => 'Bật đánh giá sản phẩm', 'group' => 'system', 'type' => 'boolean', 'value' => '1', 'sort_order' => 2],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting + ['is_public' => false]
            );
        }
    }
}
