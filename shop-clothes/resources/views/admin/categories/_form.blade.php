<div class="bg-white border border-gray-200 rounded-xl p-6 space-y-4">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="lg:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Tên danh mục *</label>
            <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-red-500">
            @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $category->slug ?? '') }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2.5" placeholder="Để trống tự tạo">
            @error('slug')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Danh mục cha</label>
            <select name="parent_id" class="w-full border border-gray-300 rounded-lg px-3 py-2.5">
                <option value="">Không có</option>
                @foreach ($parents as $parent)
                    <option value="{{ $parent->id }}" @selected((string) old('parent_id', $category->parent_id ?? '') === (string) $parent->id)>
                        {{ $parent->name }}
                    </option>
                @endforeach
            </select>
            @error('parent_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Thứ tự hiển thị</label>
            <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2.5">
            @error('sort_order')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="lg:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Mô tả</label>
            <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2.5">{{ old('description', $category->description ?? '') }}</textarea>
            @error('description')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Ảnh danh mục</label>
            <input type="file" name="image" accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-2.5">
            @error('image')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            @if (!empty($category?->image))
                <img src="{{ asset('storage/' . $category->image) }}" class="mt-2 w-24 h-24 object-cover rounded border border-gray-200" alt="current-image">
            @endif
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Trạng thái</label>
            <label class="inline-flex items-center gap-2 text-sm text-gray-700 mt-2">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active ?? false))>
                Kích hoạt hiển thị
            </label>
            @error('is_active')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>
</div>
