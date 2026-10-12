<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Gemini (Google AI Studio)
    |--------------------------------------------------------------------------
    | Lấy API key miễn phí tại https://aistudio.google.com/apikey
    | Nếu không có key hoặc gọi API lỗi, chatbot tự chuyển sang bộ phân tích
    | tiếng Việt theo luật (rule-based) nên vẫn hoạt động bình thường.
    */
    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-3.5-flash'),
        // Mức "suy nghĩ" của model Gemini 3.x (low = nhanh, phù hợp chatbot); model 2.5 dùng thinkingBudget = 0
        'thinking_level' => env('GEMINI_THINKING_LEVEL', 'low'),
        // Model dùng để viết lại câu trả lời (hạn mức riêng, nhanh). Để trống = dùng chung model trên
        'reply_model' => env('GEMINI_REPLY_MODEL', 'gemini-flash-lite-latest'),
        'endpoint' => env('GEMINI_ENDPOINT', 'https://generativelanguage.googleapis.com/v1beta'),
        'timeout' => (int) env('GEMINI_TIMEOUT', 15),
        // Cho phép Gemini viết lại câu trả lời cho tự nhiên (vẫn chỉ dựa trên dữ liệu website)
        'rewrite_reply' => (bool) env('CHATBOT_LLM_REPLY', true),
    ],

    // Hiện nút "Demo" (5 tình huống demo bắt buộc) trong khung chat – bật khi bảo vệ đề tài
    'demo_mode' => (bool) env('CHATBOT_DEMO_MODE', false),

    'max_products' => 6,
    'max_outfits' => 3,
    'history_turns' => 6,

    /*
    |--------------------------------------------------------------------------
    | Bảng size (cm / kg) – ưu tiên theo cân nặng
    |--------------------------------------------------------------------------
    */
    'size_chart' => [
        'nam' => [
            ['size' => 'XS', 'height' => [0, 159], 'weight' => [0, 49]],
            ['size' => 'S', 'height' => [160, 164], 'weight' => [50, 56]],
            ['size' => 'M', 'height' => [165, 169], 'weight' => [57, 63]],
            ['size' => 'L', 'height' => [170, 174], 'weight' => [64, 70]],
            ['size' => 'XL', 'height' => [175, 179], 'weight' => [71, 77]],
            ['size' => 'XXL', 'height' => [180, 184], 'weight' => [78, 85]],
            ['size' => 'XXXL', 'height' => [185, 250], 'weight' => [86, 300]],
        ],
        'nu' => [
            ['size' => 'XS', 'height' => [0, 149], 'weight' => [0, 41]],
            ['size' => 'S', 'height' => [150, 154], 'weight' => [42, 47]],
            ['size' => 'M', 'height' => [155, 159], 'weight' => [48, 53]],
            ['size' => 'L', 'height' => [160, 164], 'weight' => [54, 59]],
            ['size' => 'XL', 'height' => [165, 169], 'weight' => [60, 66]],
            ['size' => 'XXL', 'height' => [170, 174], 'weight' => [67, 73]],
            ['size' => 'XXXL', 'height' => [175, 250], 'weight' => [74, 300]],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Thông tin cửa hàng (nguồn: trang Chính sách đổi trả & logic thanh toán)
    |--------------------------------------------------------------------------
    | Chatbot CHỈ trả lời các câu hỏi chung dựa trên các thông tin dưới đây.
    */
    'store' => [
        'name' => 'MODESPORT',
        'hotline' => '0123 456 789',
        'email' => 'support@modesport.vn',
        'address' => '123 Đường Thể Thao, Quận 1, TP.HCM',
        'shipping_fee' => 30000,
        'free_shipping_min' => 500000,
        'payment_methods' => ['Thanh toán khi nhận hàng (COD)'],
        'return_days' => 7,
    ],

    'faq' => [
        'shipping' => "Phí giao hàng là **30.000đ**/đơn. Đơn hàng từ **500.000đ** (sau giảm giá) được **miễn phí vận chuyển**.",
        'return' => "Shop hỗ trợ **đổi trả trong 7 ngày** kể từ ngày nhận hàng nếu sản phẩm còn nguyên tem, nhãn, chưa qua sử dụng/giặt ủi, hoặc bị lỗi do nhà sản xuất, giao sai hàng, không đúng mô tả.\n"
            . "Không áp dụng với sản phẩm đã sử dụng, hàng giảm giá/khuyến mãi đặc biệt hoặc quá 7 ngày.\n"
            . "Liên hệ hotline **0123 456 789** hoặc email **support@modesport.vn**, cung cấp mã đơn và hình ảnh sản phẩm. Tiền hoàn qua chuyển khoản trong 5-7 ngày làm việc.",
        'payment' => "Hiện tại shop hỗ trợ **thanh toán khi nhận hàng (COD)**. Bạn kiểm tra hàng rồi mới thanh toán cho nhân viên giao hàng.",
        'contact' => "Bạn có thể liên hệ shop qua:\n- Hotline: **0123 456 789**\n- Email: **support@modesport.vn**\n- Địa chỉ: 123 Đường Thể Thao, Quận 1, TP.HCM\n- Hoặc gửi tin nhắn tại trang Liên hệ.",
        'order' => "Bạn có thể tra cứu đơn hàng tại trang **Tra cứu đơn hàng** (cần mã đơn và số điện thoại/email đặt hàng). Nếu đã đăng nhập, xem tại mục **Đơn hàng của tôi** – đơn đang chờ xác nhận có thể tự hủy ở đó.",
    ],

    // Liên kết đi kèm câu trả lời FAQ (tên route)
    'faq_links' => [
        'return' => ['label' => 'Chính sách đổi trả', 'route' => 'pages.return-policy'],
        'contact' => ['label' => 'Trang liên hệ', 'route' => 'pages.contact'],
        'order' => ['label' => 'Tra cứu đơn hàng', 'route' => 'order.lookup'],
    ],
];
