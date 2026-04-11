@extends('layouts.client')

@section('title', 'Client Đăng ký | Shop Nam')

@section('content')
    <div class="card">
        <div class="card-inner">
            <h1 class="hero-title">Đăng ký khách hàng</h1>
            <p class="hero-subtitle">Tạo tài khoản để đặt mua và theo dõi đơn hàng thời trang nam.</p>

            @if ($errors->any())
                <div class="error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('client.register') }}">
                @csrf

                <div class="form-group">
                    <label for="name">Họ và tên</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input id="password" type="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Xác nhận mật khẩu</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                </div>

                <button type="submit">Tạo tài khoản Client</button>
            </form>

            <div class="helper-links">
                <a href="{{ route('client.login') }}">Đã có tài khoản? Đăng nhập</a>
                <a href="{{ route('admin.register') }}">Đăng ký admin</a>
            </div>
        </div>
    </div>
@endsection
