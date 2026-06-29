<?php

namespace App\Livewire;

use App\Models\Address;
use App\Models\Coupon;
use App\Mail\OrderPlacedMail;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CheckoutPage extends Component
{
    // Cart data
    public Collection $cartItems;
    public ?Coupon $appliedCoupon = null;
    public float $subtotal = 0;
    public float $discountAmount = 0;
    public float $shippingFee = 0;
    public float $total = 0;

    // Address selection
    public ?int $selectedAddressId = null;
    public bool $useNewAddress = false;

    // New/Edit address form
    public string $recipientName = '';
    public string $phone = '';
    public string $addressDetail = '';

    // Edit address
    public ?int $editingAddressId = null;

    // Coupon
    public string $couponCode = '';
    public string $couponError = '';
    public string $couponSuccess = '';

    // Payment method
    public string $paymentMethod = 'cod';

    // Order notes
    public string $notes = '';

    /**
     * Get the effective price, prioritizing flash sale over sale_price.
     */
    private function getEffectivePrice($product): float
    {
        $flashSale = $product->flashSales()
            ->running()
            ->orderBy('flash_price')
            ->first();

        if ($flashSale) {
            return (float) $flashSale->flash_price;
        }

        return (float) ($product->sale_price ?? $product->price);
    }

    public function mount()
    {
        if (!auth()->check()) {
            $this->redirect('/login');
        }

        // Load cart from session and sync prices
        $this->cartItems = collect(session()->get('cart', []));
        $this->syncCartPrices();

        if ($this->cartItems->isEmpty()) {
            $this->redirect('/cart');
        }

        // Auto-detect new address mode if no saved addresses
        $hasAddresses = auth()->user()->addresses()->exists();
        if (!$hasAddresses) {
            $this->useNewAddress = true;
        } else {
            // Auto-select default address
            $defaultAddr = auth()->user()->addresses()->where('is_default', true)->first();
            if ($defaultAddr) {
                $this->selectedAddressId = $defaultAddr->id;
            }
        }

        // Calculate totals from cart
        $this->calculateTotals();

        // Load applied coupon
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

            // Recalculate after coupon is loaded/validated
            $this->calculateTotals();
        }
    }

    /**
     * Sync cart item prices with current product prices (including flash sales).
     */
    private function syncCartPrices(): void
    {
        $this->cartItems = $this->cartItems->map(function ($item) {
            $variant = \App\Models\ProductVariant::with('product')->find($item['product_variant_id'] ?? null);
            if ($variant && $variant->product && $variant->product->is_active) {
                $basePrice = $this->getEffectivePrice($variant->product);
                $item['price'] = (float) ($basePrice + ($variant->additional_price ?? 0));
            }
            return $item;
        })->values();
    }

    public function calculateTotals()
    {
        $this->subtotal = $this->cartItems->sum(fn($item) => $item['price'] * $item['quantity']);

        if ($this->appliedCoupon) {
            $this->discountAmount = $this->calculateDiscount();
        } else {
            $this->discountAmount = 0;
        }

        $shippingThreshold = 500000;
        $this->shippingFee = ($this->subtotal - $this->discountAmount) >= $shippingThreshold ? 0 : 30000;
        $this->total = max(0, $this->subtotal - $this->discountAmount + $this->shippingFee);
    }

    protected function calculateDiscount()
    {
        $discount = 0;
        if ($this->appliedCoupon->type === 'percent') {
            $discount = ($this->subtotal * $this->appliedCoupon->value) / 100;
        } else {
            $discount = $this->appliedCoupon->value;
        }

        if ($this->appliedCoupon->max_discount) {
            $discount = min($discount, $this->appliedCoupon->max_discount);
        }

        return $discount;
    }

    #[Computed]
    public function savedAddresses()
    {
        return auth()->user()->addresses ?? collect([]);
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

    public function selectAddress($addressId)
    {
        $this->selectedAddressId = $addressId;
        $this->useNewAddress = false;
        $this->editingAddressId = null;
    }

    public function useNewAddress()
    {
        $this->selectedAddressId = null;
        $this->useNewAddress = true;
        $this->editingAddressId = null;
        $this->recipientName = '';
        $this->phone = '';
        $this->addressDetail = '';
    }

    public function editAddress($addressId)
    {
        $address = Address::where('user_id', auth()->id())->find($addressId);
        if (!$address) {
            $this->dispatch('notify', message: 'Địa chỉ không tồn tại', type: 'error');
            return;
        }

        $this->editingAddressId = $addressId;
        $this->useNewAddress = true;
        $this->selectedAddressId = null;
        $this->recipientName = $address->recipient_name;
        $this->phone = $address->phone;
        $this->addressDetail = $address->address_detail;
    }

    public function saveAddress()
    {
        $this->validate([
            'recipientName' => 'required|min:2|max:100',
            'phone' => ['required', 'regex:/^(\+84|0)[0-9]{9,10}$/'],
            'addressDetail' => 'required|min:5|max:500',
        ], [
            'recipientName.required' => 'Vui lòng nhập tên người nhận',
            'recipientName.min' => 'Tên phải có ít nhất 2 ký tự',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.regex' => 'Số điện thoại không hợp lệ',
            'addressDetail.required' => 'Vui lòng nhập địa chỉ giao hàng',
            'addressDetail.min' => 'Địa chỉ phải có ít nhất 5 ký tự',
        ]);

        if ($this->editingAddressId) {
            // Update existing address
            $address = Address::where('user_id', auth()->id())->find($this->editingAddressId);
            if ($address) {
                $address->update([
                    'recipient_name' => $this->recipientName,
                    'phone' => $this->phone,
                    'address_detail' => $this->addressDetail,
                ]);
                $this->selectedAddressId = $address->id;
                $this->dispatch('notify', message: 'Đã cập nhật địa chỉ', type: 'success');
            }
        } else {
            // Create new address
            $address = Address::create([
                'user_id' => auth()->id(),
                'recipient_name' => $this->recipientName,
                'phone' => $this->phone,
                'address_detail' => $this->addressDetail,
                'is_default' => false,
            ]);
            $this->selectedAddressId = $address->id;
            $this->dispatch('notify', message: 'Đã thêm địa chỉ mới', type: 'success');
        }

        // Reset form
        $this->useNewAddress = false;
        $this->editingAddressId = null;
        $this->recipientName = '';
        $this->phone = '';
        $this->addressDetail = '';
    }

    public function cancelEditAddress()
    {
        $this->useNewAddress = false;
        $this->editingAddressId = null;
        $this->recipientName = '';
        $this->phone = '';
        $this->addressDetail = '';

        // Re-select default if no address is selected
        if (!$this->selectedAddressId) {
            $defaultAddr = auth()->user()->addresses()->where('is_default', true)->first();
            if ($defaultAddr) {
                $this->selectedAddressId = $defaultAddr->id;
            }
        }
    }

    public function deleteAddress($addressId)
    {
        $address = Address::where('user_id', auth()->id())->find($addressId);
        if (!$address) {
            $this->dispatch('notify', message: 'Địa chỉ không tồn tại', type: 'error');
            return;
        }

        $wasDefault = $address->is_default;
        $address->delete();

        if ($this->selectedAddressId == $addressId) {
            $this->selectedAddressId = null;
            $next = auth()->user()->addresses()->first();
            if ($next) {
                $this->selectedAddressId = $next->id;
                if ($wasDefault) {
                    $next->update(['is_default' => true]);
                }
            }
        }

        if (auth()->user()->addresses()->count() === 0) {
            $this->useNewAddress = true;
        }

        $this->dispatch('notify', message: 'Đã xóa địa chỉ', type: 'success');
    }

    public function setDefaultAddress($addressId)
    {
        $address = Address::where('user_id', auth()->id())->find($addressId);
        if (!$address) {
            return;
        }

        Address::where('user_id', auth()->id())->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        $this->selectedAddressId = $addressId;
        $this->dispatch('notify', message: 'Đã đặt làm địa chỉ mặc định', type: 'success');
    }

    // Coupon methods
    public function applyCoupon()
    {
        $this->couponError = '';
        $this->couponSuccess = '';

        $this->couponCode = strtoupper(trim($this->couponCode));

        if (empty($this->couponCode)) {
            $this->couponError = 'Vui lòng nhập mã giảm giá';
            return;
        }

        $coupon = Coupon::where('code', $this->couponCode)->first();

        if (!$coupon) {
            $this->couponError = 'Mã giảm giá không tồn tại';
            return;
        }

        $validation = $coupon->checkValid($this->subtotal);

        if (!$validation['valid']) {
            $this->couponError = $validation['message'];
            return;
        }

        $this->appliedCoupon = $coupon;
        session()->put('coupon_id', $coupon->id);
        $this->calculateTotals();
        $this->couponSuccess = "Áp dụng mã {$coupon->code} thành công! Giảm " .
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
        $this->couponError = '';
        $this->couponSuccess = '';
        session()->forget('coupon_id');
        $this->calculateTotals();
    }

    public function placeOrder()
    {
        if (!$this->selectedAddressId && $this->savedAddresses->count() == 0) {
            $this->useNewAddress = true;
        }

        if (!$this->selectedAddressId && !$this->useNewAddress && !empty($this->recipientName)) {
            $this->useNewAddress = true;
        }

        if (!$this->selectedAddressId && !$this->useNewAddress) {
            $this->dispatch('notify', message: 'Vui lòng chọn địa chỉ giao hàng', type: 'warning');
            return;
        }

        if ($this->useNewAddress) {
            $this->validate([
                'recipientName' => 'required|min:2|max:100',
                'phone' => ['required', 'regex:/^(\+84|0)[0-9]{9,10}$/'],
                'addressDetail' => 'required|min:5|max:500',
                'paymentMethod' => 'required|in:cod,bank,vnpay',
                'notes' => 'nullable|max:500',
            ], [
                'recipientName.required' => 'Vui lòng nhập tên người nhận',
                'recipientName.min' => 'Tên phải có ít nhất 2 ký tự',
                'phone.required' => 'Vui lòng nhập số điện thoại',
                'phone.regex' => 'Số điện thoại không hợp lệ',
                'addressDetail.required' => 'Vui lòng nhập địa chỉ giao hàng',
                'addressDetail.min' => 'Địa chỉ phải có ít nhất 5 ký tự',
            ]);
        }

        $shippingAddress = [];

        if ($this->selectedAddressId) {
            $address = Address::find($this->selectedAddressId);
            $shippingAddress = [
                'recipient_name' => $address->recipient_name,
                'phone' => $address->phone,
                'address' => $address->full_address,
            ];
        } else {
            $shippingAddress = [
                'recipient_name' => $this->recipientName,
                'phone' => $this->phone,
                'address' => $this->addressDetail,
            ];

            Address::create([
                'user_id' => auth()->id(),
                'recipient_name' => $this->recipientName,
                'phone' => $this->phone,
                'address_detail' => $this->addressDetail,
                'is_default' => false,
            ]);
        }

        $paymentMethod = match ($this->paymentMethod) {
            'bank' => 'bank_transfer',
            'vnpay' => 'e_wallet',
            default => 'cod',
        };

        $order = Order::create([
            'user_id' => auth()->id(),
            'order_code' => 'ORD-' . strtoupper(uniqid()),
            'status' => Order::STATUS_PENDING,
            'payment_method' => $paymentMethod,
            'payment_status' => Order::PAYMENT_STATUS_UNPAID,
            'subtotal' => $this->subtotal,
            'discount_amount' => $this->discountAmount,
            'shipping_fee' => $this->shippingFee,
            'total' => $this->total,
            'shipping_address' => $shippingAddress,
            'notes' => $this->notes,
        ]);

        foreach ($this->cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_variant_id' => $item['product_variant_id'],
                'product_name' => $item['product_name'],
                'size' => $item['size'],
                'color' => $item['color'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['price'] * $item['quantity'],
            ]);

            $variant = \App\Models\ProductVariant::find($item['product_variant_id']);
            if ($variant) {
                $variant->decrement('stock_quantity', $item['quantity']);
            }
        }

        if ($this->appliedCoupon) {
            $this->appliedCoupon->increment('used_count');
        }

        try {
            Mail::to($order->user->email)->send(new OrderPlacedMail($order));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to send order confirmation email: ' . $e->getMessage());
        }

        session()->forget('cart');
        session()->forget('coupon_id');
        $this->dispatch('cart-updated', count: 0);

        if ($this->paymentMethod === 'vnpay') {
            $this->redirect(route('payment.vnpay', ['order_code' => $order->order_code]));
        } else {
            $this->redirect(route('order.success', ['code' => $order->order_code]));
        }
    }

    public function render()
    {
        return view('livewire.checkout-page');
    }
}
