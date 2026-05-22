@extends('layouts.ndstyle')

@section('meta_title', 'ND Style Clone | Thời trang nam nữ cao cấp')
@section('meta_description', 'Thời trang nam nữ phong cách hiện đại')
@section('meta_keywords', 'thời trang, quần áo, nam, nữ')

@section('content')
<link rel="preload" as="script" href="//bizweb.dktcdn.net/100/534/571/themes/972900/assets/swiper.js?1749442635129" />
<script src="//bizweb.dktcdn.net/100/534/571/themes/972900/assets/swiper.js?1749442635129"></script>

<header class="header">
    {{-- Topbar --}}
    <div class="header__topbar" style="background:var(--topBarColor);">
        <div class="container">
            <div class="topbar-swiper swiper-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide" style="text-align:center;height:36px;line-height:36px;font-size:13px;font-weight:600;">
                        <a href="{{ route('shop.index') }}" style="color:var(--topBarTextColor);text-decoration:none;">Chào đón Bộ Sưu Tập Thu Đông 2026</a>
                    </div>
                    <div class="swiper-slide" style="text-align:center;height:36px;line-height:36px;font-size:13px;font-weight:600;">
                        <a href="{{ route('shop.index') }}" style="color:var(--topBarTextColor);text-decoration:none;">Phái đẹp để yêu, vạn deal cưng chiều</a>
                    </div>
                    <div class="swiper-slide" style="text-align:center;height:36px;line-height:36px;font-size:13px;font-weight:600;">
                        <a href="{{ route('shop.index') }}" style="color:var(--topBarTextColor);text-decoration:none;">Đồ mặc cả nhà, êm ái cả ngày</a>
                    </div>
                </div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
    </div>

    {{-- Header Middle --}}
    <div class="header__middle" style="background:var(--middleHeaderColor);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-2 col-lg-2 col-md-4 col-12 col-logo order-lg-1">
                    <a href="{{ route('home') }}" class="nd-logo-text">ND<span>Style</span></a>
                </div>
                <div class="col-xl-7 col-lg-7 col-md-8 col-12 col-search order-lg-3 order-md-4 order-md-2 order-3">
                    <form action="{{ route('shop.index') }}" method="get" class="header-search" role="search" style="position:relative;">
                        <input type="text" name="query" autocomplete="off" required placeholder="Tìm kiếm..." class="input-group-field">
                        <button type="submit" class="btn-search">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        </button>
                    </form>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-12 col-12 d-flex align-items-center justify-content-end col-right order-lg-4 order-md-3 order-2">
                    <div class="header-wishlist">
                        <a href="{{ auth()->check() ? route('wishlist.index') : route('login') }}" title="Yêu thích">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 25" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9932 5.44636C9.9938 3.10895 6.65975 2.48019 4.15469 4.62056C1.64964 6.76093 1.29697 10.3395 3.2642 12.8709C4.89982 14.9757 9.84977 19.4146 11.4721 20.8514C11.6536 21.0121 11.7444 21.0925 11.8502 21.1241C11.9426 21.1516 12.0437 21.1516 12.1361 21.1241C12.2419 21.0925 12.3327 21.0121 12.5142 20.8514C14.1365 19.4146 19.0865 14.9757 20.7221 12.8709C22.6893 10.3395 22.3797 6.73842 19.8316 4.62056C17.2835 2.5027 13.9925 3.10895 11.9932 5.44636Z" stroke="var(--mainColor)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Yêu thích
                        </a>
                    </div>
                    <div class="header-account">
                        <a href="{{ auth()->check() ? route('profile.edit') : route('login') }}" title="Tài khoản">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="23" viewBox="0 0 22 23" fill="none">
                                <path d="M4.3163 18.9238C4.92462 17.4906 6.34492 16.4854 8 16.4854H14C15.6551 16.4854 17.0754 17.4906 17.6837 18.9238M15 8.98535C15 11.1945 13.2091 12.9854 11 12.9854C8.79086 12.9854 7 11.1945 7 8.98535C7 6.77621 8.79086 4.98535 11 4.98535C13.2091 4.98535 15 6.77621 15 8.98535ZM21 11.4854C21 17.0082 16.5228 21.4854 11 21.4854C5.47715 21.4854 1 17.0082 1 11.4854C1 5.9625 5.47715 1.48535 11 1.48535C16.5228 1.48535 21 5.9625 21 11.4854Z" stroke="var(--mainColor)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Tài khoản
                        </a>
                    </div>
                    <div class="header-cart">
                        <a href="{{ route('cart.index') }}" title="Giỏ hàng">
                            <span class="count_item count_item_pr" style="position:absolute;top:-2px;right:-2px;background:var(--mainColor);color:#fff;font-size:10px;font-weight:700;min-width:18px;height:18px;border-radius:9px;display:flex;align-items:center;justify-content:center;"></span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="21" viewBox="0 0 22 21" fill="none">
                                <path d="M14.9996 6.48535C14.9996 7.54622 14.5782 8.56363 13.828 9.31378C13.0779 10.0639 12.0605 10.4854 10.9996 10.4854C9.93872 10.4854 8.92131 10.0639 8.17116 9.31378C7.42102 8.56363 6.99959 7.54622 6.99959 6.48535M2.63281 5.88674L1.93281 14.2867C1.78243 16.0913 1.70724 16.9935 2.01227 17.6895C2.28027 18.3011 2.74462 18.8057 3.33177 19.1236C4.00006 19.4853 4.90545 19.4853 6.71623 19.4853H15.283C17.0937 19.4853 17.9991 19.4853 18.6674 19.1236C19.2546 18.8057 19.7189 18.3011 19.9869 17.6895C20.2919 16.9935 20.2167 16.0913 20.0664 14.2867L19.3664 5.88673C19.237 4.3341 19.1723 3.55779 18.8285 2.97021C18.5257 2.45279 18.0748 2.03795 17.5341 1.7792C16.92 1.48535 16.141 1.48535 14.583 1.48535L7.41623 1.48535C5.85821 1.48535 5.07921 1.48535 4.4651 1.7792C3.92433 2.03795 3.47349 2.45279 3.17071 2.97021C2.82689 3.55778 2.76219 4.3341 2.63281 5.88674Z" stroke="var(--mainColor)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Giỏ hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <div class="header__menu" style="background:#fff;border-bottom:1px solid #eee;">
        <div class="container">
            <div class="navigation-head">
                <nav class="nav-horizontal">
                    <ul class="item_big item_big_pc">
                        <li class="nav-item active">
                            <a class="a-img" href="{{ route('home') }}">Trang chủ</a>
                        </li>
                        @if(isset($categories) && $categories->count() > 0)
                            @foreach($categories->take(5) as $cat)
                            <li class="nav-item">
                                <a class="a-img" href="{{ route('shop.index', ['category' => $cat->id]) }}">{{ $cat->name }}</a>
                            </li>
                            @endforeach
                        @else
                            <li class="nav-item"><a class="a-img" href="{{ route('shop.index') }}">Nữ</a></li>
                            <li class="nav-item"><a class="a-img" href="{{ route('shop.index') }}">Nam</a></li>
                            <li class="nav-item"><a class="a-img" href="{{ route('shop.index') }}">Áo</a></li>
                            <li class="nav-item"><a class="a-img" href="{{ route('shop.index') }}">Quần</a></li>
                            <li class="nav-item"><a class="a-img" href="{{ route('shop.index') }}">Phụ kiện</a></li>
                        @endif
                        <li class="nav-item">
                            <a class="a-img" href="{{ route('shop.index', ['collection' => 'new_arrivals', 'sort' => 'newest']) }}">Hàng mới</a>
                        </li>
                        <li class="nav-item">
                            <a class="a-img" href="{{ route('shop.index', ['sort' => 'price_low']) }}">Giảm giá</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>

