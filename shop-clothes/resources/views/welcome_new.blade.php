@extends('layouts.shop')

@section('title', 'Shop Nam - Quần áo thể thao nam | Yody Style')

@section('content')
    <!-- Hero Banner -->
    <div style="background: linear-gradient(135deg, #0ea5e9 0%, #06a5d9 100%); height: 500px; display: flex; align-items: center; justify-content: center; color: white; text-align: center; position: relative; overflow: hidden; margin-bottom: 2rem;">
        <div style="position: absolute; inset: 0; opacity: 0.3; background-image: radial-gradient(circle at 20% 50%, #ffffff 1px, transparent 1px); background-size: 50px 50px;"></div>
        <div style="position: relative; z-index: 10; max-width: 800px; padding: 0 2rem; animation: slideDown 0.8s ease;">
            <h1 style="font-size: 3.5rem; font-weight: 900; margin: 0 0 1rem; line-height: 1.1;">⚽ SHOP NAM YODY 💪</h1>
            <p style="font-size: 1.3rem; margin: 0 0 2rem; opacity: 0.95;">Nơi mua sắm quần áo thể thao nam chất lượng cao - Năng động, thoải mái, tự tin</p>
            <a href="#featured" style="display: inline-block; padding: 1rem 2rem; background: white; color: #0ea5e9; font-weight: 800; border-radius: 8px; cursor: pointer; font-size: 1.1rem; transition: all 0.3s;">🛍️ Khám phá ngay</a>
        </div>
    </div>

    <!-- Trust Section -->
    <section class="container" style="padding: 3rem 0; text-align: center; border-bottom: 1px solid rgba(15,23,42,0.08);">
        <div style="display: grid; gap: 1.5rem; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
            <div style="padding: 1.5rem;">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">💯</div>
                <h3 style="margin: 0 0 0.5rem; font-weight: 700;">Chất lượng đảm bảo</h3>
                <p style="margin: 0; color: #64748b;">100% hàng chính hãng, kiểm định kỹ càng</p>
            </div>
            <div style="padding: 1.5rem;">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">⚡</div>
                <h3 style="margin: 0 0 0.5rem; font-weight: 700;">Giao hàng nhanh</h3>
                <p style="margin: 0; color: #64748b;">Miễn phí vận chuyển từ 500K, giao 1-2 ngày</p>
            </div>
            <div style="padding: 1.5rem;">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🔄</div>
                <h3 style="margin: 0 0 0.5rem; font-weight: 700;">Đổi trả dễ dàng</h3>
                <p style="margin: 0; color: #64748b;">30 ngày đổi hàng miễn phí, không câu hỏi</p>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="container categories-section">
        <h2 class="section-title">🔥 Danh mục nổi bật</h2>
        <div class="categories-grid">
            <div class="category-card" style="cursor: pointer; transition: all 0.3s;">
                <div style="font-size: 3rem; margin-bottom: 0.75rem;">👕</div>
                <h3>Áo thun</h3>
                <p>Chất liệu thấm hút mồ hôi</p>
                <small style="color: #0ea5e9; font-weight: 700;">100+ sản phẩm</small>
            </div>
            <div class="category-card" style="cursor: pointer; transition: all 0.3s;">
                <div style="font-size: 3rem; margin-bottom: 0.75rem;">👖</div>
                <h3>Quần</h3>
                <p>Form chuẩn, thoáng mát</p>
                <small style="color: #0ea5e9; font-weight: 700;">80+ sản phẩm</small>
            </div>
            <div class="category-card" style="cursor: pointer; transition: all 0.3s;">
                <div style="font-size: 3rem; margin-bottom: 0.75rem;">🧥</div>
                <h3>Áo khoác</h3>
                <p>Chống nước, chống gió</p>
                <small style="color: #0ea5e9; font-weight: 700;">60+ sản phẩm</small>
            </div>
            <div class="category-card" style="cursor: pointer; transition: all 0.3s;">
                <div style="font-size: 3rem; margin-bottom: 0.75rem;">🎒</div>
                <h3>Phụ kiện</h3>
                <p>Hoàn thiện phong cách</p>
                <small style="color: #0ea5e9; font-weight: 700;">50+ sản phẩm</small>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="container section" id="featured">
        <div class="section-header">
            <h2 class="section-title">🔥 Bán chạy nhất</h2>
            <span style="background: #fef3c7; color: #92400e; padding: 0.6rem 1rem; border-radius: 9999px; font-weight: 700; font-size: 0.9rem;">⏰ Hôm nay giảm 25%</span>
        </div>
        <div class="product-grid">
            <article class="product-card">
                <div class="product-image" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3.5rem; position: relative;">👕
                    <span style="position: absolute; top: -10px; right: -10px; background: #ef4444; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; font-weight: 700;">-25%</span>
                </div>
                <div class="product-body">
                    <h3 class="product-name">Áo thun thể thao cao cấp</h3>
                    <p class="product-desc">Chất liệu Polyester cao cấp 100%, thấm hút mồ hôi tốt</p>
                    <div style="display: flex; gap: 0.5rem; margin: 0.75rem 0;">
                        <span style="color: #fbbf24; font-size: 0.9rem;">★★★★★</span>
                        <span style="color: #64748b; font-size: 0.85rem;">(2.3K đánh giá)</span>
                    </div>
                    <div class="product-price">
                        <span class="product-original-price">499.000</span>
                        <strong>399.000 ₫</strong>
                    </div>
                </div>
            </article>

            <article class="product-card">
                <div class="product-image" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3.5rem; position: relative;">👖
                    <span style="position: absolute; top: -10px; right: -10px; background: #ef4444; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; font-weight: 700;">-25%</span>
                </div>
                <div class="product-body">
                    <h3 class="product-name">Quần short năng động</h3>
                    <p class="product-desc">Form chuẩn mực, 2 túi hông chất lượng</p>
                    <div style="display: flex; gap: 0.5rem; margin: 0.75rem 0;">
                        <span style="color: #fbbf24; font-size: 0.9rem;">★★★★★</span>
                        <span style="color: #64748b; font-size: 0.85rem;">(1.8K đánh giá)</span>
                    </div>
                    <div class="product-price">
                        <span class="product-original-price">600.000</span>
                        <strong>450.000 ₫</strong>
                    </div>
                </div>
            </article>

            <article class="product-card">
                <div class="product-image" style="background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3.5rem; position: relative;">🧥
                    <span style="position: absolute; top: -10px; right: -10px; background: #ef4444; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; font-weight: 700;">-25%</span>
                </div>
                <div class="product-body">
                    <h3 class="product-name">Hoodie thể thao premium</h3>
                    <p class="product-desc">Ấm áp thoải mái, perfect cho mùa lạnh</p>
                    <div style="display: flex; gap: 0.5rem; margin: 0.75rem 0;">
                        <span style="color: #fbbf24; font-size: 0.9rem;">★★★★☆</span>
                        <span style="color: #64748b; font-size: 0.85rem;">(1.5K đánh giá)</span>
                    </div>
                    <div class="product-price">
                        <span class="product-original-price">850.000</span>
                        <strong>650.000 ₫</strong>
                    </div>
                </div>
            </article>

            <article class="product-card">
                <div class="product-image" style="background: linear-gradient(135deg, #ec4899 0%, #be185d 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3.5rem; position: relative;">👟
                    <span style="position: absolute; top: -10px; right: -10px; background: #ef4444; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; font-weight: 700;">-20%</span>
                </div>
                <div class="product-body">
                    <h3 class="product-name">Giày chạy bộ PRO</h3>
                    <p class="product-desc">Thiết kế ergonomic, hỗ trợ chân tối đa</p>
                    <div style="display: flex; gap: 0.5rem; margin: 0.75rem 0;">
                        <span style="color: #fbbf24; font-size: 0.9rem;">★★★★★</span>
                        <span style="color: #64748b; font-size: 0.85rem;">(3.1K đánh giá)</span>
                    </div>
                    <div class="product-price">
                        <span class="product-original-price">1.200.000</span>
                        <strong>950.000 ₫</strong>
                    </div>
                </div>
            </article>

            <article class="product-card">
                <div class="product-image" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3.5rem;">🧤</div>
                <div class="product-body">
                    <h3 class="product-name">Áo khoác chống nước</h3>
                    <p class="product-desc">Chống nước hiệu quả, nhẹ bồng</p>
                    <div style="display: flex; gap: 0.5rem; margin: 0.75rem 0;">
                        <span style="color: #fbbf24; font-size: 0.9rem;">★★★★☆</span>
                        <span style="color: #64748b; font-size: 0.85rem;">(890 đánh giá)</span>
                    </div>
                    <div class="product-price"><strong>549.000 ₫</strong></div>
                </div>
            </article>

            <article class="product-card">
                <div class="product-image" style="background: linear-gradient(135deg, #f97316 0%, #c2410c 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3.5rem;">🎒</div>
                <div class="product-body">
                    <h3 class="product-name">Túi đeo chéo nam</h3>
                    <p class="product-desc">Chất liệu bền bỉ, nhiều ngăn tiện lợi</p>
                    <div style="display: flex; gap: 0.5rem; margin: 0.75rem 0;">
                        <span style="color: #fbbf24; font-size: 0.9rem;">★★★★★</span>
                        <span style="color: #64748b; font-size: 0.85rem;">(750 đánh giá)</span>
                    </div>
                    <div class="product-price"><strong>199.000 ₫</strong></div>
                </div>
            </article>

            <article class="product-card">
                <div class="product-image" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3.5rem;">👔</div>
                <div class="product-body">
                    <h3 class="product-name">Quần tây thể thao</h3>
                    <p class="product-desc">Co giãn tốt, dễ di chuyển</p>
                    <div style="display: flex; gap: 0.5rem; margin: 0.75rem 0;">
                        <span style="color: #fbbf24; font-size: 0.9rem;">★★★★☆</span>
                        <span style="color: #64748b; font-size: 0.85rem;">(620 đánh giá)</span>
                    </div>
                    <div class="product-price"><strong>699.000 ₫</strong></div>
                </div>
            </article>

            <article class="product-card">
                <div class="product-image" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3.5rem;">🧢</div>
                <div class="product-body">
                    <h3 class="product-name">Nón thể thao UPF50+</h3>
                    <p class="product-desc">Chống UV hiệu quả, design hiện đại</p>
                    <div style="display: flex; gap: 0.5rem; margin: 0.75rem 0;">
                        <span style="color: #fbbf24; font-size: 0.9rem;">★★★★★</span>
                        <span style="color: #64748b; font-size: 0.85rem;">(540 đánh giá)</span>
                    </div>
                    <div class="product-price"><strong>149.000 ₫</strong></div>
                </div>
            </article>
        </div>
    </section>

    <!-- Promo Section -->
    <section class="container" style="padding: 3rem 2rem; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-radius: 16px; margin: 3rem 0; text-align: center;">
        <h2 style="margin: 0 0 1rem; font-size: 2.2rem; font-weight: 800;">🎁 Combo Hot Hôm Nay</h2>
        <p style="margin: 0 0 2rem; color: #64748b; font-size: 1.1rem;">Tiết kiệm đến 40% khi mua combo, vận chuyển miễn phí</p>
        <div style="display: grid; gap: 1.5rem; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));">
            <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">👕 + 👖</div>
                <h3 style="margin: 0 0 0.5rem; font-weight: 700;">Combo Áo + Quần</h3>
                <p style="margin: 0 0 1rem; color: #64748b;">Áo thun + Quần short</p>
                <div style="font-size: 1.5rem; font-weight: 800; color: #0ea5e9; margin-bottom: 1rem;">699.000 ₫ <span style="text-decoration: line-through; color: #cbd5e1; font-size: 1rem; margin-left: 0.5rem;">949.000</span></div>
                <button style="width: 100%; padding: 0.75rem; background: #0ea5e9; color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer;">Thêm vào giỏ</button>
            </div>
            <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">👕 x 3</div>
                <h3 style="margin: 0 0 0.5rem; font-weight: 700;">Combo 3 Áo Thun</h3>
                <p style="margin: 0 0 1rem; color: #64748b;">Ba màu khác nhau</p>
                <div style="font-size: 1.5rem; font-weight: 800; color: #0ea5e9; margin-bottom: 1rem;">999.000 ₫ <span style="text-decoration: line-through; color: #cbd5e1; font-size: 1rem; margin-left: 0.5rem;">1.497.000</span></div>
                <button style="width: 100%; padding: 0.75rem; background: #0ea5e9; color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer;">Thêm vào giỏ</button>
            </div>
            <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">🏃‍♂️ Full Set</div>
                <h3 style="margin: 0 0 0.5rem; font-weight: 700;">Combo Thể Thao Toàn Bộ</h3>
                <p style="margin: 0 0 1rem; color: #64748b;">Cơm đầy đủ từ đầu tới chân</p>
                <div style="font-size: 1.5rem; font-weight: 800; color: #0ea5e9; margin-bottom: 1rem;">1.799.000 ₫ <span style="text-decoration: line-through; color: #cbd5e1; font-size: 1rem; margin-left: 0.5rem;">2.999.000</span></div>
                <button style="width: 100%; padding: 0.75rem; background: #0ea5e9; color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer;">Thêm vào giỏ</button>
            </div>
        </div>
    </section>

    <!-- Blog Section -->
    <section class="container section" style="padding-top: 3rem; border-top: 1px solid rgba(15,23,42,0.08);">
        <h2 class="section-title">📰 Blog & Tips</h2>
        <div style="display: grid; gap: 2rem; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
            <article style="border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: all 0.3s; cursor: pointer;">
                <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">👕</div>
                <div style="padding: 1.5rem;">
                    <span style="display: inline-block; background: #e0f2fe; color: #0369a1; padding: 0.4rem 0.8rem; border-radius: 4px; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.75rem;">TƯ VẤN THỜI TRANG</span>
                    <h3 style="margin: 0 0 0.75rem; font-size: 1.2rem; font-weight: 700;">Tuyệt chiêu lựa chọn áo thể thao phù hợp</h3>
                    <p style="margin: 0; color: #64748b; line-height: 1.6;">Khám phá cách chọn áo thể thao đúng size, form và chất liệu phù hợp với hoạt động của bạn...</p>
                </div>
            </article>

            <article style="border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: all 0.3s; cursor: pointer;">
                <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">🎯</div>
                <div style="padding: 1.5rem;">
                    <span style="display: inline-block; background: #e0f2fe; color: #0369a1; padding: 0.4rem 0.8rem; border-radius: 4px; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.75rem;">MẹO CHĂM SÓC</span>
                    <h3 style="margin: 0 0 0.75rem; font-size: 1.2rem; font-weight: 700;">Cách giữ quần áo thể thao bền lâu</h3>
                    <p style="margin: 0; color: #64748b; line-height: 1.6;">Hướng dẫn giặt, phơi và bảo quản quần áo thể thao để kéo dài tuổi thọ...</p>
                </div>
            </article>

            <article style="border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: all 0.3s; cursor: pointer;">
                <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">💪</div>
                <div style="padding: 1.5rem;">
                    <span style="display: inline-block; background: #e0f2fe; color: #0369a1; padding: 0.4rem 0.8rem; border-radius: 4px; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.75rem;">SỨC KHỎE</span>
                    <h3 style="margin: 0 0 0.75rem; font-size: 1.2rem; font-weight: 700;">Lợi ích của tập luyện đều đặn</h3>
                    <p style="margin: 0; color: #64748b; line-height: 1.6;">Tìm hiểu tại sao quần áo thể thao đúng chuẩn rất quan trọng cho sức khỏe...</p>
                </div>
            </article>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="container" style="text-align: center; padding: 3rem 2rem; background: linear-gradient(135deg, #0ea5e9 0%, #06a5d9 100%); border-radius: 16px; margin: 3rem 0; color: white;">
        <h2 style="margin: 0 0 1rem; font-size: 2.2rem; font-weight: 800;">Tham gia cộng đồng Shop Nam</h2>
        <p style="margin: 0 0 2rem; font-size: 1.1rem; opacity: 0.95;">Nhận bản tin hàng tuần về sản phẩm mới, khuyến mãi độc quyền và mẹo tập luyện</p>
        <div style="display: flex; gap: 1rem; flex-wrap: wrap; justify-content: center;">
            <a href="{{ route('client.register') }}" style="display: inline-block; padding: 1rem 2.5rem; background: white; color: #0ea5e9; font-weight: 800; border-radius: 8px; cursor: pointer; font-size: 1rem; transition: all 0.3s; text-decoration: none;">Đăng ký ngay</a>
            <a href="{{ route('client.login') }}" style="display: inline-block; padding: 1rem 2.5rem; background: transparent; color: white; border: 2px solid white; font-weight: 800; border-radius: 8px; cursor: pointer; font-size: 1rem; transition: all 0.3s; text-decoration: none;">Khách hàng cũ</a>
        </div>
    </section>

    <style>
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .product-card:hover {
            transform: translateY(-8px) !important;
        }
        
        .category-card:hover {
            transform: translateY(-4px) !important;
        }
    </style>
@endsection
