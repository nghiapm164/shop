<?php

namespace App\Services\Chatbot;

use App\Support\ProductAttributes;

/**
 * CHATBOT_AI
 * Luồng xử lý một tin nhắn của khách:
 *  1. Hiểu câu hỏi: Gemini trích xuất tiêu chí dạng JSON (nếu có API key),
 *     kết hợp bộ phân tích tiếng Việt offline (QueryParser).
 *  2. Ghép ngữ cảnh hội thoại trước (câu hỏi nối tiếp: "còn màu trắng không?").
 *  3. Tư vấn size theo bảng size, trả lời FAQ, truy vấn sản phẩm/bộ đồ thật trong DB.
 *  4. Soạn câu trả lời CHỈ từ dữ liệu đã truy vấn (Gemini chỉ diễn đạt lại cho tự nhiên).
 */
class ChatbotService
{
    /** Câu hỏi gợi ý hiển thị sẵn trong khung chat */
    private const SUGGESTIONS = [
        'Tìm áo bóng đá nam dưới 300k',
        'Cao 1m70 nặng 65kg nên mặc size gì?',
        'Gợi ý bộ đồ chạy bộ khoảng 500k',
        'Có quần short tập gym màu đen không?',
        'Phí ship và đổi trả thế nào?',
    ];

    private readonly SizeAdvisor $sizeAdvisor;

    public function __construct(
        private readonly QueryParser $parser,
        private readonly ProductRecommender $recommender,
        private readonly StoreKnowledge $knowledge,
        private readonly GeminiClient $gemini,
    ) {
        $this->sizeAdvisor = SizeAdvisor::fromConfig();
    }

    public static function suggestions(): array
    {
        return self::SUGGESTIONS;
    }

    /**
     * 5 tình huống demo bắt buộc khi bảo vệ đề tài (giữ nguyên câu hỏi theo đề bài).
     * Hiển thị ở nút "Demo" trong khung chat khi CHATBOT_DEMO_MODE=true.
     */
    public static function demoCases(): array
    {
        return [
            ['title' => 'Hỏi sản phẩm', 'question' => 'Tìm áo bóng đá nam.', 'expect' => 'Trả về các sản phẩm bóng đá nam hiện có'],
            ['title' => 'Lọc theo giá', 'question' => 'Tìm cho tôi áo thể thao nam dưới 300k.', 'expect' => 'Đúng sản phẩm trong khoảng giá'],
            ['title' => 'Tư vấn size', 'question' => 'Nam 1m75, 70kg nên mặc size gì?', 'expect' => 'Đề xuất size dựa vào bảng size'],
            ['title' => 'Tư vấn theo nhu cầu', 'question' => 'Tôi cần một bộ đồ để chạy bộ khoảng 500k.', 'expect' => 'Phân tích nhu cầu, gợi ý bộ đồ trong ngân sách'],
            ['title' => 'Kết hợp nhiều điều kiện', 'question' => 'Tôi là nam, cao 1m72 nặng 65kg, muốn mua đồ chạy bộ màu đen dưới 500k.', 'expect' => 'Phân tích giới tính, size, môn, màu, giá'],
        ];
    }

    public static function money(int|float $value): string
    {
        return number_format((float) $value, 0, ',', '.') . 'đ';
    }

