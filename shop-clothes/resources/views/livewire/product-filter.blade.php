<div x-data="{ showMobileFilter: false, filterOpen: { category: true, brand: true, size: true, color: true, price: true } }">

    {{-- ═══════════════════════════════════════════════ --}}
    {{-- HERO SECTION --}}
    {{-- ═══════════════════════════════════════════════ --}}
    <section class="shop-hero">
        <div class="relative z-10 max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-12 py-12 md:py-16">
            <div class="max-w-2xl">
                {{-- Breadcrumb --}}
                <nav class="flex items-center gap-2 text-sm text-white/50 mb-6">
                    <a href="{{ route('home') }}" class="hover:text-white transition-colors">Trang chủ</a>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-white font-medium">Cửa hàng</span>
                </nav>

                <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight">
                    {{ $collectionLabel }}
                </h1>
                <p class="mt-3 text-base md:text-lg text-white/60 leading-relaxed max-w-lg">
                    Khám phá bộ sưu tập thời trang thể thao mới nhất. Phong cách hiện đại, chất liệu cao cấp, giá tốt nhất.
                </p>

                {{-- Category Chips --}}
                @if($categories->count() > 0)
                    <div class="mt-6 flex flex-wrap gap-2">
                        <button
                            wire:click="$set('category', '')"
                            class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold transition-all border {{ !$category ? 'bg-red-500 text-white border-red-500 shadow-md shadow-red-500/30' : 'bg-white/10 text-white/80 border-white/20 hover:bg-white/20 hover:text-white' }}"
                        >
                            Tất cả
                        </button>
                        @foreach($categories as $cat)
                            <button
                                wire:click="$set('category', '{{ $cat->id }}')"
                                class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold transition-all border {{ $category == $cat->id ? 'bg-red-500 text-white border-red-500 shadow-md shadow-red-500/30' : 'bg-white/10 text-white/80 border-white/20 hover:bg-white/20 hover:text-white' }}"
                            >
                                {{ $cat->name }}
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Stats --}}
            <div class="mt-8 flex items-center gap-6 text-white/40 text-sm">
                <div class="flex items-center gap-2">
                    <span class="text-2xl font-black text-white">{{ $totalProducts }}</span>
                    <span>sản phẩm</span>
                </div>
            </div>
        </div>

        {{-- Decorative Element --}}
        <div class="absolute right-0 bottom-0 w-1/3 h-full hidden lg:block opacity-20">
            <svg viewBox="0 0 400 300" fill="none" class="h-full w-full">
                <circle cx="300" cy="150" r="200" stroke="white" stroke-opacity="0.1" stroke-width="1"/>
                <circle cx="300" cy="150" r="150" stroke="white" stroke-opacity="0.08" stroke-width="1"/>
                <circle cx="300" cy="150" r="100" stroke="white" stroke-opacity="0.06" stroke-width="1"/>
            </svg>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════ --}}
    {{-- MAIN CONTENT --}}
    {{-- ═══════════════════════════════════════════════ --}}
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-12 py-8 md:py-10">

        {{-- Active Filter Tags --}}
        @if($hasActiveFilters)
            <div class="mb-6 flex flex-wrap items-center gap-2 animate-[fadeIn_0.3s_ease]">
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Đang lọc:</span>
                @if($keyword)
                    <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 text-xs font-medium px-3 py-1.5 rounded-full">
                        "{{ $keyword }}"
                        <button wire:click="$set('keyword', '')" class="text-slate-400 hover:text-red-500 transition-colors ml-0.5">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </span>
                @endif
                @if($category)
                    <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 text-xs font-medium px-3 py-1.5 rounded-full">
                        {{ $categories->firstWhere('id', $category)?->name ?? 'Danh mục' }}
                        <button wire:click="$set('category', '')" class="text-slate-400 hover:text-red-500 transition-colors ml-0.5">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </span>
                @endif
                @foreach($brand as $brandId)
                    @php $b = $brands->firstWhere('id', $brandId); @endphp
                    @if($b)
                        <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 text-xs font-medium px-3 py-1.5 rounded-full">
                            {{ $b->name }}
                            <button wire:click="toggleBrand('{{ $brandId }}')" class="text-slate-400 hover:text-red-500 transition-colors ml-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </span>
                    @endif
                @endforeach
                @foreach($size as $sizeId)
                    @php $s = $sizes->firstWhere('id', $sizeId); @endphp
                    @if($s)
                        <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 text-xs font-medium px-3 py-1.5 rounded-full">
                            {{ $s->short_label }}
                            <button wire:click="toggleSize('{{ $sizeId }}')" class="text-slate-400 hover:text-red-500 transition-colors ml-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </span>
                    @endif
                @endforeach
                @foreach($color as $colorId)
                    @php $c = $colors->firstWhere('id', $colorId); @endphp
                    @if($c)
                        <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 text-xs font-medium px-3 py-1.5 rounded-full">
                            {{ $c->name }}
                            <button wire:click="toggleColor('{{ $colorId }}')" class="text-slate-400 hover:text-red-500 transition-colors ml-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </span>
                    @endif
                @endforeach
                <button wire:click="resetFilters" class="text-xs text-red-500 hover:text-red-600 font-semibold ml-2 transition-colors">
                    Xóa tất cả
                </button>
            </div>
        @endif

        {{-- Search Bar --}}
        <div class="mb-6">
            <div class="relative max-w-xl">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-4.5 h-4.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35" stroke-linecap="round"/>
                    </svg>
                </div>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="keyword"
                    placeholder="Tìm kiếm sản phẩm..."
                    class="w-full h-12 pl-11 pr-4 rounded-2xl border border-slate-200 bg-white text-sm text-slate-700 placeholder-slate-400 focus:border-red-400 focus:ring-2 focus:ring-red-100 focus:outline-none transition-all"
                >
                @if($keyword)
                    <button wire:click="$set('keyword', '')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                @endif
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════ --}}
        {{-- LAYOUT: Sidebar + Product Grid --}}
        {{-- ═══════════════════════════════════════════════ --}}
        <div class="flex gap-8">

            {{-- ───────────────────────────────────────── --}}
            {{-- FILTER SIDEBAR (Desktop) --}}
            {{-- ───────────────────────────────────────── --}}
            <aside class="hidden lg:block w-64 flex-shrink-0">
                <div class="sticky top-28">
                    <div class="filter-card">
                        {{-- Header --}}
                        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                            <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                </svg>
                                Bộ lọc
                            </h2>
                            @if($hasActiveFilters)
                                <button wire:click="resetFilters" class="text-xs font-semibold text-red-500 hover:text-red-600 transition-colors">Đặt lại</button>
                            @endif
                        </div>

                        {{-- Category Filter --}}
                        @if($categories->count() > 0)
                            <div class="filter-section">
                                <button wire:click="filterOpen.category = !filterOpen.category" class="filter-section-header">
                                    <span class="text-[13px] font-bold text-slate-800">Danh mục</span>
                                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': filterOpen.category }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div x-show="filterOpen.category" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1" class="filter-section-body">
                                    <div class="space-y-1">
                                        <label class="flex items-center gap-3 py-1.5 cursor-pointer group">
                                            <input type="radio" name="category" value="" wire:model.live="category" class="filter-radio">
                                            <span class="text-sm text-slate-600 group-hover:text-slate-900 transition-colors">Tất cả</span>
                                        </label>
                                        @foreach($categories as $cat)
                                            <label class="flex items-center gap-3 py-1.5 cursor-pointer group">
                                                <input type="radio" name="category" value="{{ $cat->id }}" wire:model.live="category" class="filter-radio">
                                                <span class="text-sm text-slate-600 group-hover:text-slate-900 transition-colors">{{ $cat->name }}</span>
                                            </label>
                                            @if($cat->children->count() > 0)
                                                <div class="ml-6 space-y-1">
                                                    @foreach($cat->children as $child)
                                                        <label class="flex items-center gap-3 py-1 cursor-pointer group">
                                                            <input type="radio" name="category" value="{{ $child->id }}" wire:model.live="category" class="filter-radio">
                                                            <span class="text-xs text-slate-500 group-hover:text-slate-700 transition-colors">{{ $child->name }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Brand Filter --}}
                        @if($brands->count() > 0)
                            <div class="filter-section">
                                <button wire:click="filterOpen.brand = !filterOpen.brand" class="filter-section-header">
                                    <span class="text-[13px] font-bold text-slate-800">Thương hiệu</span>
                                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': filterOpen.brand }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div x-show="filterOpen.brand" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1" class="filter-section-body">
                                    <div class="space-y-1 max-h-52 overflow-y-auto pr-1">
                                        @foreach($brands as $b)
                                            <label class="flex items-center gap-3 py-1.5 cursor-pointer group">
                                                <input
                                                    type="checkbox"
                                                    value="{{ $b->id }}"
                                                    wire:change="toggleBrand('{{ $b->id }}')"
                                                    @checked(in_array($b->id, $brand))
                                                    class="filter-checkbox"
                                                >
                                                <span class="text-sm text-slate-600 group-hover:text-slate-900 transition-colors flex-1">{{ $b->name }}</span>
                                                <span class="text-[11px] text-slate-400 font-medium tabular-nums">{{ $b->products_count ?? 0 }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Size Filter --}}
                        @if($sizes->count() > 0)
                            <div class="filter-section">
                                <button wire:click="filterOpen.size = !filterOpen.size" class="filter-section-header">
                                    <span class="text-[13px] font-bold text-slate-800">Kích cỡ</span>
                                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': filterOpen.size }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div x-show="filterOpen.size" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1" class="filter-section-body">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($sizes as $s)
                                            <button
                                                wire:click="toggleSize('{{ $s->id }}')"
                                                @class([
                                                    'inline-flex items-center justify-center h-9 min-w-[36px] px-3 rounded-lg text-xs font-bold transition-all border',
                                                    'bg-slate-900 text-white border-slate-900 shadow-sm' => in_array($s->id, $size),
                                                    'bg-white text-slate-600 border-slate-200 hover:border-slate-400 hover:text-slate-900' => !in_array($s->id, $size),
                                                ])
                                            >
                                                {{ $s->short_label }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Color Filter --}}
                        @if($colors->count() > 0)
                            <div class="filter-section">
                                <button wire:click="filterOpen.color = !filterOpen.color" class="filter-section-header">
                                    <span class="text-[13px] font-bold text-slate-800">Màu sắc</span>
                                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': filterOpen.color }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div x-show="filterOpen.color" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1" class="filter-section-body">
                                    <div class="flex flex-wrap gap-2.5">
                                        @foreach($colors as $c)
                                            <button
                                                wire:click="toggleColor('{{ $c->id }}')"
                                                title="{{ $c->name }}"
                                                class="group relative"
                                            >
                                                <span
                                                    @class([
                                                        'block h-8 w-8 rounded-full border-2 transition-all shadow-sm',
                                                        'border-slate-900 ring-2 ring-slate-900/20 scale-110' => in_array($c->id, $color),
                                                        'border-slate-200 hover:border-slate-400 hover:scale-105' => !in_array($c->id, $color),
                                                    ])
                                                    style="background-color: {{ $c->hex_code }}"
                                                ></span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Price Filter --}}
                        <div class="filter-section" x-data="{ minPrice: {{ $price_min }}, maxPrice: {{ $price_max }} }">
                            <button wire:click="filterOpen.price = !filterOpen.price" class="filter-section-header">
                                <span class="text-[13px] font-bold text-slate-800">Khoảng giá</span>
                                <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': filterOpen.price }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="filterOpen.price" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1" class="filter-section-body">
                                <div class="grid grid-cols-2 gap-2 mb-3">
                                    <div class="relative">
                                        <input
                                            type="number"
                                            x-model.number="minPrice"
                                            @change="$wire.price_min = minPrice"
                                            placeholder="Từ"
                                            class="w-full h-10 rounded-lg border border-slate-200 px-3 text-xs text-slate-700 placeholder-slate-400 focus:border-red-400 focus:ring-2 focus:ring-red-100 focus:outline-none transition-all"
                                        >
                                    </div>
                                    <div class="relative">
                                        <input
                                            type="number"
                                            x-model.number="maxPrice"
                                            @change="$wire.price_max = maxPrice"
                                            placeholder="Đến"
                                            class="w-full h-10 rounded-lg border border-slate-200 px-3 text-xs text-slate-700 placeholder-slate-400 focus:border-red-400 focus:ring-2 focus:ring-red-100 focus:outline-none transition-all"
                                        >
                                    </div>
                                </div>
                                <p class="text-xs text-slate-400 font-medium">
                                    {{ number_format($price_min, 0, ',', '.') }}₫ – {{ number_format($price_max, 0, ',', '.') }}₫
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            {{-- ───────────────────────────────────────── --}}
            {{-- PRODUCT LIST --}}
            {{-- ───────────────────────────────────────── --}}
            <section class="flex-1 min-w-0">

                {{-- Sort / Toolbar Bar --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div class="flex items-center gap-3">
                        {{-- Mobile Filter Button --}}
                        <button @click="showMobileFilter = true" class="lg:hidden inline-flex items-center gap-2 h-10 px-4 rounded-xl border border-slate-200 text-sm font-semibold text-slate-700 hover:border-slate-300 hover:bg-slate-50 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Bộ lọc
                            @if($hasActiveFilters)
                                <span class="h-2 w-2 rounded-full bg-red-500"></span>
                            @endif
                        </button>

                        <p class="text-sm text-slate-500">
                            <span class="font-bold text-slate-900">{{ $totalProducts }}</span> sản phẩm
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        {{-- Sort Dropdown --}}
                        <div class="relative">
                            <select wire:model.live="sort" 
                                    class="h-10 pl-4 pr-10 rounded-xl border border-slate-200 bg-white text-sm font-medium text-slate-700 focus:border-red-400 focus:ring-2 focus:ring-red-100 focus:outline-none appearance-none cursor-pointer transition-all hover:border-slate-300">
                                <option value="newest">Mới nhất</option>
                                <option value="popularity">Phổ biến</option>
                                <option value="rating">Đánh giá cao</option>
                                <option value="price_low">Giá thấp → cao</option>
                                <option value="price_high">Giá cao → thấp</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>

                        {{-- View Toggle --}}
                        <div class="hidden sm:inline-flex rounded-xl border border-slate-200 overflow-hidden">
                            <button
                                wire:click="setViewType('grid')"
                                @class([
                                    'inline-flex items-center justify-center h-10 w-10 transition-all',
                                    'bg-slate-900 text-white' => $view_type === 'grid',
                                    'text-slate-400 hover:text-slate-600 hover:bg-slate-50' => $view_type !== 'grid',
                                ])
                                title="Hiển thị lưới"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                                </svg>
                            </button>
                            <button
                                wire:click="setViewType('list')"
                                @class([
                                    'inline-flex items-center justify-center h-10 w-10 border-l border-slate-200 transition-all',
                                    'bg-slate-900 text-white' => $view_type === 'list',
                                    'text-slate-400 hover:text-slate-600 hover:bg-slate-50' => $view_type !== 'list',
                                ])
                                title="Hiển thị danh sách"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Product Grid / List --}}
                @if($products->count() > 0)
                    @if($view_type === 'grid')
                        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-5">
                            @foreach($products as $product)
                                <x-product-card :product="$product" />
                            @endforeach
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($products as $product)
                                @php
                                    $listImage = $product->image_url ?? 'images/product-placeholder.svg';
                                    $listSrc = str_starts_with($listImage, 'http://') || str_starts_with($listImage, 'https://') ? $listImage : asset($listImage);
                                    $listDiscount = $product->sale_price ? round((1 - $product->sale_price / $product->price) * 100) : 0;
                                @endphp
                                <article class="group bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-lg hover:border-slate-200 transition-all duration-300 stagger-enter">
                                    <div class="grid md:grid-cols-[200px_1fr] gap-0">
                                        <a href="{{ route('products.show', $product->slug) }}" class="block relative h-48 md:h-full bg-slate-50 overflow-hidden">
                                            <img src="{{ $listSrc }}" alt="{{ $product->name }}" 
                                                 class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                                 loading="lazy">
                                            @if($listDiscount > 0)
                                                <span class="absolute top-3 left-3 badge badge-sale">-{{ $listDiscount }}%</span>
                                            @endif
                                        </a>
                                        <div class="p-5 md:p-6 flex flex-col justify-between gap-3">
                                            <div>
                                                <p class="text-[11px] font-semibold uppercase tracking-[0.08em] text-slate-400 mb-1.5">
                                                    {{ $product->category->name ?? 'Chưa phân loại' }}
                                                </p>
                                                <a href="{{ route('products.show', $product->slug) }}" class="block text-lg font-bold text-slate-900 hover:text-red-500 transition-colors line-clamp-2">
                                                    {{ $product->name }}
                                                </a>
                                                @if($product->description)
                                                    <p class="mt-2 text-sm text-slate-500 line-clamp-2 leading-relaxed">
                                                        {{ \Illuminate\Support\Str::limit(strip_tags($product->description), 120) }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="flex flex-wrap items-center justify-between gap-3">
                                                <div class="flex items-baseline gap-2">
                                                    @if($product->sale_price)
                                                        <span class="text-xl font-extrabold text-red-500">{{ number_format($product->sale_price, 0, ',', '.') }}₫</span>
                                                        <span class="text-sm text-slate-400 line-through font-medium">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                                    @else
                                                        <span class="text-xl font-extrabold text-slate-900">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                                    @endif
                                                </div>
                                                <a href="{{ route('products.show', $product->slug) }}" class="btn-primary !py-2.5 !px-6">
                                                    Xem chi tiết
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif

                    {{-- Pagination --}}
                    @if($products->hasPages())
                        <x-pagination :paginator="$products" />
                    @endif
                @else
                    {{-- Empty State --}}
                    <div class="flex flex-col items-center justify-center py-20 text-center">
                        <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Không tìm thấy sản phẩm</h3>
                        <p class="text-sm text-slate-500 mb-6 max-w-sm">
                            Không có sản phẩm nào phù hợp với bộ lọc hiện tại. Hãy thử thay đổi tiêu chí tìm kiếm.
                        </p>
                        <button wire:click="resetFilters" class="btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Đặt lại bộ lọc
                        </button>
                    </div>
                @endif
            </section>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════ --}}
    {{-- MOBILE FILTER DRAWER --}}
    {{-- ═══════════════════════════════════════════════ --}}
    <div x-show="showMobileFilter" x-cloak class="lg:hidden">
        {{-- Overlay --}}
        <div x-show="showMobileFilter"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="filter-drawer-overlay"
             @click="showMobileFilter = false">
        </div>

        {{-- Drawer --}}
        <div x-show="showMobileFilter"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="filter-drawer overflow-y-auto">
            
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <h2 class="text-base font-bold text-slate-900">Bộ lọc</h2>
                <button @click="showMobileFilter = false" class="h-8 w-8 inline-flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-5 space-y-5">
                {{-- Category --}}
                @if($categories->count() > 0)
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 mb-3">Danh mục</h3>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 py-1 cursor-pointer">
                                <input type="radio" name="m_category" value="" wire:model.live="category" class="filter-radio">
                                <span class="text-sm text-slate-600">Tất cả</span>
                            </label>
                            @foreach($categories as $cat)
                                <label class="flex items-center gap-3 py-1 cursor-pointer">
                                    <input type="radio" name="m_category" value="{{ $cat->id }}" wire:model.live="category" class="filter-radio">
                                    <span class="text-sm text-slate-600">{{ $cat->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Brand --}}
                @if($brands->count() > 0)
                    <div class="pt-4 border-t border-slate-100">
                        <h3 class="text-sm font-bold text-slate-900 mb-3">Thương hiệu</h3>
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                            @foreach($brands as $b)
                                <label class="flex items-center gap-3 py-1 cursor-pointer">
                                    <input type="checkbox" value="{{ $b->id }}" wire:change="toggleBrand('{{ $b->id }}')" @checked(in_array($b->id, $brand)) class="filter-checkbox">
                                    <span class="text-sm text-slate-600 flex-1">{{ $b->name }}</span>
                                    <span class="text-xs text-slate-400">{{ $b->products_count ?? 0 }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Size --}}
                @if($sizes->count() > 0)
                    <div class="pt-4 border-t border-slate-100">
                        <h3 class="text-sm font-bold text-slate-900 mb-3">Kích cỡ</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($sizes as $s)
                                <button wire:click="toggleSize('{{ $s->id }}')"
                                    @class([
                                        'inline-flex items-center justify-center h-9 min-w-[36px] px-3 rounded-lg text-xs font-bold transition-all border',
                                        'bg-slate-900 text-white border-slate-900' => in_array($s->id, $size),
                                        'bg-white text-slate-600 border-slate-200' => !in_array($s->id, $size),
                                    ])>
                                    {{ $s->short_label }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Color --}}
                @if($colors->count() > 0)
                    <div class="pt-4 border-t border-slate-100">
                        <h3 class="text-sm font-bold text-slate-900 mb-3">Màu sắc</h3>
                        <div class="flex flex-wrap gap-2.5">
                            @foreach($colors as $c)
                                <button wire:click="toggleColor('{{ $c->id }}')" title="{{ $c->name }}">
                                    <span @class([
                                        'block h-8 w-8 rounded-full border-2 transition-all',
                                        'border-slate-900 ring-2 ring-slate-900/20' => in_array($c->id, $color),
                                        'border-slate-200' => !in_array($c->id, $color),
                                    ]) style="background-color: {{ $c->hex_code }}"></span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Price --}}
                <div class="pt-4 border-t border-slate-100" x-data="{ minPrice: {{ $price_min }}, maxPrice: {{ $price_max }} }">
                    <h3 class="text-sm font-bold text-slate-900 mb-3">Khoảng giá</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" x-model.number="minPrice" @change="$wire.price_min = minPrice" placeholder="Từ" class="h-10 rounded-lg border border-slate-200 px-3 text-xs focus:border-red-400 focus:outline-none">
                        <input type="number" x-model.number="maxPrice" @change="$wire.price_max = maxPrice" placeholder="Đến" class="h-10 rounded-lg border border-slate-200 px-3 text-xs focus:border-red-400 focus:outline-none">
                    </div>
                    <p class="mt-2 text-xs text-slate-400">{{ number_format($price_min, 0, ',', '.') }}₫ – {{ number_format($price_max, 0, ',', '.') }}₫</p>
                </div>
            </div>

            {{-- Drawer Footer --}}
            <div class="sticky bottom-0 bg-white border-t border-slate-100 p-4 flex gap-3">
                @if($hasActiveFilters)
                    <button wire:click="resetFilters" class="btn-secondary flex-1">Đặt lại</button>
                @endif
                <button @click="showMobileFilter = false" class="btn-primary flex-1">
                    Xem {{ $totalProducts }} sản phẩm
                </button>
            </div>
        </div>
    </div>

</div>