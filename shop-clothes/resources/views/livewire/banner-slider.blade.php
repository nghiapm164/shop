<div class="relative w-full h-96 md:h-full md:aspect-video bg-gray-900 overflow-hidden rounded-lg" 
    x-data="{ 
        currentIndex: 0,
        totalSlides: {{ count($banners) }},
        autoplay: () => {
            setInterval(() => {
                @this.nextBanner()
            }, 5000)
        }
    }"
    x-init="autoplay()">

    @if($currentBanner)
    <!-- Banner Image -->
    <div class="absolute inset-0">
        <img src="{{ asset($currentBanner['image_url']) }}" 
            alt="{{ $currentBanner['title'] }}" 
            class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/40"></div>
    </div>

    <!-- Content -->
    <div class="relative h-full flex items-center justify-center text-center">
        <div class="max-w-2xl mx-auto px-4 text-white">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                {{ $currentBanner['title'] }}
            </h1>
            <p class="text-lg md:text-xl text-gray-200 mb-8">
                {{ $currentBanner['subtitle'] }}
            </p>
            <a href="{{ $currentBanner['link'] }}" class="btn-primary text-lg inline-block">
                {{ $currentBanner['cta_text'] }}
            </a>
        </div>
    </div>
    @endif

    <!-- Navigation Dots -->
    @if(count($banners) > 1)
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-3 z-10">
        @foreach($banners as $index => $banner)
        <button 
            wire:click="goToBanner({{ $index }})"
            class="w-2 h-2 rounded-full transition-all duration-300 {{ $currentIndex === $index ? 'bg-red-500 w-8' : 'bg-white/50 hover:bg-white' }}">
        </button>
        @endforeach
    </div>

    <!-- Previous Button -->
    <button 
        wire:click="$set('currentIndex', ({{ $currentIndex }} - 1 + {{ count($banners) }}) % {{ count($banners) }})"
        class="absolute left-4 top-1/2 -translate-y-1/2 z-10 bg-white/20 hover:bg-white/40 text-white p-3 rounded-full transition-all">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
    </button>

    <!-- Next Button -->
    <button 
        wire:click="nextBanner"
        class="absolute right-4 top-1/2 -translate-y-1/2 z-10 bg-white/20 hover:bg-white/40 text-white p-3 rounded-full transition-all">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </button>
    @endif
</div>
