<?php

namespace App\Services\Chatbot;

use Illuminate\Support\Str;

/**
 * PHAN_TICH_CAU_HOI
 * Bộ phân tích câu hỏi tiếng Việt theo luật (rule-based NLU).
 * Trích xuất: giới tính, môn thể thao, mục đích, kiểu dáng, loại sản phẩm,
 * màu sắc, size, chiều cao/cân nặng, khoảng giá và ý định (intent) của khách.
 *
 * Hỗ trợ cả câu gõ có dấu và không dấu ("ao bong da nam duoi 300k").
 */
class QueryParser
{
    private const GENDERS = [
        'nu' => ['nữ', 'con gái', 'phụ nữ', 'bạn gái', 'chị gái', 'women', 'woman', 'female', 'girl', 'lady'],
        'nam' => ['nam', 'con trai', 'đàn ông', 'bạn trai', 'anh trai', 'men', 'male', 'boy'],
    ];

    private const SPORTS = [
        'bong-da' => ['bóng đá', 'đá bóng', 'đá banh', 'football', 'soccer', 'futsal', 'đá phủi', 'sân cỏ', 'đi đá'],
        'chay-bo' => ['chạy bộ', 'chạy bền', 'chạy', 'running', 'runner', 'marathon', 'jogging', 'đi bộ'],
        'gym' => ['gym', 'tập tạ', 'thể hình', 'fitness', 'phòng tập', 'nâng tạ', 'tập cơ', 'workout', 'crossfit'],
        'yoga' => ['yoga', 'pilates'],
        'bong-ro' => ['bóng rổ', 'basketball'],
        'cau-long' => ['cầu lông', 'badminton'],
        'tennis' => ['quần vợt', 'tennis', 'pickleball'],
    ];

    /** Môn thể thao shop không kinh doanh – dùng để trả lời trung thực */
    private const UNSUPPORTED_SPORTS = [
        'Bơi lội' => ['bơi lội', 'đồ bơi', 'đi bơi', 'áo bơi', 'quần bơi', 'swimming'],
        'Võ thuật' => ['võ thuật', 'boxing', 'karate', 'taekwondo', 'judo'],
        'Golf' => ['golf'],
        'Đạp xe' => ['đạp xe', 'xe đạp', 'cycling'],
        'Bóng chuyền' => ['bóng chuyền', 'volleyball'],
        'Leo núi' => ['leo núi', 'trekking', 'hiking'],
    ];

    private const PURPOSES = [
        'thi-dau' => ['thi đấu', 'đá giải', 'giải đấu', 'đồng phục đội', 'áo đấu', 'ra sân', 'đấu giải'],
        'hang-ngay' => ['hằng ngày', 'hàng ngày', 'mặc thường', 'thường ngày', 'đi chơi', 'dạo phố', 'đi làm', 'đi học', 'mặc nhà', 'casual'],
        'tap-luyen' => ['tập luyện', 'luyện tập', 'đi tập', 'đồ tập', 'tập thể dục', 'thể dục', 'tập'],
    ];

    private const STYLES = [
        'slim' => ['ôm body', 'bó sát', 'slim', 'ôm'],
        'relaxed' => ['form rộng', 'rộng rãi', 'oversize', 'relaxed', 'rộng'],
        'regular' => ['form vừa', 'vừa vặn', 'form chuẩn', 'regular'],
        'athletic' => ['athletic', 'năng động'],
    ];

