@extends('layouts.shop')

@section('title', 'Shop Nam - Quần áo thể thao nam | Yody Style')

@section('content')
<style>
    :root {
        --primary-color: #0ea5e9;
        --secondary-color: #0f172a;
        --light-bg: #f0f9ff;
        --text-muted: #64748b;
    }
    
    /* Hero Banner */
    .hero-banner {
        background: linear-gradient(135deg, #0ea5e9 0%, #06a5d9 100%);
        color: white;
        padding: 6rem 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        min-height: 500px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .hero-banner::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 1200 500%22><defs><linearGradient id=%22grad1%22 x1=%220%25%22 y1=%220%25%22 x2=%22100%25%22 y2=%22100%25%22><stop offset=%220%25%22 style=%22stop-color:rgba(255,255,255,0.1)%22 /><stop offset=%22100%25%22 style=%22stop-color:rgba(255,255,255,0.05)%22 /></linearGradient></defs><rect width=%221200%22 height=%22500%22 fill=%22url(%23grad1)%22 /></svg>');
        opacity: 0.5;
    }
    
    .hero-content {
        position: relative;
        z-index: 10;
        max-width: 800px;
    }
    
    .hero-banner h1 {
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-weight: 900;
        margin-bottom: 1rem;
        line-height: 1.1;
    }
    
    .hero-banner p {
        font-size: 1.3rem;
        margin-bottom: 2rem;
        opacity: 0.95;
    }
    
    .hero-dots {
        display: flex;
        gap: 0.75rem;
        justify-content: center;
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
    }
    
    .hero-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .hero-dot.active {
        background: white;
        width: 14px;
        height: 14px;
    }
    
    /* Category Cards */
    .category-card {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        padding: 2rem 1.5rem;
        text-align: center;
        border-radius: 12px;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .category-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-8px);
        box-shadow: 0 8px 20px rgba(14, 165, 233, 0.15);
    }
    
    .category-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }
    
    .category-card h5 {
        font-weight: 700;
        color: var(--secondary-color);
        margin-bottom: 0.5rem;
    }
    
    .category-card p {
        color: var(--text-muted);
        margin: 0;
        font-size: 0.95rem;
    }
    
    /* Product Card */
    .product-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        background: white;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .product-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        border-color: var(--primary-color);
    }
    
    .product-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        overflow: hidden;
    }
    
    .product-body {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .product-name {
        font-weight: 700;
        color: var(--secondary-color);
        margin-bottom: 0.5rem;
    }
    
    .product-desc {
        color: var(--text-muted);
        font-size: 0.95rem;
        margin-bottom: auto;
        flex-grow: 1;
    }
    
    .product-price-container {
        margin-top: 1rem;
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }
    
    .product-original-price {
        color: #94a3b8;
        text-decoration: line-through;
        font-size: 0.95rem;
    }
    
    .product-price {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--primary-color);
    }
    
    .discount-badge {
        background: #fef3c7;
        color: #92400e;
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 700;
    }
    
    /* Section Titles */
    .section-title {
        font-size: clamp(1.5rem, 4vw, 2rem);
        font-weight: 800;
        color: var(--secondary-color);
        margin-bottom: 2rem;
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    /* Blog/Article Cards */
    .blog-card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        background: white;
        cursor: pointer;
    }
    
    .blog-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
    }
    
    .blog-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
    }
    
    .blog-body {
        padding: 1.5rem;
    }
    
    .blog-category {
        color: var(--primary-color);
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
        letter-spacing: 0.5px;
    }
    
    .blog-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--secondary-color);
        margin-bottom: 1rem;
    }
    
    .blog-excerpt {
        color: var(--text-muted);
        line-height: 1.6;
        margin: 0;
    }
    
    /* CTA Section */
    .cta-section {
        background: linear-gradient(135deg, #0ea5e9 0%, #06a5d9 100%);
        color: white;
        padding: 4rem 2rem;
        text-align: center;
        border-radius: 16px;
        margin: 4rem 0;
    }
    
    .cta-section h2 {
        font-size: clamp(1.5rem, 4vw, 2.2rem);
        font-weight: 800;
        margin-bottom: 1rem;
    }
    
    .cta-section p {
        font-size: 1.1rem;
        margin-bottom: 2rem;
        opacity: 0.95;
    }
    
    .cta-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .btn-cta {
        padding: 0.8rem 2rem;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        border: none;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-cta-primary {
        background: white;
        color: var(--primary-color);
    }
    
    .btn-cta-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }
    
    .btn-cta-secondary {
        background: transparent;
        color: white;
        border: 2px solid white;
    }
    
    .btn-cta-secondary:hover {
        background: rgba(255, 255, 255, 0.1);
    }
    
    /* Trust Section */
    .trust-section {
        background: #f8fafc;
        padding: 3rem 0;
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 3rem;
    }
    
    .trust-item {
        text-align: center;
        padding: 2rem 1rem;
    }
    
    .trust-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }
    
    .trust-title {
        font-weight: 700;
        color: var(--secondary-color);
        margin-bottom: 0.5rem;
    }
    
    .trust-desc {
        color: var(--text-muted);
        font-size: 0.95rem;
        margin: 0;
    }
