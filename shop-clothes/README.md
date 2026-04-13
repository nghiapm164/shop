# Sportswear Shop - Nền tảng thương mại điện tử quần áo thể thao nam

Ứng dụng web bán quần áo thể thao nam được xây dựng bằng Laravel 10 với kiến trúc hiện đại, hỗ trợ quản lý sản phẩm, đơn hàng, người dùng và thanh toán trực tuyến.

## 📋 Yêu cầu hệ thống

- **PHP**: 8.2+
- **Laravel**: 10.x
- **MySQL**: 8.0+
- **Redis**: 6.0+ (tuỳ chọn, nhưng được khuyến nghị)
- **Composer**: 2.0+
- **Node.js**: 16+ (để biên dịch assets)

## 🛠️ Tech Stack

- **Backend**: Laravel 10, PHP 8.2
- **Database**: MySQL 8
- **Cache & Queue**: Redis
- **Frontend**: Tailwind CSS, Blade Templates, Livewire, Alpine.js
- **Authentication**: Laravel Sanctum (API), Session (Web)
- **Permissions**: Spatie Laravel Permission
- **Image Processing**: Intervention Image
- **Development Tools**: Laravel Telescope, Laravel Debugbar

## 📁 Cấu trúc dự án

```
shop-clothes/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Controllers quản lý
│   │   │   ├── Api/            # Controllers API
│   │   │   └── Web/            # Controllers web
│   │   ├── Middleware/         # Middleware
│   │   └── Kernel.php
│   ├── Services/               # Business logic
│   ├── Repositories/           # Data access layer
│   ├── Traits/                 # Reusable traits
│   ├── Models/                 # Eloquent models
│   └── Exceptions/             # Custom exceptions
├── config/                     # Configuration files
├── database/
│   ├── migrations/             # Database migrations
│   ├── factories/              # Model factories
│   └── seeders/                # Database seeders
├── public/                     # Public assets
├── resources/
│   ├── views/                  # Blade templates
│   ├── css/                    # Stylesheets
│   └── js/                     # JavaScript files
├── routes/
│   ├── web.php                 # Web routes
│   ├── api.php                 # API routes
│   └── console.php             # Console routes
├── storage/                    # Storage & logs
├── tests/                      # Unit & feature tests
└── composer.json               # PHP dependencies
```

## 🚀 Cài đặt

### 1. Clone repository
```bash
git clone <repository-url>
cd shop-clothes
```

### 2. Cài đặt dependencies
```bash
composer install
npm install
```

### 3. Cấu hình môi trường
```bash
cp .env.example .env
php artisan key:generate
```

Chỉnh sửa file `.env` với các thông tin:
```env
APP_NAME=SportswearShop
APP_ENV=local
APP_KEY=base64:... (tự động sinh)
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sportswear_shop
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=file

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@sportswearshop.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 4. Tạo database
```bash
php artisan migrate
php artisan db:seed
```

### 5. Biên dịch assets
```bash
npm run build
# hoặc để phát triển
npm run dev
```

### 6. Tạo storage symlink
```bash
php artisan storage:link
```

### 7. Khởi động server
```bash
php artisan serve
```

Truy cập ứng dụng tại: `http://localhost:8000`

## 📦 Packages được cài đặt

### Production
- **laravel/sanctum**: API authentication
- **spatie/laravel-permission**: Role & permission management
- **intervention/image**: Image processing & manipulation

### Development
- **laravel/telescope**: Debug & monitoring tool
- **barryvdh/laravel-debugbar**: Development toolbar

## 🔒 Quản lý quyền truy cập

Dự án sử dụng **Spatie Laravel Permission** để quản lý roles và permissions:

```php
// Tạo role
$role = Role::create(['name' => 'admin']);

// Gán permission
$role->givePermissionTo('create-product');

// Gán role cho user
$user->assignRole('admin');
```

## 🖼️ Xử lý hình ảnh

Sử dụng **Intervention Image** để xử lý hình ảnh sản phẩm:

```php
use Intervention\Image\ImageManager;

$manager = new ImageManager(config('image.driver'));
$image = $manager->read('path/to/image.jpg');
$image->scale(height: 300);
$image->save('path/to/save.jpg');
```

## 🧪 Testing

Chạy unit tests:
```bash
php artisan test
```

Chạy feature tests:
```bash
php artisan test --filter=Feature
```

## 📊 Database Seeding

Tạo dữ liệu mẫu:
```bash
php artisan db:seed
```

## 🔍 Development Tools

### Laravel Telescope
Truy cập dashboard Telescope:
```
http://localhost:8000/telescope
```

### Laravel Debugbar
Toolbar được tự động hiện thị ở footer trang khi `APP_DEBUG=true`

## 🚀 Deployment

### Preparation
```bash
composer install --no-dev
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Environment Variables
Thiết lập đúng các biến môi trường:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `DB_CONNECTION`, `DB_PASSWORD`, etc.
- `CACHE_DRIVER=redis`
- `QUEUE_CONNECTION=redis`

## 📝 Hướng dẫn phát triển

### Tạo Model mới
```bash
php artisan make:model Product -m
```

### Tạo Controller
```bash
php artisan make:controller Admin/ProductController --model=Product
```

### Tạo API Resource
```bash
php artisan make:resource ProductResource
```

### Tạo Service
```bash
php artisan make:class Services/ProductService
```

### Tạo Repository
```bash
php artisan make:class Repositories/ProductRepository
```

## 🐛 Troubleshooting

### Cache issues
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Database issues
```bash
php artisan migrate:reset
php artisan migrate
php artisan db:seed
```

### Permission issues
```bash
chmod -R 775 storage bootstrap/cache
chmod -R 775 public/storage
```

## 📚 Tài liệu tham khảo

- [Laravel Documentation](https://laravel.com/docs)
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)
- [Intervention Image](https://image.intervention.io)
- [Livewire Documentation](https://livewire.laravel.com)
- [Alpine.js Documentation](https://alpinejs.dev)

## 📄 License

MIT License

## 👥 Contributors

Sportswear Shop Team

## 📧 Support

Để được hỗ trợ, vui lòng liên hệ: support@sportswearshop.com
