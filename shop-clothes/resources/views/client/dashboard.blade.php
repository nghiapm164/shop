@extends('layouts.client')

@section('title', 'Bảng điều khiển khách hàng | Shop Nam')

@section('content')
    <div class="card">
        <div class="card-inner">
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; gap: 20px; align-items: center;">
                <div>
                    <h1 class="hero-title">Bảng điều khiển khách hàng</h1>
                    <p class="hero-subtitle">Quản lý đơn hàng, xem lịch sử mua sắm và tiếp tục khám phá trang phục nam.</p>
                </div>
                <form method="POST" action="{{ route('client.logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit" style="padding: 14px 22px; border: none; border-radius: 16px; background: linear-gradient(135deg, #34d399, #10b981); color: #111827; font-weight: 700; cursor: pointer;">Đăng xuất</button>
                </form>
            </div>

            <div style="display: grid; gap: 22px; margin-top: 32px; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));">
                <div class="card" style="background: rgba(15, 23, 42, .96);">
                    <h2>Chào mừng</h2>
                    <p>Bạn đã đến đúng nơi để quản lý đơn hàng và lịch sử mua sắm sản phẩm thời trang nam.</p>
                </div>
                <div class="card" style="background: rgba(15, 23, 42, .96);">
                    <h2>Tiếp tục mua sắm</h2>
                    <p>Khám phá bộ sưu tập mới nhất, cập nhật xu hướng và hoàn tất thanh toán an toàn.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
