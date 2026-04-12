<div>
    @if($cartCount > 0)
        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
            {{ $cartCount > 99 ? '99+' : $cartCount }}
        </span>
    @endif
</div>
