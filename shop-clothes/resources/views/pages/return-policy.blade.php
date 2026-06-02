@extends('layouts.app')

@section('meta_title', 'Chính sách đổi trả - MODESPORT')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <h1 class="fashion-title text-4xl text-center mb-10">Chính sách đổi trả</h1>

    <div class="fashion-section p-8 space-y-6 text-slate-700 leading-relaxed">
        <div>
            <h2 class="text-xl font-bold text-slate-900 mb-3">1. Điều kiện đổi trả</h2>
            <ul class="space-y-2 list-disc list-inside text-sm">
                <li>Sản phẩm còn nguyên tem, nhãn, chưa qua sử dụng, giặt ủi</li>
                <li>Thời gian đổi trả trong vòng 7 ngày kể từ ngày nhận hàng</li>
                <li>Sản phẩm bị lỗi do nhà sản xuất hoặc giao sai hàng</li>
                <li>Sản phẩm không đúng với mô tả trên website</li>
            </ul>
        </div>

        <div>
            <h2 class="text-xl font-bold text-slate-900 mb-3">2. Quy trình đổi trả</h2>
            <ul class="space-y-2 list-disc list-inside text-sm">
                <li>Liên hệ hotline 0123 456 789 hoặc email support@modesport.vn</li>
                <li>Cung cấp mã đơn hàng, hình ảnh sản phẩm lỗi</li>
                <li>Nhân viên xác nhận và hướng dẫn gửi trả</li>
                <li>Hoàn tiền hoặc gửi sản phẩm thay thế trong 3-5 ngày làm việc</li>
            </ul>
        </div>

        <div>
            <h2 class="text-xl font-bold text-slate-900 mb-3">3. Trường hợp không được đổi trả</h2>
            <ul class="space-y-2 list-disc list-inside text-sm">
                <li>Sản phẩm đã qua sử dụng, giặt ủi hoặc bị hư hỏng do người dùng</li>
                <li>Sản phẩm giảm giá, khuyến mãi đặc biệt</li>
                <li>Quá thời gian 7 ngày quy định</li>
                <li>Không có hóa đơn hoặc bằng chứng mua hàng</li>
            </ul>
        </div>

        <div>
            <h2 class="text-xl font-bold text-slate-900 mb-3">4. Hình thức hoàn tiền</h2>
            <p class="text-sm">Hoàn tiền qua chuyển khoản ngân hàng trong vòng 5-7 ngày làm việc kể từ khi xác nhận đổi trả thành công.</p>
        </div>
    </div>
</div>
@endsection
