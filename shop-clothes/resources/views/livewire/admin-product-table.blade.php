<div class="space-y-4">
    {{-- Filters --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <div class="flex flex-col lg:flex-row gap-3">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Tìm theo tên, SKU, slug..."
                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all"
                >
            </div>

            <div class="flex flex-wrap gap-2">
                <select wire:model.live="categoryId" class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm min-w-[160px] focus:ring-2 focus:ring-red-500/20 focus:border-red-400">
                    <option value="">Tất cả danh mục</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>

                <select wire:model.live="brandId" class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm min-w-[150px] focus:ring-2 focus:ring-red-500/20 focus:border-red-400">
                    <option value="">Tất cả thương hiệu</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>

                <select wire:model.live="status" class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm min-w-[140px] focus:ring-2 focus:ring-red-500/20 focus:border-red-400">
                    <option value="">Mọi trạng thái</option>
                    <option value="active">Kích hoạt</option>
                    <option value="inactive">Vô hiệu</option>
                </select>

                <select wire:model.live="stock" class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm min-w-[140px] focus:ring-2 focus:ring-red-500/20 focus:border-red-400">
                    <option value="">Mọi tồn kho</option>
                    <option value="in_stock">Còn hàng</option>
                    <option value="out_of_stock">Hết hàng</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Bulk Actions --}}
    <div class="flex flex-wrap items-center gap-3 bg-white rounded-2xl shadow-sm border border-gray-100 p-3">
        <div class="flex items-center gap-2">
            <select wire:model="bulkAction" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400">
                <option value="">Thao tác hàng loạt</option>
                <option value="activate">Kích hoạt</option>
                <option value="deactivate">Vô hiệu</option>
                <option value="delete">Xóa</option>
            </select>

            <button
                type="button"
                wire:click="confirmBulkAction"
                class="px-4 py-2 bg-gray-900 text-white rounded-xl text-sm font-medium hover:bg-black disabled:opacity-40 disabled:cursor-not-allowed transition-all"
                @disabled(empty($selected))
            >
                Áp dụng <span class="ml-1 bg-white/20 px-1.5 py-0.5 rounded text-xs">{{ count($selected) }}</span>
            </button>
        </div>

        <div class="ml-auto">
            <span class="text-sm text-gray-400">
                <i class="fas fa-box mr-1"></i>{{ $products->total() }} sản phẩm
            </span>
        </div>
    </div>

    {{-- Products Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="py-3 px-4 text-left">
                            <input type="checkbox" wire:model.live="selectPage" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                        </th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Danh mục</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Giá</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tồn kho</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                        <th class="py-3 px-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($products as $product)
                        @php
                            $image = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-3 px-4">
                                <input type="checkbox" value="{{ $product->id }}" wire:model.live="selected" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <img
                                        src="{{ $image ? asset('storage/' . $image->image_path) : asset('images/placeholder.jpg') }}"
                                        alt="{{ $product->name }}"
                                        class="w-12 h-12 rounded-xl object-cover border border-gray-100 shadow-sm"
                                    >
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-900 truncate max-w-[200px]">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            <i class="fas fa-barcode mr-1"></i>{{ $product->sku }}
                                        </p>
                                        @if($product->is_featured)
                                            <span class="inline-flex items-center gap-0.5 mt-1 px-1.5 py-0.5 bg-amber-50 text-amber-600 rounded text-[10px] font-semibold">
                                                <i class="fas fa-star"></i> Nổi bật
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <p class="text-gray-700 text-sm">{{ $product->category?->name ?? '-' }}</p>
                                <p class="text-xs text-gray-400">{{ $product->brand?->name ?? '-' }}</p>
                            </td>
                            <td class="py-3 px-4">
                                @if($product->sale_price)
                                    <p class="font-bold text-red-600 text-sm">{{ number_format($product->sale_price, 0, ',', '.') }}₫</p>
                                    <p class="text-xs text-gray-400 line-through">{{ number_format($product->price, 0, ',', '.') }}₫</p>
                                @else
                                    <p class="font-bold text-gray-900 text-sm">{{ number_format($product->price, 0, ',', '.') }}₫</p>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @php $stock = (int) ($product->total_stock ?? 0); @endphp
                                @if($stock <= 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-50 text-red-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Hết hàng
                                    </span>
                                @elseif($stock < 5)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> {{ stock }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-green-50 text-green-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> {{ $stock }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input
                                        type="checkbox"
                                        class="sr-only peer"
                                        @checked($product->is_active)
                                        wire:change="toggleStatus({{ $product->id }})"
                                    >
                                    <div class="relative w-10 h-5 bg-gray-200 rounded-full peer-checked:bg-green-500 transition-colors">
                                        <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow-sm peer-checked:translate-x-5 transition-transform"></div>
                                    </div>
                                </label>
                            </td>
                            <td class="py-3 px-4 text-xs text-gray-500">{{ $product->created_at?->format('d/m/Y') }}</td>
                            <td class="py-3 px-4">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Chỉnh sửa">
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa sản phẩm này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Xóa">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-3">
                                        <i class="fas fa-box-open text-2xl text-gray-300"></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">Không tìm thấy sản phẩm</p>
                                    <p class="text-xs text-gray-400 mt-1">Thử thay đổi bộ lọc hoặc thêm sản phẩm mới</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($products->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    {{-- Confirm Modal --}}
    @if ($showConfirmModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center px-4" wire:click="closeModal">
            <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-6" wire:click.stop>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl {{ $bulkAction === 'delete' ? 'bg-red-100' : 'bg-blue-100' }} flex items-center justify-center">
                        <i class="fas {{ $bulkAction === 'delete' ? 'fa-trash text-red-500' : 'fa-check text-blue-500' }}"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Xác nhận thao tác</h3>
                </div>
                <p class="text-sm text-gray-600">
                    @if ($bulkAction === 'delete')
                        Bạn sắp <strong>xóa {{ count($selected) }} sản phẩm</strong>. Hành động này không thể hoàn tác.
                    @elseif ($bulkAction === 'activate')
                        Bạn sắp <strong>kích hoạt {{ count($selected) }} sản phẩm</strong>.
                    @else
                        Bạn sắp <strong>vô hiệu {{ count($selected) }} sản phẩm</strong>.
                    @endif
                </p>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" wire:click="closeModal" 
                            class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Hủy
                    </button>
                    <button type="button" wire:click="executeBulkAction" 
                            class="px-4 py-2.5 {{ $bulkAction === 'delete' ? 'bg-red-600 hover:bg-red-700' : 'bg-gray-900 hover:bg-black' }} text-white rounded-xl text-sm font-medium transition-colors">
                        Xác nhận
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>