    /**
     * Loại sản phẩm. Mẫu bắt đầu bằng "~" chỉ dùng khi khách gõ có dấu
     * (vì bản không dấu dễ nhầm: "tat" = "tất cả", "tham" = "tham gia", "ta" = "chúng ta"...).
     */
    private const TYPES = [
        // Phụ kiện / dụng cụ cụ thể – ưu tiên hơn "áo", "quần" chung chung
        'do-lot' => ['quần lót', 'đồ lót', 'áo lót', 'quần sịp', 'boxer', 'underwear'],
        'tat' => ['~tất', '~vớ', 'đôi tất', 'đôi vớ', 'socks', 'sock'],
        'non' => ['nón', '~mũ', 'mũ lưỡi trai', 'lưỡi trai'],
        'tui' => ['~túi', 'túi tập', 'túi xách', 'túi trống', 'túi đựng', 'balo', 'ba lô', 'backpack'],
        'bang-co-tay' => ['băng cổ tay', 'băng quấn', 'băng tay', 'wristband'],
        'khan' => ['khăn', 'towel'],
        'ta' => ['~tạ', 'tạ tay', 'quả tạ', 'tạ đơn', 'dumbbell'],
        'day-nhay' => ['dây nhảy', 'nhảy dây', 'jump rope'],
        'tham' => ['~thảm', 'thảm yoga', 'thảm tập', 'yoga mat'],
        // Quần áo, giày
        'ao-khoac' => ['áo khoác', 'áo gió', 'jacket', 'hoodie'],
        'quan-short' => ['quần short', 'quần đùi', 'quần ngắn', 'quần váy', 'shorts', 'short'],
        'quan-jogger' => ['quần jogger', 'jogger', 'quần dài', 'quần nỉ'],
        'giay' => ['giày', 'sneaker', 'shoes'],
        'phu-kien' => ['phụ kiện', 'găng tay', 'dụng cụ tập', 'thiết bị tập'],
        'ao' => ['áo', 'shirt', 'jersey', 'polo', 'tank top', 'tanktop', 'ba lỗ'],
        'quan' => ['quần', 'legging', 'pants'],
    ];

    private const SPECIFIC_TYPES = ['do-lot', 'tat', 'non', 'tui', 'bang-co-tay', 'khan', 'ta', 'day-nhay', 'tham'];

    /** Cụm từ bị loại bỏ trước khi nhận diện loại sản phẩm (không phải tên sản phẩm) */
    private const NOT_TYPES = ['quần vợt', 'tập tạ', 'nâng tạ', 'tất cả', 'mũ bảo hiểm'];

    /** Từ khóa lọc theo tên sản phẩm => chuỗi tìm trong tên */
    private const KEYWORDS = [
        'polo' => ['polo'],
        'ba lỗ' => ['ba lỗ', 'tank top', 'tanktop'],
        'dài tay' => ['dài tay', 'tay dài'],
        'giữ nhiệt' => ['giữ nhiệt'],
        'legging' => ['legging'],
        'hoodie' => ['hoodie'],
    ];

    /** Màu (tiếng Việt / tiếng Anh) => tên màu trong bảng colors */
    private const COLORS = [
        ['xanh navy', ['Navy']], ['xanh đen', ['Navy']], ['tím than', ['Navy']], ['navy', ['Navy']],
        ['xanh dương', ['Blue']], ['xanh nước biển', ['Blue']], ['xanh lam', ['Blue']], ['xanh biển', ['Blue']], ['blue', ['Blue']],
        ['xanh lá', ['Green']], ['xanh lục', ['Green']], ['green', ['Green']],
        ['xanh', ['Blue', 'Green', 'Navy']],
        ['đen', ['Black']], ['black', ['Black']],
        ['trắng', ['White']], ['white', ['White']],
        ['đỏ', ['Red']], ['red', ['Red']],
        ['vàng', ['Yellow']], ['yellow', ['Yellow']],
        ['xám', ['Gray']], ['ghi', ['Gray']], ['gray', ['Gray']], ['grey', ['Gray']],
        ['cam', ['Orange']], ['orange', ['Orange']],
        ['tím', ['Purple']], ['purple', ['Purple']],
        ['hồng', ['Pink']], ['pink', ['Pink']], ['nâu', ['Brown']], ['be', ['Beige']], ['kem', ['Beige']],
    ];

    private const OUTFIT = ['bộ đồ', 'bộ quần áo', 'một bộ', '1 bộ', 'trọn bộ', 'nguyên bộ', 'set đồ', 'set', 'combo', 'phối đồ', 'cả bộ'];

