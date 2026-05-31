<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h3 class="text-base font-bold text-gray-900 mb-5">
        <i class="fas fa-tags mr-2 text-red-500"></i>Thông tin thương hiệu
    </h3>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <div class="lg:col-span-2">
            <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Tên thương hiệu *</label>
            <input type="text" name="name" value="{{ old('name', $brand->name ?? '') }}" required
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all"
                placeholder="Nhập tên thương hiệu...">
            @error('name')<p class="text-xs text-red-500 mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $brand->slug ?? '') }}" placeholder="Để trống tự động tạo"
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all">
            @error('slug')<p class="text-xs text-red-500 mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Logo</label>
            <input type="file" name="logo" accept="image/*"
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
            @error('logo')<p class="text-xs text-red-500 mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
            @if (!empty($brand?->logo))
                <div class="mt-3">
                    <img src="{{ $brand->logo_url }}" class="w-24 h-24 object-cover rounded-xl border border-gray-100 shadow-sm" alt="current-logo">
                </div>
            @endif
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Trạng thái</label>
            <label class="inline-flex items-center gap-3 mt-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $brand->is_active ?? false))
                    class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                <span class="text-sm text-gray-700 font-medium">Kích hoạt hiển thị</span>
            </label>
            @error('is_active')<p class="text-xs text-red-500 mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
        </div>

        <div class="lg:col-span-2">
            <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Mô tả</label>
            <textarea name="description" rows="3"
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all"
                placeholder="Mô tả ngắn về thương hiệu...">{{ old('description', $brand->description ?? '') }}</textarea>
            @error('description')<p class="text-xs text-red-500 mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
        </div>
    </div>
</div>