</style>

<!-- Hero Banner -->
<div class="hero-banner">
    <div class="hero-content">
        <h1>⚽ SHOP NAM YODY 💪</h1>
        <p>Quần áo thể thao nam - Năng động, thoải mái, tự tin</p>
        <a href="#featured" class="btn btn-light btn-lg" style="font-weight: 700;">Khám phá ngay</a>
    </div>
    <div class="hero-dots">
        <span class="hero-dot active"></span>
        <span class="hero-dot"></span>
        <span class="hero-dot"></span>
    </div>
</div>

<!-- Trust Section -->
<div class="trust-section">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="trust-item">
                    <div class="trust-icon">✓</div>
                    <h5 class="trust-title">Chất lượng Đảm bảo</h5>
                    <p class="trust-desc">100% sản phẩm chính hãng, kiểm chất lượng kỹ lưỡng</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="trust-item">
                    <div class="trust-icon">🚚</div>
                    <h5 class="trust-title">Giao hàng nhanh</h5>
                    <p class="trust-desc">Miễn phí vận chuyển, giao hàng 2-3 ngày</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="trust-item">
                    <div class="trust-icon">🔄</div>
                    <h5 class="trust-title">Đổi trả dễ dàng</h5>
                    <p class="trust-desc">30 ngày đổi trả miễn phí, không cần lý do</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Categories Section -->
<div class="container mb-5">
    <div class="section-header">
        <h2 class="section-title">🔥 Danh mục nổi bật</h2>
    </div>
    <div class="row g-4">
        <div class="col-sm-6 col-md-3">
            <div class="category-card">
                <div class="category-icon">⚽</div>
                <h5>Áo thun</h5>
                <p>Chất liệu thấm hút mồ hôi</p>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="category-card">
                <div class="category-icon">👖</div>
                <h5>Quần short</h5>
                <p>Form gọn gàng nhẹ nhàng</p>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="category-card">
                <div class="category-icon">🏃</div>
                <h5>Quần dài</h5>
                <p>Thoáng mát & co giãn tốt</p>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="category-card">
                <div class="category-icon">🎒</div>
                <h5>Phụ kiện</h5>
                <p>Hoàn thiện phong cách</p>
            </div>
        </div>
    </div>
</div>