    private const SORTS = [
        'popular' => ['bán chạy', 'phổ biến', 'nhiều người mua', 'best seller', 'hot nhất', 'hot'],
        'price_asc' => ['rẻ nhất', 'rẻ hơn', 'giá rẻ', 'giá thấp', 'tiết kiệm', 'rẻ'],
        'price_desc' => ['đắt nhất', 'đắt hơn', 'cao cấp', 'giá cao', 'xịn nhất'],
        'newest' => ['mới nhất', 'mới về', 'hàng mới', 'new arrival'],
    ];

    private const TOPICS = [
        'shipping' => ['phí ship', 'phí giao', 'tiền ship', 'freeship', 'free ship', 'miễn phí vận chuyển', 'vận chuyển', 'giao hàng', 'ship', 'bao lâu thì nhận', 'bao lâu nhận'],
        'return' => ['đổi trả', 'đổi hàng', 'trả hàng', 'hoàn tiền', 'đổi size', 'bảo hành', 'đổi sản phẩm', 'trả lại'],
        'payment' => ['thanh toán', 'trả tiền', 'cod', 'chuyển khoản', 'momo', 'vnpay', 'trả góp', 'quẹt thẻ'],
        'contact' => ['liên hệ', 'hotline', 'số điện thoại', 'sđt', 'địa chỉ', 'shop ở đâu', 'cửa hàng ở đâu', 'email', 'zalo', 'fanpage', 'giờ mở cửa'],
        'order' => ['tra cứu đơn', 'kiểm tra đơn', 'theo dõi đơn', 'hủy đơn', 'huỷ đơn', 'đơn của tôi', 'đơn hàng', 'mã đơn', 'cách đặt hàng', 'đặt hàng như thế nào'],
        'promo' => ['khuyến mãi', 'giảm giá', 'mã giảm', 'voucher', 'coupon', 'flash sale', 'ưu đãi', 'sale'],
        'catalog' => ['bán những gì', 'bán gì', 'có những gì', 'có những loại', 'danh mục', 'shop có gì', 'các loại sản phẩm', 'thương hiệu', 'brand'],
        'size_chart' => ['bảng size', 'bảng kích cỡ', 'bảng kích thước', 'size chart'],
    ];

    private const SIZE_ADVICE = ['size gì', 'size nào', 'cỡ nào', 'cỡ gì', 'mặc size', 'tư vấn size', 'chọn size', 'size bao nhiêu', 'vừa size', 'mặc vừa', 'size phù hợp'];
    private const GREETING = ['xin chào', 'chào shop', 'chào bạn', 'chào', 'hello', 'hi', 'alo', 'hey', 'shop ơi'];
    private const THANKS = ['cảm ơn', 'cám ơn', 'thanks', 'thank', 'tks'];
    private const PRODUCT_MARKERS = ['tìm', 'mua', 'gợi ý', 'tư vấn', 'muốn', 'cần', 'cho xem', 'đề xuất', 'loại nào', 'sản phẩm', 'mẫu', 'đồ'];
    private const FOLLOWUP_MARKERS = ['còn', 'thế còn', 'vậy còn', 'thế', 'vậy', 'loại khác', 'mẫu khác', 'cái khác', 'thêm', 'nữa', 'có màu', 'màu', 'size'];

    /** Văn bản đang dùng để so khớp (có dấu hoặc không dấu) */
    private string $work = '';
    private bool $accented = true;

    public static function emptyCriteria(): array
    {
        return [
            'gender' => null,
            'sport' => null,
            'unsupported_sport' => null,
            'purpose' => null,
            'style' => null,
            'product_type' => null,
            'keywords' => [],
            'colors' => [],
            'color_words' => [],
            'size' => null,
            'height_cm' => null,
            'weight_kg' => null,
            'price_min' => null,
            'price_max' => null,
            'price_note' => null,
            'on_sale' => false,
            'sort' => null,
            'topics' => [],
            'wants_products' => false,
            'wants_outfit' => false,
            'wants_size_advice' => false,
            'is_followup' => false,
            'greeting' => false,
            'thanks' => false,
        ];
    }

