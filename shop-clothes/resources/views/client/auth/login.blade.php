@extends('layouts.client')

@section('title', 'Client Đăng nhập | Shop Nam')

@section('content')
    <div class="card">
        <div class="card-inner">
            <h1 class="hero-title">Đăng nhập khách hàng</h1>
            <p class="hero-subtitle">Đăng nhập để tiếp tục mua sắm quần áo nam và quản lý đơn hàng của bạn.</p>

            @if ($errors->any())
                <div class="error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('client.login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input id="password" type="password" name="password" required>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember">
                        Ghi nhớ đăng nhập
                    </label>
                </div>

                <button type="submit">Đăng nhập</button>
            </form>

            <div class="helper-links">
                <a href="{{ route('client.register') }}">Tạo tài khoản mới</a>
                <a href="{{ route('client.password.request') }}">Quên mật khẩu?</a>
                <a href="{{ route('admin.login') }}">Xem trang admin</a>
            </div>
        </div>
    </div>
@endsection
