@extends('layouts.client')

@section('title', 'Quên mật khẩu Client | Shop Nam')

@section('content')
    <div class="card">
        <div class="card-inner">
            <h1 class="hero-title">Quên mật khẩu</h1>
            <p class="hero-subtitle">Nhập email để nhận đường dẫn đặt lại mật khẩu và tiếp tục mua sắm.</p>

            @if(session('status'))
                <div class="alert">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('client.password.email') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <button type="submit">Gửi liên kết đặt lại mật khẩu</button>
            </form>

            <div class="helper-links">
                <a href="{{ route('client.login') }}">Quay lại đăng nhập Client</a>
            </div>
        </div>
    </div>
@endsection
