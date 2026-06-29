@extends('layouts.app')

@section('meta_title', 'SportWear Shop | Quần áo thể thao nam trẻ trung')
@section('meta_description', 'Nâng cấp phong cách thể thao nam với áo, quần, phụ kiện năng động. Mua sắm nhanh, giao hàng toàn quốc.')
@section('meta_keywords', 'sportwear, quần áo thể thao nam, gym, running, training')

@section('content')
<!-- Hero Banner -->
<section class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-8">
    <div class="stagger-enter">
        <livewire:banner-slider />
    </div>
</section>

<!-- Features/Benefits Strip -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 stagger-enter stagger-enter-delay-1">
        <div class="flex flex-col items-center justify-center p-6 rounded-2xl bg-white shadow-[0_4px_20px_-10px_rgba(0,0,0,0.1)] border border-slate-100 text-center transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="h-14 w-14 rounded-full bg-red-50 text-red-600 flex items-center justify-center mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
            </div>
            <h4 class="font-bold text-slate-900">Giao nhanh nội thành</h4>
            <p class="text-sm text-slate-500 mt-1.5">Chỉ trong vòng 2 giờ</p>
        </div>
        <div class="flex flex-col items-center justify-center p-6 rounded-2xl bg-white shadow-[0_4px_20px_-10px_rgba(0,0,0,0.1)] border border-slate-100 text-center transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="h-14 w-14 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <h4 class="font-bold text-slate-900">Sản phẩm chính hãng</h4>
            <p class="text-sm text-slate-500 mt-1.5">Cam kết chất lượng 100%</p>
        </div>
        <div class="flex flex-col items-center justify-center p-6 rounded-2xl bg-white shadow-[0_4px_20px_-10px_rgba(0,0,0,0.1)] border border-slate-100 text-center transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="h-14 w-14 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </div>
            <h4 class="font-bold text-slate-900">Đổi trả 30 ngày</h4>
            <p class="text-sm text-slate-500 mt-1.5">Linh hoạt và dễ dàng</p>
        </div>
        <div class="flex flex-col items-center justify-center p-6 rounded-2xl bg-white shadow-[0_4px_20px_-10px_rgba(0,0,0,0.1)] border border-slate-100 text-center transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="h-14 w-14 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <h4 class="font-bold text-slate-900">Hỗ trợ 24/7</h4>
            <p class="text-sm text-slate-500 mt-1.5">Luôn sẵn sàng tư vấn</p>
        </div>
    </div>
</section>

