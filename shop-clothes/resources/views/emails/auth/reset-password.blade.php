<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 30px 0;
        }
        .logo {
            font-size: 28px;
            font-weight: 900;
            color: #0f172a;
            text-decoration: none;
            letter-spacing: -1px;
        }
        .logo span {
            color: #ef4444;
        }
        .content {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
        }
        h1 {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 0;
            margin-bottom: 20px;
        }
        p {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 16px;
        }
        .button-wrapper {
            text-align: center;
            margin: 35px 0;
        }
        .button {
            display: inline-block;
            background-color: #ef4444;
            color: #ffffff !important;
            font-weight: 600;
            font-size: 16px;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.4);
        }
        .footer {
            text-align: center;
            padding: 30px 0;
            color: #64748b;
            font-size: 14px;
        }
        .footer-link {
            color: #3b82f6;
            text-decoration: underline;
            word-break: break-all;
        }
        .note {
            font-size: 14px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="{{ config('app.url') }}" class="logo">
                MODE<span>SPORT</span>
            </a>
        </div>
        
        <div class="content">
            <h1>Xin chào {{ $user->name ?? 'bạn' }},</h1>
            
            <p>Bạn nhận được email này vì chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn tại <strong>MODESPORT</strong>.</p>
            
            <div class="button-wrapper">
                <a href="{{ $url }}" class="button" target="_blank">Đặt Lại Mật Khẩu</a>
            </div>
            
            <p>Link đặt lại mật khẩu này sẽ hết hạn trong vòng {{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} phút.</p>
            
            <p class="note">Nếu bạn không yêu cầu đặt lại mật khẩu, bạn không cần phải làm gì thêm và tài khoản của bạn vẫn an toàn.</p>
            
            <p style="margin-bottom: 0;">Trân trọng,<br>Đội ngũ <strong>MODESPORT</strong></p>
        </div>
        
        <div class="footer">
            <p style="margin-bottom: 10px;">Nếu bạn gặp khó khăn khi bấm nút "Đặt Lại Mật Khẩu", hãy copy và dán đường link bên dưới vào trình duyệt web của bạn:</p>
            <a href="{{ $url }}" class="footer-link">{{ $url }}</a>
            <p style="margin-top: 20px;">&copy; {{ date('Y') }} MODESPORT. Đã đăng ký bản quyền.</p>
        </div>
    </div>
</body>
</html>
