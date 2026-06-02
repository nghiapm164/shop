@extends('layouts.app')

@section('meta_title', 'Chính sách bảo mật - MODESPORT')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <h1 class="fashion-title text-4xl text-center mb-10">Chính sách bảo mật</h1>

    <div class="fashion-section p-8 space-y-6 text-slate-700 leading-relaxed">
        <div>
            <h2 class="text-xl font-bold text-slate-900 mb-3">1. Thông tin thu thập</h2>
            <p class="text-sm">Chúng tôi thu thập các thông tin sau khi bạn sử dụng dịch vụ:</p>
            <ul class="space-y-2 list-disc list-inside text-sm mt-2">
                <li>Họ tên, email, số điện thoại khi đăng ký tài khoản</li>
                <li>Địa chỉ giao hàng khi đặt hàng</li>
                <li>Thông tin thanh toán (không lưu trữ thông tin thẻ)</li>
                <li>Lịch sử duyệt web và mua hàng</li>
            </ul>
        </div>

        <div>
            <h2 class="text-xl font-bold text-slate-900 mb-3">2. Mục đích sử dụng</h2>
            <ul class="space-y-2 list-disc list-inside text-sm">
                <li>Xử lý đơn hàng và giao hàng</li>
                <li>Gửi thông báo về trạng thái đơn hàng</li>
                <li>Cải thiện trải nghiệm mua sắm</li>
                <li>Gửi thông tin khuyến mãi (nếu bạn đồng ý)</li>
            </ul>
        </div>

        <div>
            <h2 class="text-xl font-bold text-slate-900 mb-3">3. Bảo mật thông tin</h2>
            <p class="text-sm">Chúng tôi cam kết bảo vệ thông tin cá nhân của bạn bằng các biện pháp bảo mật tiên tiến. Thông tin thanh toán được mã hóa và không được lưu trữ trên hệ thống của chúng tôi.</p>
        </div>

        <div>
            <h2 class="text-xl font-bold text-slate-900 mb-3">4. Chia sẻ thông tin</h2>
            <p class="text-sm">Chúng tôi không bán hoặc chia sẻ thông tin cá nhân của bạn cho bên thứ ba, trừ các trường hợp:</p>
            <ul class="space-y-2 list-disc list-inside text-sm mt-2">
                <li>Đối tác vận chuyển để giao hàng</li>
                <li>Cơ quan pháp luật khi có yêu cầu</li>
            </ul>
        </div>

        <div>
            <h2 class="text-xl font-bold text-slate-900 mb-3">5. Quyền của bạn</h2>
            <p class="text-sm">Bạn có quyền truy cập, chỉnh sửa hoặc xóa thông tin cá nhân bất kỳ lúc nào thông qua trang Tài khoản hoặc liên hệ hotline.</p>
        </div>
    </div>
</div>
@endsection
