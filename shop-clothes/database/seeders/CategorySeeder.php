<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $roots = [
            ['name' => 'Áo thể thao', 'image' => 'https://picsum.photos/seed/ao/400/500', 'children' => ['Áo thun tập gym', 'Áo chạy bộ', 'Áo khoác thể thao']],
            ['name' => 'Quần thể thao', 'image' => 'https://picsum.photos/seed/quan/400/500', 'children' => ['Quần short tập', 'Quần jogger', 'Quần nỉ']],
            ['name' => 'Giày thể thao', 'image' => 'https://picsum.photos/seed/giay/400/500', 'children' => ['Giày chạy bộ', 'Giày tập gym', 'Giày bóng rổ']],
            ['name' => 'Phụ kiện', 'image' => 'https://picsum.photos/seed/phukien/400/500', 'children' => ['Nón thể thao', 'Túi tập gym', 'Băng cổ tay']],
            ['name' => 'Đồ lót thể thao', 'image' => 'https://picsum.photos/seed/dolot/400/500', 'children' => ['Quần lót thể thao', 'Tất/Vớ', 'Khăn tập']],
            ['name' => 'Thiết bị tập', 'image' => 'https://picsum.photos/seed/thietbi/400/500', 'children' => ['Tạ tay', 'Dây nhảy', 'Thảm yoga']],
        ];

        $order = 1;
        foreach ($roots as $root) {
            $parent = Category::updateOrCreate(
                ['slug' => Str::slug($root['name'])],
                [
                    'name' => $root['name'],
                    'description' => 'Danh mục ' . $root['name'],
                    'image' => $root['image'] ?? null,
                    'parent_id' => null,
                    'sort_order' => $order++,
                    'is_active' => true,
                ]
            );

            $childOrder = 1;
            foreach ($root['children'] as $childName) {
                Category::updateOrCreate(
                    ['slug' => Str::slug($childName)],
                    [
                        'name' => $childName,
                        'description' => 'Danh mục con ' . $childName,
                        'parent_id' => $parent->id,
                        'sort_order' => $childOrder++,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