    /**
     * @param array $history  [['role' => 'user'|'model', 'text' => '...'], ...]
     * @param array|null $previous Tiêu chí tìm kiếm của lượt trước (lưu trong session)
     */
    public function handle(string $message, array $history = [], ?array $previous = null): array
    {
        // 1. Hiểu câu hỏi
        $rules = $this->parser->parse($message);
        $ai = $this->gemini->enabled() ? $this->understandWithGemini($message, $history) : null;
        $c = $ai ? $this->mergeUnderstanding($rules, $ai) : $rules;

        // Chỉ tư vấn size khi khách nêu số đo trong chính câu này (không lặp lại ở câu nối tiếp)
        $bodyInMessage = $c['height_cm'] !== null || $c['weight_kg'] !== null;

        // 2. Câu hỏi nối tiếp => kế thừa tiêu chí lượt trước
        $usedContext = false;
        if ($previous && $c['is_followup'] && !$c['sport'] && !$c['product_type'] && !$c['unsupported_sport']
            && !array_diff($c['topics'], ['promo'])) {
            $c = $this->mergeWithPrevious($c, $previous);
            $usedContext = true;
        }

        $blocks = [];
        $links = [];
        $products = [];
        $outfits = [];
        $newContext = null;

        // 3a. Tư vấn size
        $advice = $bodyInMessage ? $this->sizeAdvisor->advise($c['gender'], $c['height_cm'], $c['weight_kg']) : null;
        if ($advice) {
            $blocks[] = $this->sizeAdviceText($advice);
            // Lọc sản phẩm còn size phù hợp (chỉ với quần áo – giày, phụ kiện dùng size khác)
            if (!$c['size'] && (!$c['product_type'] || in_array($c['product_type'], ProductRecommender::APPAREL_TYPES, true))) {
                $c['size'] = $advice['size'];
                $c['size_from_body'] = true;
            }
        } elseif ($c['wants_size_advice'] || in_array('size_chart', $c['topics'], true)) {
            $intro = $c['wants_size_advice']
                ? 'Bạn cho mình biết **chiều cao, cân nặng** (ví dụ: "nam 1m70, 65kg") để mình tư vấn size chính xác nhé. Bảng size tham khảo:'
                : 'Bảng size của shop:';
            $blocks[] = $intro . "\n" . $this->sizeAdvisor->chartText($c['gender']);
        }

        // 3b. Câu hỏi chung (FAQ) – lấy từ dữ liệu website
        foreach ($c['topics'] as $topic) {
            $answer = $this->knowledge->answer($topic);
            if ($answer) {
                $blocks[] = $answer['text'];
                if ($answer['link']) {
                    $links[] = $answer['link'];
                }
            }
        }

        // 3c. Sản phẩm / bộ đồ
        if ($c['wants_outfit']) {
            $result = $this->recommender->outfits($c, config('chatbot.max_outfits', 3));
            $blocks[] = $this->outfitText($c, $result);
            foreach ($result['outfits'] as $i => $combo) {
                $outfits[] = [
                    'title' => 'Bộ ' . ($i + 1),
                    'total' => $combo['total'],
                    'items' => [
                        $this->recommender->toCard($combo['top'], $c['size']),
                        $this->recommender->toCard($combo['bottom'], $c['size']),
                    ],
                ];
            }
            $newContext = $c;
            if ($outfits) {
                $newContext['shown_min_price'] = min(array_column($outfits, 'total'));
            }
        } elseif ($c['wants_products']) {
            $result = $this->recommender->search($c, config('chatbot.max_products', 6));
            $blocks[] = $this->productText($c, $result);
            $products = $result['products']->map(fn ($p) => $this->recommender->toCard($p, $c['size']))->all();
            $newContext = $c;
            if ($products) {
                $newContext['shown_min_price'] = min(array_column($products, 'price'));
            }
        }

        // 3d. Chào hỏi / cảm ơn / ngoài phạm vi
        if (!$blocks) {
            $blocks[] = match (true) {
                $c['thanks'] => 'Rất vui được hỗ trợ bạn! Nếu cần tìm thêm sản phẩm hay tư vấn size, cứ nhắn mình nhé. 😊',
                $c['greeting'] => "Xin chào! Mình là **AI Sport Assistant** của " . config('chatbot.store.name') . ".\nMình có thể giúp bạn tìm quần áo thể thao theo môn, giới tính, màu sắc, size, ngân sách hoặc tư vấn size theo chiều cao/cân nặng. Bạn đang muốn tìm sản phẩm gì?",
                default => "Xin lỗi, mình chỉ hỗ trợ các câu hỏi liên quan đến **sản phẩm và dịch vụ của " . config('chatbot.store.name') . "** (tìm sản phẩm, tư vấn size, phí giao hàng, đổi trả, thanh toán, khuyến mãi...). Mình không có thông tin để trả lời câu hỏi này.\nBạn có thể thử hỏi: \"Tìm áo bóng đá nam\" hoặc \"Phí ship bao nhiêu?\"",
            };
        }

        $reply = implode("\n\n", $blocks);
        $source = $ai ? 'gemini' : 'rules';

        // 4. Gemini diễn đạt lại câu trả lời có sản phẩm/tư vấn size (chỉ dùng dữ liệu đã có)
        if ($ai && ($products || $outfits || $advice) && config('chatbot.gemini.rewrite_reply')) {
            $rewritten = $this->rewriteWithGemini($message, $reply, $products, $outfits);
            if ($rewritten) {
                $reply = $rewritten;
            }
        }

        return [
            'reply' => $reply,
            'products' => $products,
            'outfits' => $outfits,
            'links' => $links,
            'analysis' => $this->analysisChips($c, $advice, $usedContext),
            'suggestions' => $this->followUpSuggestions($c, $products || $outfits),
            'source' => $source,
            'context' => $newContext,
        ];
    }

