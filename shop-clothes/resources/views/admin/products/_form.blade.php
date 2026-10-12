@php
    $oldVariants = old('variants', $initialVariants ?? []);
    $oldSizeIds = old('selected_size_ids', $selectedSizes ?? []);
    $oldColorIds = old('selected_color_ids', $selectedColors ?? []);
@endphp

<script>
    function productForm(config) {
        return {
            name: config.initialName || '',
            slug: config.initialSlug || '',
            sku: config.initialSku || '',
            sizes: config.sizes || [],
            colors: config.colors || [],
            selectedSizes: config.selectedSizes || [],
            selectedColors: config.selectedColors || [],
            variants: [],
            newImages: [],
            metaTitle: config.metaTitle || '',
            metaDescription: config.metaDescription || '',

            init() {
                const initial = (config.initialVariants || []).map((row) => ({
                    uid: this.uid(),
                    size_id: String(row.size_id ?? ''),
                    color_id: String(row.color_id ?? ''),
                    stock_quantity: Number(row.stock_quantity ?? 0),
                    additional_price: Number(row.additional_price ?? 0),
                }));

                this.variants = initial;

                if (this.variants.length === 0 && this.selectedSizes.length > 0 && this.selectedColors.length > 0) {
                    this.syncVariantsFromSelections();
                }

                if (!this.slug && this.name) {
                    this.generateSlug();
                }

                if (!this.sku && this.name) {
                    this.suggestSku();
                }
            },

            uid() {
                return Math.random().toString(36).slice(2, 11);
            },

            generateSlug() {
                const normalized = (this.name || '')
                    .toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .replace(/[^a-z0-9\s-]/g, '')
                    .trim()
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
                this.slug = normalized;
            },

            suggestSku() {
                const seed = (this.name || 'PRODUCT')
                    .toUpperCase()
                    .replace(/[^A-Z0-9]/g, '')
                    .slice(0, 6)
                    .padEnd(6, 'X');
                const suffix = Math.random().toString(36).slice(2, 8).toUpperCase();
                this.sku = `${seed}-${suffix}`;
            },

            previewImages(event) {
                const files = Array.from(event.target.files || []);
                files.forEach(file => {
                    this.newImages.push({
                        id: this.uid(),
                        url: URL.createObjectURL(file),
                        file: file
                    });
                });
                this.syncFileInput();
            },

            removeNewImage(index) {
                this.newImages.splice(index, 1);
                this.syncFileInput();
            },

            syncFileInput() {
                const dt = new DataTransfer();
                this.newImages.forEach(img => dt.items.add(img.file));
                this.$refs.imageInput.files = dt.files;
            },

            syncVariantsFromSelections() {
                const keep = {};
                this.variants.forEach((v) => {
                    const key = `${v.size_id}-${v.color_id}`;
                    keep[key] = v;
                });

                const generated = [];
                this.selectedSizes.forEach((sizeId) => {
                    this.selectedColors.forEach((colorId) => {
                        const key = `${sizeId}-${colorId}`;
                        generated.push({
                            uid: keep[key]?.uid || this.uid(),
                            size_id: String(sizeId),
                            color_id: String(colorId),
                            stock_quantity: keep[key]?.stock_quantity ?? 0,
                            additional_price: keep[key]?.additional_price ?? 0,
                        });
                    });
                });

                this.variants = generated;
            },

            addVariantRow() {
                this.variants.push({
                    uid: this.uid(),
                    size_id: '',
                    color_id: '',
                    stock_quantity: 0,
                    additional_price: 0,
                });
            },

            removeVariant(index) {
                this.variants.splice(index, 1);
            },

            shortMetaFallback() {
                return 'Mô tả SEO sẽ hiển thị ở đây.';
            },
        };
    }
</script>

<div
    class="space-y-6"
    x-data="productForm({
        isEdit: @js($isEdit ?? false),
        initialName: @js(old('name', $product->name)),
        initialSlug: @js(old('slug', $product->slug)),
        initialSku: @js(old('sku', $product->sku)),
        sizes: @js($sizes->map(fn($s) => ['id' => (string) $s->id, 'name' => $s->short_label, 'full_name' => $s->name])->values()->all()),
        colors: @js($colors->map(fn($c) => ['id' => (string) $c->id, 'name' => $c->name, 'hex_code' => $c->hex_code])->values()->all()),
        selectedSizes: @js(array_map('strval', $oldSizeIds)),
        selectedColors: @js(array_map('strval', $oldColorIds)),
        initialVariants: @js($oldVariants),
        metaTitle: @js(old('meta_title', $product->meta_title)),
        metaDescription: @js(old('meta_description', $product->meta_description)),
    })"
    x-init="init()"