    public function parse(string $message): array
    {
        $c = self::emptyCriteria();

        $vi = $this->normalize($message);
        $ascii = Str::lower(Str::ascii($vi));
        $this->accented = $ascii !== $vi;

        // 1. Số đo cơ thể & giá: xử lý trên bản không dấu rồi xóa khỏi câu để tránh nhầm lẫn
        [$c['height_cm'], $c['weight_kg'], $ascii] = $this->extractBody($ascii);
        [$c['price_min'], $c['price_max'], $c['price_note'], $ascii] = $this->extractPrice($ascii);
        [$c['size'], $ascii] = $this->extractSize($ascii);

        $this->work = ' ' . ($this->accented ? $this->stripNumbers($vi) : $ascii) . ' ';
        $this->work = str_replace(['việt nam', 'viet nam', 'miền nam', 'mien nam'], ' ', $this->work);

        // 2. Thứ tự quan trọng: các cụm dài/dễ nhầm được "tiêu thụ" trước
        $c['sort'] = $this->matchGroup(self::SORTS, true);

        // Nhận diện loại sản phẩm trước khi tách môn thể thao ("thảm yoga", "tất chạy bộ")
        $c['product_type'] = $this->extractType();

        $c['unsupported_sport'] = $this->matchGroup(self::UNSUPPORTED_SPORTS, true);
        $c['sport'] = $this->matchGroup(self::SPORTS, true);
        $c['wants_outfit'] = $this->has(self::OUTFIT, true);
        $c['purpose'] = $this->matchGroup(self::PURPOSES);
        $c['style'] = $this->matchGroup(self::STYLES);
        $c['keywords'] = $this->matchAll(self::KEYWORDS);
        [$c['colors'], $c['color_words']] = $this->extractColors();
        $c['gender'] = $this->extractGender();

        $c['topics'] = array_keys(array_filter(
            array_map(fn ($patterns) => $this->has($patterns), self::TOPICS)
        ));
        if (in_array('promo', $c['topics'], true)) {
            $c['on_sale'] = true;
        }

        $c['wants_size_advice'] = $c['height_cm'] !== null || $c['weight_kg'] !== null
            || $this->has(self::SIZE_ADVICE);

        $hasProductSignal = $c['product_type'] || $c['sport'] || $c['unsupported_sport'] || $c['colors']
            || $c['color_words'] || $c['keywords'] || $c['wants_outfit'] || $c['style']
            || $c['price_min'] !== null || $c['price_max'] !== null || $c['sort'] !== null
            || ($c['purpose'] && $c['purpose'] !== 'tap-luyen');

        // "đồ tập", "tư vấn đồ nam" ... cũng là yêu cầu sản phẩm
        if (!$hasProductSignal && ($c['gender'] || $c['purpose']) && $this->has(self::PRODUCT_MARKERS)) {
            $hasProductSignal = !$c['wants_size_advice'] || $this->has(['tìm', 'mua', 'gợi ý', 'đề xuất']);
        }

        // Câu hỏi khuyến mãi: trả về sản phẩm đang giảm giá
        $c['wants_products'] = $hasProductSignal || in_array('promo', $c['topics'], true);

        $c['is_followup'] = $this->startsWith(self::FOLLOWUP_MARKERS)
            || (!$c['product_type'] && !$c['sport'] && !$c['keywords'] && !$c['wants_outfit']
                && ($c['colors'] || $c['size'] || $c['price_max'] !== null || $c['price_min'] !== null || $c['sort'] || $c['style']));

        $c['greeting'] = $this->has(self::GREETING);
        $c['thanks'] = $this->has(self::THANKS);

        return $c;
    }

    // ------------------------------------------------------------------
    // Chuẩn hóa & so khớp
    // ------------------------------------------------------------------

    private function normalize(string $text): string
    {
        if (class_exists(\Normalizer::class)) {
            $text = \Normalizer::normalize($text, \Normalizer::FORM_C) ?: $text;
        }

        $text = mb_strtolower($text, 'UTF-8');
        $text = preg_replace('/[?!;:"()\[\]]+|,(?!\d)|(?<!\d),/u', ' ', $text);

        return trim(preg_replace('/\s+/u', ' ', $text));
    }