    // ------------------------------------------------------------------
    // Hiểu câu hỏi bằng Gemini
    // ------------------------------------------------------------------

    private function understandWithGemini(string $message, array $history): ?array
    {
        $system = <<<PROMPT
Bạn là bộ phân tích câu hỏi cho chatbot của shop quần áo thể thao MODESPORT.
Nhiệm vụ: đọc TIN NHẮN CUỐI của khách (tiếng Việt, có thể không dấu, viết tắt) và trích xuất tiêu chí thành JSON theo schema.
Quy tắc:
- Chỉ trích xuất thông tin có trong tin nhắn cuối; không tự suy đoán. Trường không được nhắc đến để null (mảng để rỗng).
- Dùng lịch sử hội thoại chỉ để xác định is_followup = true khi tin nhắn cuối là câu hỏi nối tiếp về sản phẩm vừa tư vấn (vd: "còn màu trắng không?", "rẻ hơn được không?").
- gender: "nam" hoặc "nu" (mua cho bạn gái/vợ => nu).
- sport: bong-da, chay-bo, gym, yoga, bong-ro, cau-long, tennis. Môn khác (bơi, võ, golf, đạp xe...) ghi vào unsupported_sport.
- purpose: tap-luyen (tập luyện), thi-dau (thi đấu/đá giải), hang-ngay (mặc thường ngày/đi chơi).
- style (kiểu dáng): slim (ôm), regular (vừa), athletic, relaxed (rộng/oversize).
- product_type: ao (áo, không gồm áo khoác), ao-khoac, quan, quan-short, quan-jogger, giay, do-lot (đồ lót), tat (tất/vớ), non (nón/mũ), tui (túi/balo), bang-co-tay, khan (khăn), ta (tạ tay), day-nhay (dây nhảy), tham (thảm tập), phu-kien (phụ kiện chung). "quần áo"/"đồ" chung chung => null.
- size: size quần áo XS..XXXL, size giày 35..46 (vd "giày size 42" => "42"), FREE cho free size.
- wants_outfit = true khi khách muốn cả bộ đồ (áo + quần).
- colors: tên màu tiếng Anh: Black, White, Red, Blue, Green, Yellow, Gray, Navy, Orange, Purple, Pink, Brown, Beige.
- Giá theo VND (300k = 300000, 1tr2 = 1200000). "dưới X" => price_max = X; "trên X" => price_min = X; "khoảng X"/"tầm X" => price_max = X * 1.1; "có X" (ngân sách) => price_max = X.
- height_cm (1m75 => 175), weight_kg.
- wants_products = true khi khách muốn tìm/xem/mua/được gợi ý sản phẩm.
- wants_size_advice = true khi khách hỏi nên mặc size gì.
- topics: shipping (phí ship/giao hàng), return (đổi trả), payment (thanh toán), contact (liên hệ/địa chỉ), order (đơn hàng), promo (khuyến mãi/mã giảm giá), catalog (shop bán gì), size_chart (xem bảng size).
- off_topic = true nếu câu hỏi không liên quan đến shop/sản phẩm thể thao.
PROMPT;

        $nullableEnum = fn (array $values) => ['type' => 'STRING', 'enum' => $values, 'nullable' => true];
        $schema = [
            'type' => 'OBJECT',
            'properties' => [
                'gender' => $nullableEnum(['nam', 'nu']),
                'sport' => $nullableEnum(array_keys(ProductAttributes::SPORTS)),
                'unsupported_sport' => ['type' => 'STRING', 'nullable' => true],
                'purpose' => $nullableEnum(array_keys(ProductAttributes::PURPOSES)),
                'style' => $nullableEnum(array_keys(ProductAttributes::STYLES)),
                'product_type' => $nullableEnum(array_keys(ProductRecommender::TYPE_LABELS)),
                'colors' => ['type' => 'ARRAY', 'items' => ['type' => 'STRING', 'enum' => ['Black', 'White', 'Red', 'Blue', 'Green', 'Yellow', 'Gray', 'Navy', 'Orange', 'Purple', 'Pink', 'Brown', 'Beige']]],
                'size' => $nullableEnum(['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46', 'FREE']),
                'height_cm' => ['type' => 'INTEGER', 'nullable' => true],
                'weight_kg' => ['type' => 'INTEGER', 'nullable' => true],
                'price_min' => ['type' => 'INTEGER', 'nullable' => true],
                'price_max' => ['type' => 'INTEGER', 'nullable' => true],
                'sort' => $nullableEnum(['price_asc', 'price_desc', 'popular', 'newest']),
                'topics' => ['type' => 'ARRAY', 'items' => ['type' => 'STRING', 'enum' => ['shipping', 'return', 'payment', 'contact', 'order', 'promo', 'catalog', 'size_chart']]],
                'wants_products' => ['type' => 'BOOLEAN'],
                'wants_outfit' => ['type' => 'BOOLEAN'],
                'wants_size_advice' => ['type' => 'BOOLEAN'],
                'is_followup' => ['type' => 'BOOLEAN'],
                'greeting' => ['type' => 'BOOLEAN'],
                'thanks' => ['type' => 'BOOLEAN'],
                'off_topic' => ['type' => 'BOOLEAN'],
            ],
            'required' => ['colors', 'topics', 'wants_products', 'wants_outfit', 'wants_size_advice', 'is_followup', 'off_topic'],
        ];

        $contents = $this->historyContents($history);
        $contents[] = ['role' => 'user', 'parts' => [['text' => $message]]];

        return $this->gemini->json($system, $contents, $schema);
    }

