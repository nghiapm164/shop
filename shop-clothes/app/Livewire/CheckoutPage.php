<?php

namespace App\Livewire;

use App\Models\Address;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
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

    // New address form
    #[Validate('required|min:3|max:100')]
    public string $recipientName = '';
    
    #[Validate('required|regex:/^(\+84|0)[0-9]{9,10}$/')]
    public string $phone = '';
    
    #[Validate('required')]
    public string $province = '';
    
    #[Validate('required')]
    public string $district = '';
    
    #[Validate('required')]
    public string $ward = '';
    
    #[Validate('required')]
    public string $addressDetail = '';

    // Payment method
    #[Validate('required|in:cod,bank,vnpay')]
    public string $paymentMethod = 'cod';

    // Order notes
    #[Validate('nullable|max:500')]
    public string $notes = '';

    // Districts and wards (for dependent dropdowns)
    public array $districts = [];
    public array $wards = [];

    // Vietnam provinces
    private $provinces = [
        'Hà Nội' => ['Hà Đông', 'Thanh Xuân', 'Hoàn Kiếm', 'Cầu Giấy'],
        'TP. Hồ Chí Minh' => ['Quận 1', 'Quận 2', 'Quận 3', 'Quận 4'],
        'Đà Nẵng' => ['Hải Châu', 'Thanh Khê', 'Sơn Trà', 'Ngũ Hành Sơn'],
        'Hải Phòng' => ['Hồng Bàng', 'Ngô Quyền', 'Lê Chân', 'Kiến An'],
        'Cần Thơ' => ['Ninh Kiều', 'Bình Thủy', 'Cái Răng', 'Ô Môn'],
    ];

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

    public function updatedProvince($value)
    {
        $this->district = '';
        $this->wards = [];
        $this->districts = $this->provinces[$value] ?? [];
    }

    public function updatedDistrict($value)
    {
        $this->ward = '';
        // In a real app, you'd fetch this from a ward table
        // For demo, we'll use mock data
        $wardList = [
            'Hà Đông' => ['Phúc La', 'Dương Nội', 'Lĩnh Nam'],
            'Thanh Xuân' => ['Khương Mai', 'Khuất Duy Tiến', 'Thanh Xuân Trung'],
            'Hoàn Kiếm' => ['Hoàn Kiếm', 'Tràng Tiền', 'Cửa Đông'],
            'Cầu Giấy' => ['Dịch Vọng', 'Yên Hòa', 'Trung Hòa'],
            'Quận 1' => ['Tân Định', 'Bến Nghé', 'Nguyễn Huệ'],
            'Quận 2' => ['Thảo Điền', 'An Phú', 'Bình An'],
            'Quận 3' => ['Phường 1', 'Phường 2', 'Phường 3'],
            'Quận 4' => ['Phường 1', 'Phường 2', 'Phường 3'],
        ];
        $this->wards = $wardList[$value] ?? [];
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
        // Validate address selection
        if (!$this->selectedAddressId && !$this->useNewAddress) {
            $this->dispatch('notify', message: 'Vui lòng chọn địa chỉ giao hàng', type: 'warning');
            return;
        }

        // Validate new address if using new address
        if ($this->useNewAddress) {
            $this->validate();
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
                'address' => "{$this->addressDetail}, {$this->ward}, {$this->district}, {$this->province}",
            ];

            // Save new address if checkbox is checked
            Address::create([
                'user_id' => auth()->id(),
                'recipient_name' => $this->recipientName,
                'phone' => $this->phone,
                'province' => $this->province,
                'district' => $this->district,
                'ward' => $this->ward,
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

        // Add order items
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
        }

        // Increment coupon used count
        if ($this->appliedCoupon) {
            $this->appliedCoupon->increment('used_count');
        }

        // Clear cart
        session()->forget('cart');
        session()->forget('coupon_id');
        $this->dispatch('cart-updated', count: 0);

        // Redirect based on payment method
        if ($this->paymentMethod === 'vnpay') {
            // TODO: Implement VNPay integration
            $this->redirect(route('order.success', ['code' => $order->order_code]));
        } else {
            $this->redirect(route('order.success', ['code' => $order->order_code]));
        }
    }

    public function render()
    {
        return view('livewire.checkout-page');
    }
}
