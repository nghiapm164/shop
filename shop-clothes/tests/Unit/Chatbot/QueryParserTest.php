<?php

namespace Tests\Unit\Chatbot;

use App\Services\Chatbot\QueryParser;
use PHPUnit\Framework\TestCase;

class QueryParserTest extends TestCase
{
    private function parse(string $message): array
    {
        return (new QueryParser())->parse($message);
    }

    public function test_demo_1_football_shirt_for_men(): void
    {
        $c = $this->parse('Tìm áo bóng đá nam.');

        $this->assertSame('ao', $c['product_type']);
        $this->assertSame('bong-da', $c['sport']);
        $this->assertSame('nam', $c['gender']);
        $this->assertTrue($c['wants_products']);
        $this->assertNull($c['price_max']);
    }

    public function test_demo_2_price_filter(): void
    {
        $c = $this->parse('Tìm cho tôi áo thể thao nam dưới 300k.');

        $this->assertSame('ao', $c['product_type']);
        $this->assertSame('nam', $c['gender']);
        $this->assertSame(300000, $c['price_max']);
        $this->assertNull($c['price_min']);
        $this->assertTrue($c['wants_products']);
    }

    public function test_demo_3_size_advice_only(): void
    {
        $c = $this->parse('Nam 1m75, 70kg nên mặc size gì?');

        $this->assertSame(175, $c['height_cm']);
        $this->assertSame(70, $c['weight_kg']);
        $this->assertSame('nam', $c['gender']);
        $this->assertTrue($c['wants_size_advice']);
        $this->assertFalse($c['wants_products']);
        $this->assertNull($c['size']);
    }

    public function test_demo_4_running_outfit_with_budget(): void
    {
        $c = $this->parse('Tôi cần một bộ đồ để chạy bộ khoảng 500k.');

        $this->assertSame('chay-bo', $c['sport']);
        $this->assertTrue($c['wants_outfit']);
        $this->assertSame(550000, $c['price_max']); // "khoảng" cho phép lệch 10%
        $this->assertSame('approx', $c['price_note']);
    }

    public function test_demo_5_multiple_conditions(): void
    {
        $c = $this->parse('Tôi là nam, cao 1m72 nặng 65kg, muốn mua đồ chạy bộ màu đen dưới 500k.');

        $this->assertSame('nam', $c['gender']);
        $this->assertSame(172, $c['height_cm']);
        $this->assertSame(65, $c['weight_kg']);
        $this->assertSame('chay-bo', $c['sport']);
        $this->assertSame(['Black'], $c['colors']);
        $this->assertSame(500000, $c['price_max']);
        $this->assertFalse($c['wants_outfit']);
        $this->assertTrue($c['wants_products']);
        $this->assertTrue($c['wants_size_advice']);
    }

    public function test_other_examples_from_requirements(): void
    {
        $c = $this->parse('Shop có áo bóng đá nam dưới 300 nghìn không?');
        $this->assertSame(['ao', 'bong-da', 'nam', 300000], [$c['product_type'], $c['sport'], $c['gender'], $c['price_max']]);

        $c = $this->parse('Có quần short thể thao màu đen không?');
        $this->assertSame('quan-short', $c['product_type']);
        $this->assertSame(['Black'], $c['colors']);

        $c = $this->parse('Tôi có 500 nghìn, tư vấn cho tôi một bộ đồ tập.');
        $this->assertTrue($c['wants_outfit']);
        $this->assertSame(500000, $c['price_max']);
        $this->assertSame('budget', $c['price_note']);
        $this->assertSame('tap-luyen', $c['purpose']);
    }

    public function test_unaccented_input(): void
    {
        $c = $this->parse('tim ao bong da nam duoi 300k mau den');

        $this->assertSame('ao', $c['product_type']);
        $this->assertSame('bong-da', $c['sport']);
        $this->assertSame('nam', $c['gender']);
        $this->assertSame(300000, $c['price_max']);
        $this->assertSame(['Black'], $c['colors']);
    }

    public function test_price_range_and_formats(): void
    {
        $c = $this->parse('quần chạy bộ từ 200k đến 400k');
        $this->assertSame([200000, 400000], [$c['price_min'], $c['price_max']]);
        $this->assertSame([], $c['colors'], '"đến" không được hiểu là màu "đen"');

        $c = $this->parse('ao gym 200-350k');
        $this->assertSame([200000, 350000], [$c['price_min'], $c['price_max']]);

        $c = $this->parse('áo khoác không quá 1tr2');
        $this->assertSame('ao-khoac', $c['product_type']);
        $this->assertSame(1200000, $c['price_max']);

        $c = $this->parse('giày trên 500.000đ');
        $this->assertSame(500000, $c['price_min']);
    }

    public function test_size_color_and_followup(): void
    {
        $c = $this->parse('Còn màu trắng size XL không?');

        $this->assertSame(['White'], $c['colors']);
        $this->assertSame('XL', $c['size']);
        $this->assertTrue($c['is_followup']);
    }

    public function test_faq_and_out_of_scope(): void
    {
        $this->assertContains('shipping', $this->parse('Phí ship bao nhiêu vậy shop?')['topics']);
        $this->assertContains('return', $this->parse('Chính sách đổi trả thế nào?')['topics']);

        $c = $this->parse('Thủ đô của Pháp là gì?');
        $this->assertFalse($c['wants_products']);
        $this->assertSame([], $c['topics']);
    }

    public function test_accessory_and_equipment_types(): void
    {
        $this->assertSame('tat', $this->parse('tất chạy bộ')['product_type']);
        $this->assertSame('chay-bo', $this->parse('tất chạy bộ')['sport']);
        $this->assertSame('tham', $this->parse('thảm yoga')['product_type']);
        $this->assertSame('yoga', $this->parse('thảm yoga')['sport']);
        $this->assertSame('ta', $this->parse('tạ tay 5kg')['product_type']);
        $this->assertSame('do-lot', $this->parse('quần lót nam')['product_type']);
        $this->assertSame('non', $this->parse('nón chạy bộ màu đen')['product_type']);
        $this->assertSame('tui', $this->parse('túi đựng giày bóng đá')['product_type']);

        // "tất cả", "tập tạ" không phải tên sản phẩm
        $this->assertSame('ao-khoac', $this->parse('cho xem tất cả áo khoác')['product_type']);
        $this->assertNotSame('ta', $this->parse('đồ tập tạ nam')['product_type']);
        // Không dấu: "tham gia" không phải "thảm"
        $this->assertNull($this->parse('toi muon tham gia giai chay')['product_type']);
    }

    public function test_shoe_sizes(): void
    {
        $c = $this->parse('giày chạy bộ nam size 42');
        $this->assertSame('giay', $c['product_type']);
        $this->assertSame('42', $c['size']);
        $this->assertNull($c['price_max']);

        $this->assertSame('40', $this->parse('giày đá bóng chân 40')['size']);
    }

    public function test_tricky_words_do_not_trigger_wrong_filters(): void
    {
        // "bán chạy" là sắp xếp, không phải môn chạy bộ; "quần vợt" là tennis, không phải loại "quần"
        $c = $this->parse('áo nào bán chạy nhất');
        $this->assertNull($c['sport']);
        $this->assertSame('popular', $c['sort']);

        $c = $this->parse('áo quần vợt nữ');
        $this->assertSame('tennis', $c['sport']);
        $this->assertSame('ao', $c['product_type']);
        $this->assertSame('nu', $c['gender']);

        $c = $this->parse('đồ bơi nam');
        $this->assertSame('Bơi lội', $c['unsupported_sport']);
    }
}