@if(($categories ?? collect())->isNotEmpty())
<!-- Featured Categories -->
<section class="py-12 bg-slate-50/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Danh Mục Nổi Bật</h2>
            <p class="text-lg text-slate-500 mt-3 font-medium">Lựa chọn phong cách phù hợp với đam mê của bạn</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 md:gap-6">
            @foreach($categories as $category)
                <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="group block">
                    <div class="relative overflow-hidden rounded-[1.5rem] bg-white aspect-[4/5] shadow-sm border border-slate-100 transition-all duration-500 hover:shadow-[0_15px_30px_-10px_rgba(0,0,0,0.15)] hover:-translate-y-2">
                        @if($category->image_url)
                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        @else
                            <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-300 transition-colors duration-500 group-hover:bg-slate-200">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent opacity-70 group-hover:opacity-90 transition-opacity duration-300"></div>
                        <div class="absolute bottom-0 left-0 w-full p-5 text-center transform translate-y-2 group-hover:translate-y-0 transition-transform duration-500">
                            <h3 class="font-bold text-white text-base md:text-lg drop-shadow-md">{{ $category->name }}</h3>
                            <p class="text-white/80 text-xs font-medium mt-1">{{ $category->products_count }} sản phẩm</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@if(($flashSales ?? collect())->isNotEmpty())
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative rounded-[2.5rem] overflow-hidden bg-slate-900 p-8 md:p-12 shadow-2xl">
            <!-- Background Decoration -->
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-red-500/20 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-orange-500/20 blur-3xl"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-10">
                <div class="text-white">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-red-500/20 border border-red-500/30 text-red-400 font-semibold text-sm mb-4">
                        <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                        Flash Sale Live
                    </div>
                    <h2 class="text-3xl md:text-4xl font-black tracking-tight">Deal Nóng Trong Ngày</h2>
                    <p class="text-slate-300 mt-3 text-lg max-w-xl">Cơ hội mua sắm các sản phẩm chính hãng với mức giá ưu đãi nhất. Chốt đơn ngay!</p>
                </div>
                <a href="{{ route('shop.index', ['collection' => 'flash_sale']) }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white text-slate-900 font-bold hover:bg-slate-100 transition-colors">
                    Xem tất cả deal
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>

            <div class="relative z-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($flashSales as $flashSale)
                    @php
                        $product = $flashSale->product;
                        $originalPrice = $product->sale_price ?? $product->price;
                        $discountPercent = $flashSale->discount_percent;
                        $flashImage = $product->image_url;
                        $flashImageSrc = str_starts_with($flashImage, 'http://') || str_starts_with($flashImage, 'https://')
                            ? $flashImage
                            : asset($flashImage);
                    @endphp

                    <article class="group relative flex flex-col bg-white rounded-2xl overflow-hidden shadow-lg transition-transform duration-300 hover:-translate-y-2 hover:shadow-2xl border border-white/10">
                        <a href="{{ route('products.show', $product->slug) }}" class="absolute inset-0 z-10" aria-label="{{ $product->name }}"></a>

                        <div class="relative aspect-[4/5] overflow-hidden bg-slate-100">
                            <img
                                src="{{ $flashImageSrc }}"
                                alt="{{ $product->name }}"
                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                                loading="lazy"
                            >
                            <div class="absolute top-4 left-4 z-20 flex flex-col gap-2">
                                <span class="px-3 py-1 bg-red-600 text-white text-xs font-bold rounded-lg shadow-sm">
                                    -{{ $discountPercent }}%
                                </span>
                            </div>
                            <div class="absolute bottom-4 left-4 right-4 z-20">
                                <div class="bg-white/90 backdrop-blur-md rounded-xl p-2.5 shadow-lg border border-white/50 text-center" data-flash-countdown data-end="{{ $flashSale->end_at->toIso8601String() }}">
                                    <span class="text-slate-900 font-bold text-sm tracking-wider">Đang cập nhật...</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-5 flex flex-col flex-grow justify-between bg-white">
                            <h3 class="line-clamp-2 text-base font-bold text-slate-900 group-hover:text-red-600 transition-colors">{{ $product->name }}</h3>

                            <div class="mt-4 flex items-baseline gap-2">
                                <span class="text-xl font-black text-red-600">{{ number_format($flashSale->flash_price, 0) }}đ</span>
                                <span class="text-sm font-medium text-slate-400 line-through">{{ number_format($originalPrice, 0) }}đ</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<section class="py-16 bg-white" id="new-products">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
            <div>
                <h2 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Hàng Mới Lên Kệ</h2>
                <p class="text-lg text-slate-500 mt-3 font-medium">Tinh thần trẻ, phối nhanh, mặc đẹp mỗi ngày.</p>
            </div>
            <a href="{{ route('shop.index', ['collection' => 'new_arrivals', 'sort' => 'newest']) }}" class="group inline-flex items-center gap-2 font-bold text-slate-900 hover:text-red-600 transition-colors">
                Xem tất cả
                <span class="w-8 h-8 rounded-full bg-slate-100 group-hover:bg-red-50 flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </span>
            </a>
        </div>

        <div x-data="{
                interval: null,
                init() {
                    this.start();
                },
                start() {
                    this.stop();
                    this.interval = setInterval(() => {
                        const slider = this.$refs.slider;
                        if (slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 10) {
                            slider.scrollTo({ left: 0, behavior: 'smooth' });
                        } else {
                            slider.scrollBy({ left: 300, behavior: 'smooth' });
                        }
                    }, 5000);
                },
                stop() {
                    if (this.interval) clearInterval(this.interval);
                },
                scrollNext() { 
                    this.stop();
                    this.$refs.slider.scrollBy({ left: 300, behavior: 'smooth' }); 
                    this.start();
                },
                scrollPrev() { 
                    this.stop();
                    this.$refs.slider.scrollBy({ left: -300, behavior: 'smooth' }); 
                    this.start();
                }
            }" 
            @mouseenter="stop" @mouseleave="start" @touchstart="stop" @touchend="start"
            class="relative group/newarrivals">
            
            <button type="button" @click.prevent.stop="scrollPrev" class="absolute left-0 top-[40%] -translate-y-1/2 -ml-4 md:-ml-6 z-40 flex h-10 w-10 md:h-12 md:w-12 opacity-0 group-hover/newarrivals:opacity-100 items-center justify-center rounded-full bg-white text-slate-900 shadow-[0_4px_20px_rgba(0,0,0,0.15)] transition-all duration-300 hover:scale-110">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            
            <button type="button" @click.prevent.stop="scrollNext" class="absolute right-0 top-[40%] -translate-y-1/2 -mr-4 md:-mr-6 z-40 flex h-10 w-10 md:h-12 md:w-12 opacity-0 group-hover/newarrivals:opacity-100 items-center justify-center rounded-full bg-white text-slate-900 shadow-[0_4px_20px_rgba(0,0,0,0.15)] transition-all duration-300 hover:scale-110">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>

            <div x-ref="slider" class="flex gap-4 md:gap-8 overflow-x-auto snap-x snap-mandatory pb-8 pt-4 px-4 -mx-4" style="scrollbar-width: none; -ms-overflow-style: none;">
                <style>div[x-ref="slider"]::-webkit-scrollbar { display: none; }</style>
                @forelse($newProducts ?? [] as $product)
                    <x-product-card :product="$product" variant="minimal" />
                @empty
                    <div class="w-full bg-slate-50 rounded-2xl py-16 text-center text-slate-500 border border-slate-100">
                        <p class="text-lg font-medium">Chưa có sản phẩm mới.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-slate-50/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-[2rem] p-6 md:p-10 lg:p-12 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
                <div>
                    <h2 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Top Bán Chạy</h2>
                    <p class="text-lg text-slate-500 mt-3 font-medium">Sản phẩm được yêu thích nhất bởi hàng ngàn khách hàng.</p>
                </div>
                <a href="{{ route('shop.index', ['collection' => 'best_sellers', 'sort' => 'popularity']) }}" class="inline-flex items-center justify-center rounded-full bg-slate-900 px-6 py-2.5 text-sm font-bold text-white transition-all hover:bg-slate-800 hover:shadow-lg hover:-translate-y-0.5">
                    Khám phá ngay
                </a>
            </div>

            <div x-data="{
                    interval: null,
                    init() {
                        this.start();
                    },
                    start() {
                        this.stop();
                        this.interval = setInterval(() => {
                            const slider = this.$refs.slider2;
                            if (slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 10) {
                                slider.scrollTo({ left: 0, behavior: 'smooth' });
                            } else {
                                slider.scrollBy({ left: 300, behavior: 'smooth' });
                            }
                        }, 5000);
                    },
                    stop() {
                        if (this.interval) clearInterval(this.interval);
                    },
                    scrollNext() { 
                        this.stop();
                        this.$refs.slider2.scrollBy({ left: 300, behavior: 'smooth' }); 
                        this.start();
                    },
                    scrollPrev() { 
                        this.stop();
                        this.$refs.slider2.scrollBy({ left: -300, behavior: 'smooth' }); 
                        this.start();
                    }
                }" 
                @mouseenter="stop" @mouseleave="start" @touchstart="stop" @touchend="start"
                class="relative group/slider">
                
                <button type="button" @click.prevent.stop="scrollPrev" class="absolute left-0 top-[45%] -translate-y-1/2 -ml-6 md:-ml-8 z-40 flex h-12 w-12 opacity-0 group-hover/slider:opacity-100 items-center justify-center rounded-full bg-slate-900 text-white shadow-xl transition-all duration-300 hover:scale-110 hover:bg-red-600 border-2 border-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                
                <button type="button" @click.prevent.stop="scrollNext" class="absolute right-0 top-[45%] -translate-y-1/2 -mr-6 md:-mr-8 z-40 flex h-12 w-12 opacity-0 group-hover/slider:opacity-100 items-center justify-center rounded-full bg-slate-900 text-white shadow-xl transition-all duration-300 hover:scale-110 hover:bg-red-600 border-2 border-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                </button>

                <div x-ref="slider2" class="flex gap-6 md:gap-10 overflow-x-auto snap-x snap-mandatory pb-8 pt-4 px-4 -mx-4" style="scrollbar-width: none; -ms-overflow-style: none;">
                    <style>div[x-ref="slider2"]::-webkit-scrollbar { display: none; }</style>
                    @forelse($bestSellers ?? [] as $product)
                        <x-product-card :product="$product" variant="bold" :index="$loop->iteration" />
                    @empty
                        <div class="w-full border-2 border-dashed border-slate-200 rounded-2xl py-16 text-center text-slate-500">
                            <p class="text-lg font-medium">Chưa có dữ liệu bán chạy.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-12 bg-zinc-950 overflow-hidden relative">
    <!-- Sporty diagonal stripes background -->
    <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, #000 0, #000 2px, transparent 2px, transparent 8px);"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <h2 class="text-xl md:text-2xl font-black text-center text-white mb-8 tracking-[0.2em] uppercase italic">Thương Hiệu Đồng Hành</h2>
        
        <div x-data="{
                interval: null,
                init() { this.start(); },
                start() {
                    this.stop();
                    this.interval = setInterval(() => {
                        const slider = this.$refs.brandSlider;
                        if (slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 10) {
                            slider.scrollTo({ left: 0, behavior: 'smooth' });
                        } else {
                            slider.scrollBy({ left: 200, behavior: 'smooth' });
                        }
                    }, 3000);
                },
                stop() { clearInterval(this.interval); }
            }" 
            @mouseenter="stop" @mouseleave="start" @touchstart="stop" @touchend="start"
            class="relative">
            
            <div x-ref="brandSlider" class="flex gap-4 md:gap-6 overflow-x-auto snap-x snap-mandatory pb-4 pt-2 px-4 -mx-4 items-center" style="scrollbar-width: none; -ms-overflow-style: none;">
                <style>div[x-ref="brandSlider"]::-webkit-scrollbar { display: none; }</style>
                @forelse($brands ?? [] as $brand)
                    <div class="w-40 md:w-56 shrink-0 snap-center py-2">
                        <div class="h-16 md:h-20 bg-zinc-900/80 backdrop-blur-sm border-b-4 border-red-600 flex items-center justify-center p-4 transform -skew-x-12 hover:-skew-x-6 hover:bg-zinc-800 hover:-translate-y-1 transition-all duration-300 group shadow-lg cursor-pointer">
                            <span class="font-black text-zinc-500 group-hover:text-white transition-colors uppercase tracking-[0.2em] text-sm md:text-base text-center italic transform skew-x-12 group-hover:skew-x-6">{{ $brand->name }}</span>
                        </div>
                    </div>
                @empty
                    <div class="w-full text-center text-zinc-500 py-8">Chưa có thương hiệu.</div>
                @endforelse
            </div>
        </div>
    </div>