<main class="bodywrap">
    <h1 style="display:none;">ND Style Clone</h1>

    {{-- ═══════════════════════════════════════ --}}
    {{-- HOME SLIDER --}}
    {{-- ═══════════════════════════════════════ --}}
    <div class="home-slider swiper-container">
        <div class="swiper-wrapper">
            <div class="swiper-slide item-effect">
                <a href="{{ route('shop.index') }}" title="ND Style">
                    <div style="width:100%;aspect-ratio:1920/695;background:linear-gradient(135deg,#1a1a2e 0%,#16213e 40%,#0f3460 100%);display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden;">
                        <div style="position:absolute;inset:0;opacity:0.15;background:radial-gradient(circle at 70% 40%,rgba(255,99,71,0.4),transparent 60%);"></div>
                        <div style="position:relative;z-index:2;text-align:center;padding:40px;">
                            <div style="display:inline-block;padding:6px 16px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);border-radius:20px;color:rgba(255,255,255,0.8);font-size:13px;font-weight:600;margin-bottom:16px;">BST Mùa mới 2026</div>
                            <h2 style="color:white;font-size:48px;font-weight:900;line-height:1.1;margin-bottom:12px;">Tập chất.<br><span style="color:#ff6347;">Mặc chất.</span></h2>
                            <p style="color:rgba(255,255,255,0.6);font-size:16px;max-width:400px;margin:0 auto 24px;">Phong cách athleisure hiện đại</p>
                            <span style="display:inline-flex;align-items:center;gap:8px;background:#ff6347;color:white;padding:12px 32px;border-radius:12px;font-weight:700;font-size:15px;">Mua ngay →</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="swiper-slide item-effect">
                <a href="{{ route('shop.index', ['collection' => 'flash_sale']) }}" title="Sale">
                    <div style="width:100%;aspect-ratio:1920/695;background:linear-gradient(135deg,#dc2626 0%,#ef4444 50%,#f87171 100%);display:flex;align-items:center;justify-content:center;position:relative;">
                        <div style="position:absolute;inset:0;opacity:0.1;background:repeating-linear-gradient(45deg,transparent,transparent 40px,rgba(255,255,255,0.05) 40px,rgba(255,255,255,0.05) 80px);"></div>
                        <div style="position:relative;z-index:2;text-align:center;padding:40px;">
                            <div style="color:#fbbf24;font-size:60px;font-weight:900;line-height:1;margin-bottom:8px;">SALE</div>
                            <h2 style="color:white;font-size:42px;font-weight:900;margin-bottom:12px;">Giảm đến 50%</h2>
                            <p style="color:rgba(255,255,255,0.8);font-size:16px;margin-bottom:24px;">Áo khoác - Áo len - Phụ kiện thu đông</p>
                            <span style="display:inline-flex;align-items:center;gap:8px;background:white;color:#dc2626;padding:12px 32px;border-radius:12px;font-weight:700;font-size:15px;">Xem ngay</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script>
        var homeSwiper = new Swiper('.home-slider', {
            slidesPerView: 1, loop: false, grabCursor: true, spaceBetween: 0,
            pagination: { el: '.home-slider .swiper-pagination', clickable: true },
            navigation: { nextEl: '.home-slider .swiper-button-next', prevEl: '.home-slider .swiper-button-prev' },
            autoplay: { delay: 5000 },
        });
    </script>

    {{-- ═══════════════════════════════════════ --}}
    {{-- POLICY BAR --}}
    {{-- ═══════════════════════════════════════ --}}
    <div class="home-policy item-effect" style="background:#f8f9fa;border-top:1px solid #eee;border-bottom:1px solid #eee;padding:20px 0;">
        <div class="container">
            <div class="inner">
                <div class="policy-slider swiper-container">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide item" style="display:flex;align-items:center;gap:12px;padding:8px 0;">
                            <div class="icon" style="flex-shrink:0;width:48px;height:48px;display:flex;align-items:center;justify-content:center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ff6347" stroke-width="1.5"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5a1 1 0 0 1-1 1h-1"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                            </div>
                            <div class="info">
                                <div class="title" style="font-size:13px;font-weight:700;color:#333;margin-bottom:2px;">Giao hàng toàn quốc</div>
                                <p style="font-size:12px;color:#888;margin:0;">Thanh toán (COD) khi nhận hàng</p>
                            </div>
                        </div>
                        <div class="swiper-slide item" style="display:flex;align-items:center;gap:12px;padding:8px 0;">
                            <div class="icon" style="flex-shrink:0;width:48px;height:48px;display:flex;align-items:center;justify-content:center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ff6347" stroke-width="1.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <div class="info">
                                <div class="title" style="font-size:13px;font-weight:700;color:#333;margin-bottom:2px;">Miễn phí giao hàng</div>
                                <p style="font-size:12px;color:#888;margin:0;">Theo chính sách</p>
                            </div>
                        </div>
                        <div class="swiper-slide item" style="display:flex;align-items:center;gap:12px;padding:8px 0;">
                            <div class="icon" style="flex-shrink:0;width:48px;height:48px;display:flex;align-items:center;justify-content:center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ff6347" stroke-width="1.5"><path d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg>
                            </div>
                            <div class="info">
                                <div class="title" style="font-size:13px;font-weight:700;color:#333;margin-bottom:2px;">Đổi trả trong 7 ngày</div>
                                <p style="font-size:12px;color:#888;margin:0;">Kể từ ngày mua hàng</p>
                            </div>
                        </div>
                        <div class="swiper-slide item" style="display:flex;align-items:center;gap:12px;padding:8px 0;">
                            <div class="icon" style="flex-shrink:0;width:48px;height:48px;display:flex;align-items:center;justify-content:center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ff6347" stroke-width="1.5"><path d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                            </div>
                            <div class="info">
                                <div class="title" style="font-size:13px;font-weight:700;color:#333;margin-bottom:2px;">Hỗ trợ 24/7</div>
                                <p style="font-size:12px;color:#888;margin:0;">Theo chính sách</p>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    var policySwiper = new Swiper('.policy-slider', {
                        slidesPerView: 4, loop: false, spaceBetween: 40, autoHeight: true,
                        autoplay: { delay: 4000 },
                        breakpoints: { 300:{slidesPerView:1}, 500:{slidesPerView:1}, 768:{slidesPerView:2}, 991:{slidesPerView:3,spaceBetween:20}, 1200:{slidesPerView:4} }
                    });
                </script>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════ --}}
    {{-- CATEGORIES --}}
    {{-- ═══════════════════════════════════════ --}}
    @if(isset($categories) && $categories->count() > 0)
    <div class="home-cate" style="padding:40px 0;">
        <div class="container">
            <div class="list-cate item-effect" style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;">
                @foreach($categories->take(8) as $cat)
                <div class="item" style="text-align:center;cursor:pointer;">
                    <div class="thumb-cate" style="width:100%;aspect-ratio:1;border-radius:12px;overflow:hidden;margin-bottom:10px;background:#f5f5f5;">
                        <a href="{{ route('shop.index', ['category' => $cat->id]) }}">
                            @if($cat->image_url)
                                <img width="195" height="195" loading="lazy" src="{{ $cat->image_url }}" alt="{{ $cat->name }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#f0f0f0;">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                </div>
                            @endif
                        </a>
                    </div>
                    <div class="title" style="font-size:14px;font-weight:600;color:#333;">
                        <a href="{{ route('shop.index', ['category' => $cat->id]) }}" style="color:inherit;text-decoration:none;">{{ $cat->name }}</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════ --}}
    {{-- VOUCHER SECTION --}}
    {{-- ═══════════════════════════════════════ --}}
    <div class="home-voucher" style="padding:40px 0 20px;">
        <div class="container">
            <div class="block-title" style="text-align:center;margin-bottom:24px;">
                <h2 class="item-effect" style="font-size:24px;font-weight:800;color:#333;display:inline-block;position:relative;margin:0;">
                    <span style="display:inline-block;">Dành riêng cho bạn</span>
                </h2>
            </div>
            <div class="voucher-swiper swiper-container item-effect">
                <div class="swiper-wrapper">
                    <div class="swiper-slide item" style="border-radius:12px;overflow:hidden;cursor:pointer;border:2px dashed #305a9b;background:linear-gradient(135deg,#f0f4ff 0%,#e8eeff 100%);padding:20px;text-align:center;" onclick="copyVoucher('FREESHIP')">
                        <div style="font-size:40px;margin-bottom:8px;">🚚</div>
                        <div style="font-size:16px;font-weight:700;color:#305a9b;margin-top:8px;">FREESHIP</div>
                        <div style="font-size:12px;color:#666;margin-top:4px;">Miễn phí vận chuyển</div>
                        <div style="display:inline-block;margin-top:10px;padding:6px 16px;background:#305a9b;color:white;border-radius:6px;font-size:12px;font-weight:700;">FREESHIP</div>
                    </div>
                    <div class="swiper-slide item" style="border-radius:12px;overflow:hidden;cursor:pointer;border:2px dashed #305a9b;background:linear-gradient(135deg,#f0f4ff 0%,#e8eeff 100%);padding:20px;text-align:center;" onclick="copyVoucher('15% OFF')">
                        <div style="font-size:40px;margin-bottom:8px;">🏷️</div>
                        <div style="font-size:16px;font-weight:700;color:#305a9b;margin-top:8px;">Giảm 15%</div>
                        <div style="font-size:12px;color:#666;margin-top:4px;">Cho đơn từ 699k</div>
                        <div style="display:inline-block;margin-top:10px;padding:6px 16px;background:#305a9b;color:white;border-radius:6px;font-size:12px;font-weight:700;">15% OFF</div>
                    </div>
                    <div class="swiper-slide item" style="border-radius:12px;overflow:hidden;cursor:pointer;border:2px dashed #305a9b;background:linear-gradient(135deg,#f0f4ff 0%,#e8eeff 100%);padding:20px;text-align:center;" onclick="copyVoucher('25% OFF')">
                        <div style="font-size:40px;margin-bottom:8px;">💎</div>
                        <div style="font-size:16px;font-weight:700;color:#305a9b;margin-top:8px;">Giảm 25%</div>
                        <div style="font-size:12px;color:#666;margin-top:4px;">Cho đơn từ 1.049k</div>
                        <div style="display:inline-block;margin-top:10px;padding:6px 16px;background:#305a9b;color:white;border-radius:6px;font-size:12px;font-weight:700;">25% OFF</div>
                    </div>
                    <div class="swiper-slide item" style="border-radius:12px;overflow:hidden;cursor:pointer;border:2px dashed #305a9b;background:linear-gradient(135deg,#f0f4ff 0%,#e8eeff 100%);padding:20px;text-align:center;" onclick="copyVoucher('MUA1TANG1')">
                        <div style="font-size:40px;margin-bottom:8px;">🎁</div>
                        <div style="font-size:16px;font-weight:700;color:#305a9b;margin-top:8px;">Mua 1 + 1</div>
                        <div style="font-size:12px;color:#666;margin-top:4px;">Cho đơn từ 1699k</div>
                        <div style="display:inline-block;margin-top:10px;padding:6px 16px;background:#305a9b;color:white;border-radius:6px;font-size:12px;font-weight:700;">MUA1TANG1</div>
                    </div>
                </div>
                <div class="swiper-pagination" style="display:none;"></div>
            </div>
        </div>
    </div>
    <script>
        var swiper_threebanner = new Swiper('.voucher-swiper', {
            slidesPerView: 4, loop: false, grabCursor: true, spaceBetween: 20,
            pagination: { el: '.voucher-swiper .swiper-pagination', clickable: true },
            autoplay: { delay: 4000 },
            breakpoints: { 300:{slidesPerView:2}, 500:{slidesPerView:2}, 640:{slidesPerView:2}, 768:{slidesPerView:3}, 991:{slidesPerView:3}, 1200:{slidesPerView:4} }
        });
    </script>

    {{-- Voucher Popup --}}
    <div class="overlayVoucher" id="voucherOverlay" onclick="closeVoucher()"></div>
    <div class="popupVoucher" id="voucherPopup">
        <div class="title" id="voucherTitle">Mã khuyến mãi</div>
        <div class="code item-voucher" style="background:#edf0f3;">
            <div class="label">Mã khuyến mãi:</div>
            <div class="content" id="voucherCode">FREESHIP</div>
        </div>
        <div class="bottom item-voucher">
            <div class="voucher-code item-bottom" onclick="doCopyVoucher()" style="cursor:pointer;">Sao chép</div>
            <div class="closeVoucher item-bottom" onclick="closeVoucher()" style="cursor:pointer;">Đóng</div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════ --}}
    {{-- FLASH SALE --}}
    {{-- ═══════════════════════════════════════ --}}
    @if(($flashSales ?? collect())->isNotEmpty())
    <div class="home-flash-sale" style="background:linear-gradient(135deg,#000f36 0%,#152755 50%,#152755 100%);padding:40px 0;position:relative;overflow:hidden;">
        <div class="container">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
                <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                    <h2 class="item-effect" style="font-size:24px;font-weight:800;color:white;margin:0;">
                        <a href="{{ route('shop.index', ['collection' => 'flash_sale']) }}" style="color:white;text-decoration:none;">Ưu đãi đặc biệt</a>
                    </h2>
                    <span style="background:#fcdb10;color:#000f36;padding:4px 12px;border-radius:4px;font-size:11px;font-weight:800;text-transform:uppercase;">ĐANG DIỄN RA</span>
                    <div style="display:flex;gap:4px;align-items:center;">
                        <span id="flashHours" style="background:#fcdb10;color:#000f36;padding:4px 8px;border-radius:4px;font-size:14px;font-weight:800;min-width:32px;text-align:center;">00</span>
                        <span style="color:white;font-weight:700;font-size:16px;">:</span>
                        <span id="flashMinutes" style="background:#fcdb10;color:#000f36;padding:4px 8px;border-radius:4px;font-size:14px;font-weight:800;min-width:32px;text-align:center;">00</span>
                        <span style="color:white;font-weight:700;font-size:16px;">:</span>
                        <span id="flashSeconds" style="background:#fcdb10;color:#000f36;padding:4px 8px;border-radius:4px;font-size:14px;font-weight:800;min-width:32px;text-align:center;">00</span>
                    </div>
                </div>
                <a href="{{ route('shop.index', ['collection' => 'flash_sale']) }}" style="color:#fcdb10;font-size:14px;font-weight:600;text-decoration:none;">Xem tất cả →</a>
            </div>
            <div class="flash-sale-swiper swiper-container item-effect">
                <div class="swiper-wrapper">
                    @foreach($flashSales->take(8) as $flashSale)
                        @php
                            $product = $flashSale->product;
                            $originalPrice = $product->sale_price ?? $product->price;
                            $flashImage = $product->image_url ?? 'images/product-placeholder.svg';
                            $flashImageSrc = str_starts_with($flashImage, 'http://') || str_starts_with($flashImage, 'https://') ? $flashImage : asset($flashImage);
                        @endphp
                        <div class="swiper-slide flashsale__item">
                            <form class="variants product-action" style="background:#fff;border-radius:12px;overflow:hidden;">
                                <div class="product-thumbnail" style="position:relative;">
                                    <a class="thumb" href="{{ route('products.show', $product->slug) }}" style="display:block;padding-bottom:132%;position:relative;overflow:hidden;border-radius:12px;">
                                        <img src="{{ $flashImageSrc }}" alt="{{ $product->name }}" style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;border-radius:12px;transition:transform 0.5s;" loading="lazy">
                                    </a>
                                    <span class="smart" style="position:absolute;top:10px;left:10px;background:var(--mainColor);font-size:14px;border-radius:12px;color:#fff;padding:2px 10px;text-align:center;font-weight:500;">-{{ $flashSale->discount_percent }}%</span>
                                    <div class="list-action-right" style="transition:all .4s ease;z-index:1;position:absolute;top:10px;right:10px;transform:translateX(60px);">
                                        <a href="javascript:;" title="Yêu thích" style="width:32px;height:32px;background:#fff;margin-bottom:8px;display:flex;align-items:center;justify-content:center;border-radius:100%;box-shadow:0px 0px 5px rgba(0,0,0,0.1);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 25" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M11.9932 5.44636C9.9938 3.10895 6.65975 2.48019 4.15469 4.62056C1.64964 6.76093 1.29697 10.3395 3.2642 12.8709C4.89982 14.9757 9.84977 19.4146 11.4721 20.8514C11.6536 21.0121 11.7444 21.0925 11.8502 21.1241C11.9426 21.1516 12.0437 21.1516 12.1361 21.1241C12.2419 21.0925 12.3327 21.0121 12.5142 20.8514C14.1365 19.4146 19.0865 14.9757 20.7221 12.8709C22.6893 10.3395 22.3797 6.73842 19.8316 4.62056C17.2835 2.5027 13.9925 3.10895 11.9932 5.44636Z" stroke="#231f20" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </a>
                                    </div>
                                </div>
                                <div class="product-info" style="padding:12px;">
                                    <h3 class="product-info__name" style="font-size:13px;font-weight:600;color:#333;line-height:1.4;margin-bottom:6px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                                        <a href="{{ route('products.show', $product->slug) }}" style="color:inherit;text-decoration:none;">{{ $product->name }}</a>
                                    </h3>
                                    <div class="product-info__price" style="font-size:15px;font-weight:700;color:var(--priceColor);">
                                        {{ number_format($flashSale->flash_price, 0, ',', '.') }}₫
                                        <span class="compare-price" style="font-size:12px;font-weight:400;color:#a6a6a6;text-decoration:line-through;">{{ number_format($originalPrice, 0, ',', '.') }}₫</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
    </div>
    <script>
        var flashSwiper = new Swiper('.flash-sale-swiper', {
            slidesPerView: 4, loop: false, grabCursor: true, spaceBetween: 16,
            navigation: { nextEl: '.flash-sale-swiper .swiper-button-next', prevEl: '.flash-sale-swiper .swiper-button-prev' },
            autoplay: false,
            breakpoints: { 300:{slidesPerView:2}, 500:{slidesPerView:2}, 640:{slidesPerView:2}, 768:{slidesPerView:3}, 991:{slidesPerView:2}, 1200:{slidesPerView:4} }
        });
    </script>
    @endif

    {{-- ═══════════════════════════════════════ --}}
    {{-- NEW PRODUCTS --}}
    {{-- ═══════════════════════════════════════ --}}
    <div class="home-product" style="padding:40px 0;">
        <div class="container">
            <div class="block-title" style="text-align:center;margin-bottom:24px;">
                <h2 class="item-effect" style="font-size:24px;font-weight:800;color:#333;display:inline-block;position:relative;margin:0;">
                    <span>Hàng mới lên kệ</span>
                </h2>
            </div>
            <div style="display:flex;gap:24px;align-items:flex-start;">
                {{-- Banner --}}
                <div style="width:42%;flex-shrink:0;" class="item-effect">
                    <a href="{{ route('shop.index', ['collection' => 'new_arrivals']) }}">
                        <div style="width:100%;aspect-ratio:512/542;background:linear-gradient(135deg,#fef3c7,#fde68a,#f59e0b);border-radius:12px;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden;">
                            <div style="text-align:center;position:relative;z-index:2;padding:40px;">
                                <div style="font-size:12px;font-weight:800;color:#92400e;letter-spacing:0.1em;text-transform:uppercase;margin-bottom:8px;">NEW ARRIVALS</div>
                                <div style="font-size:36px;font-weight:900;color:#78350f;line-height:1.1;">Bộ sưu tập<br>mới</div>
                                <div style="margin-top:16px;display:inline-block;padding:8px 20px;background:#78350f;color:white;border-radius:8px;font-size:13px;font-weight:600;">Khám phá ngay →</div>
                            </div>
                        </div>
                    </a>
                </div>
                {{-- Products Grid --}}
                <div style="flex:1;">
                    <div class="new-product-swiper swiper-container item-effect">
                        <div class="swiper-wrapper">
                            @forelse($newProducts ?? [] as $product)
                                @php
                                    $img = $product->image_url ?? 'images/product-placeholder.svg';
                                    $imgSrc = str_starts_with($img, 'http://') || str_starts_with($img, 'https://') ? $img : asset($img);
                                    $hasDiscount = $product->sale_price && $product->sale_price < $product->price;
                                    $discount = $hasDiscount ? round((1 - $product->sale_price / $product->price) * 100) : 0;
                                @endphp
                                <div class="swiper-slide">
                                    <form class="variants product-action" style="background:#fff;border-radius:12px;overflow:hidden;">
                                        <div class="product-thumbnail" style="position:relative;">
                                            <a class="thumb" href="{{ route('products.show', $product->slug) }}" style="display:block;padding-bottom:132%;position:relative;overflow:hidden;border-radius:12px;">
                                                <img src="{{ $imgSrc }}" alt="{{ $product->name }}" style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;border-radius:12px;transition:transform 0.5s;" loading="lazy">
                                            </a>
                                            @if($hasDiscount)
                                                <span class="smart" style="position:absolute;top:10px;left:10px;background:var(--mainColor);font-size:14px;border-radius:12px;color:#fff;padding:2px 10px;font-weight:500;">-{{ $discount }}%</span>
                                            @endif
                                            <div class="list-action-right" style="transition:all .4s ease;z-index:1;position:absolute;top:10px;right:10px;transform:translateX(60px);">
                                                <a href="javascript:;" title="Yêu thích" style="width:32px;height:32px;background:#fff;margin-bottom:8px;display:flex;align-items:center;justify-content:center;border-radius:100%;box-shadow:0px 0px 5px rgba(0,0,0,0.1);">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 25" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M11.9932 5.44636C9.9938 3.10895 6.65975 2.48019 4.15469 4.62056C1.64964 6.76093 1.29697 10.3395 3.2642 12.8709C4.89982 14.9757 9.84977 19.4146 11.4721 20.8514C11.6536 21.0121 11.7444 21.0925 11.8502 21.1241C11.9426 21.1516 12.0437 21.1516 12.1361 21.1241C12.2419 21.0925 12.3327 21.0121 12.5142 20.8514C14.1365 19.4146 19.0865 14.9757 20.7221 12.8709C22.6893 10.3395 22.3797 6.73842 19.8316 4.62056C17.2835 2.5027 13.9925 3.10895 11.9932 5.44636Z" stroke="#231f20" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="product-info" style="padding:12px;">
                                            <h3 class="product-info__name" style="font-size:13px;font-weight:600;color:#333;line-height:1.4;margin-bottom:6px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                                                <a href="{{ route('products.show', $product->slug) }}" style="color:inherit;text-decoration:none;">{{ $product->name }}</a>
                                            </h3>
                                            <div class="product-info__price" style="font-size:15px;font-weight:700;color:var(--priceColor);">
                                                {{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}₫
                                                @if($hasDiscount)
                                                    <span class="compare-price" style="font-size:12px;font-weight:400;color:#a6a6a6;text-decoration:line-through;">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                                @endif
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @empty
                                <div class="swiper-slide" style="text-align:center;padding:40px;color:#999;">Chưa có sản phẩm mới.</div>
                            @endforelse
                        </div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                    <div style="text-align:center;margin-top:20px;" class="item-effect">
                        <a href="{{ route('shop.index', ['collection' => 'new_arrivals', 'sort' => 'newest']) }}" style="display:inline-flex;align-items:center;gap:6px;color:var(--mainColor);font-size:14px;font-weight:600;text-decoration:none;">
                            Xem tất cả →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var newSwiper = new Swiper('.new-product-swiper', {
            slidesPerView: 3, loop: false, grabCursor: true, spaceBetween: 16,
            navigation: { nextEl: '.new-product-swiper .swiper-button-next', prevEl: '.new-product-swiper .swiper-button-prev' },
            autoplay: false,
            breakpoints: { 300:{slidesPerView:2}, 500:{slidesPerView:2}, 768:{slidesPerView:3}, 991:{slidesPerView:2}, 1200:{slidesPerView:3} }
        });
    </script>

    {{-- ═══════════════════════════════════════ --}}
    {{-- BEST SELLERS --}}
    {{-- ═══════════════════════════════════════ --}}
    <div class="home-product" style="padding:40px 0;background:#f8f9fa;">
        <div class="container">
            <div class="block-title" style="text-align:center;margin-bottom:24px;">
                <h2 class="item-effect" style="font-size:24px;font-weight:800;color:#333;display:inline-block;position:relative;margin:0;">
                    <span>Top bán chạy</span>
                </h2>
            </div>
            <div style="display:flex;gap:24px;align-items:flex-start;">
                {{-- Products --}}
                <div style="flex:1;">
                    <div class="best-product-swiper swiper-container item-effect">
                        <div class="swiper-wrapper">
                            @forelse($bestSellers ?? [] as $product)
                                @php
                                    $img = $product->image_url ?? 'images/product-placeholder.svg';
                                    $imgSrc = str_starts_with($img, 'http://') || str_starts_with($img, 'https://') ? $img : asset($img);
                                    $hasDiscount = $product->sale_price && $product->sale_price < $product->price;
                                    $discount = $hasDiscount ? round((1 - $product->sale_price / $product->price) * 100) : 0;
                                @endphp
                                <div class="swiper-slide">
                                    <form class="variants product-action" style="background:#fff;border-radius:12px;overflow:hidden;">
                                        <div class="product-thumbnail" style="position:relative;">
                                            <a class="thumb" href="{{ route('products.show', $product->slug) }}" style="display:block;padding-bottom:132%;position:relative;overflow:hidden;border-radius:12px;">
                                                <img src="{{ $imgSrc }}" alt="{{ $product->name }}" style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;border-radius:12px;transition:transform 0.5s;" loading="lazy">
                                            </a>
                                            @if($hasDiscount)
                                                <span class="smart" style="position:absolute;top:10px;left:10px;background:var(--mainColor);font-size:14px;border-radius:12px;color:#fff;padding:2px 10px;font-weight:500;">-{{ $discount }}%</span>
                                            @endif
                                        </div>
                                        <div class="product-info" style="padding:12px;">
                                            <h3 class="product-info__name" style="font-size:13px;font-weight:600;color:#333;line-height:1.4;margin-bottom:6px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                                                <a href="{{ route('products.show', $product->slug) }}" style="color:inherit;text-decoration:none;">{{ $product->name }}</a>
                                            </h3>
                                            <div class="product-info__price" style="font-size:15px;font-weight:700;color:var(--priceColor);">
                                                {{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}₫
                                                @if($hasDiscount)
                                                    <span class="compare-price" style="font-size:12px;font-weight:400;color:#a6a6a6;text-decoration:line-through;">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                                @endif
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @empty
                                <div class="swiper-slide" style="text-align:center;padding:40px;color:#999;">Chưa có dữ liệu bán chạy.</div>
                            @endforelse
                        </div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                </div>
                {{-- Banner --}}
                <div style="width:42%;flex-shrink:0;" class="item-effect">
                    <a href="{{ route('shop.index', ['sort' => 'popularity']) }}">
                        <div style="width:100%;aspect-ratio:512/542;background:linear-gradient(135deg,#0f172a,#1e293b,#334155);border-radius:12px;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden;">
                            <div style="position:absolute;inset:0;opacity:0.15;background:radial-gradient(circle at 30% 60%,rgba(239,68,68,0.3),transparent 60%);"></div>
                            <div style="text-align:center;position:relative;z-index:2;padding:40px;">
                                <div style="font-size:12px;font-weight:800;color:#fbbf24;letter-spacing:0.1em;text-transform:uppercase;margin-bottom:8px;">BEST SELLERS</div>
                                <div style="font-size:36px;font-weight:900;color:white;line-height:1.1;">Top bán<br>chạy</div>
                                <div style="margin-top:16px;display:inline-block;padding:8px 20px;background:#ff6347;color:white;border-radius:8px;font-size:13px;font-weight:600;">Xem ngay →</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>
        var bestSwiper = new Swiper('.best-product-swiper', {
            slidesPerView: 3, loop: false, grabCursor: true, spaceBetween: 16,
            navigation: { nextEl: '.best-product-swiper .swiper-button-next', prevEl: '.best-product-swiper .swiper-button-prev' },
            autoplay: false,
            breakpoints: { 300:{slidesPerView:2}, 500:{slidesPerView:2}, 768:{slidesPerView:3}, 991:{slidesPerView:2}, 1200:{slidesPerView:3} }
        });
    </script>

    {{-- ═══════════════════════════════════════ --}}
    {{-- PROMO SECTION --}}
    {{-- ═══════════════════════════════════════ --}}
    <div class="home-black-friday" style="padding:40px 0;background:#f8f9fa;">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-4 col-12">
                    <div class="inner item-effect">
                        <a href="{{ route('home') }}" style="display:inline-block;margin-bottom:16px;">
                            <span style="font-size:24px;font-weight:900;color:#333;">ND<span style="color:#ff6347;">Style</span></span>
                        </a>
                        <h2 style="font-size:28px;font-weight:800;color:#333;line-height:1.3;">Khuyến mãi <span style="color:#ff6347;">đặc biệt</span> có gì?</h2>
                    </div>
                </div>
                <div class="col-lg-7 col-md-8 col-12">
                    <div class="list-black-friday item-effect" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <a href="{{ route('shop.index') }}" style="border-radius:12px;overflow:hidden;display:block;">
                            <div style="width:100%;aspect-ratio:349/140;background:linear-gradient(135deg,#dc2626,#ef4444);display:flex;align-items:center;justify-content:center;border-radius:12px;">
                                <div style="text-align:center;color:white;"><div style="font-size:24px;font-weight:900;">SALE TỚI 50%</div><div style="font-size:12px;opacity:0.8;">Sản phẩm phụ kiện</div></div>
                            </div>
                        </a>
                        <a href="{{ route('shop.index') }}" style="border-radius:12px;overflow:hidden;display:block;">
                            <div style="width:100%;aspect-ratio:349/140;background:linear-gradient(135deg,#0f172a,#1e293b);display:flex;align-items:center;justify-content:center;border-radius:12px;">
                                <div style="text-align:center;color:white;"><div style="font-size:24px;font-weight:900;">XẢ HÀNG LỚN</div><div style="font-size:12px;opacity:0.8;">Hơn 200 sản phẩm</div></div>
                            </div>
                        </a>
                        <a href="{{ route('shop.index') }}" style="border-radius:12px;overflow:hidden;display:block;">
                            <div style="width:100%;aspect-ratio:349/140;background:linear-gradient(135deg,#059669,#10b981);display:flex;align-items:center;justify-content:center;border-radius:12px;">
                                <div style="text-align:center;color:white;"><div style="font-size:24px;font-weight:900;">MUA 1 TẶNG 1</div><div style="font-size:12px;opacity:0.8;">Sản phẩm trong BST</div></div>
                            </div>
                        </a>
                        <a href="{{ route('shop.index') }}" style="border-radius:12px;overflow:hidden;display:block;">
                            <div style="width:100%;aspect-ratio:349/140;background:linear-gradient(135deg,#7c3aed,#a855f7);display:flex;align-items:center;justify-content:center;border-radius:12px;">
                                <div style="text-align:center;color:white;"><div style="font-size:24px;font-weight:900;">TẶNG PHỤ KIỆN</div><div style="font-size:12px;opacity:0.8;">Khi mua đơn từ BST</div></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════ --}}
    {{-- BRANDS --}}
    {{-- ═══════════════════════════════════════ --}}
    @if(isset($brands) && $brands->count() > 0)
    <div style="padding:40px 0;border-top:1px solid #eee;">
        <div class="container">
            <div style="display:flex;align-items:center;justify-content:center;gap:32px;flex-wrap:wrap;">
                @foreach($brands as $brand)
                <a href="{{ route('shop.index', ['brand' => $brand->id]) }}" style="opacity:0.4;transition:opacity 0.3s;cursor:pointer;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.4'">
                    @if($brand->logo_url)
                        <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" style="height:40px;width:auto;filter:grayscale(100%);transition:filter 0.3s;" loading="lazy" onmouseover="this.style.filter='grayscale(0%)'" onmouseout="this.style.filter='grayscale(100%)'">
                    @else
                        <span style="font-size:16px;font-weight:700;color:#999;">{{ $brand->name }}</span>
                    @endif
                </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════ --}}
    {{-- NEWSLETTER --}}
    {{-- ═══════════════════════════════════════ --}}
    <div style="padding:40px 0;background:#f8f9fa;">
        <div class="container">
            <div style="background:white;border-radius:16px;border:1px solid #eee;padding:40px;text-align:center;">
                <h2 style="font-size:24px;font-weight:800;color:#333;margin-bottom:8px;">Đăng ký nhận tin</h2>
                <p style="font-size:14px;color:#888;max-width:400px;margin:0 auto 20px;">Nhận thông tin sản phẩm mới, khuyến mãi và ưu đãi độc quyền.</p>
                <form style="display:flex;gap:8px;max-width:400px;margin:0 auto;" onsubmit="return false;">
                    <input type="email" placeholder="Nhập email của bạn..." style="flex:1;height:44px;padding:0 16px;border:2px solid #e5e7eb;border-radius:8px;font-size:14px;outline:none;" onfocus="this.style.borderColor='#ff6347'" onblur="this.style.borderColor='#e5e7eb'">
                    <button type="button" style="background:#ff6347;color:white;border:none;padding:0 24px;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;white-space:nowrap;">Đăng ký</button>
                </form>
                <p style="margin-top:12px;font-size:12px;color:#ccc;">Bảo mật thông tin. Hủy đăng ký bất cứ lúc nào.</p>
            </div>
        </div>
    </div>

