@php
    $oldVariants = old('variants', $initialVariants ?? []);
    $oldSizeIds = old('selected_size_ids', $selectedSizes ?? []);
    $oldColorIds = old('selected_color_ids', $selectedColors ?? []);
@endphp

<div
    class="space-y-6"
    x-data="productForm({
        isEdit: @js($isEdit ?? false),
        initialName: @js(old('name', $product->name)),
        initialSlug: @js(old('slug', $product->slug)),
        initialSku: @js(old('sku', $product->sku)),
        sizes: @js($sizes->map(fn($s) => ['id' => (string) $s->id, 'name' => $s->name])->values()->all()),
        colors: @js($colors->map(fn($c) => ['id' => (string) $c->id, 'name' => $c->name, 'hex_code' => $c->hex_code])->values()->all()),
        selectedSizes: @js(array_map('strval', $oldSizeIds)),
        selectedColors: @js(array_map('strval', $oldColorIds)),
        initialVariants: @js($oldVariants),
        metaTitle: @js(old('meta_title', $product->meta_title)),
        metaDescription: @js(old('meta_description', $product->meta_description)),
    })"
    x-init="init()"
>
    <div class="bg-white border border-gray-200 rounded-xl p-6 space-y-4">
        <h2 class="text-lg font-semibold text-gray-900">Thông tin cơ bản</h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tên sản phẩm *</label>
                <input type="text" name="name" x-model="name" @input="generateSlug()" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                <input type="text" name="slug" x-model="slug" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                @error('slug')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                <div class="flex gap-2">
                    <input type="text" name="sku" x-model="sku" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Để trống để auto suggest">
                    <button type="button" @click="suggestSku()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">Suggest</button>
                </div>
                @error('sku')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Danh mục *</label>
                <select name="category_id" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                    <option value="">Chọn danh mục</option>
                    @foreach ($categoryOptions as $categoryOption)
                        <option value="{{ $categoryOption['id'] }}" @selected((string) old('category_id', $product->category_id) === (string) $categoryOption['id'])>
                            {{ $categoryOption['name'] }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Thương hiệu *</label>
                <select name="brand_id" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                    <option value="">Chọn thương hiệu</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" @selected((string) old('brand_id', $product->brand_id) === (string) $brand->id)>{{ $brand->name }}</option>
                    @endforeach
                </select>
                @error('brand_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Giá bán *</label>
                <input type="number" min="0" step="1000" name="price" value="{{ old('price', $product->price) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                @error('price')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Giá gốc</label>
                <input type="number" min="0" step="1000" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                @error('sale_price')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả ngắn</label>
                <textarea name="short_description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2">{{ old('short_description', $product->short_description) }}</textarea>
                @error('short_description')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả chi tiết</label>
                <textarea name="description" rows="6" class="w-full border border-gray-300 rounded-lg px-3 py-2">{{ old('description', $product->description) }}</textarea>
                @error('description')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <label class="flex items-center justify-between border border-gray-200 rounded-lg px-4 py-3 cursor-pointer">
                    <div>
                        <p class="font-medium text-gray-900">Kích hoạt</p>
                        <p class="text-xs text-gray-500">Hiển thị sản phẩm trên website</p>
                    </div>
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? false))>
                </label>

                <label class="flex items-center justify-between border border-gray-200 rounded-lg px-4 py-3 cursor-pointer">
                    <div>
                        <p class="font-medium text-gray-900">Nổi bật</p>
                        <p class="text-xs text-gray-500">Ưu tiên hiển thị ngoài trang chủ</p>
                    </div>
                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured ?? false))>
                </label>
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6 space-y-4">
        <h2 class="text-lg font-semibold text-gray-900">Ảnh sản phẩm</h2>

        <label class="block border-2 border-dashed border-gray-300 rounded-xl p-8 text-center cursor-pointer hover:border-red-400 transition">
            <input type="file" name="images[]" multiple class="hidden" @change="previewImages($event)">
            <p class="text-sm text-gray-600">Kéo thả hoặc bấm để chọn nhiều ảnh (jpeg/png/webp)</p>
            <p class="text-xs text-gray-400 mt-1">Ảnh sẽ được resize 800x800 và tạo thumbnail 400x400</p>
        </label>

        @error('images.*')<p class="text-xs text-red-600">{{ $message }}</p>@enderror

        <template x-if="previewUrls.length > 0">
            <div>
                <p class="text-sm font-medium text-gray-700 mb-2">Ảnh mới sẽ upload</p>
                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3">
                    <template x-for="url in previewUrls" :key="url">
                        <img :src="url" class="w-full aspect-square object-cover rounded-lg border border-gray-200">
                    </template>
                </div>
            </div>
        </template>

        @if ($isEdit && $product->images->count() > 0)
            <div>
                <p class="text-sm font-medium text-gray-700 mb-2">Ảnh đã có</p>
                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3">
                    @foreach ($product->images as $image)
                        <div class="relative border border-gray-200 rounded-lg p-2">
                            <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full aspect-square object-cover rounded">

                            <label class="mt-2 flex items-center gap-2 text-xs text-gray-600">
                                <input type="radio" name="primary_image_id" value="{{ $image->id }}" @checked(old('primary_image_id', optional($product->images->firstWhere('is_primary', true))->id) == $image->id)>
                                Ảnh chính
                            </label>

                            <label class="mt-1 flex items-center gap-2 text-xs text-red-600">
                                <input type="checkbox" name="delete_existing_images[]" value="{{ $image->id }}">
                                Xóa ảnh
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6 space-y-4">
        <h2 class="text-lg font-semibold text-gray-900">Biến thể sản phẩm</h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div>
                <p class="text-sm font-medium text-gray-700 mb-2">Sizes</p>
                <div class="flex flex-wrap gap-2">
                    @foreach ($sizes as $size)
                        <label class="inline-flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-2 text-sm">
                            <input type="checkbox" :value="'{{ $size->id }}'" x-model="selectedSizes" @change="syncVariantsFromSelections()">
                            {{ $size->short_label }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <p class="text-sm font-medium text-gray-700 mb-2">Colors</p>
                <div class="flex flex-wrap gap-2">
                    @foreach ($colors as $color)
                        <label class="inline-flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-2 text-sm">
                            <input type="checkbox" :value="'{{ $color->id }}'" x-model="selectedColors" @change="syncVariantsFromSelections()">
                            <input type="color" value="{{ $color->hex_code }}" class="w-5 h-5 p-0 border-0 bg-transparent" disabled>
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

        <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 text-left">Size</th>
                        <th class="p-3 text-left">Màu</th>
                        <th class="p-3 text-left">Tồn kho</th>
                        <th class="p-3 text-left">Giá cộng thêm</th>
                        <th class="p-3 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(variant, index) in variants" :key="variant.uid">
                        <tr class="border-t border-gray-100">
                            <td class="p-3">
                                <select :name="`variants[${index}][size_id]`" x-model="variant.size_id" class="w-full border border-gray-300 rounded px-2 py-1.5" required>
                                    <option value="">Chọn size</option>
                                    <template x-for="size in sizes" :key="size.id">
                                        <option :value="size.id" x-text="formatSizeLabel(size)"></option>
                                    </template>
                                </select>
                            </td>
                            <td class="p-3">
                                <select :name="`variants[${index}][color_id]`" x-model="variant.color_id" class="w-full border border-gray-300 rounded px-2 py-1.5" required>
                                    <option value="">Chọn màu</option>
                                    <template x-for="color in colors" :key="color.id">
                                        <option :value="color.id" x-text="color.name"></option>
                                    </template>
                                </select>
                            </td>
                            <td class="p-3">
                                <input type="number" min="0" :name="`variants[${index}][stock_quantity]`" x-model="variant.stock_quantity" class="w-full border border-gray-300 rounded px-2 py-1.5" required>
                            </td>
                            <td class="p-3">
                                <input type="number" min="0" step="1000" :name="`variants[${index}][additional_price]`" x-model="variant.additional_price" class="w-full border border-gray-300 rounded px-2 py-1.5">
                            </td>
                            <td class="p-3 text-right">
                                <button type="button" @click="removeVariant(index)" class="text-xs text-red-600 hover:text-red-700">Xóa</button>
                            </td>
                        </tr>
                    </template>

                    <tr x-show="variants.length === 0">
                        <td colspan="5" class="p-4 text-center text-gray-500">Chưa có biến thể. Hãy chọn size và màu hoặc thêm dòng thủ công.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <button type="button" @click="addVariantRow()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">+ Thêm row thủ công</button>

        @error('variants')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
        @error('variants.*.size_id')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
        @error('variants.*.color_id')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
        @error('variants.*.stock_quantity')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
        @error('variants.*.additional_price')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6 space-y-4">
        <h2 class="text-lg font-semibold text-gray-900">SEO</h2>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Meta title</label>
            <input type="text" name="meta_title" x-model="metaTitle" maxlength="60" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            <p class="text-xs text-gray-500 mt-1"><span x-text="metaTitle.length"></span>/60</p>
            @error('meta_title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Meta description</label>
            <textarea name="meta_description" rows="3" x-model="metaDescription" maxlength="160" class="w-full border border-gray-300 rounded-lg px-3 py-2"></textarea>
            <p class="text-xs text-gray-500 mt-1"><span x-text="metaDescription.length"></span>/160</p>
            @error('meta_description')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
            <p class="text-blue-700 text-lg font-medium" x-text="metaTitle || name || 'Tiêu đề sản phẩm'"></p>
            <p class="text-green-700 text-xs" x-text="'https://shop.local/san-pham/' + (slug || 'san-pham')"></p>
            <p class="text-gray-600 text-sm mt-1" x-text="metaDescription || shortMetaFallback()"></p>
        </div>
    </div>
</div>

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
            previewUrls: [],
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
                this.previewUrls = files.map((file) => URL.createObjectURL(file));
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

            formatSizeLabel(size) {
                const code = String(size?.code || '').trim().toUpperCase();

                if (code === 'XS') return 'X';
                if (code === 'XXL') return '2XL';
                if (code === 'XXXL') return '3XL';

                return code || size?.name || 'N/A';
            },
        };
    }
</script>
