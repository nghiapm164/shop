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
            ['name' => 'Áo thể thao', 'children' => ['Áo thun tập gym', 'Áo chạy bộ', 'Áo khoác thể thao']],
            ['name' => 'Quần thể thao', 'children' => ['Quần short tập', 'Quần jogger', 'Quần nỉ']],
            ['name' => 'Phụ kiện', 'children' => ['Nón thể thao', 'Túi tập gym', 'Băng cổ tay']],
        ];

        $order = 1;
        foreach ($roots as $root) {
            $parent = Category::updateOrCreate(
                ['slug' => Str::slug($root['name'])],
                [
                    'name' => $root['name'],
                    'description' => 'Danh mục ' . $root['name'],
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
