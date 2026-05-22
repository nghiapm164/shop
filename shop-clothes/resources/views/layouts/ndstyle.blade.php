<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('meta_title', 'ND Style Clone')</title>
    <meta name="description" content="@yield('meta_description', '')">
    
    <!-- Original ND Style CSS -->
    <link rel="stylesheet" href="//bizweb.dktcdn.net/100/534/571/themes/972900/assets/common.scss.css?1749442635129">
    <link href="//bizweb.dktcdn.net/100/534/571/themes/972900/assets/main.scss.css?1749442635129" rel="stylesheet">
    <link href="//bizweb.dktcdn.net/100/534/571/themes/972900/assets/header.scss.css?1749442635129" rel="stylesheet">
    <link href="//bizweb.dktcdn.net/100/534/571/themes/972900/assets/index.scss.css?1749442635129" rel="stylesheet">
    
    <style>
        :root {
            --mainColor: #ff6347;
            --priceColor: #ff6347;
            --middleHeaderColor: #ffffff;
            --middleHeaderTextColor: #000000;
            --topBarColor: #ffc700;
            --topBarTextColor: #000000;
            --oldColor: #ffc700;
            --backgroundColorReview: #152755;
            --oldColorReview: #ffc700;
            --backgroundColorFlashSale: #152755;
            --oldColorFlashSale: #000f36;
            --oldColorFlashSale2: #fcdb10;
            --oldColorFlashSale3: #152755;
            --backgroundColorService: #152755;
            --backgroundColorItemVoucher: #305a9b;
        }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; }
        [x-cloak] { display: none !important; }
        
        /* Custom overrides */
        .nd-logo-text { font-size:24px; font-weight:900; color:#000; text-decoration:none; }
        .nd-logo-text span { color: var(--mainColor); }
        
        .header-search input { height: 42px; border: 2px solid #e5e7eb; border-radius: 8px; padding: 0 48px 0 16px; width: 100%; outline:none; font-size:14px; }
        .header-search input:focus { border-color: var(--mainColor); }
        .header-search .btn-search { position:absolute; right:4px; top:4px; height:34px; width:40px; border:none; background:var(--mainColor); color:#fff; border-radius:6px; cursor:pointer; display:flex; align-items:center; justify-content:center; }
        
        .header-wishlist a, .header-account a, .header-cart a { display:flex; flex-direction:column; align-items:center; gap:2px; font-size:12px; color:#666; text-decoration:none; padding:6px 12px; transition:color 0.2s; }
        .header-wishlist a:hover, .header-account a:hover, .header-cart a:hover { color: var(--mainColor); }
        
        .nav-horizontal ul.item_big_pc { display:flex; justify-content:center; list-style:none; margin:0; padding:0; }
        .nav-horizontal ul.item_big_pc li.nav-item { position:relative; }
        .nav-horizontal ul.item_big_pc li.nav-item a { display:block; padding:12px 20px; font-size:14px; font-weight:600; color:#333; text-decoration:none; text-transform:uppercase; letter-spacing:0.02em; transition:color 0.2s; white-space:nowrap; }
        .nav-horizontal ul.item_big_pc li.nav-item:hover a,
        .nav-horizontal ul.item_big_pc li.nav-item.active a { color: var(--mainColor); }
        .nav-horizontal ul.item_big_pc li.nav-item:after { content:''; position:absolute; bottom:0; left:50%; transform:translateX(-50%); width:0; height:2px; background:var(--mainColor); transition:width 0.3s; }
        .nav-horizontal ul.item_big_pc li.nav-item:hover:after,
        .nav-horizontal ul.item_big_pc li.nav-item.active:after { width:100%; }

        /* ND Footer */
        .nd-footer { background:#1a1a2e; color:#ccc; padding-top:48px; font-family:'Inter',sans-serif; }
        .nd-footer__grid { display:grid; grid-template-columns:2fr 1fr 1fr 2fr; gap:32px; }
        .nd-footer__title { font-size:16px; font-weight:700; color:#fff; margin-bottom:16px; text-transform:uppercase; }
        .nd-footer__text { font-size:13px; line-height:1.8; color:#999; }
        .nd-footer__links { list-style:none; padding:0; margin:0; }
        .nd-footer__links li { margin-bottom:8px; }
        .nd-footer__links a { font-size:13px; color:#999; text-decoration:none; transition:color 0.2s; }
        .nd-footer__links a:hover { color:#fff; }
        .nd-footer__social { display:flex; gap:10px; margin-top:16px; }
        .nd-footer__social a { width:36px; height:36px; border-radius:50%; background:rgba(255,255,255,0.1); display:flex; align-items:center; justify-content:center; transition:all 0.2s; color:#ccc; text-decoration:none; }
        .nd-footer__social a:hover { background:var(--mainColor); color:#fff; }
        .nd-footer__bottom { border-top:1px solid rgba(255,255,255,0.1); margin-top:32px; padding:16px 0; text-align:center; font-size:12px; color:#666; }
        
        @media (max-width: 1024px) {
            .nd-footer__grid { grid-template-columns:1fr 1fr; }
        }
        @media (max-width: 768px) {
            .nd-footer__grid { grid-template-columns:1fr; }
        }
    </style>
    
    @livewireStyles
    @yield('styles')
</head>
<body>
    @yield('content')
    
    <!-- Footer -->
    <x-footer-ndstyle />
    
    @livewireScripts
    @stack('scripts')
</body>
</html>