    /**
     * Kết hợp kết quả Gemini với bộ phân tích luật:
     * - Thuộc tính phân loại: ưu tiên Gemini (hiểu ngữ cảnh tốt hơn).
     * - Số liệu (giá, chiều cao, cân nặng): ưu tiên bộ phân tích luật (chính xác, nhất quán).
     */
    private function mergeUnderstanding(array $rules, array $ai): array
    {
        $c = $rules;

        foreach (['gender', 'sport', 'unsupported_sport', 'purpose', 'style', 'product_type', 'size', 'sort'] as $key) {
            if (!empty($ai[$key])) {
                $c[$key] = $ai[$key];
            }
        }
        if ($c['sport']) {
            $c['unsupported_sport'] = null;
        }

        if ($rules['price_min'] === null && $rules['price_max'] === null) {
            $c['price_min'] = isset($ai['price_min']) ? (int) $ai['price_min'] : null;
            $c['price_max'] = isset($ai['price_max']) ? (int) $ai['price_max'] : null;
        }
        foreach (['height_cm', 'weight_kg'] as $key) {
            $c[$key] ??= isset($ai[$key]) ? (int) $ai[$key] : null;
        }

        if (!empty($ai['colors'])) {
            $c['colors'] = array_values(array_unique($ai['colors']));
            if (!$c['color_words']) {
                $c['color_words'] = array_map(fn ($n) => mb_strtolower(ProductAttributes::colorVi($n)), $c['colors']);
            }
        }

        $c['topics'] = array_values(array_unique(array_merge($rules['topics'], $ai['topics'] ?? [])));
        $c['on_sale'] = $rules['on_sale'] || in_array('promo', $c['topics'], true);

        $offTopic = !empty($ai['off_topic']);
        $c['wants_products'] = !$offTopic && (!empty($ai['wants_products']) || $rules['wants_products']);
        $c['wants_outfit'] = !$offTopic && (!empty($ai['wants_outfit']) || $rules['wants_outfit']);
        $c['wants_size_advice'] = !empty($ai['wants_size_advice']) || $rules['wants_size_advice'];
        $c['is_followup'] = !empty($ai['is_followup']);
        $c['greeting'] = !empty($ai['greeting']) || $rules['greeting'];
        $c['thanks'] = !empty($ai['thanks']) || $rules['thanks'];

        return $c;
    }

