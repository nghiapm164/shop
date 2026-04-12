<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[['label' => 'Sản phẩm']]" />

    <!-- Page Title & Search -->
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Sản phẩm</h1>

    <!-- Search Bar -->
    <div class="mb-8">
        <input type="text" 
            wire:model.live="keyword"
            placeholder="Tìm kiếm sản phẩm..." 
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Filters -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg border border-gray-200 p-6 sticky top-24 space-y-6">
                <!-- Filter Header -->
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-900">Bộ lọc</h2>
                    @if($hasActiveFilters)
                    <button wire:click="resetFilters" class="text-xs text-red-500 hover:text-red-600 font-semibold">
                        Xóa
                    </button>
                    @endif
                </div>

                <!-- Categories -->
                @if($categories->count() > 0)
                <div class="border-t pt-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Danh mục</h3>
                    <div class="space-y-2">
                        <label class="flex items-center gap-3 cursor-pointer hover:text-red-500">
                            <input type="radio" name="category" value="" 
                                wire:model.live="category" class="w-4 h-4">
                            <span class="text-sm text-gray-700">Tất cả</span>
                        </label>
                        @foreach($categories as $cat)
                        <div>
                            <label class="flex items-center gap-3 cursor-pointer hover:text-red-500">
                                <input type="radio" name="category" value="{{ $cat->id }}" 
                                    wire:model.live="category" class="w-4 h-4">
                                <span class="text-sm text-gray-700">{{ $cat->name }}</span>
                            </label>
                            @if($cat->children->count() > 0)
                            <div class="ml-6 mt-2 space-y-2">
                                @foreach($cat->children as $child)
                                <label class="flex items-center gap-3 cursor-pointer hover:text-red-500">
                                    <input type="radio" name="category" value="{{ $child->id }}" 
                                        wire:model.live="category" class="w-4 h-4">
                                    <span class="text-xs text-gray-600">{{ $child->name }}</span>
                                </label>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Brands -->
                @if($brands->count() > 0)
                <div class="border-t pt-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Thương hiệu</h3>
                    <div class="space-y-2 max-h-56 overflow-y-auto">
                        @foreach($brands as $b)
                        <label class="flex items-center gap-3 cursor-pointer hover:text-red-500">
                            <input type="checkbox" 
                                value="{{ $b->id }}"
                                wire:change="toggleBrand('{{ $b->id }}')"
                                @checked(in_array($b->id, $brand))
                                class="w-4 h-4 rounded border-gray-300">
                            <span class="text-sm text-gray-700">{{ $b->name }}</span>
                            <span class="text-xs text-gray-400 ml-auto">({{ $b->products_count ?? 0 }})</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Sizes -->
                @if($sizes->count() > 0)
                <div class="border-t pt-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Kích cỡ</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($sizes as $s)
                        <button 
                            wire:click="toggleSize('{{ $s->id }}')"
                            @class([
                                'px-3 py-2 rounded border font-medium text-sm transition-all',
                                'bg-red-500 text-white border-red-500' => in_array($s->id, $size),
                                'border-gray-300 text-gray-700 hover:border-red-500' => !in_array($s->id, $size),
                            ])>
                            {{ $s->name }}
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Colors -->
                @if($colors->count() > 0)
                <div class="border-t pt-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Màu sắc</h3>
                    <div class="flex flex-wrap gap-3">
                        @foreach($colors as $c)
                        <button 
                            wire:click="toggleColor('{{ $c->id }}')"
                            class="group relative"
                            title="{{ $c->name }}">
                            <div @class([
                                'w-8 h-8 rounded-full border-2 transition-all',
                                'border-red-500 ring-2 ring-red-500 ring-offset-2' => in_array($c->id, $color),
                                'border-gray-300 hover:border-gray-900 cursor-pointer' => !in_array($c->id, $color),
                            ]) style="background-color: {{ $c->hex_code }}"></div>
                            <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">
                                {{ $c->name }}
                            </span>
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Price Range -->
                <div class="border-t pt-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Khoảng giá</h3>
                    <div class="space-y-4" x-data="{
                        minPrice: {{ $price_min }},
                        maxPrice: {{ $price_max }},
                    }">
                        <div class="flex gap-2">
                            <input type="number" 
                                x-model.number="minPrice"
                                @change="$wire.price_min = minPrice"
                                placeholder="Min" 
                                class="flex-1 px-3 py-2 border border-gray-300 rounded text-sm">
                            <input type="number" 
                                x-model.number="maxPrice"
                                @change="$wire.price_max = maxPrice"
                                placeholder="Max" 
                                class="flex-1 px-3 py-2 border border-gray-300 rounded text-sm">
                        </div>
                        <div class="space-y-2 text-sm text-gray-600">
                            <p><strong>Từ:</strong> <span x-text="minPrice.toLocaleString('vi-VN')"></span>₫</p>
                            <p><strong>Đến:</strong> <span x-text="maxPrice.toLocaleString('vi-VN')"></span>₫</p>
                        </div>
                        <div class="h-2 bg-red-500 rounded-full" style="width: 100%"></div>
                    </div>
                </div>

                <!-- Apply Button -->
                <button class="w-full btn-primary">
                    Áp dụng
                </button>
            </div>
        </div>

        <!-- Products Section -->
        <div class="lg:col-span-3">
            <!-- Toolbar -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div>
                    <p class="text-sm text-gray-600">
                        Hiển thị <span class="font-semibold">{{ $totalProducts }}</span> sản phẩm
                        @if($hasActiveFilters)
                            <button wire:click="resetFilters" class="text-red-500 hover:text-red-600 ml-2 font-semibold">
                                (Xóa bộ lọc)
                            </button>
                        @endif
                    </p>
                </div>

                <div class="flex gap-4">
                    <!-- Sort Dropdown -->
                    <select wire:model.live="sort" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="newest">Mới nhất</option>
                        <option value="popularity">Phổ biến</option>
                        <option value="rating">Đánh giá cao</option>
                        <option value="price_low">Giá thấp đến cao</option>
                        <option value="price_high">Giá cao đến thấp</option>
                    </select>

                    <!-- View Type Toggle -->
                    <div class="flex border border-gray-300 rounded-lg">
                        <button 
                            wire:click="setViewType('grid')"
                            @class(['px-3 py-2 transition-all', 'bg-red-500 text-white' => $view_type === 'grid', 'text-gray-700' => $view_type !== 'grid'])
                            title="Grid view">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h12a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6z"></path>
                            </svg>
                        </button>
                        <button 
                            wire:click="setViewType('list')"
                            @class(['px-3 py-2 border-l transition-all', 'bg-red-500 text-white' => $view_type === 'list', 'text-gray-700' => $view_type !== 'list'])
                            title="List view">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Products Grid/List -->
            @if($products->count() > 0)
                @if($view_type === 'grid')
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @foreach($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
                @else
                <div class="space-y-4 mb-8">
                    @foreach($products as $product)
                    <div class="flex gap-4 bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-all">
                        <!-- Image -->
                        <div class="w-32 h-32 flex-shrink-0 bg-gray-100 flex items-center justify-center overflow-hidden">
                            <img src="{{ asset($product->image_url ?? 'images/placeholder.jpg') }}" 
                                alt="{{ $product->name }}" 
                                class="w-full h-full object-cover">
                        </div>

                        <!-- Info -->
                        <div class="flex-1 p-4 flex flex-col justify-between">
                            <div>
                                <a href="{{ route('products.show', $product->slug) }}" 
                                    class="text-sm text-gray-500 hover:text-red-500 transition-all">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </a>
                                <a href="{{ route('products.show', $product->slug) }}" 
                                    class="block font-semibold text-gray-900 hover:text-red-500 transition-all line-clamp-2 mb-2">
                                    {{ $product->name }}
                                </a>
                                <div class="flex items-center gap-2 mb-2">
                                    @if($product->average_rating)
                                        <x-star-rating :rating="$product->average_rating" size="sm" />
                                        <span class="text-xs text-gray-500">({{ $product->review_count ?? 0 }})</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    @if($product->sale_price)
                                        <span class="text-lg font-bold text-red-500">{{ number_format($product->sale_price, 0) }}₫</span>
                                        <span class="text-sm text-gray-400 line-through ml-2">{{ number_format($product->price, 0) }}₫</span>
                                    @else
                                        <span class="text-lg font-bold text-gray-900">{{ number_format($product->price, 0) }}₫</span>
                                    @endif
                                </div>
                                <button class="btn-primary text-sm">
                                    Thêm giỏ
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Pagination -->
                @if($products->hasPages())
                    <x-pagination :paginator="$products" />
                @endif
            @else
                <!-- No Results -->
                <div class="py-16 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 21l-4.35-4.35m0 0A7.5 7.5 0 103.305 3.305a7.5 7.5 0 0010.345 10.345z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Không tìm thấy sản phẩm</h3>
                    <p class="text-gray-600 mb-6">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm</p>
                    <button wire:click="resetFilters" class="btn-primary">
                        Xóa bộ lọc
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