    /** Bỏ các cụm số đo/giá khỏi bản có dấu (đã được xử lý riêng) */
    private function stripNumbers(string $vi): string
    {
        return preg_replace('/\d+([.,]\d+)*\s*(m\d*|cm|kg|ký|kí|ki|k|nghìn|ngàn|nghin|tr|triệu|củ|đ|đồng|vnd)(?![\p{L}])/u', ' ', $vi);
    }

    private function pattern(string $p): string
    {
        if (str_starts_with($p, '~')) {
            if (!$this->accented) {
                return '/(?!)/'; // mẫu chỉ dùng cho câu có dấu
            }
            $p = substr($p, 1);
        }

        $p = $this->accented ? $p : Str::lower(Str::ascii($p));

        return '/(?<![\p{L}\p{N}])' . preg_quote($p, '/') . '(?![\p{L}\p{N}])/u';
    }

    private function has(array $patterns, bool $consume = false): bool
    {
        foreach ($patterns as $p) {
            if (preg_match($this->pattern($p), $this->work)) {
                if ($consume) {
                    $this->work = preg_replace($this->pattern($p), ' ', $this->work);
                }

                return true;
            }
        }

        return false;
    }

    private function matchGroup(array $groups, bool $consume = false): ?string
    {
        foreach ($groups as $key => $patterns) {
            if ($this->has($patterns, $consume)) {
                return $key;
            }
        }

        return null;
    }

    private function matchAll(array $groups): array
    {
        $found = [];
        foreach ($groups as $key => $patterns) {
            if ($this->has($patterns)) {
                $found[] = $key;
            }
        }

        return $found;
    }

    private function startsWith(array $patterns): bool
    {
        $text = ltrim($this->work);
        foreach ($patterns as $p) {
            $p = $this->accented ? $p : Str::lower(Str::ascii($p));
            if (preg_match('/^' . preg_quote($p, '/') . '(?![\p{L}\p{N}])/u', $text)) {
                return true;
            }
        }

        return false;
    }

    // ------------------------------------------------------------------
    // Trích xuất từng thuộc tính
    // ------------------------------------------------------------------

    private function extractGender(): ?string
    {
        $isFemale = $this->has(self::GENDERS['nu']);
        $isMale = $this->has(self::GENDERS['nam']);

        if ($isFemale xor $isMale) {
            return $isFemale ? 'nu' : 'nam';
        }

        return null; // không nói hoặc nói cả nam lẫn nữ
    }

    private function extractType(): ?string
    {
        // Làm việc trên bản sao để không ảnh hưởng các bước nhận diện sau
        $original = $this->work;
        foreach (self::NOT_TYPES as $phrase) {
            $this->work = preg_replace($this->pattern($phrase), ' ', $this->work);
        }

        $found = [];
        foreach (self::TYPES as $type => $patterns) {
            if ($this->has($patterns)) {
                $found[] = $type;
            }
        }
        $this->work = $original;

        // "quần lót", "tất chạy bộ", "túi đựng giày"... => loại cụ thể
        $specific = array_values(array_intersect($found, self::SPECIFIC_TYPES));
        if ($specific) {
            return $specific[0];
        }

        $found = array_values(array_filter($found, function ($type) use ($found) {
            // "áo khoác" đã khớp thì bỏ "áo"; "quần short" thì bỏ "quần"
            if ($type === 'ao') {
                return !in_array('ao-khoac', $found, true);
            }
            if ($type === 'quan') {
                return !array_intersect(['quan-short', 'quan-jogger'], $found);
            }

            return true;
        }));

        // "quần áo thể thao" là nói chung, không lọc theo loại
        return count($found) === 1 ? $found[0] : null;
    }

