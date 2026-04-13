<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">
    <div class="mb-8 md:mb-10">
        <x-breadcrumb :items="[['label' => 'Cửa hàng']]" />
        <div class="mt-4 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="fashion-title text-3xl md:text-4xl">Cửa hàng thời trang</h1>
                <p class="fashion-subtitle mt-2">Chọn theo phong cách, màu sắc và mức giá phù hợp với bạn.</p>
            </div>
            <div class="w-full md:w-96">
                <input
                    type="text"
                    wire:model.live="keyword"
                    placeholder="Tìm theo tên, mô tả hoặc SKU..."
                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 focus:border-red-400 focus:outline-none"
                >
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <aside class="lg:col-span-1">
            <div class="fashion-section p-5 sticky top-24 space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="fashion-title text-lg">Bộ lọc</h2>
                    @if($hasActiveFilters)
                        <button wire:click="resetFilters" class="text-xs font-bold text-red-500 hover:text-red-600">Đặt lại</button>
                    @endif
                </div>

                @if($categories->count() > 0)
                    <div class="border-t border-slate-200 pt-5">
                        <h3 class="text-sm font-bold text-slate-900 mb-3">Danh mục</h3>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm text-slate-600">
                                <input type="radio" name="category" value="" wire:model.live="category" class="border-slate-300 text-red-500 focus:ring-red-500">
                                Tất cả
                            </label>
                            @foreach($categories as $cat)
                                <div>
                                    <label class="flex items-center gap-2 text-sm text-slate-700">
                                        <input type="radio" name="category" value="{{ $cat->id }}" wire:model.live="category" class="border-slate-300 text-red-500 focus:ring-red-500">
                                        {{ $cat->name }}
                                    </label>
                                    @if($cat->children->count() > 0)
                                        <div class="ml-6 mt-2 space-y-2">
                                            @foreach($cat->children as $child)
                                                <label class="flex items-center gap-2 text-xs text-slate-500">
                                                    <input type="radio" name="category" value="{{ $child->id }}" wire:model.live="category" class="border-slate-300 text-red-500 focus:ring-red-500">
                                                    {{ $child->name }}
                                                </label>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($brands->count() > 0)
                    <div class="border-t border-slate-200 pt-5">
                        <h3 class="text-sm font-bold text-slate-900 mb-3">Thương hiệu</h3>
                        <div class="space-y-2 max-h-56 overflow-y-auto pr-2">
                            @foreach($brands as $b)
                                <label class="flex items-center gap-2 text-sm text-slate-700">
                                    <input
                                        type="checkbox"
                                        value="{{ $b->id }}"
                                        wire:change="toggleBrand('{{ $b->id }}')"
                                        @checked(in_array($b->id, $brand))
                                        class="rounded border-slate-300 text-red-500 focus:ring-red-500"
                                    >
                                    <span>{{ $b->name }}</span>
                                    <span class="ml-auto text-xs text-slate-400">{{ $b->products_count ?? 0 }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($sizes->count() > 0)
                    <div class="border-t border-slate-200 pt-5">
                        <h3 class="text-sm font-bold text-slate-900 mb-3">Kích cỡ</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($sizes as $s)
                                <button
                                    wire:click="toggleSize('{{ $s->id }}')"
                                    @class([
                                        'rounded-full border px-3 py-1.5 text-xs font-bold transition-all',
                                        'border-red-500 bg-red-500 text-white' => in_array($s->id, $size),
                                        'border-slate-300 text-slate-600 hover:border-slate-500' => !in_array($s->id, $size),
                                    ])
                                >
                                    {{ $s->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($colors->count() > 0)
                    <div class="border-t border-slate-200 pt-5">
                        <h3 class="text-sm font-bold text-slate-900 mb-3">Màu sắc</h3>
                        <div class="flex flex-wrap gap-3">
                            @foreach($colors as $c)
                                <button
                                    wire:click="toggleColor('{{ $c->id }}')"
                                    title="{{ $c->name }}"
                                    class="relative"
                                >
                                    <span
                                        @class([
                                            'block h-7 w-7 rounded-full border-2 transition-all',
                                            'border-red-500 ring-2 ring-red-200' => in_array($c->id, $color),
                                            'border-slate-300 hover:border-slate-500' => !in_array($c->id, $color),
                                        ])
                                        style="background-color: {{ $c->hex_code }}"
                                    ></span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="border-t border-slate-200 pt-5" x-data="{ minPrice: {{ $price_min }}, maxPrice: {{ $price_max }} }">
                    <h3 class="text-sm font-bold text-slate-900 mb-3">Khoảng giá</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <input
                            type="number"
                            x-model.number="minPrice"
                            @change="$wire.price_min = minPrice"
                            placeholder="Từ"
                            class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs focus:border-red-400 focus:outline-none"
                        >
                        <input
                            type="number"
                            x-model.number="maxPrice"
                            @change="$wire.price_max = maxPrice"
                            placeholder="Đến"
                            class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs focus:border-red-400 focus:outline-none"
                        >
                    </div>
                    <p class="mt-3 text-xs text-slate-500">{{ number_format($price_min, 0, ',', '.') }}₫ - {{ number_format($price_max, 0, ',', '.') }}₫</p>
                </div>
            </div>
        </aside>

        <section class="lg:col-span-3">
            <div class="fashion-section p-4 md:p-5 mb-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <p class="text-sm text-slate-600">
                    Có <span class="font-extrabold text-slate-900">{{ $totalProducts }}</span> sản phẩm
                    @if($hasActiveFilters)
                        <button wire:click="resetFilters" class="ml-2 text-red-500 font-bold">Xóa lọc</button>
                    @endif
                </p>

                <div class="flex items-center gap-3">
                    <select wire:model.live="sort" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-red-400 focus:outline-none">
                        <option value="newest">Mới nhất</option>
                        <option value="popularity">Phổ biến</option>
                        <option value="rating">Đánh giá cao</option>
                        <option value="price_low">Giá thấp đến cao</option>
                        <option value="price_high">Giá cao đến thấp</option>
                    </select>

                    <div class="inline-flex rounded-xl border border-slate-300 overflow-hidden">
                        <button
                            wire:click="setViewType('grid')"
                            @class(['px-3 py-2 text-sm font-semibold', 'bg-red-500 text-white' => $view_type === 'grid', 'text-slate-600 bg-white' => $view_type !== 'grid'])
                        >
                            Lưới
                        </button>
                        <button
                            wire:click="setViewType('list')"
                            @class(['px-3 py-2 text-sm font-semibold border-l border-slate-300', 'bg-red-500 text-white' => $view_type === 'list', 'text-slate-600 bg-white' => $view_type !== 'list'])
                        >
                            Danh sách
                        </button>
                    </div>
                </div>
            </div>

            @if($products->count() > 0)
                @if($view_type === 'grid')
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                        @foreach($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>
                @else
                    <div class="space-y-4 mb-8">
                        @foreach($products as $product)
                            @php
                                $listImage = $product->image_url ?? 'images/placeholder.jpg';
                                $listSrc = str_starts_with($listImage, 'http://') || str_starts_with($listImage, 'https://') ? $listImage : asset($listImage);
                            @endphp
                            <article class="fashion-card overflow-hidden">
                                <div class="grid md:grid-cols-[180px_1fr] gap-0">
                                    <a href="{{ route('products.show', $product->slug) }}" class="block h-44 md:h-full bg-slate-100">
                                        <img src="{{ $listSrc }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                    </a>
                                    <div class="p-4 md:p-5 flex flex-col justify-between gap-3">
                                        <div>
                                            <p class="text-xs uppercase tracking-[0.18em] text-slate-500">{{ $product->category->name ?? 'Chưa phân loại' }}</p>
                                            <a href="{{ route('products.show', $product->slug) }}" class="mt-1 block text-lg font-extrabold text-slate-900 hover:text-red-500 line-clamp-2">{{ $product->name }}</a>
                                        </div>
                                        <div class="flex flex-wrap items-center justify-between gap-3">
                                            <div>
                                                @if($product->sale_price)
                                                    <span class="text-2xl font-extrabold text-red-500">{{ number_format($product->sale_price, 0) }}₫</span>
                                                    <span class="ml-2 text-sm text-slate-400 line-through">{{ number_format($product->price, 0) }}₫</span>
                                                @else
                                                    <span class="text-2xl font-extrabold text-slate-900">{{ number_format($product->price, 0) }}₫</span>
                                                @endif
                                            </div>
                                            <a href="{{ route('products.show', $product->slug) }}" class="btn-primary">Xem chi tiết</a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif

                @if($products->hasPages())
                    <x-pagination :paginator="$products" />
                @endif
            @else
                <div class="fashion-section py-14 px-6 text-center">
                    <h3 class="fashion-title text-2xl">Không tìm thấy sản phẩm</h3>
                    <p class="fashion-subtitle mt-2">Hãy thử thay đổi từ khóa hoặc tiêu chí lọc.</p>
                    <button wire:click="resetFilters" class="btn-primary mt-5">Xóa bộ lọc</button>
                </div>
            @endif
        </section>
    </div>
</div>
