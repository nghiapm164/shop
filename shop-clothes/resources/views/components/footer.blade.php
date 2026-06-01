<footer class="bg-slate-900 text-slate-300">
    {{-- Footer Main --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 py-12 md:py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8">
            {{-- Brand --}}
            <div>
                <a href="{{ route('home') }}" class="inline-block mb-4">
                    <span class="text-2xl font-black tracking-tighter text-white">
                        MODE<span class="text-red-500">SPORT</span>
                    </span>
                </a>
                <p class="text-sm text-slate-400 leading-relaxed">
                    Cửa hàng thời trang thể thao nam phong cách hiện đại, chất lượng cao, giá cạnh tranh, giao hàng nhanh toàn quốc.
                </p>
                <div class="flex gap-3 mt-5">
                    <a href="#" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-red-500 transition-colors text-slate-300 hover:text-white">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-red-500 transition-colors text-slate-300 hover:text-white">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-red-500 transition-colors text-slate-300 hover:text-white">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.11v-3.5a6.37 6.37 0 00-.79-.05A6.34 6.34 0 003.15 15.2a6.34 6.34 0 0010.86 4.48A6.34 6.34 0 0015.67 15V8.56a8.3 8.3 0 004.85 1.52V6.69h-.93z"/></svg>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-red-500 transition-colors text-slate-300 hover:text-white">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div>
                <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Danh mục</h3>
                <ul class="space-y-2.5">
                    <li><a href="{{ route('shop.index') }}" class="text-sm text-slate-400 hover:text-white transition-colors">Tất cả sản phẩm</a></li>
                    <li><a href="{{ route('shop.new-arrivals') }}" class="text-sm text-slate-400 hover:text-white transition-colors">Hàng mới về</a></li>
                    <li><a href="{{ route('shop.index', ['collection' => 'best_sellers']) }}" class="text-sm text-slate-400 hover:text-white transition-colors">Bán chạy</a></li>
                    <li><a href="{{ route('shop.best-prices') }}" class="text-sm text-slate-400 hover:text-white transition-colors">Giá tốt</a></li>
                    <li><a href="{{ route('shop.index', ['collection' => 'flash_sale']) }}" class="text-sm text-slate-400 hover:text-white transition-colors">Flash Sale</a></li>
                </ul>
            </div>

            {{-- Support --}}
            <div>
                <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Hỗ trợ</h3>
                <ul class="space-y-2.5">
                    <li><a href="#" class="text-sm text-slate-400 hover:text-white transition-colors">Về chúng tôi</a></li>
                    <li><a href="#" class="text-sm text-slate-400 hover:text-white transition-colors">Chính sách đổi trả</a></li>
                    <li><a href="#" class="text-sm text-slate-400 hover:text-white transition-colors">Hướng dẫn chọn size</a></li>
                    <li><a href="#" class="text-sm text-slate-400 hover:text-white transition-colors">Hướng dẫn mua hàng</a></li>
                    <li><a href="#" class="text-sm text-slate-400 hover:text-white transition-colors">Liên hệ</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Liên hệ</h3>
                <ul class="space-y-3">
                    <li class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 mt-0.5 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="text-sm text-slate-400">Hồ Tùng Mậu, Hà Nội</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 mt-0.5 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span class="text-sm text-slate-400">1900 6750</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 mt-0.5 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span class="text-sm text-slate-400">support@modesport.vn</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Footer Bottom --}}
    <div class="border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 py-5">
            <p class="text-xs text-slate-500 text-center">&copy; {{ date('Y') }} MODESPORT. All rights reserved.</p>
        </div>
    </div>
</footer>
