<div class="bg-white border border-gray-200 rounded-xl p-6 space-y-4">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="lg:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Tên thương hiệu *</label>
            <input type="text" name="name" value="{{ old('name', $brand->name ?? '') }}" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-red-500">
            @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $brand->slug ?? '') }}" placeholder="Để trống tự tạo"
                class="w-full border border-gray-300 rounded-lg px-3 py-2.5">
            @error('slug')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Logo</label>
            <input type="file" name="logo" accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-2.5">
            @error('logo')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            @if (!empty($brand?->logo))
                <img src="{{ $brand->logo_url }}" class="mt-2 w-24 h-24 object-cover rounded border border-gray-200" alt="current-logo">
            @endif
        </div>

        <div class="lg:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Mô tả</label>
            <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2.5">{{ old('description', $brand->description ?? '') }}</textarea>
            @error('description')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Trạng thái</label>
            <label class="inline-flex items-center gap-2 text-sm text-gray-700 mt-2">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $brand->is_active ?? false))>
                Kích hoạt hiển thị
            </label>
            @error('is_active')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>
</div>
