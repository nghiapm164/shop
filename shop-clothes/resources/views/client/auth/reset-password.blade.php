@extends('layouts.client')

@section('title', 'Đặt lại mật khẩu Client | Shop Nam')

@section('content')
    <div class="card">
        <div class="card-inner">
            <h1 class="hero-title">Đặt lại mật khẩu</h1>
            <p class="hero-subtitle">Nhập email và mật khẩu mới để tiếp tục mua sắm thời trang nam.</p>

            @if ($errors->any())
                <div class="error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('client.password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu mới</label>
                    <input id="password" type="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Xác nhận mật khẩu</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                </div>

                <button type="submit">Cập nhật mật khẩu</button>
            </form>

            <div class="helper-links">
                <a href="{{ route('client.login') }}">Quay lại đăng nhập Client</a>
            </div>
        </div>
    </div>
@endsection
