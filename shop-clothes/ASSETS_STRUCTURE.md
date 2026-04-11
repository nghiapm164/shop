# 📁 Assets Structure - Shop Nam

## Cấu trúc thư mục Assets

```
shop-clothes/
├── resources/
│   ├── assets/
│   │   ├── css/
│   │   │   └── app.css          # Main stylesheet với tất cả styles
│   │   └── js/
│   │       └── app.js           # Main JavaScript cho interactions & carousel
│   ├── views/
│   │   ├── layouts/
│   │   │   └── shop.blade.php   # Main layout (liên kết với Vite)
│   │   └── welcome.blade.php    # Homepage
│   └── ...
│
├── public/
│   ├── images/                  # Thư mục cho images (nếu cần)
│   └── ...
│
├── vite.config.js               # Cấu hình Vite build tool
├── package.json                 # Dependencies & scripts
└── ...
```

## 🚀 Cách sử dụng

### 1. **Development**
Chạy Vite dev server để auto-reload khi thay đổi files:
```bash
npm run dev
```

### 2. **Build cho Production**
Build & minify tất cả assets:
```bash
npm run build
```

### 3. **Thêm CSS/JS mới**
- **Styles**: Thêm CSS mới vào `resources/assets/css/app.css`
- **JavaScript**: Thêm JS mới vào `resources/assets/js/app.js`
- **Images**: Upload images vào `public/images/` 

### 4. **Link Assets trong Blade Templates**
```blade
<!-- Vite sẽ tự động inject -->
@vite(['resources/assets/css/app.css', 'resources/assets/js/app.js'])

<!-- Static images -->
<img src="{{ asset('images/filename.jpg') }}" alt="description">
```

## 📦 CSS Organization

### Sections trong `app.css`:
- ✅ Reset & Base Styles
- ✅ Layout (page-shell, main-content, container)
- ✅ Header & Navigation
- ✅ Carousel
- ✅ Hero Banner
- ✅ Categories
- ✅ Product Grid
- ✅ Buttons
- ✅ Promotions
- ✅ Footer
- ✅ Responsive Design

## 🎯 JavaScript Features dalam `app.js`:

### Carousel
- Auto-rotate carousel mỗi 5 giây
- Click dot để navigate
- Smooth transitions

### Interactions
- Category card click handling
- Product card click handling
- Search input handler
- Button ripple effect
- Toast notifications

### Utility Functions
- `scrollToElement()` - Smooth scroll
- `formatCurrency()` - Format tiền VND
- `addToCart()` - Add to cart logic
- `showNotification()` - Show toast messages

## 🎨 Color Scheme

```css
Primary Blue:   #0ea5e9
Dark Blue:      #0086b3
Light Blue BG:  #f0f9ff
Sky Blue:       #e0f2fe
Dark Text:      #0f172a
Gray:           #64748b
Light Gray:     #cbd5e1
```

## 📱 Responsive Breakpoints

- **Desktop**: 1200px+ (default)
- **Tablet**: 768px - 1200px
- **Mobile**: 480px - 768px
- **Small Mobile**: < 480px

## 🔧 Customization Guide

### Thay đổi màu sắc chính
Tìm `#0ea5e9` trong `app.css` và thay thế bằng màu mới

### Thêm font chữ mới
Update font-family trong html selector

### Điều chỉnh spacing
Thay đổi `padding` và `margin` giá trị

## ⚡ Performance Tips

1. **Minimize bloat**: Xóa CSS/JS không dùng
2. **Image optimization**: Compress images trước khi upload
3. **Lazyload images**: Sử dụng `loading="lazy"` attribute
4. **Cache busting**: Vite tự động handle với @vite directive

## 🐛 Troubleshooting

### Assets không load
- Chạy `npm run build` để build production assets
- Clear Laravel cache: `php artisan cache:clear`
- Reload browser (Ctrl+F5 để hard refresh)

### CSS tidak áp dụng
- Kiểm tra class names trong HTML
- Ensure Vite dev server đang chạy nếu dùng `npm run dev`
- Check browser console cho errors

### JavaScript không hoạt động
- Open browser console (F12) để kiểm tra errors
- Ensure `@vite` directive có trong layout
- Check nếu JavaScript conflicts với HTML

---

**Created**: 2026-04-11  
**Last Updated**: 2026-04-11  
**Version**: 1.0
