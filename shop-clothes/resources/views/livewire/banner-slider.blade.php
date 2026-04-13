<div
    class="relative w-full h-[370px] md:h-[460px] overflow-hidden rounded-[1.8rem] bg-slate-950"
    wire:poll.5s="nextBanner"
>
    @if($currentBanner)
        <div class="absolute inset-0">
            <img src="{{ $currentBanner['image_url'] }}"
                alt="{{ $currentBanner['title'] }}"
                class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/30 to-black/8"></div>
            <div class="absolute inset-y-0 right-0 w-1/3 bg-gradient-to-l from-black/35 to-transparent"></div>
        </div>

        <div class="relative h-full flex items-end px-5 pb-8 md:px-10 md:pb-12 lg:px-14">
            <div class="max-w-2xl text-white">
                <p class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/35 bg-black/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-white/90 backdrop-blur">
                    Spring Edit
                    <span class="h-1.5 w-1.5 rounded-full bg-white/80"></span>
                </p>

                <h1 class="text-3xl md:text-5xl lg:text-6xl font-black leading-[1.03] tracking-tight">
                    {{ $currentBanner['title'] }}
                </h1>

                <p class="mt-4 max-w-xl text-sm md:text-base text-slate-100/90">
                    {{ $currentBanner['subtitle'] }}
                </p>

                <div class="mt-6 flex items-center gap-3">
                    <a href="{{ $currentBanner['link'] }}" class="inline-flex items-center rounded-full bg-white px-6 py-3 text-sm font-bold text-slate-900 transition-all hover:-translate-y-0.5 hover:bg-slate-100">
                        {{ $currentBanner['cta_text'] }}
                    </a>
                    <span class="hidden sm:inline-flex items-center gap-2 text-xs font-medium uppercase tracking-[0.16em] text-white/85">
                        Limited Collection
                        <span class="h-px w-12 bg-white/60"></span>
                    </span>
                </div>
            </div>
        </div>
    @endif

    @if(count($banners) > 1)
        <div class="absolute bottom-5 left-1/2 z-10 flex -translate-x-1/2 items-center gap-2 rounded-full bg-black/28 px-3 py-2 backdrop-blur">
            @foreach($banners as $index => $banner)
                <button
                    wire:click="goToBanner({{ $index }})"
                    class="h-2.5 rounded-full transition-all duration-300 {{ $currentIndex === $index ? 'w-7 bg-white' : 'w-2.5 bg-white/55 hover:bg-white/85' }}">
                </button>
            @endforeach
        </div>

        <button
            wire:click="$set('currentIndex', ({{ $currentIndex }} - 1 + {{ count($banners) }}) % {{ count($banners) }})"
            class="absolute left-3 md:left-4 top-1/2 -translate-y-1/2 z-10 hidden md:flex items-center justify-center rounded-full border border-white/45 bg-black/20 p-2.5 text-white transition-all hover:bg-black/40">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>

        <button
            wire:click="nextBanner"
            class="absolute right-3 md:right-4 top-1/2 -translate-y-1/2 z-10 hidden md:flex items-center justify-center rounded-full border border-white/45 bg-black/20 p-2.5 text-white transition-all hover:bg-black/40">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
    @endif
</div>