    private function mergeWithPrevious(array $c, array $prev): array
    {
        $inherit = ['gender', 'sport', 'product_type', 'purpose', 'style', 'keywords', 'colors', 'color_words', 'height_cm', 'weight_kg', 'on_sale', 'wants_outfit'];
        foreach ($inherit as $key) {
            if (empty($c[$key]) && !empty($prev[$key])) {
                $c[$key] = $prev[$key];
            }
        }

        // Size: giữ size khách nói trước đó hoặc size đã tư vấn
        if (!$c['size'] && !empty($prev['size'])) {
            $c['size'] = $prev['size'];
        }

        if ($c['price_min'] === null && $c['price_max'] === null) {
            $c['price_min'] = $prev['price_min'] ?? null;
            $c['price_max'] = $prev['price_max'] ?? null;
            $c['price_note'] = $prev['price_note'] ?? null;

            // "rẻ hơn" => thấp hơn sản phẩm rẻ nhất vừa gợi ý
            if ($c['sort'] === 'price_asc' && !empty($prev['shown_min_price'])) {
                $c['price_max'] = (int) $prev['shown_min_price'] - 1000;
                $c['price_min'] = null;
                $c['price_note'] = 'max';
            }
        }

        $c['wants_products'] = true;

        return $c;
    }

    // ------------------------------------------------------------------
    // Soạn câu trả lời (từ dữ liệu thật)
    // ------------------------------------------------------------------

    private function sizeAdviceText(array $a): string
    {
        $who = [];
        if ($a['height_cm']) {
            $who[] = 'cao ' . intdiv($a['height_cm'], 100) . 'm' . str_pad((string) ($a['height_cm'] % 100), 2, '0', STR_PAD_LEFT);
        }
        if ($a['weight_kg']) {
            $who[] = 'nặng ' . $a['weight_kg'] . 'kg';
        }

        $text = 'Với ' . implode(', ', $who) . ', bạn nên mặc **size ' . $a['size'] . '**.' . "\n" . $a['explanation'];
        if ($a['assumed_gender']) {
            $text .= "\n(Mình đang tư vấn theo bảng size nam – nếu bạn là nữ, hãy cho mình biết để tư vấn lại nhé.)";
        }

        return $text;
    }