    private function extractColors(): array
    {
        $names = [];
        $words = [];

        foreach (self::COLORS as [$word, $colorNames]) {
            if ($this->accented) {
                $regex = $this->pattern($word);
            } else {
                // Không dấu: "den" có thể là "đến", "do" là "đồ"... nên bắt buộc có chữ "mau"
                $asciiWord = Str::lower(Str::ascii($word));
                $isEnglish = $asciiWord === $word && in_array($word, ['navy', 'blue', 'green', 'black', 'white', 'red', 'yellow', 'gray', 'grey', 'orange', 'purple', 'pink'], true);
                $regex = $isEnglish
                    ? $this->pattern($word)
                    : '/(?<![\p{L}])mau\s+(?:sac\s+)?' . preg_quote($asciiWord, '/') . '(?![\p{L}])/u';
            }

            if (preg_match($regex, $this->work)) {
                $this->work = preg_replace($regex, ' ', $this->work);
                $names = array_merge($names, $colorNames);
                $words[] = $word;
            }
        }

        return [array_values(array_unique($names)), $words];
    }

    private function extractSize(string $ascii): array
    {
        $re = '/(?<![a-z])(?:size|sz|mac|co)\s*(xxxl|3xl|xxl|2xl|xl|xs|s|m|l)(?![a-z0-9])/';
        if (preg_match($re, $ascii, $m)) {
            $size = strtoupper(str_replace(['3xl', '2xl'], ['xxxl', 'xxl'], $m[1]));

            return [$size, preg_replace($re, ' ', $ascii)];
        }

        // Size giày: "size 42", "cỡ 40", "chân 41"
        $re = '/(?<![a-z])(?:size|sz|co|chan|giay)\s*(3[5-9]|4[0-6])(?!\d)/';
        if (preg_match($re, $ascii, $m)) {
            return [$m[1], preg_replace($re, ' ', $ascii)];
        }

        if (preg_match('/free\s*size|freesize/', $ascii)) {
            return ['FREE', preg_replace('/free\s*size|freesize/', ' ', $ascii)];
        }

        return [null, $ascii];
    }

    /**
     * Chiều cao: 1m75, 1m7, 1.75m, 175cm, cao 175
     * Cân nặng: 70kg, 70 ký, nặng 70, 70 cân
     */
    private function extractBody(string $ascii): array
    {
        $height = null;
        $weight = null;

        $heightPatterns = [
            '/(?<!\d)([12])\s*m\s*(\d{1,2})(?!\d)/' => fn ($m) => $m[1] * 100 + (strlen($m[2]) === 1 ? $m[2] * 10 : (int) $m[2]),
            '/(?<!\d)([12])[.,](\d{1,2})\s*m(?![a-z])/' => fn ($m) => $m[1] * 100 + (strlen($m[2]) === 1 ? $m[2] * 10 : (int) $m[2]),
            '/(?<!\d)(\d{3})\s*cm(?![a-z])/' => fn ($m) => (int) $m[1],
            '/cao\s*(?:khoang\s*)?(\d{3})(?!\d)/' => fn ($m) => (int) $m[1],
        ];
        foreach ($heightPatterns as $re => $fn) {
            if (preg_match($re, $ascii, $m)) {
                $value = (int) $fn($m);
                if ($value >= 120 && $value <= 220) {
                    $height = $value;
                    $ascii = preg_replace($re, ' ', $ascii, 1);
                    break;
                }
            }
        }

        $weightPatterns = [
            '/(?<!\d)(\d{2,3}(?:[.,]\d)?)\s*(?:kg|kilo|ky|ki|can)(?![a-z])/',
            '/nang\s*(?:khoang\s*)?(\d{2,3})(?!\d)/',
        ];
        foreach ($weightPatterns as $re) {
            if (preg_match($re, $ascii, $m)) {
                $value = (int) round((float) str_replace(',', '.', $m[1]));
                if ($value >= 30 && $value <= 200) {
                    $weight = $value;
                    $ascii = preg_replace($re, ' ', $ascii, 1);
                    break;
                }
            }
        }

        return [$height, $weight, $ascii];
    }

