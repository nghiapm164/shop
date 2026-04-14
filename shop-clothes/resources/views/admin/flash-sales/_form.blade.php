<div class="bg-white border border-gray-200 rounded-xl p-6 space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Tên chương trình *</label>
            <input type="text" name="title" value="{{ old('title', $flashSale->title ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5" required>
            @error('title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Sản phẩm *</label>
            <select name="product_id" class="w-full border border-gray-300 rounded-lg px-3 py-2.5" required>
                <option value="">-- Chọn sản phẩm --</option>
                @foreach ($products as $product)
                    @php
                        $basePrice = $product->sale_price ?? $product->price;
                    @endphp
                    <option value="{{ $product->id }}" @selected((int) old('product_id', $flashSale->product_id ?? 0) === $product->id)>
                        {{ $product->name }} ({{ $product->sku }}) - {{ number_format($basePrice, 0) }}đ
                    </option>
                @endforeach
            </select>
            @error('product_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Giá Flash Sale *</label>
            <input type="number" step="0.01" min="1000" name="flash_price" value="{{ old('flash_price', $flashSale->flash_price ?? 0) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5" required>
            @error('flash_price')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Thứ tự hiển thị</label>
            <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $flashSale->sort_order ?? 0) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5">
            @error('sort_order')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Bắt đầu *</label>
            <input type="datetime-local" name="start_at" value="{{ old('start_at', isset($flashSale->start_at) ? $flashSale->start_at->format('Y-m-d\\TH:i') : now()->format('Y-m-d\\TH:i')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5" required>
            @error('start_at')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Kết thúc *</label>
            <input type="datetime-local" name="end_at" value="{{ old('end_at', isset($flashSale->end_at) ? $flashSale->end_at->format('Y-m-d\\TH:i') : now()->addDays(3)->format('Y-m-d\\TH:i')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5" required>
            @error('end_at')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="md:col-span-2">
            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $flashSale->is_active ?? true))>
                Kích hoạt flash sale
            </label>
            @error('is_active')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>
</div>
