<?php

namespace App\Services\Chatbot;

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use App\Support\ProductAttributes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * GOI_Y_SAN_PHAM
 * Tìm sản phẩm thật trong database theo tiêu chí đã phân tích.
 * - Điều kiện "cứng": giới tính, môn thể thao, loại sản phẩm, giá, màu, size, từ khóa.
 * - Điều kiện "mềm" (cộng điểm xếp hạng): mục đích sử dụng, kiểu dáng, nổi bật, đang giảm giá.
 * - Không có kết quả đúng hoàn toàn => nới lỏng lần lượt size → màu → từ khóa → giá
 *   và báo rõ cho khách tiêu chí nào chưa đáp ứng được (không bịa sản phẩm).
 */
class ProductRecommender
{
    /**
     * Loại sản phẩm => [slug danh mục (gồm cả danh mục con), từ khóa trong tên (dự phòng), slug danh mục loại trừ]
     */
    private const TYPE_MAP = [
        'ao' => [['ao-the-thao'], null, ['ao-khoac-the-thao']],
        'ao-khoac' => [['ao-khoac-the-thao'], 'khoác'],
        'quan' => [['quan-the-thao'], null],
        'quan-short' => [['quan-short-tap'], 'short'],
        'quan-jogger' => [['quan-jogger', 'quan-ni'], 'jogger'],
        'giay' => [['giay-the-thao'], null],
        'phu-kien' => [['phu-kien', 'do-lot-the-thao', 'thiet-bi-tap'], null],
        'do-lot' => [['quan-lot-the-thao'], null],
        'tat' => [['tatvo'], null],
        'non' => [['non-the-thao'], null],
        'tui' => [['tui-tap-gym'], null],
        'bang-co-tay' => [['bang-co-tay'], null],
        'khan' => [['khan-tap'], null],
        'ta' => [['ta-tay'], null],
        'day-nhay' => [['day-nhay'], null],
        'tham' => [['tham-yoga'], null],
    ];

    /** Loại sản phẩm dùng size quần áo (XS–XXXL) – size tư vấn theo chiều cao/cân nặng chỉ áp dụng cho các loại này */
    public const APPAREL_TYPES = ['ao', 'ao-khoac', 'quan', 'quan-short', 'quan-jogger', 'do-lot'];

    /** Danh mục quần áo – được ưu tiên khi khách hỏi chung chung ("đồ chạy bộ") */
    private const APPAREL_ROOTS = ['ao-the-thao', 'quan-the-thao'];

    private const SIZE_ORDER = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', 'FREE'];

    public const TYPE_LABELS = [
        'ao' => 'áo',
        'ao-khoac' => 'áo khoác',
        'quan' => 'quần',
        'quan-short' => 'quần short',
        'quan-jogger' => 'quần dài/jogger',
        'giay' => 'giày',
        'phu-kien' => 'phụ kiện',
        'do-lot' => 'đồ lót',
        'tat' => 'tất/vớ',
        'non' => 'nón/mũ',
        'tui' => 'túi/balo',
        'bang-co-tay' => 'băng cổ tay',
        'khan' => 'khăn tập',
        'ta' => 'tạ tay',
        'day-nhay' => 'dây nhảy',
        'tham' => 'thảm tập',
    ];

    private const RELAX_ORDER = ['size', 'colors', 'keywords', 'price'];

    private array $categoryCache = [];

    /**
     * @return array{products: Collection, relaxed: array}
     */
    public function search(array $c, int $limit = 6): array
    {
        foreach ($this->relaxSteps($c) as $dropped) {
            $criteria = $this->drop($c, $dropped);
            $products = $this->rank($this->query($criteria)->limit(80)->get(), $criteria, in_array('price', $dropped, true));

            if ($products->isNotEmpty()) {
                return ['products' => $products->take($limit)->values(), 'relaxed' => $dropped];
            }
        }

        return ['products' => collect(), 'relaxed' => []];
    }

