# Authentication & Authorization Setup - Sportswear Shop

## 🎯 Overview

Complete authentication and authorization system for the Sportswear Shop website with:
- Laravel Breeze (Blade stack) for authentication
- Email verification for new accounts
- "Remember me" for 30 days
- Spatie Laravel Permission for role-based access control
- AdminMiddleware for route protection
- Profile management with address and order history

---

## 🔐 Authentication Setup

### 1. **Registration** (`/register`)

Updates made:
- Added **phone field** to registration form
- Phone validation: `regex:/^(\+\d{1,3}[- ]?)?\d{10,}$/`
- Email verification **required** after registration
- Registered users automatically get **customer role**

**Example Registration:**
```
Name: John Doe
Email: john@example.com
Phone: +84912345678 or 0912345678
Password: SecurePassword123!
```

### 2. **Login** (`/login`)

Features:
- Standard email/password authentication
- **"Remember me" for 30 days** - automatically sets `auth_token` cookie
- Email verification check (unverified users must verify first)
- Rate limiting (5 attempts per minute)

**Implementation:**
```php
// In LoginRequest, remember duration is 43200 minutes (30 days)
$remember = $this->boolean('remember') ? 60 * 24 * 30 : false;
Auth::attempt($this->only('email', 'password'), $remember);
```

### 3. **Email Verification**

Automatic after registration:
- User receives verification email
- Must verify email to access protected areas
- Route: `/email/verify` 
- Verification link in email (valid for 24 hours)
- Resend option available at verification prompt

**Configuration:**
```php
// In User model
class User extends Authenticatable implements MustVerifyEmail
```

---

## 🛡️ Authorization Setup (Spatie Permission)

### **Roles**

| Role | Permissions | Use Case |
|------|-------------|----------|
| **super_admin** | All permissions | System administrator |
| **admin** | manage_products, manage_orders, manage_coupons, view_reports | Admin staff |
| **staff** | manage_orders | Order support staff |
| **customer** | None (frontend only) | Regular users |

### **Permissions**

```php
$permissions = [
    'manage_products',    // View/create/edit/delete products
    'manage_orders',      // View/update orders
    'manage_users',       // Manage user accounts
    'manage_coupons',     // Create/edit coupons
    'view_reports',       // Access reports dashboard
];
```

### **Checking Permissions in Code**

```php
// Check role
if ($user->role === 'admin' || $user->role === 'super_admin') {
    // Allow admin access
}

// Check specific permission (via Spatie)
if ($user->hasPermissionTo('manage_products')) {
    // Allow product management
}

// In Blade routes/views
@if(auth()->user()->role === 'admin')
    <!-- Admin only content -->
@endif
```

---

## 👤 User Model Extensions

### **New Fields**
```php
$user->phone;      // User phone number
$user->address;    // User address
$user->avatar;     // Avatar image path
$user->role;       // Role enum: super_admin, admin, staff, customer
```

### **Role Helper Methods**
```php
$user->isAdmin();       // Check if admin or super_admin
$user->isStaff();       // Check if staff
$user->isCustomer();    // Check if customer
```

### **Relations**
```php
$user->orders();           // All user orders
$user->addresses();        // All user addresses
$user->defaultAddress;     // User's default address
$user->reviews();          // User's reviews
$user->wishlist();         // User's wishlisted products
$user->roles();            // Spatie roles (if using role-based permissions)
```

---

## 🛣️ Administration Routes

### **Admin Middleware**

All admin routes protected with `admin` middleware:

```php
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('users', UserController::class);
    Route::resource('coupons', CouponController::class);
});
```

### **Middleware Protection**

```php
// In HTTP Kernel, registered as:
'admin' => \App\Http\Middleware\AdminMiddleware::class,

// Usage in routes:
Route::get('/admin', fn() => '...')
    ->middleware(['auth', 'admin'])
    ->name('admin.dashboard');
```

### **Middleware Logic**
```php
// Checks:
1. User is authenticated (redirects to login if not)
2. User has 'admin' or 'super_admin' role
3. Returns 403 Unauthorized if role check fails
```

---

## 👥 User Profile Management

### **ProfileController Routes** (Protected with `auth`)

#### 1. **Update Personal Information**
```
PUT /profile/personal
```

Fields:
- `name` (required, string)
- `phone` (required, valid phone format)
- `avatar` (optional, image, max 2MB)

Example:
```php
$user->update([
    'name' => 'New Name',
    'phone' => '+84912345678',
    // avatar uploaded to storage/app/public/avatars/
]);
```

#### 2. **Change Password**
```
PUT /profile/password
```

Validation:
- `current_password` (required, must match current)
- `password` (required, confirmed, strong)
- Password confirmation included

#### 3. **Manage Addresses** (User's shipping addresses)

**List Addresses:**
```
GET /profile/addresses
```

Returns: All addresses with default address highlighted

**Create Address:**
```
POST /profile/addresses/create
GET /profile/addresses/create (form)
```

Fields:
```php
'recipient_name'    // Required, string
'phone'            // Required, valid phone
'province'         // Required
'district'         // Required
'ward'            // Required
'address_detail'   // Required, text
'is_default'       // Optional, boolean (sets as default if true)
```

**Update Address:**
```
PUT /profile/addresses/{id}
GET /profile/addresses/{id}/edit (form)
```

Same validation as create. Can set as default.

**Delete Address:**
```
DELETE /profile/addresses/{id}
```

Authorization: Only address owner can delete

