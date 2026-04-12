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
        if ($this->appliedCoupon->type === 'percentage') {
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

    public function updateQuantity($productVariantId, $quantity)
    {
        $quantity = max(1, min($quantity, 999)); // Validate quantity

        $this->cartItems = $this->cartItems->map(function ($item) use ($productVariantId, $quantity) {
            if ($item['product_variant_id'] == $productVariantId) {
                $item['quantity'] = $quantity;
            }
            return $item;
        });

        session()->put('cart', $this->cartItems->toArray());
        $this->dispatch('update-cart-badge');
    }

    public function removeItem($productVariantId)
    {
        $this->cartItems = $this->cartItems->filter(
            fn($item) => $item['product_variant_id'] != $productVariantId
        );

        session()->put('cart', $this->cartItems->toArray());
        $this->dispatch('update-cart-badge');
        $this->dispatch('notify', message: 'Đã xóa sản phẩm khỏi giỏ hàng', type: 'success');
    }

    public function applyCoupon()
    {
        $this->errorMessage = '';
        $this->successMessage = '';

        if (empty($this->couponCode)) {
            $this->errorMessage = 'Vui lòng nhập mã giảm giá';
            return;
        }

        $coupon = Coupon::where('code', strtoupper($this->couponCode))->first();

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
        $this->successMessage = "Áp dụng mã {$coupon->code} thành công! Giảm " . 
            number_format($this->discountAmount, 0, ',', '.') . '₫';
    }

    public function removeCoupon()
    {
        $this->appliedCoupon = null;
        $this->couponCode = '';
        $this->errorMessage = '';
        $this->successMessage = '';
    }

    public function continueShopping()
    {
        $this->redirect('/products');
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
