<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Size;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;

class AddToCart extends Component
{
    public Product $product;
    public Collection $variantCollection;
    public ?int $selectedColorId = null;
    public ?int $selectedSizeId = null;
    public int $quantity = 1;
    public bool $showSizeGuide = false;

    public function mount(): void
    {
        $this->product->loadMissing(['variants.size', 'variants.color']);

        $this->variantCollection = $this->product->variants->values();
        $this->initDefaultVariantSelection();
    }

    private function initDefaultVariantSelection(): void
    {
        $default = $this->variantCollection
            ->sortBy(fn ($variant) => [
                (int) $variant->stock_quantity <= 0 ? 1 : 0,
                (int) $variant->color_id,
                (int) $variant->size_id,
            ])
            ->first();

        if (!$default) {
            return;
        }

        $this->selectedColorId = (int) $default->color_id;
        $this->selectedSizeId = (int) $default->size_id;
        $this->quantity = 1;
    }

    #[Computed]
    public function availableColors()
    {
        return $this->variantCollection
            ->pluck('color')
            ->filter()
            ->unique('id')
            ->sortBy('name')
            ->values();
    }

    #[Computed]
    public function availableSizes()
    {
        $variants = $this->variantCollection;

        if ($this->selectedColorId) {
            $variants = $variants->where('color_id', $this->selectedColorId);
        }

        return $variants
            ->pluck('size')
            ->filter()
            ->unique('id')
            ->sortBy('id')
            ->values();
    }

    #[Computed]
    public function maxQuantity()
    {
        if (!$this->selectedVariant) {
            return 0;
        }

        return (int) $this->selectedVariant->stock_quantity;
    }

    #[Computed]
    public function variantPrice()
    {
        if (!$this->selectedVariant) {
            return null;
        }

        $basePrice = $this->product->sale_price ?? $this->product->price;
        return $basePrice + ($this->selectedVariant->additional_price ?? 0);
    }

    #[Computed]
    public function displayPrice()
    {
        return $this->variantPrice ?? ($this->product->sale_price ?? $this->product->price);
    }

    #[Computed]
    public function selectedVariant()
    {
        if (!$this->selectedColorId || !$this->selectedSizeId) {
            return null;
        }

        return $this->variantCollection
            ->where('color_id', $this->selectedColorId)
            ->where('size_id', $this->selectedSizeId)
            ->first();
    }

    public function sizeLabel(Size $size): string
    {
        return $size->short_label;
    }

    public function isColorOutOfStock(int $colorId): bool
    {
        return $this->variantCollection
            ->where('color_id', $colorId)
            ->sum('stock_quantity') <= 0;
    }

    public function isSizeOutOfStock(int $sizeId): bool
    {
        $variants = $this->variantCollection->where('size_id', $sizeId);

        if ($this->selectedColorId) {
            $variants = $variants->where('color_id', $this->selectedColorId);
        }

        return $variants->sum('stock_quantity') <= 0;
    }

    public function selectColor($colorId)
    {
        $this->selectedColorId = (int) $colorId;

        $firstSizeForColor = $this->variantCollection
            ->where('color_id', $this->selectedColorId)
            ->sortBy(fn ($variant) => [
                (int) $variant->stock_quantity <= 0 ? 1 : 0,
                (int) $variant->size_id,
            ])
            ->first();

        $this->selectedSizeId = $firstSizeForColor?->size_id
            ? (int) $firstSizeForColor->size_id
            : null;

        $this->quantity = 1;
    }

    public function selectSize($sizeId)
    {
        $this->selectedSizeId = (int) $sizeId;
        $this->quantity = 1;
    }

    public function syncSelectionState(?int $colorId, ?int $sizeId, ?int $quantity = null): void
    {
        $this->selectedColorId = $colorId ? (int) $colorId : null;
        $this->selectedSizeId = $sizeId ? (int) $sizeId : null;

        if ($quantity !== null) {
            $this->quantity = max(1, (int) $quantity);
        }
    }

    public function addToCartFromState(?int $colorId, ?int $sizeId, ?int $quantity = null): void
    {
        $this->syncSelectionState($colorId, $sizeId, $quantity);
        $this->addToCart();
    }

    public function buyNowFromState(?int $colorId, ?int $sizeId, ?int $quantity = null): void
    {
        $this->syncSelectionState($colorId, $sizeId, $quantity);
        $this->buyNow();
    }

    public function increaseQuantity()
    {
        if ($this->quantity < $this->maxQuantity) {
            $this->quantity++;
        }
    }

    public function decreaseQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart(): bool
    {
        if (!$this->selectedColorId || !$this->selectedSizeId) {
            $this->dispatch('notify', message: 'Vui lòng chọn màu sắc và kích cỡ', type: 'error');
            return false;
        }

        $variant = $this->selectedVariant;

        if (!$variant) {
            $this->dispatch('notify', message: 'Biến thể sản phẩm không hợp lệ', type: 'error');
            return false;
        }

        if ($this->quantity > $this->maxQuantity) {
            $this->dispatch('notify', message: 'Số lượng không hợp lệ', type: 'error');
            return false;
        }

        if ((int) $variant->stock_quantity <= 0) {
            $this->dispatch('notify', message: 'Biến thể này đã hết hàng', type: 'warning');
            return false;
        }

        $cart = collect(session()->get('cart', []));
        $existingIndex = $cart->search(fn ($item) => (int) $item['product_variant_id'] === (int) $variant->id);

        $currentPrice = (float) ($this->displayPrice ?? 0);
        $productImage = $this->product->image_url;

        if ($existingIndex !== false) {
            $existing = $cart->get($existingIndex);
            $newQty = min((int) $existing['quantity'] + $this->quantity, (int) $variant->stock_quantity);
            $existing['quantity'] = $newQty;
            $existing['price'] = $currentPrice;
            $existing['updated_at'] = now()->toDateTimeString();
            $cart->put($existingIndex, $existing);
        } else {
            $cart->push([
                'product_id' => $this->product->id,
                'product_variant_id' => $variant->id,
                'product_name' => $this->product->name,
                'product_slug' => $this->product->slug,
                'sku' => $this->product->sku,
                'size' => $variant->size?->short_label,
                'size_code' => $variant->size?->code,
                'color' => $variant->color?->name,
                'color_hex' => $variant->color?->hex_code,
                'price' => $currentPrice,
                'quantity' => $this->quantity,
                'stock_quantity' => (int) $variant->stock_quantity,
                'image_url' => Str::startsWith($productImage, ['http://', 'https://'])
                    ? $productImage
                    : asset($productImage),
                'created_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString(),
            ]);
        }

        session()->put('cart', $cart->values()->all());

        $cartCount = $cart->sum(fn ($item) => (int) $item['quantity']);
        $this->dispatch('cart-updated', count: $cartCount);

        $this->dispatch('notify', message: 'Đã thêm vào giỏ hàng!', type: 'success');
        
        // Keep UI smooth by selecting a default variant instead of clearing all selections
        $this->initDefaultVariantSelection();

        return true;
    }

    public function buyNow()
    {
        if (!$this->selectedColorId || !$this->selectedSizeId) {
            $this->dispatch('notify', message: 'Vui lòng chọn màu sắc và kích cỡ', type: 'error');
            return;
        }

        if (!$this->addToCart()) {
            return;
        }

        // Redirect to checkout
        $this->redirect('/checkout');
    }

    public function toggleSizeGuide()
    {
        $this->showSizeGuide = !$this->showSizeGuide;
    }

    public function render()
    {
        return view('livewire.add-to-cart');
    }
}
