@php
    $positions = [
        'home_top' => 'Trang chủ - Trên cùng',
        'home_middle' => 'Trang chủ - Giữa',
        'home_bottom' => 'Trang chủ - Dưới cùng',
        'category' => 'Trang danh mục',
        'product' => 'Trang sản phẩm',
    ];
@endphp

<div class="bg-white border border-gray-200 rounded-xl p-6 space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Tiêu đề banner *</label>
            <input type="text" name="title" value="{{ old('title', $banner->title ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5" required>
            @error('title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Vị trí *</label>
            <select name="position" class="w-full border border-gray-300 rounded-lg px-3 py-2.5" required>
                @foreach ($positions as $key => $label)
                    <option value="{{ $key }}" @selected(old('position', $banner->position ?? 'home_top') === $key)>{{ $label }}</option>
                @endforeach
            </select>
            @error('position')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Thứ tự hiển thị</label>
            <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5">
            @error('sort_order')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Link chuyển hướng</label>
            <input type="url" name="link" value="{{ old('link', $banner->link ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5" placeholder="https://...">
            @error('link')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Ảnh banner {{ empty($banner->id) ? '*' : '' }}</label>
            <input type="file" name="image" accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-2.5">
            @error('image')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            @if (!empty($banner->image))
                <img src="{{ $banner->image_url }}" alt="banner" class="mt-3 w-full max-w-md h-40 object-cover rounded border border-gray-200">
            @endif
        </div>

        <div class="md:col-span-2">
            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $banner->is_active ?? false))>
                Kích hoạt hiển thị
            </label>
            @error('is_active')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>
</div>