    /**
     * Gợi ý bộ đồ (áo + quần) có tổng giá nằm trong ngân sách.
     *
     * @return array{outfits: array, relaxed: array}
     */
    public function outfits(array $c, int $limit = 3): array
    {
        $budgetMax = $c['price_max'];
        $budgetMin = $c['price_min'];

        $base = array_merge($c, ['price_min' => null, 'price_max' => $budgetMax, 'keywords' => []]);
        $topType = in_array($c['product_type'], ['ao', 'ao-khoac'], true) ? $c['product_type'] : 'ao';
        $bottomType = in_array($c['product_type'], ['quan', 'quan-short', 'quan-jogger'], true) ? $c['product_type'] : 'quan';

        $tops = $this->search(array_merge($base, ['product_type' => $topType]), 15);
        $bottoms = $this->search(array_merge($base, ['product_type' => $bottomType]), 15);
        $relaxed = array_values(array_unique(array_merge($tops['relaxed'], $bottoms['relaxed'])));

        $combos = [];
        foreach ($tops['products'] as $top) {
            foreach ($bottoms['products'] as $bottom) {
                // Không phối áo nam với quần nữ (và ngược lại)
                if ($top->gender !== $bottom->gender && !in_array('unisex', [$top->gender, $bottom->gender], true)) {
                    continue;
                }

                $total = $this->price($top) + $this->price($bottom);
                $score = $top->chat_score + $bottom->chat_score;

                if ($budgetMax !== null) {
                    if ($total > $budgetMax || ($budgetMin !== null && $total < $budgetMin)) {
                        continue;
                    }
                    $score += 4 * $total / $budgetMax; // tận dụng ngân sách tốt hơn
                    // "khoảng 500k": bộ vượt 500k vẫn được gợi ý nhưng xếp sau
                    $nominal = $c['price_note'] === 'approx' ? $budgetMax / 1.1 : $budgetMax;
                    if ($total > $nominal) {
                        $score -= 3;
                    }
                }

                $sharedColors = array_intersect($this->colorNames($top), $this->colorNames($bottom));
                if ($sharedColors) {
                    $score += 1; // dễ phối cùng tông màu
                }

                $combos[] = ['top' => $top, 'bottom' => $bottom, 'total' => $total, 'score' => $score];
            }
        }

        usort($combos, $c['sort'] === 'price_asc'
            ? fn ($a, $b) => $a['total'] <=> $b['total']
            : fn ($a, $b) => $b['score'] <=> $a['score']);

        $result = [];
        $usedTops = [];
        $usedBottoms = [];
        foreach ($combos as $combo) {
            if (isset($usedTops[$combo['top']->id]) || isset($usedBottoms[$combo['bottom']->id])) {
                continue;
            }
            $usedTops[$combo['top']->id] = true;
            $usedBottoms[$combo['bottom']->id] = true;
            $result[] = $combo;
            if (count($result) >= $limit) {
                break;
            }
        }

        return ['outfits' => $result, 'relaxed' => $relaxed];
    }

    public function price(Product $p): int
    {
        return (int) ($p->sale_price ?? $p->price);
    }

    /** Màu còn hàng (tên tiếng Anh trong DB) */
    public function colorNames(Product $p, ?string $size = null): array
    {
        return $p->variants
            ->filter(fn ($v) => $v->stock_quantity > 0 && $v->color && (!$size || $v->size?->code === $size))
            ->map(fn ($v) => $v->color->name)
            ->unique()->values()->all();
    }

    public function sizeCodes(Product $p): array
    {
        $order = self::SIZE_ORDER;

        return $p->variants
            ->filter(fn ($v) => $v->stock_quantity > 0 && $v->size)
            ->map(fn ($v) => $v->size->code)
            ->unique()
            ->sortBy(fn ($code) => array_search($code, $order, true) === false ? 99 : array_search($code, $order, true))
            ->values()->all();
    }

    // ------------------------------------------------------------------

    private function relaxSteps(array $c): array
    {
        // Khách muốn mẫu rẻ hơn: giữ nguyên màu/size, chỉ nới giá trước (=> trả về mẫu rẻ nhất phù hợp)
        $order = $c['sort'] === 'price_asc'
            ? array_merge(['price'], array_diff(self::RELAX_ORDER, ['price']))
            : self::RELAX_ORDER;

        $active = array_values(array_filter($order, function ($key) use ($c) {
            return match ($key) {
                'size' => $c['size'] !== null,
                'colors' => !empty($c['colors']) || !empty($c['color_words']),
                'keywords' => !empty($c['keywords']),
                'price' => $c['price_min'] !== null || $c['price_max'] !== null,
            };
        }));

        $steps = [[]];
        for ($i = 1; $i <= count($active); $i++) {
            $steps[] = array_slice($active, 0, $i);
        }

        return $steps;
    }

    private function drop(array $c, array $dropped): array
    {
        foreach ($dropped as $key) {
            match ($key) {
                'size' => $c['size'] = null,
                'colors' => [$c['colors'], $c['color_words']] = [[], []],
                'keywords' => $c['keywords'] = [],
                'price' => [$c['price_min'], $c['price_max']] = [null, null],
            };
        }

        return $c;
    }