    private function productText(array $c, array $result): string
    {
        $summary = $this->criteriaSummary($c);
        $products = $result['products'];

        if ($c['unsupported_sport']) {
            return 'Rất tiếc, hiện shop **chưa kinh doanh sản phẩm dành cho ' . mb_strtolower($c['unsupported_sport']) . '**. '
                . 'Shop chuyên đồ cho các môn: ' . implode(', ', ProductAttributes::SPORTS) . '. Bạn có muốn mình tư vấn sản phẩm cho môn nào trong số này không?';
        }

        if ($products->isEmpty()) {
            return 'Rất tiếc, hiện shop **chưa có sản phẩm** ' . ($summary ?: 'phù hợp') . ' còn hàng. '
                . 'Bạn thử đổi tiêu chí (môn thể thao, loại sản phẩm hoặc khoảng giá) để mình tìm lại nhé.';
        }

        $lines = [];
        if ($result['relaxed'] === ['price'] && $c['sort'] === 'price_asc') {
            $cheapest = $products->first();
            $lines[] = 'Hiện chưa có mẫu ' . $this->criteriaSummary(array_merge($c, ['price_min' => null, 'price_max' => null]))
                . ' nào rẻ hơn. **' . $cheapest->name . '** (' . self::money($this->recommender->price($cheapest)) . ') đã là mẫu rẻ nhất phù hợp với bạn. Các lựa chọn theo giá tăng dần:';
        } elseif ($result['relaxed']) {
            $relaxedCriteria = array_merge($c, [
                'size' => in_array('size', $result['relaxed'], true) ? null : $c['size'],
                'colors' => in_array('colors', $result['relaxed'], true) ? [] : $c['colors'],
                'color_words' => in_array('colors', $result['relaxed'], true) ? [] : $c['color_words'],
                'price_min' => in_array('price', $result['relaxed'], true) ? null : $c['price_min'],
                'price_max' => in_array('price', $result['relaxed'], true) ? null : $c['price_max'],
            ]);
            $lines[] = 'Hiện shop chưa có sản phẩm ' . $this->criteriaSummary($relaxedCriteria) . ' đáp ứng đúng **' . $this->relaxedText($c, $result['relaxed']) . '**. '
                . 'Mình gợi ý ' . $products->count() . ' sản phẩm gần nhất với yêu cầu của bạn:';
        } else {
            $lines[] = 'Mình tìm được **' . $products->count() . ' sản phẩm** ' . $summary . ' phù hợp với bạn:';
        }

        foreach ($products->take(3) as $i => $p) {
            $card = $this->recommender->toCard($p, $c['size']);
            $line = ($i + 1) . '. **' . $p->name . '** – ' . self::money($card['price']);
            if ($card['original_price']) {
                $line .= ' (giảm ' . $card['discount'] . '%)';
            }
            $details = [];
            if ($card['colors']) {
                $details[] = 'màu ' . implode(', ', array_slice($card['colors'], 0, 4));
            }
            if ($card['sizes']) {
                $details[] = 'size ' . implode(', ', $card['sizes']);
            }
            $lines[] = $line . ($details ? ' – ' . implode('; ', $details) : '');
        }

        if ($products->count() > 3) {
            $lines[] = '...và ' . ($products->count() - 3) . ' sản phẩm khác bên dưới.';
        }
        if ($c['size'] && !in_array('size', $result['relaxed'], true)) {
            $lines[] = 'Tất cả sản phẩm trên đều **còn size ' . $c['size'] . '**' . (!empty($c['colors']) && !in_array('colors', $result['relaxed'], true) ? ' đúng màu bạn chọn' : '') . '.';
        }
        $lines[] = 'Bấm vào sản phẩm để xem chi tiết và đặt hàng nhé!';

        return implode("\n", $lines);
    }

    private function outfitText(array $c, array $result): string
    {
        $summary = $this->criteriaSummary(array_merge($c, ['product_type' => null, 'price_min' => null, 'price_max' => null]));
        $budget = $this->priceText($c);

        if (!$result['outfits']) {
            return 'Rất tiếc, mình chưa phối được bộ đồ ' . ($summary ?: '') . ($budget ? ' ' . $budget : '')
                . ' từ các sản phẩm hiện có. Bạn thử tăng ngân sách hoặc bỏ bớt điều kiện (màu, size) nhé.';
        }

        $lines = [];
        $intro = 'Mình gợi ý **' . count($result['outfits']) . ' bộ đồ**' . ($summary ? ' ' . $summary : '') . ($budget ? ' với tổng giá ' . $budget : '') . ':';
        if ($result['relaxed']) {
            $intro = 'Shop chưa có đủ sản phẩm đáp ứng đúng **' . $this->relaxedText($c, $result['relaxed']) . '**, ' . lcfirst($intro);
        }
        $lines[] = $intro;

        foreach ($result['outfits'] as $i => $combo) {
            $lines[] = '**Bộ ' . ($i + 1) . '** (tổng ' . self::money($combo['total']) . '): '
                . $combo['top']->name . ' (' . self::money($this->recommender->price($combo['top'])) . ') + '
                . $combo['bottom']->name . ' (' . self::money($this->recommender->price($combo['bottom'])) . ')';
        }
        if ($c['price_note'] === 'approx' && $c['price_max']) {
            $lines[] = '(Với "khoảng", mình cho phép tổng giá lệch tối đa 10%.)';
        }
        $lines[] = 'Bấm vào từng sản phẩm để chọn màu, size và thêm vào giỏ hàng nhé!';

        return implode("\n", $lines);
    }

