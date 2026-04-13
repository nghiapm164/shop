<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class AdminProductTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $categoryId = '';
    public string $brandId = '';
    public string $status = '';
    public string $stock = '';

    public array $selected = [];
    public bool $selectPage = false;
    public bool $showConfirmModal = false;
    public string $bulkAction = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryId' => ['except' => ''],
        'brandId' => ['except' => ''],
        'status' => ['except' => ''],
        'stock' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategoryId(): void
    {
        $this->resetPage();
    }

    public function updatedBrandId(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function updatedStock(): void
    {
        $this->resetPage();
    }

    public function updatedSelectPage(bool $value): void
    {
        if ($value) {
            $this->selected = $this->products()->pluck('id')->map(fn ($id) => (string) $id)->toArray();
            return;
        }

        $this->selected = [];
    }

    public function confirmBulkAction(): void
    {
        if (empty($this->bulkAction) || empty($this->selected)) {
            return;
        }

        $this->showConfirmModal = true;
    }

    public function executeBulkAction(): void
    {
        if (empty($this->bulkAction) || empty($this->selected)) {
            $this->closeModal();
            return;
        }

        $products = Product::whereIn('id', $this->selected);

        if ($this->bulkAction === 'activate') {
            $products->update(['is_active' => true]);
            session()->flash('success', 'Đã kích hoạt các sản phẩm đã chọn.');
        }

        if ($this->bulkAction === 'deactivate') {
            $products->update(['is_active' => false]);
            session()->flash('success', 'Đã vô hiệu các sản phẩm đã chọn.');
        }

        if ($this->bulkAction === 'delete') {
            $products->delete();
            session()->flash('success', 'Đã xóa các sản phẩm đã chọn.');
        }

        $this->selected = [];
        $this->selectPage = false;
        $this->bulkAction = '';
        $this->showConfirmModal = false;
        $this->resetPage();
    }

    public function closeModal(): void
    {
        $this->showConfirmModal = false;
    }

    public function toggleStatus(int $productId): void
    {
        $product = Product::findOrFail($productId);
        $product->update(['is_active' => !$product->is_active]);
    }

    private function products(): LengthAwarePaginator
    {
        $query = Product::query()
            ->with(['category', 'brand', 'images'])
            ->withSum('variants as total_stock', 'stock_quantity')
            ->latest();

        if ($this->search !== '') {
            $keyword = trim($this->search);
            $query->where(function ($builder) use ($keyword) {
                $builder->where('name', 'like', "%{$keyword}%")
                    ->orWhere('sku', 'like', "%{$keyword}%")
                    ->orWhere('slug', 'like', "%{$keyword}%");
            });
        }

        if ($this->categoryId !== '') {
            $query->where('category_id', $this->categoryId);
        }

        if ($this->brandId !== '') {
            $query->where('brand_id', $this->brandId);
        }

        if ($this->status === 'active') {
            $query->where('is_active', true);
        }

        if ($this->status === 'inactive') {
            $query->where('is_active', false);
        }

        if ($this->stock === 'in_stock') {
            $query->whereHas('variants', function ($builder) {
                $builder->where('stock_quantity', '>', 0);
            });
        }

        if ($this->stock === 'out_of_stock') {
            $query->whereDoesntHave('variants', function ($builder) {
                $builder->where('stock_quantity', '>', 0);
            });
        }

        return $query->paginate(10);
    }

    public function render()
    {
        return view('livewire.admin-product-table', [
            'products' => $this->products(),
            'categories' => Category::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
        ]);
    }
}