</main>

<script>
    // Flash Sale Countdown
    var pad = function(v) { return String(v).padStart(2, '0'); };
    function updateFlashCountdown() {
        var now = new Date();
        var end = new Date(now);
        end.setHours(23, 59, 59, 999);
        var diff = end - now;
        if (diff <= 0) return;
        var h = Math.floor(diff / 3600000);
        var m = Math.floor((diff % 3600000) / 60000);
        var s = Math.floor((diff % 60000) / 1000);
        var elH = document.getElementById('flashHours');
        var elM = document.getElementById('flashMinutes');
        var elS = document.getElementById('flashSeconds');
        if (elH) elH.textContent = pad(h);
        if (elM) elM.textContent = pad(m);
        if (elS) elS.textContent = pad(s);
    }
    updateFlashCountdown();
    setInterval(updateFlashCountdown, 1000);

    // Voucher Copy
    function copyVoucher(code) {
        document.getElementById('voucherCode').textContent = code;
        document.getElementById('voucherPopup').classList.add('active');
        document.getElementById('voucherOverlay').classList.add('active');
    }
    function closeVoucher() {
        document.getElementById('voucherPopup').classList.remove('active');
        document.getElementById('voucherOverlay').classList.remove('active');
    }
    function doCopyVoucher() {
        var code = document.getElementById('voucherCode').textContent;
        navigator.clipboard.writeText(code).then(function() {
            var btn = document.querySelector('.voucher-code');
            btn.textContent = 'Đã lưu!';
            setTimeout(function() { btn.textContent = 'Sao chép'; }, 1500);
        });
    }

    // Topbar Swiper
    new Swiper('.topbar-swiper', {
        slidesPerView: 1, loop: false, spaceBetween: 0,
        navigation: { nextEl: '.topbar-swiper .swiper-button-next', prevEl: '.topbar-swiper .swiper-button-prev' },
        autoplay: false,
        breakpoints: { 300:{slidesPerView:1}, 1200:{slidesPerView:1} }
    });
</script>
@endsection