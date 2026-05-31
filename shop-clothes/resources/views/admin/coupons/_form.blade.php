<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h3 class="text-base font-bold text-gray-900 mb-5">
        <i class="fas fa-ticket-alt mr-2 text-red-500"></i>Thông tin mã giảm giá
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Mã giảm giá *</label>
            <input type="text" name="code" value="{{ old('code', $coupon->code ?? '') }}" required
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all uppercase"
                placeholder="VD: SUMMER2024">
            @error('code')<p class="text-xs text-red-500 mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Loại giảm giá *</label>
            <select name="type" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all" required>
                <option value="percent" @selected(old('type', $coupon->type ?? 'percent') === 'percent')>Phần trăm (%)</option>
                <option value="fixed" @selected(old('type', $coupon->type ?? 'percent') === 'fixed')>Cố định (VNĐ)</option>
            </select>
            @error('type')<p class="text-xs text-red-500 mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Giá trị giảm *</label>
            <input type="number" step="0.01" min="0" name="value" value="{{ old('value', $coupon->value ?? 0) }}" required
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all">
            @error('value')<p class="text-xs text-red-500 mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Đơn tối thiểu</label>
            <input type="number" step="0.01" min="0" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount ?? 0) }}"
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all">
            @error('min_order_amount')<p class="text-xs text-red-500 mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Giảm tối đa</label>
            <input type="number" step="0.01" min="0" name="max_discount" value="{{ old('max_discount', $coupon->max_discount) }}"
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all">
            @error('max_discount')<p class="text-xs text-red-500 mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Giới hạn lượt dùng</label>
            <input type="number" min="1" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}"
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all"
                placeholder="Để trống = không giới hạn">
            @error('usage_limit')<p class="text-xs text-red-500 mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Ngày bắt đầu *</label>
            <input type="date" name="start_date" value="{{ old('start_date', isset($coupon->start_date) ? $coupon->start_date->format('Y-m-d') : now()->format('Y-m-d')) }}" required
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all">
            @error('start_date')<p class="text-xs text-red-500 mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Ngày kết thúc *</label>
            <input type="date" name="end_date" value="{{ old('end_date', isset($coupon->end_date) ? $coupon->end_date->format('Y-m-d') : now()->addMonth()->format('Y-m-d')) }}" required
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all">
            @error('end_date')<p class="text-xs text-red-500 mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
        </div>

        <div class="md:col-span-2">
            <label class="inline-flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $coupon->is_active ?? false))
                    class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                <span class="text-sm text-gray-700 font-medium">Kích hoạt mã giảm giá</span>
            </label>
            @error('is_active')<p class="text-xs text-red-500 mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
        </div>
    </div>
</div>