**Set Default Address:**
```php
$address->makeDefault(); // Sets as default, unsets others
```

#### 4. **View Order History**
```
GET /profile/orders
```

Returns: Paginated list (10 per page) of user's orders with:
- Order code
- Status
- Total price
- Created date
- Items count

**View Single Order:**
```
GET /profile/orders/{id}
```

Shows:
- Order details (code, status, payment status)
- Shipping address
- Order items with product details (name, size, color, price, qty)
- Total calculation (subtotal, discount, shipping, total)

Authorization: Only order owner or staff can view

---

## 🔑 Admin Accounts

### **Default Super Admin**

Created during `php artisan db:seed`:

```env
Email: admin@sportswear.shop
Password: password123
Phone: +84912345678
Role: super_admin
```

**Environment Variables** (in `.env`):
```env
ADMIN_EMAIL=admin@sportswear.shop
ADMIN_PASSWORD=password123
ADMIN_PHONE=+84912345678
```

### **Create Additional Admins**

Via Tinker or manually:
```php
$admin = User::create([
    'name' => 'Admin Name',
    'email' => 'admin2@sportswear.shop',
    'phone' => '+84987654321',
    'password' => Hash::make('password'),
    'role' => 'admin', // or 'super_admin', 'staff', 'customer'
    'email_verified_at' => now(),
]);

// Assign role (Spatie)
$admin->assignRole('admin');
```

---

## 📋 Policies

### **AddressPolicy**

```php
// User can CRUD their own addresses
viewAny()  // Can view all their addresses
view()     // Can view specific address (must be owner)
create()   // Can create new address
update()   // Can update address (must be owner)
delete()   // Can delete address (must be owner)
```

### **OrderPolicy**

```php
// User can view orders they own or are admin/staff
viewAny()  // Can view all (filtered by policy in controller)
view()     // Can view if owner OR admin/staff
create()   // Can create (checkout process)
update()   // Only admin/staff can update
delete()   // Cannot delete orders
```

**Usage in Controller:**
```php
$this->authorize('update', $address);  // Throws 403 if unauthorized
$this->authorize('view', $order);      // Checks ownership
```

---

## 🔐 Security Features

✅ **Email Verification** - Required for all new users
✅ **CSRF Protection** - All forms include CSRF token
✅ **Password Hashing** - bcrypt with cost factor
✅ **Remember Me Token** - Secure, 30-day expiration
✅ **Rate Limiting** - Login attempt throttling
✅ **Role-Based Access** - AdminMiddleware on protected routes
✅ **Policy Authorization** - Resource-level permission checks
✅ **Phone Validation** - Regex validation for international numbers
✅ **Avatar Upload** - Files stored in `storage/app/public/avatars/`

---

## 📱 API Ready

User model also includes **Spatie Sanctum** for API token authentication:

```php
// Generate API token
$token = $user->createToken('API Token')->plainTextToken;

// Use in API requests
Authorization: Bearer {token}
```

---

## 🚀 Quick Start

### **1. After Fresh Installation:**
```bash
php artisan migrate
php artisan db:seed   # Creates all roles, permissions, admin account
php artisan storage:link
```

### **2. Test Admin Account:**
- Email: `admin@sportswear.shop`
- Password: `password123`
- Navigate to `/admin` (or define in routes)

### **3. Create Customer Account:**
- Go to `/register`
- Fill all fields including phone
- Verify email from inbox
- Login at `/login`

### **4. Test Role Restrictions:**
```php
// In routes/web.php
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', fn() => 'Admin Dashboard');
});

// Only admin/super_admin can access /admin
```

---

## 📚 Files Modified/Created

| File | Type | Purpose |
|------|------|---------|
| `app/Models/User.php` | Model | Added MustVerifyEmail, roles, relationships |
| `app/Http/Controllers/ProfileController.php` | Controller | Profile, address, order management |
| `app/Http/Controllers/Auth/RegisteredUserController.php` | Controller | Added phone validation |
| `app/Http/Controllers/Auth/AuthenticatedSessionController.php` | Controller | Login logic |
| `app/Http/Requests/Auth/LoginRequest.php` | Request | 30-day remember me |
| `app/Http/Middleware/AdminMiddleware.php` | Middleware | Admin access checks |
| `app/Policies/AddressPolicy.php` | Policy | Address authorization |
| `app/Policies/OrderPolicy.php` | Policy | Order authorization |
| `app/Providers/AuthServiceProvider.php` | Provider | Policy registration |
| `database/seeders/RolePermissionSeeder.php` | Seeder | Create roles 
 permissions |
| `database/seeders/AdminSeeder.php` | Seeder | Create admin account |
| `database/migrations/*add_role_to_users_table.php` | Migration | Added role enum |
| `resources/views/auth/register.blade.php` | View | Added phone field |
| `.env` | Config | Admin credentials |

---

## 🐛Troubleshooting

**Q: User can't verify email**
- Check MAIL_* variables in `.env`
- Ensure `MAIL_FROM_ADDRESS` is set
- Run `php artisan queue:work` if using queue

**Q: Admin redirect not working**
- Verify middleware is registered in `HTTP Kernel`
- Check user role is exactly 'admin' or 'super_admin'
- Clear route cache: `php artisan route:clear`

**Q: Phone validation failing**
- Must be 10+ digits
- Can include +country code
- Space or dash separators allowed

**Q: Policies not working**
- Ensure policies registered in `AuthServiceProvider`
- Use `$this->authorize()` in controllers
- Models must implement correct trait/interface
