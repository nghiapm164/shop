<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class CartBadge extends Component
{
    public $cartCount = 0;

    public function mount()
    {
        $this->cartCount = auth()->user()?->cart?->items?->count() ?? 0;
    }

    #[On('cart-updated')]
    public function updateCart($count = null)
    {
        if ($count !== null) {
            $this->cartCount = $count;
        } else {
            $this->cartCount = auth()->user()?->cart?->items?->count() ?? 0;
        }
    }

    public function render()
    {
        return view('livewire.cart-badge');
    }
}
