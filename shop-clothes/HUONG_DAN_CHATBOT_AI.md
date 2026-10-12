# Hướng dẫn sử dụng chức năng AI – AI Sport Assistant

Tài liệu bàn giao chức năng **chatbot AI tư vấn sản phẩm** của website MODESPORT: cách cài đặt, cách dùng, cách chạy 5 tình huống demo khi bảo vệ, cách quản lý dữ liệu và xử lý sự cố.

---

## Mục lục

1. [Checklist nhanh trước buổi demo](#1-checklist-nhanh-trước-buổi-demo)
2. [Chức năng AI làm được gì](#2-chức-năng-ai-làm-được-gì)
   - [2.2. Chatbot hoạt động như thế nào? (giải thích dễ hiểu)](#22-chatbot-hoạt-động-như-thế-nào-giải-thích-dễ-hiểu)
   - [2.3. Hành trình của một câu hỏi (chi tiết từng bước)](#23-hành-trình-của-một-câu-hỏi-chi-tiết-từng-bước)
3. [Cài đặt và chạy trên XAMPP](#3-cài-đặt-và-chạy-trên-xampp)
4. [Sử dụng khung chat](#4-sử-dụng-khung-chat)
5. [Chế độ Demo – 5 tình huống bắt buộc](#5-chế-độ-demo--5-tình-huống-bắt-buộc)
6. [Quản lý dữ liệu cho AI](#6-quản-lý-dữ-liệu-cho-ai)
7. [Gemini API: key, hạn mức, chế độ dự phòng](#7-gemini-api-key-hạn-mức-chế-độ-dự-phòng)
8. [Cách chatbot hoạt động (dùng cho báo cáo)](#8-cách-chatbot-hoạt-động-dùng-cho-báo-cáo)
9. [Kiểm thử tự động](#9-kiểm-thử-tự-động)
10. [Xử lý sự cố](#10-xử-lý-sự-cố)

---

## 1. Checklist nhanh trước buổi demo

- [ ] Mở **XAMPP Control Panel** → bấm **Start** cho **Apache** và **MySQL**.
- [ ] Mở web: **http://localhost:8080** (hoặc chạy `php artisan serve` rồi mở http://127.0.0.1:8000, xem [mục 3.5](#35-chạy-website)).
- [ ] Máy có Internet (để dùng Gemini). Không có mạng chatbot vẫn chạy được, xem [mục 7.3](#73-chế-độ-dự-phòng-không-cần-internet).
- [ ] Mở khung chat → bấm **↻ (Cuộc trò chuyện mới)** để xoá hội thoại cũ.
- [ ] Bấm **▶ Demo** → chạy thử lần lượt Demo 1 → 5 một lượt (kết quả sẽ được lưu cache 1 ngày, lúc bảo vệ trả lời ngay).
- [ ] Khi trình bày, hỏi các câu cách nhau khoảng 10–15 giây (hạn mức Gemini miễn phí, xem [mục 7.2](#72-hạn-mức-miễn-phí)).

---

## 2. Chức năng AI làm được gì

### 2.1. Đáp ứng yêu cầu đề bài

| Yêu cầu đề bài | Cách đáp ứng |
|---|---|
| 1. Hiểu câu hỏi bằng ngôn ngữ tự nhiên | Gemini AI phân tích câu hỏi thành tiêu chí (JSON) + bộ phân tích tiếng Việt tự viết (hiểu cả câu không dấu, "300k", "1tr2", "1m75", "70kg"...) |
| 2. Trả lời câu hỏi về website và sản phẩm | Phí ship, đổi trả, thanh toán, liên hệ, tra cứu đơn, mã giảm giá, danh mục sản phẩm, bảng size |
| 3. Tư vấn sản phẩm theo nhu cầu | Phân tích môn thể thao, mục đích, ngân sách; gợi ý **cả bộ đồ** (áo + quần) trong ngân sách |
| 4. Gợi ý theo giới tính, môn thể thao, mục đích, size, màu, khoảng giá, kiểu dáng | Lọc trên dữ liệu thật: giới tính / môn / mục đích / kiểu dáng (thuộc tính sản phẩm), size & màu (biến thể **còn hàng**), giá (giá sau khuyến mãi) |
| 5. Đưa ra nhiều sản phẩm thay vì chỉ trả lời văn bản | Hiển thị tối đa 6 thẻ sản phẩm (ảnh, giá, % giảm, màu, size) hoặc 3 bộ đồ |
| 6. Dẫn khách đến trang sản phẩm | Bấm thẻ sản phẩm → mở trang chi tiết; khung chat vẫn giữ hội thoại khi chuyển trang |
| 7. Không trả lời tuỳ tiện thông tin ngoài dữ liệu | Sản phẩm/giá/size luôn lấy từ database; câu hỏi ngoài phạm vi bị từ chối lịch sự; không có hàng đúng yêu cầu thì nói rõ tiêu chí nào chưa đáp ứng |

Ngoài ra chatbot **hiểu câu hỏi nối tiếp** (ví dụ hỏi "Tìm áo bóng đá nam" rồi hỏi tiếp "Còn màu trắng không?") và tư vấn size theo chiều cao/cân nặng.

### 2.2. Chatbot hoạt động như thế nào? (giải thích dễ hiểu)

Hãy hình dung chatbot như **một nhân viên bán hàng** đứng trong cửa hàng, có **kho hàng** phía sau và một **cuốn sổ quy định** của shop. Mỗi khi khách hỏi, nhân viên này làm 6 bước:

```
  Khách hỏi
     │
     ▼
① NGHE HIỂU ──► ② ĐO SIZE ──► ③ VÀO KHO TÌM HÀNG ──► ④ SOẠN CÂU TRẢ LỜI ──► ⑤ ĐƯA HÀNG CHO KHÁCH XEM
     ▲                                                                              │
     └──────────────────────── ⑥ GHI NHỚ CÂU CHUYỆN ◄───────────────────────────────┘
```

| Bước | Nhân viên làm gì | Trong hệ thống |
|---|---|---|
| **① Nghe hiểu** | Nghe khách nói rồi ghi ra một **"phiếu yêu cầu"**: khách là nam hay nữ, chơi môn gì, cần áo hay quần, màu gì, size gì, bao nhiêu tiền... | Trí tuệ nhân tạo **Gemini của Google** đọc câu hỏi và điền phiếu. Nếu mất mạng hoặc Gemini bận, một **bộ đọc hiểu tiếng Việt do nhóm tự viết** điền phiếu thay, nên chatbot không bao giờ "đứng hình" |
| **② Đo size** | Nếu khách cho biết chiều cao, cân nặng thì tra **bảng size** của shop để chọn size phù hợp | Tra bảng size nam/nữ; ưu tiên theo cân nặng |
| **③ Vào kho tìm hàng** | Cầm phiếu yêu cầu vào kho, chỉ lấy **hàng thật đang bán, còn hàng**, đúng màu, đúng size, đúng tầm giá. Nếu khách cần cả bộ thì ghép áo + quần sao cho tổng tiền vừa ngân sách | Tìm trong **cơ sở dữ liệu sản phẩm** của website (chính là kho hàng hiển thị trên web) |
| **④ Soạn câu trả lời** | Nói cho khách biết đã tìm được gì. Nếu không có hàng đúng hoàn toàn thì **nói thật** là thiếu tiêu chí nào và đưa mẫu gần nhất | Câu trả lời được soạn từ chính hàng vừa tìm; Gemini chỉ giúp **diễn đạt cho tự nhiên**, không được thêm sản phẩm hay giá nào khác |
| **⑤ Đưa hàng cho khách xem** | Bày sản phẩm ra cho khách xem, khách thích món nào thì dẫn tới quầy món đó | Hiện các **thẻ sản phẩm** có ảnh, giá, màu, size; bấm vào thẻ là mở trang sản phẩm để đặt mua |
| **⑥ Ghi nhớ câu chuyện** | Nhớ khách vừa hỏi gì, để khi khách nói "còn màu trắng không?" thì hiểu là vẫn đang nói về món cũ | Lưu lại nội dung cuộc trò chuyện đang diễn ra |

**Câu hỏi về shop** (phí ship, đổi trả, thanh toán, liên hệ...): nhân viên mở **cuốn sổ quy định** để trả lời đúng như chính sách đã đăng trên website. **Mã giảm giá** và **danh mục hàng** thì xem trực tiếp trên hệ thống.

**Câu hỏi không liên quan đến shop** (thời tiết, kiến thức chung...) hoặc **món shop không bán** (đồ bơi, đồ võ...): nhân viên lịch sự nói là không hỗ trợ, **không đoán bừa**.

**Ví dụ – Demo 5:** khách hỏi *"Tôi là nam, cao 1m72 nặng 65kg, muốn mua đồ chạy bộ màu đen dưới 500k."*

1. **Nghe hiểu** → phiếu yêu cầu: *Nam · Chạy bộ · Màu đen · Cao 1m72, nặng 65kg · Giá dưới 500.000đ*.
2. **Đo size** → tra bảng size nam: 1m72 và 65kg đều ứng với size **L**.
3. **Vào kho** → chỉ lấy đồ chạy bộ dành cho nam, **còn màu đen size L**, giá không quá 500.000đ.
4. **Soạn câu trả lời** → "Bạn nên mặc size L… Mình tìm được 6 sản phẩm…".
5. **Đưa hàng** → 6 thẻ sản phẩm, bấm vào là mở trang mua.

Trên màn hình, dòng **"AI hiểu"** dưới câu trả lời chính là **phiếu yêu cầu** ở bước ①, giúp người xem thấy rõ AI đã phân tích câu hỏi ra sao.

**Vì sao có thể tin câu trả lời?** Vì nhân viên này **chỉ được bán hàng có trong kho**: mọi tên sản phẩm, giá, màu, size đều lấy từ dữ liệu thật của website, còn AI chỉ làm hai việc là *hiểu câu hỏi* và *nói cho dễ nghe*.

### 2.3. Hành trình của một câu hỏi (chi tiết từng bước)

Ví dụ xuyên suốt là **Demo 5**:

> *"Tôi là nam, cao 1m72 nặng 65kg, muốn mua đồ chạy bộ màu đen dưới 500k."*

#### Sơ đồ tổng quát

```
 NGƯỜI DÙNG                           HỆ THỐNG
 ──────────                           ────────
 ① Gõ câu hỏi, bấm Gửi
 ② Thấy tin nhắn của mình +  ──────► ③ Nhận câu hỏi, kiểm tra hợp lệ
    hiệu ứng "AI đang làm việc"       ④ Lấy lại trí nhớ cuộc trò chuyện
                                      ⑤ HIỂU câu hỏi → "phiếu yêu cầu"
                                      ⑥ Câu hỏi nối tiếp? → ghép với lần trước
                                      ⑦ TƯ VẤN SIZE (nếu có chiều cao/cân nặng)
                                      ⑧ HỎI CHÍNH SÁCH? → tra sổ quy định
                                      ⑨ TÌM HÀNG trong kho (hoặc phối bộ đồ)
                                      ⑩ SOẠN câu trả lời từ hàng tìm được
 ⑫ Thấy câu trả lời + thẻ   ◄──────  ⑪ Gửi kết quả về + ghi nhớ
    sản phẩm, bấm để mua
```

Thường mất **2–5 giây**. Câu đã hỏi trong ngày được trả lời gần như ngay lập tức nhờ bộ nhớ đệm.

#### ① Người dùng gõ câu hỏi

Gõ vào ô **"Nhập câu hỏi của bạn..."** rồi nhấn Enter hoặc nút gửi. Cũng có thể bấm một câu trong mục **Gợi ý cho bạn** hoặc chọn từ nút **▶ Demo**.

#### ② Màn hình phản hồi ngay lập tức

Chưa cần chờ máy chủ, giao diện đã:

- Hiện tin nhắn của khách (bong bóng đỏ bên phải) kèm giờ gửi.
- Thu gọn mục gợi ý để nhường chỗ cho câu trả lời.
- Bật hiệu ứng **"AI đang làm việc"**: quả cầu xoay, dòng chữ đổi lần lượt *Đang đọc hiểu câu hỏi → Đang phân tích nhu cầu → Đang tìm trong kho hàng → Đang chọn sản phẩm phù hợp*, kèm 3 thẻ sản phẩm mờ.
- Khoá nút gửi để tránh gửi trùng.

#### ③ Máy chủ nhận câu hỏi

Trình duyệt gửi câu hỏi lên máy chủ kèm mã bảo mật. Máy chủ kiểm tra câu hỏi không rỗng, không quá 500 ký tự, và người dùng không gửi quá 30 câu/phút (chống spam).

#### ④ Lấy lại "trí nhớ"

Máy chủ mở lại cuộc trò chuyện đang diễn ra (20 tin nhắn gần nhất) và **tiêu chí tìm kiếm lần trước**, để hiểu được các câu nối tiếp như "còn màu trắng không?".

#### ⑤ Hiểu câu hỏi: hai "bộ não" cùng đọc

| Bộ não | Làm gì | Khi nào dùng |
|---|---|---|
| **Bộ đọc tiếng Việt** (nhóm tự viết) | Tách số đo, giá tiền, màu, môn, loại sản phẩm theo luật; hiểu cả câu gõ không dấu | **Luôn chạy** |
| **Gemini AI** (Google) | Hiểu ngôn ngữ tự nhiên, cách diễn đạt đa dạng, ngữ cảnh hội thoại | Khi có mạng và còn lượt dùng |

Hai kết quả được gộp lại: **con số** (giá, chiều cao, cân nặng) lấy theo bộ đọc tiếng Việt vì chính xác; **phân loại** (môn, màu, giới tính...) lấy theo Gemini vì hiểu ngữ cảnh tốt hơn. Kết quả là một **phiếu yêu cầu**:

| Mục | Giá trị |
|---|---|
| Giới tính | Nam |
| Môn thể thao | Chạy bộ |
| Màu | Đen |
| Chiều cao / cân nặng | 172 cm / 65 kg |
| Giá | Tối đa 500.000đ |
| Khách muốn | Xem sản phẩm + tư vấn size |

Nếu Gemini lỗi, mất mạng hoặc hết lượt, phiếu vẫn được điền bằng bộ đọc tiếng Việt, nên chatbot **không bao giờ đứng hình**.

#### ⑥ Có phải câu hỏi nối tiếp không?

Nếu câu mới chỉ là *"Còn màu trắng không?"*, hệ thống lấy phiếu lần trước (nam, chạy bộ, dưới 500k...) rồi **chỉ đổi màu sang trắng**. Câu *"rẻ hơn"* thì hạ mức giá xuống dưới mẫu rẻ nhất vừa đưa ra. Demo 5 là câu hỏi mới nên bỏ qua bước này.

#### ⑦ Tư vấn size

Khách có nêu chiều cao và cân nặng nên hệ thống tra **bảng size nam**: 1m72 ứng với size L, 65kg ứng với size L, vậy kết luận **size L**. Nếu hai chỉ số lệch nhau, shop ưu tiên theo cân nặng.

Size L được ghi thêm vào phiếu để lọc hàng ở bước ⑨.

#### ⑧ Có hỏi về chính sách không?

Nếu khách hỏi phí ship, đổi trả, thanh toán, liên hệ..., hệ thống tra **sổ quy định** (khớp với chính sách đăng trên website). Mã giảm giá và danh mục sản phẩm được đọc trực tiếp từ hệ thống. Demo 5 không hỏi phần này.

#### ⑨ Vào kho tìm hàng

Hệ thống tìm trong **kho dữ liệu sản phẩm thật** của website, chỉ giữ sản phẩm thoả **tất cả** điều kiện:

1. Đang bán (đã kích hoạt)
2. Dành cho nam (hoặc unisex)
3. Môn chạy bộ
4. Giá sau khuyến mãi ≤ 500.000đ
5. **Cùng một phiên bản phải vừa màu đen, vừa size L, vừa còn hàng**

Sau đó **chấm điểm** để xếp hạng (đúng mục đích, đúng kiểu dáng, sản phẩm nổi bật, đang giảm giá...) và chọn **6 sản phẩm** tốt nhất.

- **Không đủ hàng:** hệ thống nới dần điều kiện (bỏ size → bỏ màu → bỏ giá) và **ghi lại đã nới tiêu chí nào** để nói thật với khách. Giới tính và môn thể thao không bao giờ bị nới.
- **Khách cần "một bộ đồ"** (như Demo 4): hệ thống tìm áo và quần riêng, ghép thành từng cặp **cùng giới tính** có tổng tiền vừa ngân sách, rồi chọn 3 bộ hợp lý nhất.

#### ⑩ Soạn câu trả lời

1. Hệ thống tự soạn **bản nháp** từ đúng số hàng vừa tìm được: *"Với cao 1m72, nặng 65kg, bạn nên mặc size L... Mình tìm được 6 sản phẩm chạy bộ nam màu đen dưới 500.000đ..."*
2. Nếu còn lượt, **Gemini viết lại** bản nháp cho tự nhiên, thân thiện hơn, kèm quy định **không được thêm** sản phẩm, giá hay chính sách nào khác.
3. Thẻ sản phẩm luôn lấy **trực tiếp từ kho**, nên giá và đường link luôn đúng.

#### ⑪ Gửi kết quả về và ghi nhớ

Máy chủ trả về: câu trả lời; 6 thẻ sản phẩm (ảnh, giá, % giảm, màu, size, link); dòng **"AI hiểu bạn cần"** (chính là phiếu yêu cầu ở bước ⑤); các gợi ý hỏi tiếp ("Còn màu trắng không?"...); và nguồn phân tích (Gemini AI hay bộ NLP tiếng Việt). Đồng thời phiếu được lưu lại làm trí nhớ cho câu hỏi kế tiếp.

#### ⑫ Người dùng thấy kết quả

Hiệu ứng chờ biến mất, câu trả lời trượt lên. Màn hình tự cuộn để **câu hỏi nằm trên cùng**, khách đọc câu trả lời từ đầu:

- Lời tư vấn: size L và danh sách sản phẩm
- Ô **"AI hiểu bạn cần"**: *Giới tính: Nam · Môn: Chạy bộ · Màu: đen · Size đề xuất: L · Giá: dưới 500.000đ*
- Hàng thẻ sản phẩm vuốt ngang. **Bấm vào thẻ để mở trang sản phẩm và đặt mua**; khung chat vẫn giữ nguyên cuộc trò chuyện.

#### Các nhánh đặc biệt

| Tình huống | Hệ thống làm gì | Người dùng thấy |
|---|---|---|
| Hỏi ngoài phạm vi ("Thời tiết hôm nay?") | Không tìm hàng, không đoán | Lời từ chối lịch sự kèm gợi ý câu nên hỏi |
| Hỏi món shop không bán ("đồ bơi") | Không tìm hàng | "Shop chưa kinh doanh sản phẩm dành cho bơi lội" |
| Có hàng nhưng thiếu tiêu chí ("quần short màu hồng") | Nới tiêu chí màu | "Chưa có sản phẩm đúng **màu hồng**", kèm các mẫu gần nhất |
| Mất mạng / Gemini hết lượt | Dùng bộ đọc tiếng Việt và câu trả lời soạn sẵn | Vẫn có kết quả đúng; dòng nguồn ghi "bộ NLP tiếng Việt" |
| Gửi quá nhanh / máy chủ lỗi | Báo lỗi, giữ lại câu hỏi | Thông báo thân thiện, câu hỏi còn trong ô nhập để gửi lại |

> **Câu chốt khi trình bày:** AI chỉ làm hai việc là *hiểu câu hỏi* và *nói cho dễ nghe*. Sản phẩm, giá, size đều lấy từ kho dữ liệu thật của shop, nên chatbot không thể "bịa" ra hàng không có.

---

## 3. Cài đặt và chạy trên XAMPP

### 3.1. Yêu cầu

- **XAMPP** có PHP 8.1 trở lên (đã thử với XAMPP PHP 8.2.12 + MariaDB 10.4).
- **Composer** và **Node.js** (để cài thư viện và build giao diện).
- Trong `C:\xampp\php\php.ini` phải bật (bỏ dấu `;` ở đầu dòng) các extension: `extension=gd`, `extension=intl`, `extension=zip` (cùng `curl`, `fileinfo`, `mbstring`, `openssl`, `pdo_mysql` đã bật sẵn). Sửa xong phải **Stop → Start** lại Apache.

### 3.2. Đã thiết lập sẵn trên máy bàn giao

| Hạng mục | Giá trị |
|---|---|
| Database web | `modesport` (MySQL của XAMPP, user `root`, không mật khẩu) |
| Database chạy test | `modesport_test` (test **không** đụng vào dữ liệu web) |
| `php.ini` | Đã bật `gd`, `intl`, `zip` – bản gốc lưu ở `C:\xampp\php\php.ini.bak-before-modesport` |
| Apache | Thêm VirtualHost cổng **8080** trỏ vào thư mục `public` trong `C:\xampp\apache\conf\extra\httpd-vhosts.conf` – bản gốc lưu ở `httpd-vhosts.conf.bak-before-modesport` |
| File `.env` | Đã cấu hình database, Gemini key, chế độ Demo |

### 3.3. Cài trên máy mới (từ mã nguồn)

Mở terminal tại thư mục `shop-clothes`:

```bash
composer install
npm install
npm run build

copy .env.example .env          # Windows (macOS/Linux: cp .env.example .env)
php artisan key:generate
```

Tạo database rỗng tên `modesport` (bảng mã `utf8mb4_unicode_ci`) bằng phpMyAdmin (http://localhost/phpmyadmin), sửa `.env`:

```
DB_DATABASE=modesport
DB_USERNAME=root
DB_PASSWORD=
GEMINI_API_KEY=...      # xem mục 7.1
```

Sau đó tạo bảng + dữ liệu mẫu:

```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

> `migrate:fresh --seed` **xoá toàn bộ dữ liệu cũ** rồi tạo lại: 100 sản phẩm đầy đủ thuộc tính, danh mục, size, màu, tài khoản mẫu, mã giảm giá, flash sale...

### 3.4. Các biến cấu hình trong `.env`

| Biến | Giá trị đang dùng | Ý nghĩa |
|---|---|---|
| `GEMINI_API_KEY` | (key của bạn) | Key Google AI Studio. Để trống = chatbot chạy chế độ dự phòng, không dùng Gemini |
| `GEMINI_MODEL` | `gemini-flash-lite-latest` | Model dùng để **hiểu câu hỏi** (gói miễn phí: 15 lượt/phút) |
| `GEMINI_REPLY_MODEL` | `gemini-3.5-flash` | Model dùng để **viết lại câu trả lời** cho tự nhiên (gói miễn phí: 5 lượt/phút) |
| `GEMINI_THINKING_LEVEL` | `low` | Mức "suy nghĩ" của model Gemini 3.x – `low` cho phản hồi nhanh |
| `CHATBOT_LLM_REPLY` | `true` | `false` = không cho Gemini viết lại câu trả lời (mỗi câu hỏi chỉ tốn 1 lượt API) |
| `CHATBOT_DEMO_MODE` | `true` | Hiện nút **▶ Demo** chứa 5 tình huống demo. Đặt `false` để ẩn với khách thường |
| `MAIL_MAILER` | `log` | Email đặt hàng được ghi vào `storage/logs/laravel.log` thay vì gửi thật. Muốn gửi thật thì điền SMTP (Gmail...) |
| `DEBUGBAR_ENABLED` | `false` | Tắt thanh Laravel Debugbar (che giao diện khi demo) |

Sau khi sửa `.env` luôn chạy: `php artisan config:clear`

### 3.5. Chạy website

**Cách 1 – Apache của XAMPP (khuyên dùng khi demo):** bật Apache + MySQL trong XAMPP Control Panel rồi mở **http://localhost:8080**. Không cần mở terminal.

Nếu cài trên máy khác, thêm vào cuối `C:\xampp\apache\conf\extra\httpd-vhosts.conf` (sửa đường dẫn cho đúng máy), rồi **Stop → Start** Apache:

```apache
Listen 8080
<VirtualHost *:8080>
    ServerName localhost
    DocumentRoot "C:/BTL/shop/shop-clothes/public"
    <Directory "C:/BTL/shop/shop-clothes/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**Cách 2 – Server của Laravel:** bật MySQL trong XAMPP, mở terminal tại `shop-clothes`:

```bash
php artisan serve
```

rồi mở **http://127.0.0.1:8000** (giữ cửa sổ terminal mở trong suốt lúc dùng).

### 3.6. Tài khoản mẫu

| Vai trò | Email | Mật khẩu |
|---|---|---|
| Quản trị (admin) | `admin@sportswear.shop` | `password123` |
| Khách hàng | `customer.a@shop.local` | `12345678` |

Chatbot dùng được cả khi **chưa đăng nhập**.

---

## 4. Sử dụng khung chat

### 4.1. Mở khung chat

- Bấm nút đỏ **"Tư vấn AI"** ở góc phải dưới màn hình (trên điện thoại là nút tròn).
- Máy tính: khung chat mở thành **thanh bên phải** cao hết màn hình. Điện thoại: mở **toàn màn hình**.
- Các nút trên thanh tiêu đề:
  - **▶ Demo** – danh sách 5 tình huống demo (chỉ hiện khi `CHATBOT_DEMO_MODE=true`)
  - **↻** – bắt đầu cuộc trò chuyện mới (xoá hội thoại và ngữ cảnh)
  - **✕** – đóng khung chat (hoặc phím **Esc**)

### 4.2. Đặt câu hỏi

- Gõ câu hỏi vào ô **"Hỏi về sản phẩm, size, giá..."** rồi nhấn **Enter** hoặc nút mũi tên (tối đa 500 ký tự).
- Hoặc bấm một câu trong mục **"Gợi ý cho bạn"** ngay trên ô nhập. Sau khi gửi câu hỏi, mục này tự thu gọn để nhường chỗ cho câu trả lời – bấm **Hiện** để mở lại.
- Sau mỗi câu trả lời, phía đầu mục gợi ý có thêm các câu hỏi tiếp theo (viền nét đứt), ví dụ "Còn mẫu nào rẻ hơn không?".

### 4.3. Câu trả lời gồm những gì

1. **Lời tư vấn** bằng văn bản (có in đậm, gạch đầu dòng).
2. **"AI hiểu"** – các thẻ nhỏ cho biết AI đã phân tích được gì: giới tính, môn, loại sản phẩm, màu, size đề xuất, khoảng giá... (rất hữu ích khi trình bày).
3. **Thẻ sản phẩm** (vuốt ngang để xem thêm): ảnh, giá, giá gốc gạch ngang và % giảm, chấm màu còn hàng, size còn hàng, nút **Xem sản phẩm**.
4. **Bộ đồ** (khi hỏi "một bộ đồ"): 2 sản phẩm áo + quần và **tổng tiền**.
5. **Nút liên kết** tới trang chính sách đổi trả, liên hệ, tra cứu đơn hàng (khi hỏi các câu đó).
6. Dòng chữ nhỏ cuối câu trả lời cho biết nguồn phân tích: **"Phân tích bởi Gemini AI"** hoặc **"Phân tích bởi bộ NLP tiếng Việt"** (chế độ dự phòng).

### 4.4. Những câu chatbot hiểu được

| Nhu cầu | Ví dụ câu hỏi |
|---|---|
| Tìm theo môn, loại, giới tính | "Tìm áo bóng đá nam", "giày chạy bộ nữ", "đồ yoga nữ" |
| Lọc theo giá | "áo gym dưới 300k", "quần chạy bộ từ 200k đến 400k", "giày trên 1 triệu", "áo khoác không quá 1tr2" |
| Lọc theo màu, size | "Có quần short thể thao màu đen không?", "giày chạy bộ nam size 42" |
| Kiểu dáng, mục đích | "áo form rộng đi chơi", "áo bóng đá thi đấu", "quần ôm tập gym" |
| Tư vấn size | "Nam 1m75, 70kg nên mặc size gì?", "nữ cao 158 nặng 50kg", "bảng size" |
| Phối cả bộ theo ngân sách | "Tôi cần một bộ đồ để chạy bộ khoảng 500k", "Tôi có 500 nghìn, tư vấn cho tôi một bộ đồ tập" |
| Kết hợp nhiều điều kiện | "Tôi là nam, cao 1m72 nặng 65kg, muốn mua đồ chạy bộ màu đen dưới 500k" |
| Phụ kiện, dụng cụ | "tất chạy bộ", "thảm yoga", "tạ tay", "nón chạy bộ", "quần lót nam" |
| Hỏi tiếp | "Còn màu trắng không?", "Còn mẫu nào rẻ hơn không?", "Còn bộ nào rẻ hơn không?" |
| Thông tin cửa hàng | "Phí ship bao nhiêu?", "Chính sách đổi trả thế nào?", "Thanh toán bằng gì?", "Shop có mã giảm giá không?", "Shop bán những gì?" |
| Gõ không dấu | "tim ao bong da nam duoi 300k mau den" |

Cách diễn đạt giá: **"dưới X"** = tối đa X; **"trên X"** = từ X trở lên; **"có X"** (ngân sách) = tối đa X; **"khoảng / tầm X"** = cho phép vượt tối đa 10%.

Những câu chatbot **từ chối** (đúng yêu cầu số 7): câu hỏi không liên quan đến shop (thời tiết, kiến thức chung...), sản phẩm cho môn shop không bán (bơi lội, võ thuật, golf, đạp xe...).

### 4.5. Lịch sử hội thoại

- Hội thoại được giữ trong **phiên trình duyệt** (tối đa 40 tin nhắn gần nhất): chuyển trang, bấm vào sản phẩm vẫn còn.
- Server nhớ 20 tin nhắn gần nhất và tiêu chí tìm kiếm lượt trước để hiểu câu hỏi nối tiếp (hết hạn sau 120 phút không hoạt động).
- Đóng tab/trình duyệt hoặc bấm **↻** là hội thoại bị xoá. Hiện chưa lưu lịch sử chat vào database.

---

## 5. Chế độ Demo – 5 tình huống bắt buộc

Bấm **▶ Demo** trên thanh tiêu đề khung chat → bấm một tình huống để gửi ngay câu hỏi. Hoặc dùng phím tắt **Alt+1 … Alt+5** (gửi được cả khi khung chat đang đóng).

| Phím tắt | Tình huống | Câu hỏi (đúng như đề bài) | Kết quả mong đợi – điểm cần chỉ ra khi trình bày |
|---|---|---|---|
| Alt+1 | Demo 1 – Hỏi sản phẩm | "Tìm áo bóng đá nam." | 6 áo bóng đá nam đang bán. Thẻ "AI hiểu": Nam · Bóng đá · Áo |
| Alt+2 | Demo 2 – Lọc theo giá | "Tìm cho tôi áo thể thao nam dưới 300k." | 6 áo nam, **tất cả giá ≤ 300.000đ** (giá sau khuyến mãi) |
| Alt+3 | Demo 3 – Tư vấn size | "Nam 1m75, 70kg nên mặc size gì?" | Đề xuất **size L**: chiều cao 1m75 ứng với XL, cân nặng 70kg ứng với L; shop ưu tiên cân nặng; thích mặc rộng thì chọn XL |
| Alt+4 | Demo 4 – Tư vấn theo nhu cầu | "Tôi cần một bộ đồ để chạy bộ khoảng 500k." | **3 bộ đồ chạy bộ** (áo + quần cùng giới tính), mỗi bộ có tổng tiền ≈ 500.000đ (tối đa 550.000đ vì "khoảng") |
| Alt+5 | Demo 5 – Kết hợp nhiều điều kiện | "Tôi là nam, cao 1m72 nặng 65kg, muốn mua đồ chạy bộ màu đen dưới 500k." | Đề xuất **size L** + 6 sản phẩm chạy bộ **nam**, **còn màu đen size L**, giá **≤ 500.000đ**. Thẻ "AI hiểu" hiện đủ 5 điều kiện |

Mẹo khi trình bày:

- Bấm **↻** trước khi bắt đầu, để câu hỏi trước không bị hiểu là câu hỏi nối tiếp.
- Sau Demo 1 có thể hỏi thêm **"Còn màu trắng không?"** để khoe khả năng hiểu ngữ cảnh.
- Bấm một thẻ sản phẩm để chứng minh chatbot **dẫn khách đến trang sản phẩm** (khung chat vẫn mở với đầy đủ hội thoại).
- Hỏi **"Shop có đồ bơi không?"** hoặc **"Thời tiết hôm nay thế nào?"** để chứng minh chatbot **không trả lời tuỳ tiện**.
- Chạy thử toàn bộ 5 tình huống trước buổi bảo vệ: kết quả Gemini được lưu cache 1 ngày nên lúc bảo vệ trả lời ngay, không tốn hạn mức.

---

## 6. Quản lý dữ liệu cho AI

Chatbot **chỉ** tư vấn dựa trên dữ liệu website, vì vậy dữ liệu sản phẩm càng đầy đủ, tư vấn càng chính xác.

### 6.1. Thuộc tính sản phẩm (trang quản trị)

Đăng nhập admin → **Sản phẩm** → **Thêm/Sửa sản phẩm**. Ngoài các trường cũ, có thêm 4 ô dùng cho AI:

| Ô | Giá trị |
|---|---|
| Giới tính | Nam / Nữ / Unisex (sản phẩm Unisex xuất hiện khi khách hỏi đồ nam lẫn đồ nữ) |
| Môn thể thao | Bóng đá, Chạy bộ, Gym/Thể hình, Yoga, Bóng rổ, Cầu lông, Tennis |
| Mục đích sử dụng | Tập luyện, Thi đấu, Mặc hằng ngày/dạo phố |
| Kiểu dáng | Ôm body (Slim), Vừa vặn (Regular), Thể thao (Athletic), Rộng rãi (Relaxed/Oversize) |

Ngoài ra AI còn dùng:

- **Danh mục** để biết loại sản phẩm (áo, áo khoác, quần short, jogger, giày, tất, nón, túi, thảm...).
- **Biến thể** (size × màu × tồn kho): chỉ biến thể **còn hàng** mới được tính khi lọc theo màu/size.
- **Giá khuyến mãi**: khi có giá khuyến mãi, AI lọc theo giá khuyến mãi.
- **Kích hoạt**: sản phẩm tắt kích hoạt sẽ không được gợi ý.

> Sản phẩm thiếu thuộc tính (ví dụ không chọn môn thể thao) sẽ không xuất hiện khi khách lọc theo thuộc tính đó.

### 6.2. Dữ liệu mẫu

`database/seeders/ProductSeeder.php` tạo **100 sản phẩm cố định** (không ngẫu nhiên – mỗi lần seed giống nhau để demo ổn định), đủ 7 môn, nam/nữ/unisex, có giày (size 36–45) và phụ kiện (Free Size). Muốn tạo lại toàn bộ dữ liệu: `php artisan migrate:fresh --seed` (xoá hết dữ liệu cũ, kể cả đơn hàng và tài khoản đã tạo thêm).

> Ảnh sản phẩm mẫu là ảnh minh hoạ ngẫu nhiên (picsum.photos), không phải ảnh quần áo thật. Nên tải ảnh thật cho các sản phẩm dùng trong demo qua trang admin.

### 6.3. Bảng size, thông tin cửa hàng, câu trả lời chung

Sửa trong `config/chatbot.php` (sửa xong chạy `php artisan config:clear`):

| Mục | Nội dung |
|---|---|
| `size_chart` | Bảng size nam/nữ theo chiều cao (cm) và cân nặng (kg). AI ưu tiên cân nặng; nếu chiều cao lệch từ 2 size trở lên thì tăng 1 size |
| `store` | Tên shop, hotline, email, địa chỉ, phí ship, ngưỡng miễn phí ship |
| `faq` | Câu trả lời về phí ship, đổi trả, thanh toán, liên hệ, đơn hàng |
| `faq_links` | Nút liên kết kèm câu trả lời |
| `max_products`, `max_outfits` | Số sản phẩm (6) và số bộ đồ (3) hiển thị tối đa |

> Nội dung `faq` hiện khớp với trang **Chính sách đổi trả** và logic thanh toán của web (phí ship 30.000đ, miễn phí từ 500.000đ, thanh toán COD). Nếu đổi chính sách trên web, nhớ sửa cả ở đây.

Mã giảm giá và danh mục sản phẩm được đọc **trực tiếp từ database**, không cần sửa cấu hình.

### 6.4. Câu hỏi gợi ý và tình huống demo

Sửa trong `app/Services/Chatbot/ChatbotService.php`:

- `SUGGESTIONS` – các câu trong mục **"Gợi ý cho bạn"**.
- `demoCases()` – 5 tình huống trong nút **▶ Demo** (tên, câu hỏi, kết quả mong đợi).

---

## 7. Gemini API: key, hạn mức, chế độ dự phòng

### 7.1. Lấy và thay API key

1. Vào **https://aistudio.google.com/apikey** (đăng nhập Google) → **Create API key**.
2. Dán vào `.env`: `GEMINI_API_KEY=...` → chạy `php artisan config:clear`.

> **Bảo mật:** key chỉ để trong file `.env` (file này không được đưa lên Git). Không gửi key qua tin nhắn/email công khai; nếu đã lộ, vào trang trên để xoá key cũ và tạo key mới.

### 7.2. Hạn mức miễn phí

Đo thực tế với key miễn phí:

| Model | Hạn mức | Dùng cho |
|---|---|---|
| `gemini-flash-lite-latest` | 15 lượt/phút | Hiểu câu hỏi (bắt buộc) |
| `gemini-3.5-flash` | 5 lượt/phút | Viết lại câu trả lời cho tự nhiên (không bắt buộc) |

Gói miễn phí còn giới hạn theo ngày – xem tại https://aistudio.google.com/rate-limit.

- Mỗi câu hỏi tốn tối đa 2 lượt (1 hiểu câu hỏi + 1 viết lại câu trả lời).
- Câu hỏi **giống hệt** trong vòng 1 ngày được lấy từ cache, **không tốn lượt**.
- Muốn tăng hạn mức: trong AI Studio chọn **Set up billing** (trả theo lượng dùng; với lượng câu hỏi khi demo chi phí không đáng kể). Tạo thêm key trong cùng project **không** tăng hạn mức.

### 7.3. Chế độ dự phòng (không cần Internet)

Khi **không có key**, **mất mạng**, **hết hạn mức** hoặc Gemini lỗi, chatbot tự chuyển sang **bộ phân tích tiếng Việt tự viết** – vẫn chạy đúng cả 5 tình huống demo, chỉ khác là câu trả lời dùng mẫu soạn sẵn thay vì do Gemini viết. Dòng chữ nhỏ cuối câu trả lời sẽ hiện **"Phân tích bởi bộ NLP tiếng Việt"**.

Khi hạn mức của model viết lại câu trả lời hết trước, câu trả lời vẫn được Gemini phân tích nhưng dùng mẫu soạn sẵn – nội dung vẫn đúng.

---

## 8. Cách chatbot hoạt động (dùng cho báo cáo)

### 8.1. Luồng xử lý một câu hỏi

```
Khách nhập câu hỏi (khung chat – resources/views/components/chatbot.blade.php)
        │  POST /chatbot/message
        ▼
ChatbotController ── lưu lịch sử + tiêu chí lượt trước trong session
        ▼
ChatbotService (điều phối)
  1. Hiểu câu hỏi
       ├─ GeminiClient: Gemini trả về tiêu chí dạng JSON (structured output)
       └─ QueryParser: bộ phân tích tiếng Việt theo luật (dự phòng + chuẩn hoá số liệu)
  2. Ghép ngữ cảnh câu hỏi nối tiếp ("còn màu trắng không?")
  3. SizeAdvisor        – đề xuất size theo bảng size
  4. StoreKnowledge     – phí ship, đổi trả, thanh toán, mã giảm giá, danh mục
  5. ProductRecommender – truy vấn sản phẩm THẬT trong database, xếp hạng, phối bộ đồ theo ngân sách
  6. Soạn câu trả lời từ dữ liệu đã truy vấn; Gemini chỉ diễn đạt lại cho tự nhiên
        ▼
JSON: câu trả lời + sản phẩm + bộ đồ + "AI hiểu" + gợi ý hỏi tiếp
```

### 8.2. Kết hợp Gemini và bộ phân tích tiếng Việt

- **Thuộc tính** (giới tính, môn, loại sản phẩm, màu...): ưu tiên kết quả Gemini vì hiểu ngữ cảnh tốt hơn.
- **Con số** (giá, chiều cao, cân nặng): ưu tiên bộ phân tích luật vì chính xác và nhất quán.

### 8.3. Vì sao chatbot không "bịa" thông tin

1. Tên, giá, màu, size, tồn kho sản phẩm **luôn lấy từ database**; thẻ sản phẩm hiển thị trực tiếp từ dữ liệu này.
2. Gemini chỉ được dùng để **phân tích câu hỏi** và **diễn đạt lại** câu trả lời đã soạn từ dữ liệu, với yêu cầu không thêm sản phẩm, giá hay chính sách nào khác.
3. Không có sản phẩm khớp hoàn toàn: hệ thống nới lần lượt size → màu → kiểu → giá và **nói rõ tiêu chí nào chưa đáp ứng**. Riêng khi khách hỏi "rẻ hơn", hệ thống giữ màu/size và chỉ nới giá.
4. Câu hỏi ngoài phạm vi hoặc môn shop không bán: từ chối lịch sự, không đoán.

### 8.4. Các file chính

| File | Vai trò |
|---|---|
| `app/Services/Chatbot/ChatbotService.php` | Điều phối, soạn câu trả lời, câu hỏi gợi ý, 5 tình huống demo |
| `app/Services/Chatbot/QueryParser.php` | Phân tích câu hỏi tiếng Việt |
| `app/Services/Chatbot/GeminiClient.php` | Gọi Gemini API (có cache, tự xử lý lỗi) |
| `app/Services/Chatbot/ProductRecommender.php` | Tìm sản phẩm, xếp hạng, phối bộ đồ |
| `app/Services/Chatbot/SizeAdvisor.php` | Tư vấn size |
| `app/Services/Chatbot/StoreKnowledge.php` | Thông tin cửa hàng, mã giảm giá, danh mục |
| `app/Http/Controllers/ChatbotController.php` | API `/chatbot/message`, `/chatbot/reset` |
| `app/Support/ProductAttributes.php` | Danh sách giới tính / môn / mục đích / kiểu dáng |
| `config/chatbot.php` | Cấu hình Gemini, bảng size, thông tin cửa hàng |
| `resources/views/components/chatbot.blade.php` | Giao diện khung chat |
| `database/migrations/2026_10_12_000001_add_ai_attributes_to_products_table.php` | Thêm 4 cột thuộc tính AI cho bảng `products` |
| `database/seeders/ProductSeeder.php` | 100 sản phẩm mẫu đầy đủ thuộc tính |

Từ khoá tra nhanh trong code (Ctrl + Shift + F): `CHATBOT_AI`, `PHAN_TICH_CAU_HOI`, `GOI_Y_SAN_PHAM`, `TU_VAN_SIZE` – xem `muc_luc_code.md`.

---

## 9. Kiểm thử tự động

```bash
php artisan test tests/Unit tests/Feature/ChatbotTest.php
```

Gồm 32 test: phân tích câu hỏi (có dấu/không dấu, giá, size giày, phụ kiện...), tư vấn size, đủ 5 tình huống demo, câu hỏi nối tiếp, không bịa thông tin, dữ liệu seed đủ 100 sản phẩm, nhánh Gemini (giả lập) và tự chuyển chế độ dự phòng khi Gemini lỗi.

> Test dùng database riêng `modesport_test` (cấu hình trong `.env.testing`), không ảnh hưởng dữ liệu web. Trên máy mới hãy tạo database này trước, hoặc tạo `.env.testing` trỏ tới một database trống khác – **tuyệt đối không** để test chạy trên database thật vì test sẽ xoá dữ liệu.

---

## 10. Xử lý sự cố

| Hiện tượng | Nguyên nhân / cách xử lý |
|---|---|
| Không thấy nút "Tư vấn AI" | Trang không dùng layout chính, hoặc trình duyệt còn cache cũ → tải lại trang (Ctrl+F5) |
| Không thấy nút **▶ Demo** | `CHATBOT_DEMO_MODE=false` → đặt `true`, chạy `php artisan config:clear` |
| Câu trả lời ghi "Phân tích bởi bộ NLP tiếng Việt" | Chưa có key, mất mạng hoặc hết hạn mức Gemini. Chatbot vẫn chạy đúng; đợi 1 phút rồi hỏi tiếp hoặc kiểm tra key |
| "Bạn gửi tin nhắn hơi nhanh..." | Gửi quá 30 tin/phút → đợi một chút |
| "Phiên làm việc đã hết hạn..." | Để trang quá lâu → tải lại trang |
| "Xin lỗi, hệ thống đang bận..." | Lỗi server → xem `storage/logs/laravel.log` |
| Không có sản phẩm nào được gợi ý | Chưa seed dữ liệu (`php artisan migrate:fresh --seed`) hoặc sản phẩm thiếu thuộc tính / hết hàng |
| Ảnh sản phẩm không hiện | Ảnh mẫu tải từ Internet; không có mạng thì hiện ảnh thay thế. Tải ảnh thật qua admin |
| Lỗi `could not find driver` / `ext-gd` khi cài | Chưa bật extension trong `php.ini` (mục 3.1) → bật và khởi động lại Apache |
| `localhost:8080` không mở được | Chưa Stop → Start Apache sau khi thêm VirtualHost, hoặc cổng 8080 bị chương trình khác dùng |
| Sửa `.env` / `config/chatbot.php` không có tác dụng | Chạy `php artisan config:clear` |
| Gemini báo lỗi model không còn hỗ trợ | Google ngừng model cũ → đổi `GEMINI_MODEL` / `GEMINI_REPLY_MODEL` sang model mới trong AI Studio, chạy `php artisan config:clear` |
