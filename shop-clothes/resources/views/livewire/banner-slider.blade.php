<div
    class="relative w-full h-[450px] md:h-[550px] lg:h-[650px] overflow-hidden rounded-[2rem] bg-slate-950 shadow-2xl"
    wire:poll.5s="nextBanner"
>
    @if($currentBanner)
        <div class="absolute inset-0">
            @php
                $bannerImage = $currentBanner['image_url'];
                $bannerImageSrc = str_starts_with($bannerImage, 'http://') || str_starts_with($bannerImage, 'https://')
                    ? $bannerImage
                    : asset($bannerImage);
            @endphp
            <img src="{{ $bannerImageSrc }}"
                alt="{{ $currentBanner['title'] }}"
                class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent"></div>
            <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/60 to-transparent"></div>
        </div>

        <div class="relative h-full flex flex-col justify-end px-6 pb-12 md:px-24 md:pb-16 lg:px-32 lg:pb-20">
            <div class="max-w-2xl text-white stagger-enter">
                <p class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-1.5 text-xs font-bold uppercase tracking-[0.2em] text-white backdrop-blur">
                    Bộ Sưu Tập Mới
                    <span class="h-1.5 w-1.5 rounded-full bg-red-500 animate-pulse"></span>
                </p>

                <h1 class="text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-black leading-[1.05] tracking-tight">
                    {{ $currentBanner['title'] }}
                </h1>

                <p class="mt-4 md:mt-6 max-w-xl text-base md:text-lg text-slate-200 leading-relaxed font-medium drop-shadow-md">
                    {{ $currentBanner['subtitle'] }}
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="{{ $currentBanner['link'] }}" class="inline-flex items-center justify-center rounded-full bg-red-600 px-8 py-3.5 text-sm md:text-base font-bold text-white shadow-[0_0_20px_rgba(220,38,38,0.4)] transition-all duration-300 hover:-translate-y-1 hover:bg-red-500 hover:shadow-[0_8px_25px_rgba(220,38,38,0.6)]">
                        {{ $currentBanner['cta_text'] }}
                    </a>
                    <a href="{{ route('shop.index', ['collection' => 'new_arrivals']) }}" class="inline-flex items-center justify-center rounded-full border-2 border-white/70 bg-white/5 px-8 py-3 text-sm md:text-base font-bold text-white backdrop-blur transition-all duration-300 hover:-translate-y-1 hover:bg-white hover:text-slate-900 hover:border-white shadow-lg">
                        Khám phá ngay
                    </a>
                </div>
            </div>
        </div>
    @endif

    @if(count($banners) > 1)
        <div class="absolute bottom-6 left-1/2 z-10 flex -translate-x-1/2 items-center gap-2.5 rounded-full bg-black/40 px-4 py-2.5 backdrop-blur-md border border-white/10">
            @foreach($banners as $index => $banner)
                <button
                    wire:click="goToBanner({{ $index }})"
                    aria-label="Go to banner {{ $index + 1 }}"
                    class="h-2 rounded-full transition-all duration-300 {{ $currentIndex === $index ? 'w-8 bg-red-500' : 'w-2 bg-white/50 hover:bg-white/80' }}">
                </button>
            @endforeach
        </div>

        <button
            wire:click="$set('currentIndex', ({{ $currentIndex }} - 1 + {{ count($banners) }}) % {{ count($banners) }})"
            class="absolute left-4 md:left-6 top-1/2 -translate-y-1/2 z-10 hidden md:flex h-12 w-12 items-center justify-center rounded-full border border-white/20 bg-black/20 text-white backdrop-blur-md transition-all duration-300 hover:bg-white/20 hover:scale-110">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>

        <button
            wire:click="nextBanner"
            class="absolute right-4 md:right-6 top-1/2 -translate-y-1/2 z-10 hidden md:flex h-12 w-12 items-center justify-center rounded-full border border-white/20 bg-black/20 text-white backdrop-blur-md transition-all duration-300 hover:bg-white/20 hover:scale-110">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
    @endif
</div>
