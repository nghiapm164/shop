# Mục Lục Hướng Dẫn Code

Dưới đây là mục lục các từ khóa tìm kiếm để bạn dễ dàng tra cứu các đoạn code quan trọng đã được bình luận giải thích chi tiết trong dự án.

Bạn chỉ cần mở phần mềm soạn thảo mã nguồn (như VS Code), bấm `Ctrl + Shift + F` (hoặc `Cmd + Shift + F` trên Mac) và dán một trong các từ khóa ở dưới (ví dụ: `XU_LY_DAT_HANG`) vào ô tìm kiếm để đi thẳng tới đoạn code tương ứng.

## 1. Đặt hàng và Giao dịch
*   **`XU_LY_DAT_HANG`**: Hàm xử lý đặt hàng (Kiểm tra địa chỉ, tạo mã đơn, trừ tồn kho, gửi email).
    *   *Tệp tin:* `app/Livewire/CheckoutPage.php` (Hàm `placeOrder`)
*   **`HUY_DON_HANG`**: Hàm xử lý khi khách hàng tự hủy đơn (Hoàn lại số lượng tồn kho, gửi email báo hủy).
    *   *Tệp tin:* `app/Http/Controllers/Client/OrderController.php` (Hàm `cancel`)

## 2. Quản lý Sản phẩm
*   **`TAO_SAN_PHAM`**: Hàm tạo mới sản phẩm và biến thể (Sử dụng Transaction an toàn, lưu thông tin, đồng bộ màu sắc/kích cỡ, upload ảnh).
    *   *Tệp tin:* `app/Http/Controllers/Admin/AdminProductController.php` (Hàm `store`)

## 3. Hiển thị Giao diện
*   **`LOGIC_TRANG_CHU`**: Logic truy vấn dữ liệu hiển thị trên trang chủ (Sản phẩm mới, bán chạy, Flash Sale, danh mục, đánh giá...).
    *   *Tệp tin:* `app/Http/Controllers/HomeController.php` (Hàm `__invoke`)
