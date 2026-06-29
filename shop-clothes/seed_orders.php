<?php
$products = App\Models\Product::with('variants')->get();
DB::table('order_items')->truncate(); // clear previous fake data
DB::table('orders')->updateOrInsert(['id' => 1], [
    'user_id' => 1, 
    'status' => App\Models\Order::STATUS_DELIVERED, 
    'total' => 1000000, 
    'payment_method' => 'cod', 
    'payment_status' => App\Models\Order::PAYMENT_STATUS_PAID,
    'shipping_address' => json_encode(['address' => 'Hanoi', 'phone' => '0987654321', 'name' => 'Nguyễn Văn A']),
    'created_at' => now(), 
    'updated_at' => now()
]);

foreach ($products as $p) {
    if (rand(0, 100) > 40 && $p->variants->count() > 0) {
        $variant = $p->variants->random();
        DB::table('order_items')->insert([
            'order_id' => 1,
            'product_variant_id' => $variant->id,
            'product_name' => $p->name,
            'size' => $variant->size->name ?? 'M',
            'color' => $variant->color->name ?? 'Đen',
            'price' => $p->price ?? 0,
            'quantity' => rand(10, 500),
            'subtotal' => ($p->price ?? 0) * 10,
            'created_at' => now()->subDays(rand(1, 30)),
            'updated_at' => now()
        ]);
    }
}
echo "Fake sales data generated successfully.\n";