>
    {{-- ═══════════════ TABS ═══════════════ --}}
    <div x-data="{ tab: 'basic' }" class="space-y-6">
        {{-- Tab Navigation --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="flex border-b border-gray-200 overflow-x-auto">
                <button type="button" @click="tab = 'basic'" :class="tab === 'basic' ? 'text-red-600 border-red-600 bg-red-50/50' : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50'" class="flex items-center gap-2 px-5 py-3.5 text-sm font-semibold border-b-2 transition-all whitespace-nowrap">
                    <i class="fas fa-info-circle text-xs"></i> Thông tin cơ bản
                </button>
                <button type="button" @click="tab = 'images'" :class="tab === 'images' ? 'text-red-600 border-red-600 bg-red-50/50' : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50'" class="flex items-center gap-2 px-5 py-3.5 text-sm font-semibold border-b-2 transition-all whitespace-nowrap">
                    <i class="fas fa-images text-xs"></i> Ảnh sản phẩm
                </button>
                <button type="button" @click="tab = 'variants'" :class="tab === 'variants' ? 'text-red-600 border-red-600 bg-red-50/50' : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50'" class="flex items-center gap-2 px-5 py-3.5 text-sm font-semibold border-b-2 transition-all whitespace-nowrap">
                    <i class="fas fa-layer-group text-xs"></i> Biến thể
                </button>
                <button type="button" @click="tab = 'seo'" :class="tab === 'seo' ? 'text-red-600 border-red-600 bg-red-50/50' : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50'" class="flex items-center gap-2 px-5 py-3.5 text-sm font-semibold border-b-2 transition-all whitespace-nowrap">
                    <i class="fas fa-search text-xs"></i> SEO
                </button>
            </div>
        </div>

        {{-- ═══════════════ TAB: BASIC INFO ═══════════════ --}}
        <div x-show="tab === 'basic'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="bg-white border border-gray-200 rounded-xl p-6 space-y-5">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tên sản phẩm <span class="text-red-500">*</span></label>
                        <input type="text" name="name" x-model="name" @input="generateSlug()" class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition" required>
                        @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Slug</label>
                        <input type="text" name="slug" x-model="slug" class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition">
                        @error('slug')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">SKU</label>
                        <div class="flex gap-2">
                            <input type="text" name="sku" x-model="sku" class="flex-1 border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition" placeholder="Để trống để tự tạo">
                            <button type="button" @click="suggestSku()" class="px-3 py-2.5 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition"><i class="fas fa-magic text-xs"></i></button>
                        </div>
                        @error('sku')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Danh mục <span class="text-red-500">*</span></label>
                        <select name="category_id" class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition" required>
                            <option value="">Chọn danh mục</option>
                            @foreach ($categoryOptions as $categoryOption)
                                <option value="{{ $categoryOption['id'] }}" @selected((string) old('category_id', $product->category_id) === (string) $categoryOption['id'])>{{ $categoryOption['name'] }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Thương hiệu <span class="text-red-500">*</span></label>
                        <select name="brand_id" class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition" required>
                            <option value="">Chọn thương hiệu</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" @selected((string) old('brand_id', $product->brand_id) === (string) $brand->id)>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        @error('brand_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Giá bán <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="number" min="0" step="1000" name="price" value="{{ old('price', $product->price) }}" class="w-full border border-gray-300 rounded-lg pl-3.5 pr-10 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition" required>
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">₫</span>
                        </div>
                        @error('price')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Giá khuyến mãi</label>
                        <div class="relative">
                            <input type="number" min="0" step="1000" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" class="w-full border border-gray-300 rounded-lg pl-3.5 pr-10 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">₫</span>
                        </div>
                        @error('sale_price')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    {{-- Thuộc tính phục vụ chatbot AI tư vấn sản phẩm --}}
                    @foreach ([
                        'gender' => ['Giới tính', \App\Support\ProductAttributes::GENDERS],
                        'sport' => ['Môn thể thao', \App\Support\ProductAttributes::SPORTS],
                        'purpose' => ['Mục đích sử dụng', \App\Support\ProductAttributes::PURPOSES],
                        'style' => ['Kiểu dáng', \App\Support\ProductAttributes::STYLES],
                    ] as $attrField => [$attrLabel, $attrOptions])
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ $attrLabel }}</label>
                            <select name="{{ $attrField }}" class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition">
                                <option value="">-- Không xác định --</option>
                                @foreach ($attrOptions as $attrValue => $attrText)
                                    <option value="{{ $attrValue }}" @selected(old($attrField, $product->{$attrField}) === $attrValue)>{{ $attrText }}</option>
                                @endforeach
                            </select>
                            @error($attrField)<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    @endforeach
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Mô tả ngắn</label>
                        <textarea name="short_description" rows="2" class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition">{{ old('short_description', $product->short_description) }}</textarea>
                    </div>
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Mô tả chi tiết</label>
                        <textarea name="description" rows="5" class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition">{{ old('description', $product->description) }}</textarea>
                    </div>
                    <div class="lg:col-span-2 flex flex-wrap gap-4">
                        <label class="flex items-center gap-3 border border-gray-200 rounded-xl px-4 py-3 cursor-pointer hover:border-green-300 transition flex-1 min-w-[200px]">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? false)) class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <div>
                                <p class="font-medium text-gray-900 text-sm">Kích hoạt</p>
                                <p class="text-xs text-gray-500">Hiển thị trên website</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 border border-gray-200 rounded-xl px-4 py-3 cursor-pointer hover:border-amber-300 transition flex-1 min-w-[200px]">
                            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured ?? false)) class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                            <div>
                                <p class="font-medium text-gray-900 text-sm">Nổi bật</p>
                                <p class="text-xs text-gray-500">Ưu tiên trên trang chủ</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════════ TAB: IMAGES ═══════════════ --}}
        <div x-show="tab === 'images'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="bg-white border border-gray-200 rounded-xl p-6 space-y-5">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Ảnh sản phẩm</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Mỗi màu sắc nên có ít nhất 1 ảnh. Chọn màu sắc cho từng ảnh.</p>
                </div>

                <label class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-xl p-8 cursor-pointer hover:border-red-400 hover:bg-red-50/30 transition-all group">
                    <input type="file" name="images[]" multiple class="hidden" x-ref="imageInput" @change="previewImages($event)">
                    <div class="w-12 h-12 rounded-full bg-gray-100 group-hover:bg-red-100 flex items-center justify-center mb-3 transition">
                        <i class="fas fa-cloud-upload-alt text-gray-400 group-hover:text-red-500 text-lg transition"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-600">Kéo thả hoặc bấm để chọn ảnh</p>
                    <p class="text-xs text-gray-400 mt-1">JPEG, PNG, WebP - Tự động resize 800x800</p>
                </label>
                @error('images.*')<p class="text-xs text-red-600">{{ $message }}</p>@enderror

                <template x-if="newImages.length > 0">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Ảnh mới sẽ upload</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                            <template x-for="(image, idx) in newImages" :key="image.id">
                                <div class="border border-gray-200 rounded-xl overflow-hidden bg-white relative group">
                                    <button type="button" @click="removeNewImage(idx)" class="absolute top-2 right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow-sm hover:bg-red-600 z-10">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                    <img :src="image.url" class="w-full aspect-square object-cover">
                                    <div class="p-2.5">
                                        <select name="image_colors[]" class="w-full border border-gray-200 rounded-lg px-2 py-1.5 text-xs focus:ring-2 focus:ring-red-500/20 focus:border-red-400">
                                            <option value="">-- Chọn màu sắc --</option>
                                            <template x-for="color in colors.filter(c => selectedColors.includes(c.id))" :key="color.id">
                                                <option :value="color.id" x-text="color.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                @if ($isEdit && $product->images->count() > 0)
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Ảnh hiện có ({{ $product->images->count() }})</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach ($product->images as $image)
                                @php
                                    $imageSrc = \Illuminate\Support\Str::startsWith($image->image_path, ['http://', 'https://'])
                                        ? $image->image_path
                                        : asset('storage/' . $image->image_path);
                                @endphp
                                <div x-data="{ deleted: {{ in_array($image->id, old('delete_existing_images', [])) ? 'true' : 'false' }} }" x-show="!deleted" class="border border-gray-200 rounded-xl overflow-hidden bg-white {{ $image->is_primary ? 'ring-2 ring-red-500' : '' }}">
                                    <template x-if="deleted">
                                        <input type="hidden" name="delete_existing_images[]" value="{{ $image->id }}">
                                    </template>
                                    <div class="relative group">
                                        <button type="button" @click="deleted = true" class="absolute top-2 right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow-sm hover:bg-red-600 z-10" title="Xóa ảnh này">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                        <img src="{{ $imageSrc }}" class="w-full aspect-square object-cover" onerror="this.onerror=null;this.src='{{ asset('images/product-placeholder.svg') }}';">
                                        @if ($image->is_primary)
                                            <span class="absolute top-2 left-2 bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded">Ảnh chính</span>
                                        @endif
                                        @if ($image->color)
                                            <span class="absolute bottom-2 left-2 flex items-center gap-1 bg-black/60 text-white text-[10px] font-medium px-2 py-0.5 rounded backdrop-blur-sm">
                                                <span class="w-2.5 h-2.5 rounded-full border border-white/30" style="background-color: {{ $image->color->hex_code }}"></span>
                                                {{ $image->color->name }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="p-2.5 space-y-2">
                                        <select name="existing_image_colors[{{ $image->id }}]" class="w-full border border-gray-200 rounded-lg px-2 py-1.5 text-xs focus:ring-2 focus:ring-red-500/20 focus:border-red-400">
                                            <option value="">-- Chọn màu sắc --</option>
                                            <template x-for="color in colors.filter(c => selectedColors.includes(c.id) || c.id === '{{ $image->color_id }}')" :key="color.id">
                                                <option :value="color.id" :selected="color.id === '{{ $image->color_id }}'" x-text="color.name"></option>
                                            </template>
                                        </select>
                                        <div class="flex items-center justify-between">
                                            <label class="flex items-center gap-1.5 text-xs text-gray-600 cursor-pointer">
                                                <input type="radio" name="primary_image_id" value="{{ $image->id }}" @checked($image->is_primary) class="text-red-600 focus:ring-red-500">
                                                Chính
                                            </label>
                                            <button type="button" @click="deleted = true" class="text-red-500 hover:text-red-700 text-xs font-medium flex items-center gap-1.5 transition-colors">
                                                <i class="fas fa-trash"></i> Xóa
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ═══════════════ TAB: VARIANTS ═══════════════ --}}
        <div x-show="tab === 'variants'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="bg-white border border-gray-200 rounded-xl p-6 space-y-5">
                <h3 class="text-base font-semibold text-gray-900">Biến thể sản phẩm</h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-2">Kích cỡ</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($sizes as $size)
                                @php $sizeIdStr = (string) $size->id; @endphp
                                <label class="inline-flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-2 text-sm cursor-pointer hover:border-gray-400 transition" :class="selectedSizes.includes('{!! $sizeIdStr !!}') ? 'border-red-400 bg-red-50' : ''">
                                    <input type="checkbox" value="{!! $sizeIdStr !!}" x-model="selectedSizes" @change="syncVariantsFromSelections()" class="rounded text-red-600 focus:ring-red-500">
                                    {{ $size->short_label }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-2">Màu sắc</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($colors as $color)
                                @php $colorIdStr = (string) $color->id; @endphp
                                <label class="inline-flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-2 text-sm cursor-pointer hover:border-gray-400 transition" :class="selectedColors.includes('{!! $colorIdStr !!}') ? 'border-red-400 bg-red-50' : ''">
                                    <input type="checkbox" value="{!! $colorIdStr !!}" x-model="selectedColors" @change="syncVariantsFromSelections()" class="rounded text-red-600 focus:ring-red-500">
                                    <span class="w-4 h-4 rounded-full border border-gray-300" style="background-color: {{ $color->hex_code }}"></span>
                                    {{ $color->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <template x-for="(id, idx) in selectedSizes" :key="'size-'+idx">
                    <input type="hidden" :name="`selected_size_ids[${idx}]`" :value="id">
                </template>
                <template x-for="(id, idx) in selectedColors" :key="'color-'+idx">
                    <input type="hidden" :name="`selected_color_ids[${idx}]`" :value="id">
                </template>

                <div class="overflow-x-auto border border-gray-200 rounded-xl">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="p-3 text-left text-xs font-semibold text-gray-500 uppercase">Size</th>
                                <th class="p-3 text-left text-xs font-semibold text-gray-500 uppercase">Màu</th>
                                <th class="p-3 text-left text-xs font-semibold text-gray-500 uppercase">Tồn kho</th>
                                <th class="p-3 text-left text-xs font-semibold text-gray-500 uppercase">Giá thêm</th>
                                <th class="p-3 text-right text-xs font-semibold text-gray-500 uppercase">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <template x-for="(variant, index) in variants" :key="variant.uid">
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="p-2.5">
                                        <select :name="`variants[${index}][size_id]`" x-model="variant.size_id" class="w-full border border-gray-200 rounded-lg px-2 py-1.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400" required>
                                            <option value="">--</option>
                                            <template x-for="size in sizes" :key="size.id">
                                                <option :value="size.id" :selected="variant.size_id === size.id" x-text="size.name"></option>
                                            </template>
                                        </select>
                                    </td>
                                    <td class="p-2.5">
                                        <select :name="`variants[${index}][color_id]`" x-model="variant.color_id" class="w-full border border-gray-200 rounded-lg px-2 py-1.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400" required>
                                            <option value="">--</option>
                                            <template x-for="color in colors" :key="color.id">
                                                <option :value="color.id" :selected="variant.color_id === color.id" x-text="color.name"></option>
                                            </template>
                                        </select>
                                    </td>
                                    <td class="p-2.5">
                                        <input type="number" min="0" :name="`variants[${index}][stock_quantity]`" x-model="variant.stock_quantity" class="w-24 border border-gray-200 rounded-lg px-2 py-1.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400" required>
                                    </td>
                                    <td class="p-2.5">
                                        <input type="number" min="0" step="1000" :name="`variants[${index}][additional_price]`" x-model="variant.additional_price" class="w-28 border border-gray-200 rounded-lg px-2 py-1.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400">
                                    </td>
                                    <td class="p-2.5 text-right">
                                        <button type="button" @click="removeVariant(index)" class="text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg p-1.5 transition">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="variants.length === 0">
                                <td colspan="5" class="p-6 text-center text-gray-400 text-sm">
                                    <i class="fas fa-layer-group text-2xl text-gray-300 mb-2 block"></i>
                                    Chọn size và màu sắc phía trên để tạo biến thể tự động
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <button type="button" @click="addVariantRow()" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition">
                    <i class="fas fa-plus text-xs"></i> Thêm biến thể thủ công
                </button>

                @error('variants')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                @error('variants.*.size_id')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                @error('variants.*.color_id')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                @error('variants.*.stock_quantity')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                @error('variants.*.additional_price')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- ═══════════════ TAB: SEO ═══════════════ --}}
        <div x-show="tab === 'seo'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="bg-white border border-gray-200 rounded-xl p-6 space-y-5">
                <h3 class="text-base font-semibold text-gray-900">Tối ưu SEO</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Meta title</label>
                    <input type="text" name="meta_title" x-model="metaTitle" maxlength="60" class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition">
                    <div class="flex justify-between mt-1">
                        @error('meta_title')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                        <p class="text-xs text-gray-400 ml-auto"><span x-text="metaTitle.length"></span>/60</p>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Meta description</label>
                    <textarea name="meta_description" rows="3" x-model="metaDescription" maxlength="160" class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition"></textarea>
                    <div class="flex justify-between mt-1">
                        @error('meta_description')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                        <p class="text-xs text-gray-400 ml-auto"><span x-text="metaDescription.length"></span>/160</p>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-xl p-4 bg-gray-50">
                    <p class="text-xs text-gray-400 mb-2 font-medium">Xem trước trên Google</p>
                    <p class="text-blue-700 text-base font-medium leading-snug" x-text="metaTitle || name || 'Tiêu đề sản phẩm'"></p>
                    <p class="text-green-700 text-xs mt-0.5" x-text="'https://shop.local/san-pham/' + (slug || 'san-pham')"></p>
                    <p class="text-gray-600 text-sm mt-1 line-clamp-2" x-text="metaDescription || shortMetaFallback()"></p>
                </div>
            </div>
        </div>
    </div>
</div>