    /**
     * Giá: "dưới 300k", "không quá 1 triệu", "từ 200k đến 400k", "khoảng 500 nghìn",
     * "tôi có 500k", "300.000đ", "1tr2", "nửa triệu", "trên 500k"
     */
    private function extractPrice(string $ascii): array
    {
        $ascii = preg_replace('/nua\s*trieu/', '500k', $ascii);
        // "200-400k" => "200k - 400k"
        $ascii = preg_replace('/(?<![\d.,])(\d+)\s*-\s*(\d+(?:[.,]\d+)?)\s*(k|nghin|ngan|tr|trieu)(?![a-z])/', '$1$3 - $2$3', $ascii);

        $amountRe = '/(\d+(?:[.,]\d+)*)\s*(trieu|tr|cu|k|nghin|ngan|vnd|dong|d)?(\d{1,3})?(?![a-z0-9])/';
        preg_match_all($amountRe, $ascii, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        $amounts = [];
        foreach ($matches as $m) {
            $value = $this->moneyValue($m[1][0], $m[2][0] ?? '', $m[3][0] ?? '');
            if ($value !== null) {
                $amounts[] = ['value' => $value, 'start' => $m[0][1], 'end' => $m[0][1] + strlen($m[0][0])];
            }
        }

        if (!$amounts) {
            return [null, null, null, $ascii];
        }

        $min = null;
        $max = null;
        $note = null;

        $first = $amounts[0];
        $before = substr($ascii, max(0, $first['start'] - 30), min(30, $first['start']));
        $after = substr($ascii, $first['end'], 25);

        if (count($amounts) >= 2) {
            $between = substr($ascii, $first['end'], $amounts[1]['start'] - $first['end']);
            if (preg_match('/^\s*(-|~|den|toi|tro len den)\s*$/', $between)) {
                $min = min($first['value'], $amounts[1]['value']);
                $max = max($first['value'], $amounts[1]['value']);
                $note = 'range';
            }
        }

        if ($note === null) {
            $v = $first['value'];
            if (preg_match('/(duoi|khong qua|toi da|nho hon|re hon|it hon|<|max|chua toi|khong vuot)\s*(muc\s*)?$/', $before)
                || preg_match('/^\s*(do lai|tro xuong|tro lai|toi da)/', $after)) {
                $max = $v;
                $note = 'max';
            } elseif (preg_match('/(tren|hon|lon hon|dat hon|it nhat|toi thieu|>|min)\s*$/', $before)
                || preg_match('/^\s*(tro len|tro di)/', $after)) {
                $min = $v;
                $note = 'min';
            } elseif (preg_match('/(co|ngan sach|tai chinh|budget|chi|voi)\s*$/', $before)) {
                $max = $v;
                $note = 'budget';
            } else {
                // "khoảng 500k", "tầm 300 nghìn" hoặc chỉ nêu giá: cho phép lệch 10%
                $max = (int) (round($v * 1.1 / 1000) * 1000);
                $note = 'approx';
            }
        }

        foreach (array_reverse($amounts) as $a) {
            $ascii = substr_replace($ascii, ' ', $a['start'], $a['end'] - $a['start']);
        }

        return [$min, $max, $note, $ascii];
    }

    private function moneyValue(string $number, string $unit, string $tail): ?int
    {
        // "300.000" / "1,500,000" là dấu phân cách hàng nghìn; "1.5" là số thập phân
        if (preg_match('/^\d{1,3}([.,]\d{3})+$/', $number)) {
            $num = (float) preg_replace('/[.,]/', '', $number);
        } else {
            $num = (float) str_replace(',', '.', $number);
        }

        $value = match ($unit) {
            'trieu', 'tr', 'cu' => $num * 1_000_000 + ($tail !== '' ? (int) str_pad($tail, 3, '0') * 1000 : 0),
            'k', 'nghin', 'ngan' => $num * 1000,
            'vnd', 'dong', 'd' => $num,
            default => $num >= 10000 ? $num : null, // số trơn chỉ coi là tiền khi đủ lớn
        };

        if ($value === null || $value < 10000 || $value > 100_000_000) {
            return null;
        }

        return (int) $value;
    }
}
