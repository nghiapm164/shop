<?php

namespace Tests\Feature;

use App\Models\Product;
use Database\Seeders\BrandSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\ColorSeeder;
use Database\Seeders\SizeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Kiểm thử 5 kịch bản demo bắt buộc của chatbot (chế độ offline, không gọi Gemini).
 */
class ChatbotTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['chatbot.gemini.api_key' => null]);
        $this->seed([SizeSeeder::class, ColorSeeder::class, CategorySeeder::class, BrandSeeder::class, ProductSeeder::class]);
    }

    private function ask(string $message): array
    {
        return $this->postJson(route('chatbot.message'), ['message' => $message])
            ->assertOk()
            ->assertJsonStructure(['reply', 'products', 'outfits', 'links', 'analysis', 'suggestions', 'source'])
            ->json();
    }

    private function productsByIds(array $cards)
    {
        return Product::with('variants.size', 'variants.color')->findMany(array_column($cards, 'id'));
    }

    public function test_demo_1_find_mens_football_shirts(): void
    {
        $res = $this->ask('Tìm áo bóng đá nam.');

        $this->assertNotEmpty($res['products']);
        foreach ($this->productsByIds($res['products']) as $p) {
            $this->assertSame('bong-da', $p->sport);
            $this->assertContains($p->gender, ['nam', 'unisex']);
            $this->assertStringStartsWith('Áo', $p->name);
        }
        $this->assertStringContainsString('/products/', $res['products'][0]['url']);
        $this->assertSame('rules', $res['source']);
    }

    public function test_demo_2_price_filter(): void
    {
        $res = $this->ask('Tìm cho tôi áo thể thao nam dưới 300k.');

        $this->assertNotEmpty($res['products']);
        foreach ($res['products'] as $card) {
            $this->assertLessThanOrEqual(300000, $card['price']);
        }
    }

    public function test_demo_3_size_advice_from_size_chart(): void
    {
        $res = $this->ask('Nam 1m75, 70kg nên mặc size gì?');

        $this->assertStringContainsString('size L', $res['reply']);
        $this->assertStringContainsString('bảng size', $res['reply']);
        $this->assertSame([], $res['products']);
    }

    public function test_demo_4_running_outfit_within_budget(): void
    {
        $res = $this->ask('Tôi cần một bộ đồ để chạy bộ khoảng 500k.');

        $this->assertNotEmpty($res['outfits']);
        foreach ($res['outfits'] as $outfit) {
            $this->assertLessThanOrEqual(550000, $outfit['total']);
            $this->assertCount(2, $outfit['items']);
            foreach ($this->productsByIds($outfit['items']) as $p) {
                $this->assertSame('chay-bo', $p->sport);
            }
        }
    }

    public function test_demo_5_multiple_conditions(): void
    {
        $res = $this->ask('Tôi là nam, cao 1m72 nặng 65kg, muốn mua đồ chạy bộ màu đen dưới 500k.');

        $this->assertStringContainsString('size L', $res['reply']);
        $this->assertNotEmpty($res['products']);

        foreach ($this->productsByIds($res['products']) as $p) {
            $this->assertSame('chay-bo', $p->sport);
            $this->assertContains($p->gender, ['nam', 'unisex']);
            $this->assertLessThanOrEqual(500000, $p->sale_price ?? $p->price);
            $this->assertTrue(
                $p->variants->contains(fn ($v) => $v->stock_quantity > 0 && $v->size->code === 'L' && $v->color->name === 'Black'),
                "{$p->name} phải còn biến thể màu đen size L"
            );
        }
    }

    public function test_followup_question_reuses_previous_criteria(): void
    {
        $this->ask('Tìm áo bóng đá nam');
        $res = $this->ask('Còn màu trắng không?');

        $this->assertContains('Tiếp nối câu hỏi trước', $res['analysis']);
        foreach ($this->productsByIds($res['products']) as $p) {
            $this->assertSame('bong-da', $p->sport);
            $this->assertTrue($p->variants->contains(fn ($v) => $v->color->name === 'White'));
        }
    }

    public function test_does_not_invent_unavailable_products_or_answers(): void
    {
        $res = $this->ask('Shop có đồ bơi nam không?');
        $this->assertSame([], $res['products']);
        $this->assertStringContainsString('chưa kinh doanh', $res['reply']);

        $res = $this->ask('Thủ đô của Pháp là gì?');
        $this->assertSame([], $res['products']);
        $this->assertStringContainsString('chỉ hỗ trợ', $res['reply']);
    }

    public function test_seeder_creates_100_complete_products(): void
    {
        $products = Product::with('variants', 'images')->get();

        $this->assertCount(100, $products);
        foreach ($products as $p) {
            $this->assertNotNull($p->gender, $p->name);
            $this->assertNotNull($p->sport, $p->name);
            $this->assertNotNull($p->purpose, $p->name);
            $this->assertNotEmpty($p->description, $p->name);
            $this->assertTrue($p->variants->sum('stock_quantity') > 0, $p->name);
            $this->assertSame(1, $p->images->where('is_primary', true)->count(), $p->name);
        }
    }

    public function test_reseeding_keeps_variants_referenced_by_orders(): void
    {
        $variantIds = \App\Models\ProductVariant::orderBy('id')->pluck('id')->all();

        $this->seed(ProductSeeder::class);

        $this->assertSame(100, Product::count());
        $this->assertSame($variantIds, \App\Models\ProductVariant::orderBy('id')->pluck('id')->all());
    }

    public function test_shirt_query_excludes_jackets_and_shoe_query_ignores_letter_size(): void
    {
        $res = $this->ask('Tìm áo bóng đá nam.');
        foreach ($res['products'] as $card) {
            $this->assertStringNotContainsString('khoác', $card['name']);
        }

        $res = $this->ask('Nam cao 1m72 nặng 65kg cần giày chạy bộ');
        $this->assertNotEmpty($res['products']);
        foreach ($res['products'] as $card) {
            $this->assertStringStartsWith('Giày', $card['name']);
        }

        $res = $this->ask('giày chạy bộ nam size 42');
        $this->assertNotEmpty($res['products']);
        foreach ($res['products'] as $card) {
            $this->assertContains('42', $card['sizes']);
        }
    }

    public function test_cheaper_followup_after_outfits_returns_cheaper_outfits(): void
    {
        $first = $this->ask('Tôi cần một bộ đồ để chạy bộ khoảng 500k.');
        $cheapest = min(array_column($first['outfits'], 'total'));

        $res = $this->ask('Còn bộ nào rẻ hơn không?');
        foreach ($res['outfits'] as $outfit) {
            $this->assertLessThan($cheapest, $outfit['total']);
        }
    }

    public function test_uses_gemini_when_api_key_is_configured(): void
    {
        config(['chatbot.gemini.api_key' => 'test-key']);

        $extraction = [
            'gender' => 'nam', 'sport' => 'bong-da', 'product_type' => 'ao', 'colors' => [], 'topics' => [],
            'wants_products' => true, 'wants_outfit' => false, 'wants_size_advice' => false,
            'is_followup' => false, 'off_topic' => false,
        ];
        Http::fakeSequence()
            ->push(['candidates' => [['content' => ['parts' => [['text' => json_encode($extraction)]]]]]])
            ->push(['candidates' => [['content' => ['parts' => [['text' => 'Câu trả lời do Gemini viết lại']]]]]]);

        $res = $this->ask('cho mình xem mấy cái áo đá banh cho con trai');

        $this->assertSame('gemini', $res['source']);
        $this->assertSame('Câu trả lời do Gemini viết lại', $res['reply']);
        foreach ($this->productsByIds($res['products']) as $p) {
            $this->assertSame('bong-da', $p->sport);
        }
        Http::assertSentCount(2);
    }

    public function test_falls_back_to_rules_when_gemini_fails(): void
    {
        config(['chatbot.gemini.api_key' => 'test-key']);
        Http::fake(['*' => Http::response(['error' => 'quota exceeded'], 429)]);

        $res = $this->ask('Tìm áo bóng đá nam');

        $this->assertSame('rules', $res['source']);
        $this->assertNotEmpty($res['products']);
    }

    public function test_faq_answers_come_from_store_data(): void
    {
        $res = $this->ask('Phí ship bao nhiêu?');

        $this->assertStringContainsString('30.000đ', $res['reply']);
        $this->assertStringContainsString('500.000đ', $res['reply']);
    }
}
