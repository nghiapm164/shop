<?php

namespace App\Livewire;

use App\Models\Coupon;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CartPage extends Component
{
    public Collection $cartItems;
    public string $couponCode = '';
    public ?Coupon $appliedCoupon = null;
    public string $errorMessage = '';
    public string $successMessage = '';
    
    const SHIPPING_FREE_THRESHOLD = 500000;
    const SHIPPING_COST = 30000;

    public function mount()
    {
        $this->cartItems = collect(session()->get('cart', []));
        $this->syncCartData();

        $couponId = session()->get('coupon_id');
        if ($couponId) {
            $coupon = Coupon::find($couponId);
            if ($coupon) {
                $validation = $coupon->checkValid($this->subtotal);
                if ($validation['valid']) {
                    $this->appliedCoupon = $coupon;
                    $this->couponCode = $coupon->code;
                } else {
                    session()->forget('coupon_id');
                }
            } else {
                session()->forget('coupon_id');
            }
        }
    }

    private function syncCartData(): void
    {
        $items = $this->cartItems->map(function ($item) {
            $variant = ProductVariant::with(['product', 'size', 'color'])->find($item['product_variant_id'] ?? null);

            if (!$variant || !$variant->product || !$variant->product->is_active) {
                return null;
            }

            $price = (float) (($variant->product->sale_price ?? $variant->product->price) + ($variant->additional_price ?? 0));
            $qty = max(1, min((int) ($item['quantity'] ?? 1), (int) $variant->stock_quantity));

            $item['product_id'] = $variant->product_id;
            $item['product_name'] = $variant->product->name;
            $item['product_slug'] = $variant->product->slug;
            $item['sku'] = $variant->product->sku;
            $item['size'] = $variant->size?->short_label;
            $item['size_code'] = $variant->size?->code;
            $item['color'] = $variant->color?->name;
            $item['color_hex'] = $variant->color?->hex_code;
            $item['price'] = $price;
            $item['quantity'] = $qty;
            $item['stock_quantity'] = (int) $variant->stock_quantity;
            $item['image_url'] = $variant->product->image_url;
            $item['updated_at'] = now()->toDateTimeString();

            return $item;
        })->filter()->values();

        $this->cartItems = $items;
        session()->put('cart', $this->cartItems->toArray());
        $this->dispatch('cart-updated', count: $this->cartItems->sum('quantity'));
    }

    #[Computed]
    public function subtotal()
    {
        return $this->cartItems->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    #[Computed]
    public function discountAmount()
    {
        if (!$this->appliedCoupon) {
            return 0;
        }

        $discount = 0;
        if ($this->appliedCoupon->type === 'percent') {
            $discount = ($this->subtotal * $this->appliedCoupon->value) / 100;
        } else {
            $discount = $this->appliedCoupon->value;
        }

        // Don't exceed max discount
        if ($this->appliedCoupon->max_discount) {
            $discount = min($discount, $this->appliedCoupon->max_discount);
        }

        return $discount;
    }

    #[Computed]
    public function shippingFee()
    {
        $subtotalAfterDiscount = $this->subtotal - $this->discountAmount;
        return $subtotalAfterDiscount >= self::SHIPPING_FREE_THRESHOLD ? 0 : self::SHIPPING_COST;
    }

    #[Computed]
    public function total()
    {
        return $this->subtotal - $this->discountAmount + $this->shippingFee;
    }

    #[Computed]
    public function availableCoupons()
    {
        return Coupon::valid()
            ->where(function ($query) {
                $query->whereNull('usage_limit')
                    ->orWhereColumn('used_count', '<', 'usage_limit');
            })
            ->orderByDesc('value')
            ->limit(8)
            ->get();
    }

    public function updateQuantity($productVariantId, $quantity)
    {
        $variant = ProductVariant::with(['product', 'size', 'color'])->find($productVariantId);

        if (!$variant || !$variant->product || !$variant->product->is_active) {
            $this->removeItem($productVariantId);
            $this->errorMessage = 'Biến thể sản phẩm không còn khả dụng.';
            return;
        }

        $quantity = max(1, min((int) $quantity, (int) $variant->stock_quantity));
        $latestPrice = (float) (($variant->product->sale_price ?? $variant->product->price) + ($variant->additional_price ?? 0));

        $this->cartItems = $this->cartItems->map(function ($item) use ($productVariantId, $quantity, $latestPrice, $variant) {
            if ($item['product_variant_id'] == $productVariantId) {
                $item['quantity'] = $quantity;
                $item['price'] = $latestPrice;
                $item['stock_quantity'] = (int) $variant->stock_quantity;
                $item['updated_at'] = now()->toDateTimeString();
            }
            return $item;
        });

        session()->put('cart', $this->cartItems->toArray());
        $this->dispatch('cart-updated', count: $this->cartItems->sum('quantity'));
    }

    public function removeItem($productVariantId)
    {
        $this->cartItems = $this->cartItems->filter(
            fn($item) => $item['product_variant_id'] != $productVariantId
        );

        session()->put('cart', $this->cartItems->toArray());
        $this->dispatch('cart-updated', count: $this->cartItems->sum('quantity'));
        $this->dispatch('notify', message: 'Đã xóa sản phẩm khỏi giỏ hàng', type: 'success');
    }

    public function applyCoupon()
    {
        $this->errorMessage = '';
        $this->successMessage = '';

        $this->couponCode = strtoupper(trim($this->couponCode));

        if (empty($this->couponCode)) {
            $this->errorMessage = 'Vui lòng nhập mã giảm giá';
            return;
        }

        $coupon = Coupon::where('code', $this->couponCode)->first();

        if (!$coupon) {
            $this->errorMessage = 'Mã giảm giá không tồn tại';
            return;
        }

        $validation = $coupon->checkValid($this->subtotal);
        
        if (!$validation['valid']) {
            $this->errorMessage = $validation['message'];
            return;
        }

        $this->appliedCoupon = $coupon;
        session()->put('coupon_id', $coupon->id);
        $this->successMessage = "Áp dụng mã {$coupon->code} thành công! Giảm " . 
            number_format($this->discountAmount, 0, ',', '.') . '₫';

        $this->dispatch('notify', message: "Đã áp dụng mã {$coupon->code}", type: 'success');
    }

    public function applyCouponCode(string $code): void
    {
        $this->couponCode = strtoupper(trim($code));
        $this->applyCoupon();
    }

    public function removeCoupon()
    {
        $this->appliedCoupon = null;
        $this->couponCode = '';
        $this->errorMessage = '';
        $this->successMessage = '';
        session()->forget('coupon_id');
    }

    public function continueShopping()
    {
        $this->redirect('/shop');
    }

    public function proceedToCheckout()
    {
        if ($this->cartItems->isEmpty()) {
            $this->dispatch('notify', message: 'Giỏ hàng trống', type: 'warning');
            return;
        }

        session()->put('coupon_id', $this->appliedCoupon?->id);
        $this->redirect('/checkout');
    }

    public function render()
    {
        return view('livewire.cart-page');
    }
}