</section>

<section class="py-20 bg-zinc-900 relative overflow-hidden">
    <!-- Sporty background elements -->
    <div class="absolute top-0 right-0 w-1/2 h-full bg-red-600 transform skew-x-12 translate-x-1/2 opacity-5 pointer-events-none"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
            <div>
                <h2 class="text-sm font-black tracking-[0.2em] uppercase text-red-500 mb-2 italic">Đánh Giá Thực Tế</h2>
                <h3 class="text-3xl md:text-5xl font-black text-white tracking-tight uppercase italic">Khách Hàng Nói Gì?</h3>
            </div>
        </div>

        <div x-data="{
                interval: null,
                init() { this.start(); },
                start() {
                    this.stop();
                    this.interval = setInterval(() => { this.scrollNext(true); }, 5000);
                },
                stop() { clearInterval(this.interval); },
                scrollNext(auto = false) {
                    const slider = this.$refs.testiSlider;
                    if (slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 10) {
                        slider.scrollTo({ left: 0, behavior: 'smooth' });
                    } else {
                        slider.scrollBy({ left: 400, behavior: 'smooth' });
                    }
                },
                scrollPrev() {
                    this.$refs.testiSlider.scrollBy({ left: -400, behavior: 'smooth' });
                }
            }" 
            @mouseenter="stop" @mouseleave="start" @touchstart="stop" @touchend="start"
            class="relative group/testi">
            
            <button type="button" @click.prevent.stop="scrollPrev(); stop(); start();" class="absolute left-0 top-[45%] -translate-y-1/2 -ml-4 md:-ml-6 z-40 flex h-12 w-12 opacity-0 group-hover/testi:opacity-100 items-center justify-center bg-zinc-800 text-white border-2 border-red-600 transition-all duration-300 hover:scale-110 hover:bg-red-600 transform -skew-x-12 shadow-[0_4px_20px_rgba(220,38,38,0.3)]">
                <svg class="h-6 w-6 transform skew-x-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            
            <button type="button" @click.prevent.stop="scrollNext(); stop(); start();" class="absolute right-0 top-[45%] -translate-y-1/2 -mr-4 md:-mr-6 z-40 flex h-12 w-12 opacity-0 group-hover/testi:opacity-100 items-center justify-center bg-zinc-800 text-white border-2 border-red-600 transition-all duration-300 hover:scale-110 hover:bg-red-600 transform -skew-x-12 shadow-[0_4px_20px_rgba(220,38,38,0.3)]">
                <svg class="h-6 w-6 transform skew-x-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
            </button>

            <div x-ref="testiSlider" class="flex gap-6 overflow-x-auto snap-x snap-mandatory pb-8 pt-4 px-4 -mx-4" style="scrollbar-width: none; -ms-overflow-style: none;">
                <style>div[x-ref="testiSlider"]::-webkit-scrollbar { display: none; }</style>
                
                @forelse($testimonials ?? [] as $item)
                    <div class="w-80 md:w-96 lg:w-[28rem] shrink-0 snap-start">
                        <div class="bg-zinc-800/80 backdrop-blur border-l-4 border-red-600 p-8 md:p-10 relative h-full flex flex-col justify-between hover:bg-zinc-800 transition-colors shadow-xl">
                            <div class="absolute top-6 right-6 text-zinc-700">
                                <svg class="w-12 h-12 opacity-20" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z"/></svg>
                            </div>
                            
                            <div>
                                <div class="flex gap-1 mb-6 text-red-500">
                                    @for($i = 0; $i < ($item['rating'] ?? 5); $i++)
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    @endfor
                                </div>
                                <p class="text-lg md:text-xl text-zinc-300 font-medium leading-relaxed mb-8 italic">"{{ $item['text'] }}"</p>
                            </div>
                            
                            <div class="flex items-center gap-4 mt-auto">
                                <div class="w-12 h-12 bg-zinc-700/50 rounded flex items-center justify-center text-2xl shadow-inner text-white font-bold">{{ $item['avatar'] }}</div>
                                <div class="text-left">
                                    <h4 class="font-black text-white text-base md:text-lg uppercase tracking-wider">{{ $item['name'] }}</h4>
                                    <p class="text-[10px] md:text-xs text-red-500 font-bold uppercase tracking-[0.15em]">Khách Hàng Thực Tế</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="w-full text-center text-zinc-500 py-8">Chưa có đánh giá.</div>
                @endforelse
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const formatTime = (value) => String(value).padStart(2, '0');
        const countdownElements = document.querySelectorAll('[data-flash-countdown]');

        const tick = () => {
            countdownElements.forEach((element) => {
                const endAt = new Date(element.dataset.end || '').getTime();
                const now = Date.now();
                const diff = endAt - now;

                if (Number.isNaN(endAt) || diff <= 0) {
                    element.textContent = 'Đã kết thúc';
                    return;
                }

                const totalSeconds = Math.floor(diff / 1000);
                const days = Math.floor(totalSeconds / 86400);
                const hours = Math.floor((totalSeconds % 86400) / 3600);
                const minutes = Math.floor((totalSeconds % 3600) / 60);
                const seconds = totalSeconds % 60;

                element.textContent = days > 0
                    ? `Còn ${days}d ${formatTime(hours)}h`
                    : `Còn ${formatTime(hours)}:${formatTime(minutes)}:${formatTime(seconds)}`;
            });
        };

        tick();
        setInterval(tick, 1000);
    });
</script>
@endpush
