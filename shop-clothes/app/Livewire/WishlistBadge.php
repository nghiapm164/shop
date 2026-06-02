<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class WishlistBadge extends Component
{
    public $wishlistCount = 0;

    public function mount()
    {
        $this->refreshCount();
    }

    #[On('wishlist-updated')]
    public function refreshFromEvent()
    {
        $this->refreshCount();
    }

    private function refreshCount(): void
    {
        if (auth()->check()) {
            $this->wishlistCount = auth()->user()->wishlist()->count();
        } else {
            $this->wishlistCount = 0;
        }
    }

    public function render()
    {
        return view('livewire.wishlist-badge');
    }
}