    private function relaxedText(array $c, array $relaxed): string
    {
        $parts = [];
        foreach ($relaxed as $key) {
            $parts[] = match ($key) {
                'size' => 'size ' . $c['size'],
                'colors' => 'màu ' . implode('/', $c['color_words'] ?: array_map(fn ($n) => mb_strtolower(ProductAttributes::colorVi($n)), $c['colors'])),
                'keywords' => 'kiểu ' . implode(', ', $c['keywords']),
                'price' => 'mức giá ' . $this->priceText($c),
            };
        }

        return implode(', ', $parts);
    }

    private function criteriaSummary(array $c): string
    {
        $parts = [];
        if ($c['product_type']) {
            $parts[] = ProductRecommender::TYPE_LABELS[$c['product_type']] ?? $c['product_type'];
        }
        if (!empty($c['keywords'])) {
            $parts[] = implode(' ', $c['keywords']);
        }
        if ($c['sport']) {
            $parts[] = mb_strtolower(ProductAttributes::label('sport', $c['sport']));
        }
        if ($c['gender'] === 'nam' || $c['gender'] === 'nu') {
            $parts[] = mb_strtolower(ProductAttributes::label('gender', $c['gender']));
        }
        if (!empty($c['color_words']) || !empty($c['colors'])) {
            $parts[] = 'màu ' . implode('/', $c['color_words'] ?: array_map(fn ($n) => mb_strtolower(ProductAttributes::colorVi($n)), $c['colors']));
        }
        if ($price = $this->priceText($c)) {
            $parts[] = $price;
        }
        if (!empty($c['on_sale'])) {
            $parts[] = 'đang giảm giá';
        }

        return implode(' ', $parts);
    }

    private function priceText(array $c): ?string
    {
        $min = $c['price_min'];
        $max = $c['price_max'];

        if ($min !== null && $max !== null) {
            return 'từ ' . self::money($min) . ' đến ' . self::money($max);
        }
        if ($max !== null) {
            return ($c['price_note'] === 'approx' ? 'khoảng ' . self::money(round($max / 1.1 / 1000) * 1000) : 'dưới ' . self::money($max));
        }
        if ($min !== null) {
            return 'từ ' . self::money($min) . ' trở lên';
        }

        return null;
    }

    private function analysisChips(array $c, ?array $advice, bool $usedContext): array
    {
        $chips = [];
        if ($usedContext) {
            $chips[] = 'Tiếp nối câu hỏi trước';
        }
        if ($c['gender'] === 'nam' || $c['gender'] === 'nu') {
            $chips[] = 'Giới tính: ' . ProductAttributes::label('gender', $c['gender']);
        }
        if ($c['sport']) {
            $chips[] = 'Môn: ' . ProductAttributes::label('sport', $c['sport']);
        }
        if ($c['unsupported_sport']) {
            $chips[] = 'Môn: ' . $c['unsupported_sport'] . ' (shop chưa bán)';
        }
        if ($c['purpose']) {
            $chips[] = 'Mục đích: ' . ProductAttributes::label('purpose', $c['purpose']);
        }
        if ($c['product_type']) {
            $label = ProductRecommender::TYPE_LABELS[$c['product_type']];
            $chips[] = 'Loại: ' . mb_strtoupper(mb_substr($label, 0, 1)) . mb_substr($label, 1);
        }
        if ($c['wants_outfit']) {
            $chips[] = 'Gợi ý cả bộ';
        }
        if ($c['style']) {
            $chips[] = 'Kiểu dáng: ' . ProductAttributes::label('style', $c['style']);
        }
        if (!empty($c['colors']) || !empty($c['color_words'])) {
            $chips[] = 'Màu: ' . implode('/', $c['color_words'] ?: array_map(fn ($n) => mb_strtolower(ProductAttributes::colorVi($n)), $c['colors']));
        }
        if ($advice) {
            $chips[] = 'Size đề xuất: ' . $advice['size'];
        } elseif ($c['size']) {
            $chips[] = 'Size: ' . $c['size'];
        }
        if ($price = $this->priceText($c)) {
            $chips[] = 'Giá: ' . $price;
        }
        if (!empty($c['on_sale'])) {
            $chips[] = 'Khuyến mãi';
        }

        return $chips;
    }

