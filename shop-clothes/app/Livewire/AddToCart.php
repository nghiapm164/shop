<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductVariant;
use Livewire\Attributes\Computed;
use Livewire\Component;

class AddToCart extends Component
{
    public Product $product;
    public ?int $selectedColorId = null;
    public ?int $selectedSizeId = null;
    public int $quantity = 1;
    public bool $showSizeGuide = false;

    #[Computed]
    public function availableColors()
    {
        return $this->product->variants()
            ->whereHas('color')
            ->distinct('color_id')
            ->pluck('color_id')
            ->map(fn($colorId) => \App\Models\Color::find($colorId))
            ->filter()
            ->values();
    }

    #[Computed]
    public function availableSizes()
    {
        $query = $this->product->variants();
        
        if ($this->selectedColorId) {
            $query->where('color_id', $this->selectedColorId);
        }
        
        return $query->whereHas('size')
            ->distinct('size_id')
            ->pluck('size_id')
            ->map(fn($sizeId) => \App\Models\Size::find($sizeId))
            ->filter()
            ->values();
    }

    #[Computed]
    public function maxQuantity()
    {
        if (!$this->selectedColorId || !$this->selectedSizeId) {
            return 0;
        }

        $variant = $this->product->variants()
            ->where('color_id', $this->selectedColorId)
            ->where('size_id', $this->selectedSizeId)
            ->first();

        return $variant?->stock_quantity ?? 0;
    }

    #[Computed]
    public function variantPrice()
    {
        if (!$this->selectedColorId || !$this->selectedSizeId) {
            return null;
        }

        $variant = $this->product->variants()
            ->where('color_id', $this->selectedColorId)
            ->where('size_id', $this->selectedSizeId)
            ->first();

        if (!$variant) {
            return null;
        }

        $basePrice = $this->product->sale_price ?? $this->product->price;
        return $basePrice + ($variant->additional_price ?? 0);
    }

    public function selectColor($colorId)
    {
        $this->selectedColorId = $colorId;
        $this->selectedSizeId = null; // Reset size when color changes
        $this->quantity = 1;
    }

    public function selectSize($sizeId)
    {
        $this->selectedSizeId = $sizeId;
        $this->quantity = 1;
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

    public function addToCart()
    {
        if (!$this->selectedColorId || !$this->selectedSizeId) {
            $this->dispatch('notify', message: 'Vui lòng chọn màu sắc và kích cỡ', type: 'error');
            return;
        }

        if ($this->quantity > $this->maxQuantity) {
            $this->dispatch('notify', message: 'Số lượng không hợp lệ', type: 'error');
            return;
        }

        // TODO: Integrate with cart system (session or Livewire event)
        // For now, we'll dispatch an event
        $this->dispatch('cart-item-added', [
            'product_id' => $this->product->id,
            'color_id' => $this->selectedColorId,
            'size_id' => $this->selectedSizeId,
            'quantity' => $this->quantity,
        ]);

        // Update CartBadge
        $this->dispatch('update-cart-badge');

        $this->dispatch('notify', message: 'Đã thêm vào giỏ hàng!', type: 'success');
        
        // Reset form
        $this->selectedColorId = null;
        $this->selectedSizeId = null;
        $this->quantity = 1;
    }

    public function buyNow()
    {
        if (!$this->selectedColorId || !$this->selectedSizeId) {
            $this->dispatch('notify', message: 'Vui lòng chọn màu sắc và kích cỡ', type: 'error');
            return;
        }

        // Add to cart first
        $this->dispatch('cart-item-added', [
            'product_id' => $this->product->id,
            'color_id' => $this->selectedColorId,
            'size_id' => $this->selectedSizeId,
            'quantity' => $this->quantity,
        ]);

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
