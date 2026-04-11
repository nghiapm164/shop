<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Shop Nam')</title>
    @vite('resources/assets/css/app.css')
    <style>
        :root {
            --primary-color: #0ea5e9;
            --secondary-color: #0f172a;
            --light-bg: #f0f9ff;
            --border-color: #e2e8f0;
            --text-muted: #64748b;
        }
        
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #ffffff;
            color: var(--secondary-color);
        }
        
        main {
            flex: 1;
        }
        
        .header-top {
            background-color: var(--light-bg);
            color: #0369a1;
            text-align: center;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: #ffffff !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }
        
        .navbar-brand {
            font-weight: 800;
            color: var(--primary-color) !important;
            font-size: 1.5rem;
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }
        
        .brand-subtitle {
            font-size: 0.7rem;
            color: var(--text-muted);
            font-weight: 500;
        }
        
        .navbar-search {
            flex: 1;
            min-width: 250px;
            margin: 0 1rem;
        }
        
        .navbar-search input {
            width: 100%;
            padding: 0.6rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.95rem;
        }
        
        .navbar-search input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        }
        
        .nav-link {
            margin-left: 1rem !important;
        }
        
        .btn-nav-primary {
            background-color: var(--primary-color) !important;
            color: white !important;
            padding: 0.65rem 1.2rem !important;
            border-radius: 8px;
            font-weight: 700;
            transition: all 0.2s;
        }
        
        .btn-nav-primary:hover {
            background-color: #0086b3 !important;
            transform: translateY(-2px);
        }
        
        .btn-nav-secondary {
            color: var(--primary-color) !important;
            border: 2px solid var(--primary-color) !important;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            font-weight: 700;
            background-color: transparent !important;
            transition: all 0.2s;
        }
        
        .btn-nav-secondary:hover {
            background-color: var(--light-bg) !important;
        }
        
        .footer {
            background-color: var(--secondary-color);
            color: white;
            margin-top: 3rem;
            padding: 2.5rem 1rem;
        }
        
        .footer-section h5 {
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        
        .footer-section ul {
            list-style: none;
            padding: 0;
        }
        
        .footer-section a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .footer-section a:hover {
            color: white;
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 2rem;
            padding-top: 2rem;
            text-align: center;
            color: #cbd5e1;
        }
    </style>
</head>
<body>
    <!-- Promo Header -->
    <div class="header-top">
        🏃 Mua sắm để khỏe mạnh - Giảm giá lên đến 40% cho tất cả sản phẩm mới
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="/">
                SHOP NAM
                <span class="brand-subtitle">YODY STYLE</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContents">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarContents">
                <div class="navbar-search ms-auto me-auto">
                    <input type="text" placeholder="Tìm kiếm sản phẩm..." class="form-control">
                </div>
                
                <div class="navbar-nav ms-auto gap-2">
                    <a href="{{ route('client.login') }}" class="btn btn-nav-primary nav-link">Đăng nhập</a>
                    <a href="{{ route('client.register') }}" class="btn btn-nav-secondary nav-link">Đăng ký</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container my-5">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6 footer-section">
                    <h5>Về Shop Nam</h5>
                    <ul>
                        <li><a href="#">Giới thiệu</a></li>
                        <li><a href="#">Chính sách bảo mật</a></li>
                        <li><a href="#">Điều khoản sử dụng</a></li>
                        <li><a href="#">Liên hệ</a></li>
                    </ul>
                </div>
                <div class="col-md-3 col-sm-6 footer-section">
                    <h5>Hỗ trợ khách hàng</h5>
                    <ul>
                        <li><a href="#">Hướng dẫn mua hàng</a></li>
                        <li><a href="#">Hướng dẫn thanh toán</a></li>
                        <li><a href="#">Chính sách vận chuyển</a></li>
                        <li><a href="#">Chính sách đổi trả</a></li>
                    </ul>
                </div>
                <div class="col-md-3 col-sm-6 footer-section">
                    <h5>Kết nối với chúng tôi</h5>
                    <ul>
                        <li><a href="#">Facebook</a></li>
                        <li><a href="#">Instagram</a></li>
                        <li><a href="#">TikTok</a></li>
                        <li><a href="#">YouTube</a></li>
                    </ul>
                </div>
                <div class="col-md-3 col-sm-6 footer-section">
                    <h5>Liên hệ</h5>
                    <ul>
                        <li><a href="tel:0123456789">Hotline: 0123 456 789</a></li>
                        <li><a href="mailto:support@shopnam.com">Email: support@shopnam.com</a></li>
                        <li>Địa chỉ: Hà Nội, Việt Nam</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                © {{ date('Y') }} Shop Nam. Thương mại điện tử quần áo thể thao nam. Tất cả các quyền được bảo lưu.
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @vite('resources/assets/js/app.js')
</body>
</html>