<!-- Featured Products Section -->
<div class="container mb-5" id="featured">
    <div class="section-header">
        <h2 class="section-title">🔥 Bán chạy nhất</h2>
        <span class="discount-badge">Giảm 25%</span>
    </div>
    <div class="row g-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card product-card">
                <div class="product-image" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);">👕</div>
                <div class="product-body">
                    <h5 class="product-name">Áo thun thể thao cao cấp</h5>
                    <p class="product-desc">Chất liệu Polyester cao cấp, thoáng mát</p>
                    <div class="product-price-container">
                        <span class="product-original-price">499.000</span>
                        <span class="product-price">399.000 ₫</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card product-card">
                <div class="product-image" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">👖</div>
                <div class="product-body">
                    <h5 class="product-name">Quần short năng động</h5>
                    <p class="product-desc">Form gọn gàng, 2 túi hông</p>
                    <div class="product-price-container">
                        <span class="product-original-price">600.000</span>
                        <span class="product-price">450.000 ₫</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card product-card">
                <div class="product-image" style="background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);">🧥</div>
                <div class="product-body">
                    <h5 class="product-name">Hoodie thể thao</h5>
                    <p class="product-desc">Ấm áp & thoải mái</p>
                    <div class="product-price-container">
                        <span class="product-original-price">850.000</span>
                        <span class="product-price">650.000 ₫</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card product-card">
                <div class="product-image" style="background: linear-gradient(135deg, #ec4899 0%, #be185d 100%);">👟</div>
                <div class="product-body">
                    <h5 class="product-name">Giày chạy bộ</h5>
                    <p class="product-desc">Hỗ trợ chân tốt</p>
                    <div class="product-price-container">
                        <span class="product-original-price">1.200.000</span>
                        <span class="product-price">850.000 ₫</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card product-card">
                <div class="product-image" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%);">🧤</div>
                <div class="product-body">
                    <h5 class="product-name">Áo khoác thể thao</h5>
                    <p class="product-desc">Chống nước nhẹ</p>
                    <div class="product-price-container">
                        <span class="product-original-price">699.000</span>
                        <span class="product-price">549.000 ₫</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card product-card">
                <div class="product-image" style="background: linear-gradient(135deg, #f97316 0%, #c2410c 100%);">🎒</div>
                <div class="product-body">
                    <h5 class="product-name">Túi đeo chéo nam</h5>
                    <p class="product-desc">Chất liệu bền bỉ</p>
                    <div class="product-price-container">
                        <span class="product-original-price">299.000</span>
                        <span class="product-price">199.000 ₫</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card product-card">
                <div class="product-image" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">👔</div>
                <div class="product-body">
                    <h5 class="product-name">Quần tây thể thao</h5>
                    <p class="product-desc">Co giãn tốt</p>
                    <div class="product-price-container">
                        <span class="product-original-price">899.000</span>
                        <span class="product-price">699.000 ₫</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card product-card">
                <div class="product-image" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">🧢</div>
                <div class="product-body">
                    <h5 class="product-name">Nón thể thao</h5>
                    <p class="product-desc">Chống UV hiệu quả</p>
                    <div class="product-price-container">
                        <span class="product-original-price">199.000</span>
                        <span class="product-price">149.000 ₫</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Blog Section -->
<div class="container mb-5">
    <div class="section-header">
        <h2 class="section-title">📰 Blog & Mẹo</h2>
    </div>
    <div class="row g-4">
        <div class="col-md-4">
            <article class="blog-card">
                <div class="blog-image" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); display: flex; align-items: center; justify-content: center; font-size: 4rem;">👕</div>
                <div class="blog-body">
                    <div class="blog-category">Cộng Đông Yody</div>
                    <h3 class="blog-title">Tuyệt chiêu lựa chọn áo thể thao phù hợp</h3>
                    <p class="blog-excerpt">Khám phá cách chọn áo thể thao đúng size, form và chất liệu phù hợp với hoạt động của bạn...</p>
                </div>
            </article>
        </div>
        <div class="col-md-4">
            <article class="blog-card">
                <div class="blog-image" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); display: flex; align-items: center; justify-content: center; font-size: 4rem;">👖</div>
                <div class="blog-body">
                    <div class="blog-category">Mẹo thời trang</div>
                    <h3 class="blog-title">Cách phối đồ thể thao trendy như sao</h3>
                    <p class="blog-excerpt">Mix & match các item thể thao thành outfit đẹp, phù hợp mọi hoàn cảnh từ tập gym đến dạo phố...</p>
                </div>
            </article>
        </div>
        <div class="col-md-4">
            <article class="blog-card">
                <div class="blog-image" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%); display: flex; align-items: center; justify-content: center; font-size: 4rem;">💪</div>
                <div class="blog-body">
                    <div class="blog-category">Sức khỏe</div>
                    <h3 class="blog-title">Chọn trang phục phù hợp cho các môn thể thao</h3>
                    <p class="blog-excerpt">Mỗi môn thể thao cần trang phục khác nhau, tìm hiểu để chọn đúng cho hoạt động của bạn...</p>
                </div>
            </article>
        </div>
    </div>
</div>

<!-- Call To Action Section -->
<div class="container mb-5">
    <div class="cta-section">
        <h2>Tham gia cộng đồng Shop Nam ngay!</h2>
        <p>Nhận ưu đãi độc quyền, sản phẩm mới, và khuyến mãi hằng tháng</p>
        <div class="cta-buttons">
            <a href="{{ route('client.register') }}" class="btn-cta btn-cta-primary">Đăng ký miễn phí</a>
            <a href="{{ route('client.login') }}" class="btn-cta btn-cta-secondary">Khách hàng cũ</a>
        </div>
    </div>
</div>

@endsection
