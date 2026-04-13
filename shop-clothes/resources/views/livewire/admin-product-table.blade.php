<div class="space-y-4">
    <div class="flex flex-col lg:flex-row gap-3">
        <div class="flex-1">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Tìm theo tên, SKU, slug..."
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-red-500"
            >
        </div>

        <select wire:model.live="categoryId" class="border border-gray-300 rounded-lg px-3 py-2.5 min-w-[190px]">
            <option value="">Tất cả danh mục</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>

        <select wire:model.live="brandId" class="border border-gray-300 rounded-lg px-3 py-2.5 min-w-[170px]">
            <option value="">Tất cả thương hiệu</option>
            @foreach ($brands as $brand)
                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
            @endforeach
        </select>

        <select wire:model.live="status" class="border border-gray-300 rounded-lg px-3 py-2.5 min-w-[150px]">
            <option value="">Mọi trạng thái</option>
            <option value="active">Kích hoạt</option>
            <option value="inactive">Vô hiệu</option>
        </select>

        <select wire:model.live="stock" class="border border-gray-300 rounded-lg px-3 py-2.5 min-w-[170px]">
            <option value="">Mọi tồn kho</option>
            <option value="in_stock">Còn hàng</option>
            <option value="out_of_stock">Hết hàng</option>
        </select>
    </div>

    <div class="flex flex-wrap items-center gap-3 bg-white border border-gray-200 rounded-lg p-3">
        <select wire:model="bulkAction" class="border border-gray-300 rounded-lg px-3 py-2">
            <option value="">Thao tác hàng loạt</option>
            <option value="activate">Kích hoạt</option>
            <option value="deactivate">Vô hiệu</option>
            <option value="delete">Xóa</option>
        </select>

        <button
            type="button"
            wire:click="confirmBulkAction"
            class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-black disabled:opacity-50"
            @disabled(empty($selected))
        >
            Áp dụng ({{ count($selected) }})
        </button>

        <span class="text-sm text-gray-500">Tổng: {{ $products->total() }} sản phẩm</span>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-700">
                    <tr>
                        <th class="p-3 text-left">
                            <input type="checkbox" wire:model.live="selectPage">
                        </th>
                        <th class="p-3 text-left">Ảnh</th>
                        <th class="p-3 text-left">Tên + SKU</th>
                        <th class="p-3 text-left">Danh mục</th>
                        <th class="p-3 text-left">Giá</th>
                        <th class="p-3 text-left">Tồn kho</th>
                        <th class="p-3 text-left">Trạng thái</th>
                        <th class="p-3 text-left">Ngày tạo</th>
                        <th class="p-3 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        @php
                            $image = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
                        @endphp
                        <tr class="border-t border-gray-100 hover:bg-gray-50">
                            <td class="p-3 align-top">
                                <input type="checkbox" value="{{ $product->id }}" wire:model.live="selected">
                            </td>
                            <td class="p-3">
                                <img
                                    src="{{ $image ? asset('storage/' . $image->image_path) : asset('images/placeholder.jpg') }}"
                                    alt="{{ $product->name }}"
                                    class="w-12 h-12 rounded-md object-cover border border-gray-200"
                                >
                            </td>
                            <td class="p-3 align-top">
                                <p class="font-semibold text-gray-900">{{ $product->name }}</p>
                                <p class="text-xs text-gray-500">SKU: {{ $product->sku }}</p>
                            </td>
                            <td class="p-3 align-top">
                                <p>{{ $product->category?->name ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $product->brand?->name ?? '-' }}</p>
                            </td>
                            <td class="p-3 align-top">
                                <p class="font-semibold">{{ number_format($product->price, 0, ',', '.') }}đ</p>
                                @if ($product->sale_price)
                                    <p class="text-xs text-gray-500 line-through">{{ number_format($product->sale_price, 0, ',', '.') }}đ</p>
                                @endif
                            </td>
                            <td class="p-3 align-top">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ ((int) ($product->total_stock ?? 0)) > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ (int) ($product->total_stock ?? 0) }}
                                </span>
                            </td>
                            <td class="p-3 align-top">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input
                                        type="checkbox"
                                        class="sr-only peer"
                                        @checked($product->is_active)
                                        wire:change="toggleStatus({{ $product->id }})"
                                    >
                                    <div class="relative w-10 h-5 bg-gray-300 rounded-full peer peer-checked:bg-green-500 transition"></div>
                                </label>
                            </td>
                            <td class="p-3 align-top text-gray-600">{{ $product->created_at?->format('d/m/Y H:i') }}</td>
                            <td class="p-3 align-top text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="px-2.5 py-1.5 text-xs bg-blue-50 text-blue-700 rounded border border-blue-200 hover:bg-blue-100">Sửa</a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa sản phẩm này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 text-xs bg-red-50 text-red-700 rounded border border-red-200 hover:bg-red-100">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-gray-500">Không tìm thấy sản phẩm phù hợp.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3 border-t border-gray-100">
            {{ $products->links() }}
        </div>
    </div>

    @if ($showConfirmModal)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center px-4">
            <div class="w-full max-w-md bg-white rounded-xl shadow-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900">Xác nhận thao tác hàng loạt</h3>
                <p class="mt-2 text-sm text-gray-600">
                    @if ($bulkAction === 'delete')
                        Bạn sắp xóa {{ count($selected) }} sản phẩm. Hành động này không thể hoàn tác.
                    @elseif ($bulkAction === 'activate')
                        Bạn sắp kích hoạt {{ count($selected) }} sản phẩm.
                    @else
                        Bạn sắp vô hiệu {{ count($selected) }} sản phẩm.
                    @endif
                </p>
                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 border border-gray-300 rounded-lg">Hủy</button>
                    <button type="button" wire:click="executeBulkAction" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Xác nhận</button>
                </div>
            </div>
        </div>
    @endif
</div>