    private function followUpSuggestions(array $c, bool $hasResults): array
    {
        if (!$hasResults) {
            return array_slice(self::SUGGESTIONS, 0, 3);
        }

        $suggestions = [];
        $suggestions[] = 'Còn mẫu nào rẻ hơn không?';
        if (empty($c['colors'])) {
            $suggestions[] = 'Có màu đen không?';
        } else {
            $suggestions[] = in_array('White', $c['colors'], true) ? 'Còn màu đen không?' : 'Còn màu trắng không?';
        }
        if (!$c['height_cm']) {
            $suggestions[] = 'Cao 1m70 nặng 65kg mặc size gì?';
        }
        $suggestions[] = 'Phí ship bao nhiêu?';

        return $suggestions;
    }

    private function rewriteWithGemini(string $message, string $draft, array $products, array $outfits): ?string
    {
        $facts = [
            'cau_tra_loi_nhap' => $draft,
            'san_pham' => array_map(fn ($p) => [
                'ten' => $p['name'], 'gia' => self::money($p['price']),
                'mau' => $p['colors'], 'size' => $p['sizes'],
            ], $products),
            'bo_do' => array_map(fn ($o) => [
                'ten' => $o['title'], 'tong' => self::money($o['total']),
                'gom' => array_map(fn ($i) => $i['name'] . ' (' . self::money($i['price']) . ')', $o['items']),
            ], $outfits),
        ];

        $system = <<<PROMPT
Bạn là AI Sport Assistant – nhân viên tư vấn thân thiện của shop thể thao MODESPORT.
Viết lại "cau_tra_loi_nhap" thành câu trả lời tự nhiên, ngắn gọn (tối đa 130 từ), xưng "mình", gọi khách là "bạn".
BẮT BUỘC:
- Chỉ dùng thông tin trong DỮ LIỆU. Không thêm sản phẩm, giá, màu, size, chính sách hay khuyến mãi nào khác.
- Giữ nguyên chính xác tên sản phẩm, giá tiền, size đề xuất và lý do chọn size.
- Nếu dữ liệu nói chưa có sản phẩm đúng tiêu chí thì phải nói rõ điều đó.
- Có thể nêu ngắn gọn vì sao sản phẩm phù hợp nhu cầu của khách (dựa trên tên/thuộc tính).
- Định dạng: văn bản thường, được dùng **in đậm** và gạch đầu dòng "- ". Không dùng tiêu đề, không chèn đường link.
PROMPT;

        $contents = [[
            'role' => 'user',
            'parts' => [['text' => "Câu hỏi của khách: {$message}\n\nDỮ LIỆU:\n" . json_encode($facts, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)]],
        ]];

        return $this->gemini->text($system, $contents);
    }

    private function historyContents(array $history): array
    {
        $turns = array_slice($history, -2 * (int) config('chatbot.history_turns', 6));
        while ($turns && ($turns[0]['role'] ?? '') !== 'user') {
            array_shift($turns); // Gemini yêu cầu hội thoại bắt đầu bằng lượt của người dùng
        }

        return array_values(array_map(fn ($turn) => [
            'role' => $turn['role'] === 'model' ? 'model' : 'user',
            'parts' => [['text' => mb_substr($turn['text'], 0, 600)]],
        ], $turns));
    }
}