    private function query(array $c): Builder
    {
        $q = Product::query()
            ->active()
            ->with(['images', 'variants.size', 'variants.color', 'category'])
            ->whereHas('variants', fn ($v) => $v->where('stock_quantity', '>', 0));

        if (in_array($c['gender'], ['nam', 'nu'], true)) {
            $q->whereIn('gender', [$c['gender'], 'unisex']);
        }

        if ($c['unsupported_sport']) {
            $q->whereRaw('1 = 0'); // shop không kinh doanh môn này
        } elseif ($c['sport']) {
            $q->where('sport', $c['sport']);
        }

        if ($c['product_type'] && isset(self::TYPE_MAP[$c['product_type']])) {
            [$slugs, $nameKeyword, $exclude] = self::TYPE_MAP[$c['product_type']] + [2 => []];
            $categoryIds = array_values(array_diff($this->categoryIds($slugs), $this->categoryIds($exclude)));
            $q->where(function (Builder $w) use ($categoryIds, $nameKeyword) {
                $w->whereIn('category_id', $categoryIds ?: [0]);
                if ($nameKeyword) {
                    $w->orWhere('name', 'like', '%' . $nameKeyword . '%');
                }
            });
        }

        if ($c['price_min'] !== null) {
            $q->whereRaw('COALESCE(sale_price, price) >= ?', [$c['price_min']]);
        }
        if ($c['price_max'] !== null) {
            $q->whereRaw('COALESCE(sale_price, price) <= ?', [$c['price_max']]);
        }

        if (!empty($c['on_sale'])) {
            $q->whereNotNull('sale_price')->whereColumn('sale_price', '<', 'price');
        }

        foreach ($c['keywords'] as $keyword) {
            $q->where('name', 'like', '%' . $keyword . '%');
        }

        $wantsColor = !empty($c['colors']) || !empty($c['color_words']);
        if ($wantsColor || $c['size']) {
            $colorIds = $wantsColor ? Color::whereIn('name', $c['colors'])->pluck('id')->all() : [];
            $sizeId = $c['size'] ? Size::where('code', $c['size'])->value('id') : null;

            if (($wantsColor && !$colorIds) || ($c['size'] && !$sizeId)) {
                $q->whereRaw('1 = 0'); // màu/size shop không có
            } else {
                // Cùng một biến thể phải vừa đúng màu vừa đúng size và còn hàng
                $q->whereHas('variants', function ($v) use ($colorIds, $sizeId) {
                    $v->where('stock_quantity', '>', 0);
                    if ($colorIds) {
                        $v->whereIn('color_id', $colorIds);
                    }
                    if ($sizeId) {
                        $v->where('size_id', $sizeId);
                    }
                });
            }
        }

        return $q;
    }

    private function rank(Collection $products, array $c, bool $priceRelaxed): Collection
    {
        foreach ($products as $p) {
            $score = 0;
            if ($c['purpose'] && $p->purpose === $c['purpose']) {
                $score += 3;
            }
            if ($c['style'] && $p->style === $c['style']) {
                $score += 2;
            }
            if ($c['gender'] && $p->gender === $c['gender']) {
                $score += 1; // đúng giới tính ưu tiên hơn unisex
            }
            if ($p->is_featured) {
                $score += 1;
            }
            if ($p->sale_price && $p->sale_price < $p->price) {
                $score += 0.5;
            }
            if (!$c['product_type'] && $this->isApparel($p)) {
                $score += 1.5;
            }
            $score += min(1, ($p->view_count ?? 0) / 1000);
            $p->chat_score = $score;
        }

        $sort = $priceRelaxed ? 'price_asc' : $c['sort'];

        $sorted = match ($sort) {
            'price_asc' => $products->sortBy(fn ($p) => $this->price($p)),
            'price_desc' => $products->sortByDesc(fn ($p) => $this->price($p)),
            'newest' => $products->sortByDesc('created_at'),
            'popular' => $products->sortByDesc('view_count'),
            default => $products->sortByDesc('chat_score'),
        };

        return $sorted->values();
    }

    private function isApparel(Product $p): bool
    {
        return in_array($p->category_id, $this->categoryIds(self::APPAREL_ROOTS));
    }

    private function categoryIds(array $slugs): array
    {
        $key = implode(',', $slugs);
        if (isset($this->categoryCache[$key])) {
            return $this->categoryCache[$key];
        }

        $ids = Category::whereIn('slug', $slugs)->pluck('id')->all();
        $frontier = $ids;
        while ($frontier) {
            $frontier = Category::whereIn('parent_id', $frontier)->pluck('id')->diff($ids)->all();
            $ids = array_merge($ids, $frontier);
        }

        return $this->categoryCache[$key] = $ids;
    }

    /** Dữ liệu sản phẩm trả về cho giao diện chat */
    public function toCard(Product $p, ?string $size = null): array
    {
        $price = $this->price($p);
        $onSale = $p->sale_price && $p->sale_price < $p->price;

        return [
            'id' => $p->id,
            'name' => $p->name,
            'url' => route('products.show', $p->slug),
            'image' => $p->image_url,
            'price' => $price,
            'original_price' => $onSale ? (int) $p->price : null,
            'discount' => $onSale ? (int) $p->sale_price_percent : 0,
            'category' => $p->category?->name,
            'gender' => ProductAttributes::label('gender', $p->gender),
            'sport' => ProductAttributes::label('sport', $p->sport),
            'colors' => array_map(fn ($n) => ProductAttributes::colorVi($n), $this->colorNames($p)),
            'swatches' => $p->variants
                ->filter(fn ($v) => $v->stock_quantity > 0 && $v->color)
                ->map(fn ($v) => ['name' => ProductAttributes::colorVi($v->color->name), 'hex' => $v->color->hex_code])
                ->unique('name')->values()->all(),
            'sizes' => $this->sizeCodes($p),
            'has_size' => $size ? in_array($size, $this->sizeCodes($p), true) : null,
        ];
    }
}
