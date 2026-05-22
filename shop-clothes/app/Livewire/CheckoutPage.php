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

    // New address form (simple text inputs)
    public string $recipientName = '';
    public string $phone = '';
    public string $addressDetail = '';

    // Payment method
    public string $paymentMethod = 'cod';

    // Order notes
    public string $notes = '';

    public function mount()
    {
        if (!auth()->check()) {
            $this->redirect('/login');
        }

        // Load cart from session
        $this->cartItems = collect(session()->get('cart', []));
        
        if ($this->cartItems->isEmpty()) {
            $this->redirect('/cart');
        }

        // Auto-detect new address mode if no saved addresses
        if ($this->savedAddresses->count() == 0) {
            $this->useNewAddress = true;
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

    public function calculateTotals()
    {
        $this->subtotal = $this->cartItems->sum(fn($item) => $item['price'] * $item['quantity']);
        
        if ($this->appliedCoupon) {
            $this->discountAmount = $this->calculateDiscount();
        }

        $shippingThreshold = 500000;
        $this->shippingFee = ($this->subtotal - $this->discountAmount) >= $shippingThreshold ? 0 : 30000;
        $this->total = $this->subtotal - $this->discountAmount + $this->shippingFee;
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

    public function selectAddress($addressId)
    {
        $this->selectedAddressId = $addressId;
        $this->useNewAddress = false;
    }

    public function useNewAddress()
    {
        $this->selectedAddressId = null;
        $this->useNewAddress = true;
    }

    public function placeOrder()
    {
        // Auto-detect new address mode if no saved addresses exist or fields are filled
        if (!$this->selectedAddressId && $this->savedAddresses->count() == 0) {
            $this->useNewAddress = true;
        }

        // Also detect if user filled in address fields without selecting saved address
        if (!$this->selectedAddressId && !$this->useNewAddress && !empty($this->recipientName)) {
            $this->useNewAddress = true;
        }

        // Validate address selection
        if (!$this->selectedAddressId && !$this->useNewAddress) {
            $this->dispatch('notify', message: 'Vui lòng chọn địa chỉ giao hàng', type: 'warning');
            return;
        }

        // Validate new address if using new address
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

        // Prepare shipping address
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

            // Save new address
            Address::create([
                'user_id' => auth()->id(),
                'recipient_name' => $this->recipientName,
                'phone' => $this->phone,
                'address_detail' => $this->addressDetail,
                'is_default' => false,
            ]);
        }

        // Create order
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

        // Add order items and deduct stock
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

            // Deduct stock from product variant
            $variant = \App\Models\ProductVariant::find($item['product_variant_id']);
            if ($variant) {
                $variant->decrement('stock_quantity', $item['quantity']);
            }
        }

        // Increment coupon used count
        if ($this->appliedCoupon) {
            $this->appliedCoupon->increment('used_count');
        }

        // Send order confirmation email
        try {
            Mail::to($order->user->email)->send(new OrderPlacedMail($order));
        } catch (\Exception $e) {
            // Don't fail the order if email fails
            \Illuminate\Support\Facades\Log::warning('Failed to send order confirmation email: ' . $e->getMessage());
        }

        // Clear cart
        session()->forget('cart');
        session()->forget('coupon_id');
        $this->dispatch('cart-updated', count: 0);

        // Redirect based on payment method
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
