## 🏃 Shop Nam - Quần áo thể thao nam

### Giao diện và Cấu trúc Assets

Dự án sử dụng **Vite** làm build tool và **Laravel** làm backend framework.

#### 📂 Cấu trúc thư mục Assets

```
resources/
├── assets/
│   ├── css/
│   │   └── app.css      ← All styles (organized by sections)
│   └── js/
│       └── app.js       ← All JavaScript (carousel, interactions, utilities)
```

#### 🚀 Chạy dự án

**1. Cài đặt dependencies:**
```bash
npm install
```

**2. Development mode (auto-reload):**
```bash
npm run dev
```

Mở http://localhost:5173 (hoặc cổng do Vite cấp)

**3. Production build:**
```bash
npm run build
```

**4. Chạy Laravel server (terminal khác):**
```bash
php artisan serve
```

Mở http://localhost:8000

#### ✨ Tính năng đã implement

✅ **Header** - Sticky navigation với search bar  
✅ **Hero Carousel** - Auto-rotate banner  
✅ **Product Grid** - Responsive grid layout  
✅ **Categories** - Quick category selector  
✅ **Promotional Banners** - Dynamic promo sections  
✅ **Blog Section** - Featured articles  
✅ **Footer** - Comprehensive footer  
✅ **Responsive Design** - Mobile-friendly  

#### 🎨 Customization

- **Colors**: Chỉnh sửa trong `resources/assets/css/app.css`
- **Layout**: Thay đổi grid/flex settings
- **Content**: Update `welcome.blade.php` với content mới
- **Images**: Upload vào `public/images/` folder

#### 📋 Xem chi tiết tại:
[ASSETS_STRUCTURE.md](./ASSETS_STRUCTURE.md)

---

**Status**: ✅ Development ready  
**Last Updated**: 2026-04-11
