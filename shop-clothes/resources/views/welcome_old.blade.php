@extends('layouts.shop')

@section('title', 'Shop Nam - Quần áo thể thao nam | Yody Style')

@section('content')
    <!-- Hero Carousel Banner -->
    <div class="carousel" style="background: linear-gradient(135deg, #0ea5e9 0%, #06a5d9 100%); height: 500px; display: flex; align-items: center; justify-content: center; color: white; text-align: center; position: relative; overflow: hidden;">
        <div style="position: absolute; inset: 0; background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 1200 500%22><defs><linearGradient id=%22grad1%22 x1=%220%25%22 y1=%220%25%22 x2=%22100%25%22 y2=%22100%25%22><stop offset=%220%25%22 style=%22stop-color:rgba(255,255,255,0.1)%22 /><stop offset=%22100%25%22 style=%22stop-color:rgba(255,255,255,0.05)%22 /></linearGradient></defs><rect width=%221200%22 height=%22500%22 fill=%22url(%23grad1)%22 /></svg>'); opacity: 0.5;"></div>
        <div style="position: relative; z-index: 10; max-width: 800px; padding: 0 2rem;">
            <h1 style="font-size: 3.5rem; font-weight: 900; margin: 0 0 1rem; line-height: 1.1;">⚽ SHOP NAM YODY 💪</h1>
            <p style="font-size: 1.3rem; margin: 0 0 2rem; opacity: 0.95;">Quần áo thể thao nam - Năng động, thoải mái, tự tin</p>
            <a href="#featured" style="display: inline-block; padding: 1rem 2rem; background: white; color: #0ea5e9; font-weight: 800; border-radius: 8px; cursor: pointer; font-size: 1.1rem; transition: all 0.3s;">Khám phá ngay</a>
        </div>
        <div class="carousel-controls" style="bottom: 30px; left: 50%; transform: translateX(-50%);">
            <span class="carousel-dot active" style="background: white; width: 14px; height: 14px;"></span>
            <span class="carousel-dot" style="background: rgba(255,255,255,0.5); width: 12px; height: 12px;"></span>
            <span class="carousel-dot" style="background: rgba(255,255,255,0.5); width: 12px; height: 12px;"></span>
        </div>
    </div>

    <!-- Categories Section -->
    <section class="container categories-section">
        <h2 class="section-title">🔥 Danh mục nổi bật</h2>
        <div class="categories-grid">
            <div class="category-card">
                <div style="font-size: 2.2rem; margin-bottom: 0.5rem;">⚽</div>
                <h3>Áo thun</h3>
                <p>Chất liệu thấm hút mồ hôi</p>
            </div>
            <div class="category-card">
                <div style="font-size: 2.2rem; margin-bottom: 0.5rem;">👖</div>
                <h3>Quần short</h3>
                <p>Form gọn gàng nhẹ nhàng</p>
            </div>
            <div class="category-card">
                <div style="font-size: 2.2rem; margin-bottom: 0.5rem;">🏃</div>
                <h3>Quần dài</h3>
                <p>Thoáng mát & co giãn tốt</p>
            </div>
            <div class="category-card">
                <div style="font-size: 2.2rem; margin-bottom: 0.5rem;">🎒</div>
                <h3>Phụ kiện</h3>
                <p>Hoàn thiện phong cách</p>
            </div>
        </div>
    </section>

    <!-- Bán chạy nhất -->
    <section class="container section" id="featured">
        <div class="section-header">
            <h2 class="section-title">🔥 Bán chạy nhất</h2>
            <span style="background: #fef3c7; color: #92400e; padding: 0.6rem 1rem; border-radius: 9999px; font-weight: 700; font-size: 0.9rem;">Giảm 25%</span>
        </div>
        <div class="product-grid">
            <article class="product-card">
                <div class="product-image" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">👕</div>
                <div class="product-body">
                    <h3 class="product-name">Áo thun thể thao cao cấp</h3>
                    <p class="product-desc">Chất liệu Polyester cao cấp, thoáng mát</p>
                    <div class="product-price">
                        <span class="product-original-price">499.000</span>
                        399.000 ₫
                    </div>
                </div>
            </article>

            <article class="product-card">
                <div class="product-image" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">👖</div>
                <div class="product-body">
                    <h3 class="product-name">Quần short năng động</h3>
                    <p class="product-desc">Form gọn gàng, 2 túi hông</p>
                    <div class="product-price">
                        <span class="product-original-price">600.000</span>
                        450.000 ₫
                    </div>
                </div>
            </article>

            <article class="product-card">
                <div class="product-image" style="background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">🧥</div>
                <div class="product-body">
                    <h3 class="product-name">Hoodie thể thao</h3>
                    <p class="product-desc">Ấm áp & thoải mái</p>
                    <div class="product-price">
                        <span class="product-original-price">850.000</span>
                        650.000 ₫
                    </div>
                </div>
            </article>

            <article class="product-card">
                <div class="product-image" style="background: linear-gradient(135deg, #ec4899 0%, #be185d 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">👟</div>
                <div class="product-body">
                    <h3 class="product-name">Giày chạy bộ</h3>
                    <p class="product-desc">Hỗ trợ chân tốt</p>
                    <div class="product-price">
                        <span class="product-original-price">1.200.000</span>
                        850.000 ₫
                    </div>
                </div>
            </article>

            <article class="product-card">
                <div class="product-image" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">🧤</div>
                <div class="product-body">
                    <h3 class="product-name">Áo khoác thể thao</h3>
                    <p class="product-desc">Chống nước nhẹ</p>
                    <div class="product-price">
                        <span class="product-original-price">699.000</span>
                        549.000 ₫
                    </div>
                </div>
            </article>

            <article class="product-card">
                <div class="product-image" style="background: linear-gradient(135deg, #f97316 0%, #c2410c 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">🎒</div>
                <div class="product-body">
                    <h3 class="product-name">Túi đeo chéo nam</h3>
                    <p class="product-desc">Chất liệu bền bỉ</p>
                    <div class="product-price">
                        <span class="product-original-price">299.000</span>
                        199.000 ₫
                    </div>
                </div>
            </article>

            <article class="product-card">
                <div class="product-image" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">👔</div>
                <div class="product-body">
                    <h3 class="product-name">Quần tây thể thao</h3>
                    <p class="product-desc">Co giãn tốt</p>
                    <div class="product-price">
                        <span class="product-original-price">899.000</span>
                        699.000 ₫
                    </div>
                </div>
            </article>

            <article class="product-card">
                <div class="product-image" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">🧢</div>
                <div class="product-body">
                    <h3 class="product-name">Nón thể thao</h3>
                    <p class="product-desc">Chống UV hiệu quả</p>
                    <div class="product-price">
                        <span class="product-original-price">199.000</span>
                        149.000 ₫
                    </div>
                </div>
            </article>
        </div>
    </section>

    <!-- Promotional Banner 1 -->
    <section class="container section" style="margin-top: 3rem;">
        <div class="promo-banner" style="background-image: url('https://images.unsplash.com/photo-1579546928782-a9b3a4f7d0e2?auto=format&fit=crop&w=1200&q=80');">
            <div class="promo-banner-content">
                <h2>ÁO CHỐNG HẠNG YODY</h2>
                <p>Công nghệ chống nắng cao cấp, thoáng mát 100%</p>
                <button class="btn-primary">Khám phá ngay</button>
            </div>
        </div>
    </section>

    <!-- Đã dùng phổ biến -->
    <section class="container section">
        <div class="section-header">
            <h2 class="section-title">👕 Áo chống hạng Yody</h2>
        </div>
        <div class="product-grid">
            <article class="product-card">
                <img class="product-image" src="https://images.unsplash.com/photo-1618701969855-49a4c5b65a39?auto=format&fit=crop&w=400&h=400&q=80" alt="Áo chống hạng xanh">
                <div class="product-body">
                    <h3 class="product-name">Áo chống hạng xanh dương</h3>
                    <p class="product-desc">UPF 50+ bảo vệ tối đa</p>
                    <div class="product-price">599.000 ₫</div>
                </div>
            </article>

            <article class="product-card">
                <img class="product-image" src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=400&h=400&q=80" alt="Áo chống hạng đỏ">
                <div class="product-body">
                    <h3 class="product-name">Áo chống hạng đỏ tươi</h3>
                    <p class="product-desc">Thoáng mát kiêu hãng</p>
                    <div class="product-price">599.000 ₫</div>
                </div>
            </article>

            <article class="product-card">
                <img class="product-image" src="https://images.unsplash.com/photo-1556228578-8c89e6adf883?auto=format&fit=crop&w=400&h=400&q=80" alt="Áo chống hạng đen">
                <div class="product-body">
                    <h3 class="product-name">Áo chống hạng đen cổ điển</h3>
                    <p class="product-desc">Dễ phối đồ</p>
                    <div class="product-price">599.000 ₫</div>
                </div>
            </article>

            <article class="product-card">
                <img class="product-image" src="https://images.unsplash.com/photo-1539533057440-7cc17b63183f?auto=format&fit=crop&w=400&h=400&q=80" alt="Áo chống hạng trắng">
                <div class="product-body">
                    <h3 class="product-name">Áo chống hạng trắng sạch</h3>
                    <p class="product-desc">Tinh khôi & tươi mát</p>
                    <div class="product-price">599.000 ₫</div>
                </div>
            </article>

            <article class="product-card">
                <img class="product-image" src="https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=400&h=400&q=80" alt="Áo chống hạng tím">
                <div class="product-body">
                    <h3 class="product-name">Áo chống hạng tím nhạt</h3>
                    <p class="product-desc">Cool & năng động</p>
                    <div class="product-price">599.000 ₫</div>
                </div>
            </article>

            <article class="product-card">
                <img class="product-image" src="https://images.unsplash.com/photo-1598194286519-162b3d63ee89?auto=format&fit=crop&w=400&h=400&q=80" alt="Áo chống hạng xám">
                <div class="product-body">
                    <h3 class="product-name">Áo chống hạng xám lạnh</h3>
                    <p class="product-desc">Nhẹ & thoáng khí</p>
                    <div class="product-price">599.000 ₫</div>
                </div>
            </article>
        </div>
    </section>

    <!-- Promotional Banner 2 -->
    <section class="container section" style="margin-top: 3rem;">
        <div class="promo-banner" style="background-image: url('https://images.unsplash.com/photo-1506126613408-eca07ce68773?auto=format&fit=crop&w=1200&q=80'); background-color: #3b82f6;">
            <div class="promo-banner-content">
                <h2>BỘ THỂ THAO ĐẦU HÈ 2026</h2>
                <p>Tìm ngay combo hot nhất mùa hè này</p>
                <button class="btn-primary">Mua combo</button>
            </div>
        </div>
    </section>

    <!-- Bộ thể thao đầu hè -->
    <section class="container section">
        <div class="section-header">
            <h2 class="section-title">☀️ Bộ thể thao đầu hè</h2>
        </div>
        <div class="product-grid">
            <article class="product-card">
                <img class="product-image" src="https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=400&h=400&q=80" alt="Combo áo quần">
                <div class="product-body">
                    <h3 class="product-name">Combo áo quần hè 1</h3>
                    <p class="product-desc">Áo + quần short</p>
                    <div class="product-price">699.000 ₫</div>
                </div>
            </article>

            <article class="product-card">
                <img class="product-image" src="https://images.unsplash.com/photo-1503392522023-2d62b06fc7d0?auto=format&fit=crop&w=400&h=400&q=80" alt="Combo áo quần">
                <div class="product-body">
                    <h3 class="product-name">Combo áo quần hè 2</h3>
                    <p class="product-desc">Áo dài tay + quần</p>
                    <div class="product-price">749.000 ₫</div>
                </div>
            </article>

            <article class="product-card">
                <img class="product-image" src="https://images.unsplash.com/photo-1618701969855-49a4c5b65a39?auto=format&fit=crop&w=400&h=400&q=80" alt="Combo áo phụ kiện">
                <div class="product-body">
                    <h3 class="product-name">Combo áo phụ kiện</h3>
                    <p class="product-desc">Áo + nón + túi</p>
                    <div class="product-price">799.000 ₫</div>
                </div>
            </article>

            <article class="product-card">
                <img class="product-image" src="https://images.unsplash.com/photo-1556821552-23dfe28b9a51?auto=format&fit=crop&w=400&h=400&q=80" alt="Combo 5 áo">
                <div class="product-body">
                    <h3 class="product-name">Combo 5 áo thun</h3>
                    <p class="product-desc">5 màu khác nhau</p>
                    <div class="product-price">1.799.000 ₫</div>
                </div>
            </article>

            <article class="product-card">
                <img class="product-image" src="https://images.unsplash.com/photo-1614008375890-cb53b6c5f8d5?auto=format&fit=crop&w=400&h=400&q=80" alt="Combo hè hoàn chỉnh">
                <div class="product-body">
                    <h3 class="product-name">Combo hè hoàn chỉnh</h3>
                    <p class="product-desc">Đầy đủ từ đầu tới chân</p>
                    <div class="product-price">2.499.000 ₫</div>
                </div>
            </article>

            <article class="product-card">
                <img class="product-image" src="https://images.unsplash.com/photo-1618701969855-49a4c5b65a39?auto=format&fit=crop&w=400&h=400&q=80" alt="Combo du lịch">
                <div class="product-body">
                    <h3 class="product-name">Combo du lịch mùa hè</h3>
                    <p class="product-desc">Gọn gàng dễ xách</p>
                    <div class="product-price">1.999.000 ₫</div>
                </div>
            </article>
        </div>
    </section>

    <!-- Call To Action -->
    <section class="container cta-section">
        <h2>Tham gia cộng đồng Shop Nam ngay!</h2>
        <p>Nhận ưu đãi độc quyền, sản phẩm mới, và khuyến mãi hằng tháng</p>
        <div class="cta-actions">
            <a href="{{ route('client.register') }}" class="btn-primary">Đăng ký miễn phí</a>
            <a href="{{ route('client.login') }}" class="btn-secondary">Khách hàng cũ</a>
        </div>
    </section>

    <!-- Blog Section -->
    <section class="container section" style="border-top: 1px solid rgba(15, 23, 42, 0.08); padding-top: 3rem;">
        <h2 class="section-title">📰 Blog Yody</h2>
        <div style="display: grid; gap: 2rem; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
            <article style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.08); transition: all 0.3s ease; cursor: pointer;">
                <img src="https://images.unsplash.com/photo-1517836357463-d25ddfcbf042?auto=format&fit=crop&w=500&h=300&q=80" style="width: 100%; height: 250px; object-fit: cover;">
                <div style="padding: 1.5rem;">
                    <h3 style="margin: 0 0 0.5rem; color: #0ea5e9;">Cộng Đông Yody</h3>
                    <h2 style="margin: 0 0 1rem; font-size: 1.3rem; font-weight: 700;">Tuyệt chiêu lựa chọn áo thể thao phù hợp</h2>
                    <p style="margin: 0; color: #64748b; line-height: 1.6;">Khám phá cách chọn áo thể thao đúng size, form và chất liệu phù hợp với hoạt động của bạn...</p>
                </div>
            </article>

            <article style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.08); transition: all 0.3s ease; cursor: pointer;">
                <img src="https://images.unsplash.com/photo-1506433773649-c0b4d3538e15?auto=format&fit=crop&w=500&h=300&q=80" style="width: 100%; height: 250px; object-fit: cover;">
                <div style="padding: 1.5rem;">
                    <h3 style="margin: 0 0 0.5rem; color: #0ea5e9;">Mẹo thời trang</h3>
                    <h2 style="margin: 0 0 1rem; font-size: 1.3rem; font-weight: 700;">Cách phối đồ thể thao trendy như sao</h2>
                    <p style="margin: 0; color: #64748b; line-height: 1.6;">Mix & match các item thể thao thành outfit đẹp, phù hợp mọi hoàn cảnh từ tập gym đến dạo phố...</p>
                </div>
            </article>

            <article style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.08); transition: all 0.3s ease; cursor: pointer;">
                <img src="https://images.unsplash.com/photo-1552668288-648411517345?auto=format&fit=crop&w=500&h=300&q=80" style="width: 100%; height: 250px; object-fit: cover;">
                <div style="padding: 1.5rem;">
                    <h3 style="margin: 0 0 0.5rem; color: #0ea5e9;">Sức khỏe</h3>
                    <h2 style="margin: 0 0 1rem; font-size: 1.3rem; font-weight: 700;">Chọn trang phục phù hợp cho các môn thể thao</h2>
                    <p style="margin: 0; color: #64748b; line-height: 1.6;">Mỗi môn thể thao cần trang phục khác nhau, tìm hiểu để chọn đúng cho hoạt động của bạn...</p>
                </div>
            </article>
        </div>
    </section>
@endsection
