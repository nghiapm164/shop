<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class CartBadge extends Component
{
    public $cartCount = 0;

    public function mount()
    {
        $this->refreshCount();
    }

    #[On('cart-updated')]
    public function updateCart($count = null)
    {
        if ($count !== null) {
            $this->cartCount = $count;
        } else {
            $this->refreshCount();
        }
    }

    #[On('update-cart-badge')]
    public function refreshFromLegacyEvent()
    {
        $this->refreshCount();
    }

    private function refreshCount(): void
    {
        $this->cartCount = collect(session()->get('cart', []))
            ->sum(fn ($item) => (int) ($item['quantity'] ?? 0));
    }

    public function render()
    {
        return view('livewire.cart-badge');
    }
}
