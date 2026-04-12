@props(['items' => []])

@if(count($items) > 0)
<nav class="mb-6">
    <ol class="flex items-center gap-2 text-sm">
        <!-- Home -->
        <li>
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-red-500 transition-all flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                Trang chủ
            </a>
        </li>

        <!-- Breadcrumb Items -->
        @foreach($items as $index => $item)
            <li class="text-gray-400">/</li>
            <li>
                @if(isset($item['url']) && $index < count($items) - 1)
                    <a href="{{ $item['url'] }}" class="text-gray-600 hover:text-red-500 transition-all">
                        {{ $item['label'] }}
                    </a>
                @else
                    <span class="text-gray-900 font-medium">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
@endif
