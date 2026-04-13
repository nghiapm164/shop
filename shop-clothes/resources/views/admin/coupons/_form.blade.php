<div class="bg-white border border-gray-200 rounded-xl p-6 space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Mã giảm giá *</label>
            <input type="text" name="code" value="{{ old('code', $coupon->code ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5" required>
            @error('code')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Loại *</label>
            <select name="type" class="w-full border border-gray-300 rounded-lg px-3 py-2.5" required>
                <option value="percent" @selected(old('type', $coupon->type ?? 'percent') === 'percent')>Phần trăm (%)</option>
                <option value="fixed" @selected(old('type', $coupon->type ?? 'percent') === 'fixed')>Cố định (VNĐ)</option>
            </select>
            @error('type')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Giá trị giảm *</label>
            <input type="number" step="0.01" min="0" name="value" value="{{ old('value', $coupon->value ?? 0) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5" required>
            @error('value')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Đơn tối thiểu</label>
            <input type="number" step="0.01" min="0" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount ?? 0) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5">
            @error('min_order_amount')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Giảm tối đa</label>
            <input type="number" step="0.01" min="0" name="max_discount" value="{{ old('max_discount', $coupon->max_discount) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5">
            @error('max_discount')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Giới hạn lượt dùng</label>
            <input type="number" min="1" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5">
            @error('usage_limit')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Ngày bắt đầu *</label>
            <input type="date" name="start_date" value="{{ old('start_date', isset($coupon->start_date) ? $coupon->start_date->format('Y-m-d') : now()->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5" required>
            @error('start_date')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Ngày kết thúc *</label>
            <input type="date" name="end_date" value="{{ old('end_date', isset($coupon->end_date) ? $coupon->end_date->format('Y-m-d') : now()->addMonth()->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5" required>
            @error('end_date')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="md:col-span-2">
            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $coupon->is_active ?? false))>
                Kích hoạt mã giảm giá
            </label>
            @error('is_active')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>
</div>
