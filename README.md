# Dự án Store

Ứng dụng web PHP được xây dựng với FastRoute routing và tạo kiểu với Tailwind CSS.

## Yêu cầu hệ thống

Đảm bảo bạn đã cài đặt các công cụ sau trên hệ thống:

- **PHP** (phiên bản 7.4 trở lên)
- **Composer** (trình quản lý dependencies PHP)
- **Node.js** (phiên bản 14 trở lên)
- **npm** (đi kèm với Node.js)

## Liên Kết

- **[Tailwind](https://tailwindcss.com/)**
- **[BasecoatCss](https://basecoatui.com/introduction/)**
- **[Mailtrap](https://mailtrap.io/)**

## Cài đặt

1. **Clone repository** (nếu có) - Lấy code về máy:
   ```bash
   git clone <repository-url>
   cd store
   ```

2. **Cài đặt dependencies PHP**:
   ```bash
   composer install
   ```

3. **Cài đặt dependencies Node.js**:
   ```bash
   npm install
   ```

4. **Build Tailwind CSS**:
   ```bash
   npm run build-css
   ```

5. **Khởi tạo database** (chỉ lần đầu):
   ```bash
   php cli-config.php orm:schema-tool:create
   ```

6. **Tạo dữ liệu mẫu** (tùy chọn):
   ```bash
   php database/seeds/SeedDataVietnam.php
   ```

## Phát triển

### Chạy ứng dụng

1. **Khởi động PHP development server**:
   ```bash
   php -S localhost:8000 -t public
   ```

2. **Truy cập ứng dụng**:
   Mở trình duyệt và vào `http://localhost:8000`

### Làm việc với Styles

- **Build CSS một lần**:
  ```bash
  npm run build-css
  ```

- **Theo dõi thay đổi** (khuyến nghị cho development):
  ```bash
  npm run watch-css
  ```
  Lệnh này sẽ tự động rebuild CSS khi bạn thay đổi các class Tailwind trong views.

## Cấu trúc dự án

```
store/
├── public/
│   ├── index.php          # Điểm khởi đầu chính
│   └── css/
│       └── style.css      # Tailwind CSS được tạo
├── src/
│   ├── Controllers/
│   │   └── HomeController.php
│   ├── Entity/            # Các entity Doctrine (tiếng Việt)
│   │   ├── NguoiDung.php
│   │   ├── SanPham.php
│   │   ├── ThuongHieu.php
│   │   └── ... (14 entities)
│   ├── Database/
│   │   └── EntityManager.php
│   └── input.css          # File nguồn Tailwind
├── config/
│   └── doctrine.php       # Cấu hình Doctrine
├── database/
│   ├── store.sqlite       # Database SQLite
│   └── seeds/
│       └── SeedDataVietnam.php  # Dữ liệu mẫu iPhone
├── views/
│   ├── layouts/
│   │   ├── main.php       # Template layout chính
│   │   └── admin.php      # Template layout admin
│   └── home.php           # View trang chủ
├── vendor/                # Dependencies Composer
├── node_modules/          # Dependencies npm
├── cli-config.php         # Cấu hình Doctrine CLI
├── composer.json          # Dependencies PHP
└── package.json           # Dependencies Node.js
```

## Routes có sẵn

- `GET /` - Trang chủ (HomeController@index)

### Làm việc với Database

#### Lệnh Database

- **Tạo schema** (cài đặt lần đầu):
  ```bash
  php cli-config.php orm:schema-tool:create
  ```

- **Cập nhật schema** (sau khi thay đổi entity):
  ```bash
  php cli-config.php orm:schema-tool:update --force
  ```

- **Xóa schema** (xóa tất cả bảng):
  ```bash
  php cli-config.php orm:schema-tool:drop --force
  ```

- **Kiểm tra schema**:
  ```bash
  php cli-config.php orm:validate-schema
  ```

#### Dữ liệu mẫu (Seed Data)

- **Tạo dữ liệu mẫu iPhone**:
  ```bash
  php database/seeds/SeedDataVietnam.php
  ```

Lệnh này sẽ tạo:
- 1 thương hiệu Apple
- 2 danh mục sản phẩm (Điện thoại thông minh, iPhone)
- 10 sản phẩm iPhone (từ iPhone X đến iPhone 16)
- Các biến thể sản phẩm với màu sắc và dung lượng khác nhau
- Thông số kỹ thuật chi tiết bằng tiếng Việt
- Hình ảnh sản phẩm từ thư mục `public/images/`
- Giá cả tính bằng VND

**Lưu ý**: Chỉ chạy seed data sau khi đã tạo schema database.

#### Thêm/Cập nhật Entities

1. **Tạo entity mới** trong `src/Entity/`:
   ```php
   <?php
   
   namespace App\Entity;
   
   use Doctrine\ORM\Mapping as ORM;
   
   #[ORM\Entity]
   #[ORM\Table(name: 'your_table')]
   class YourEntity
   {
       #[ORM\Id]
       #[ORM\GeneratedValue]
       #[ORM\Column(type: 'integer')]
       private int $id;
   
       #[ORM\Column(type: 'string', length: 255)]
       private string $name;
   
       // Getters và setters...
   }
   ```

2. **Cập nhật database schema**:
   ```bash
   php cli-config.php orm:schema-tool:update --force
   ```

#### Sử dụng Entities trong Controllers

```php
use App\Models\NguoiDung;
use App\Models\SanPham;

class ProductController
{
    public function index()
    {
        $em = require __DIR__ . '/../config/doctrine.php';
        
        // Tìm tất cả sản phẩm
        $sanPhams = $em->getRepository(SanPham::class)->findAll();
        
        // Tìm sản phẩm theo thương hiệu
        $iphones = $em->getRepository(SanPham::class)
                     ->createQueryBuilder('sp')
                     ->join('sp.thuongHieu', 'th')
                     ->where('th.ten = :brand')
                     ->setParameter('brand', 'Apple')
                     ->getQuery()
                     ->getResult();
        
        // Tìm sản phẩm nổi bật
        $sanPhamNoiBat = $em->getRepository(SanPham::class)
                           ->findBy(['noiBat' => true, 'kichHoat' => true]);
    }
}
```

### Gửi Email

#### Cấu hình Email

1. **Email đã được cấu hình với Mailtrap** trong `config/email.php`:
   ```php
   return [
       'host' => 'sandbox.smtp.mailtrap.io',
       'port' => 2525,
       'username' => 'xx',
       'password' => 'xx',
       'from_email' => 'noreply@cuahangstore.com',
       'from_name' => 'Cửa hàng Store',
   ];
   ```

2. **Sử dụng EmailService**:
   ```php
   use App\Services\EmailService;
   use App\Controllers\EmailController;
   
   $emailController = new EmailController();
   
   // Gửi email chào mừng
   $emailController->sendWelcomeEmail($nguoiDung);
   
   // Gửi xác nhận đơn hàng
   $emailController->sendOrderConfirmation($donHang);
   
   // Gửi đặt lại mật khẩu
   $emailController->sendPasswordReset($email, $resetToken);
   ```

3. **Test email**:
   ```bash
   php test-email.php
   ```

#### Các loại email có sẵn:
- Email chào mừng người dùng mới
- Xác nhận đơn hàng
- Đặt lại mật khẩu  
- Form liên hệ

**Lưu ý**: Để sử dụng Gmail, bạn cần tạo App Password trong cài đặt bảo mật Google.

## Công nghệ sử dụng

- **Backend**: PHP với FastRoute cho routing
- **Database**: Doctrine ORM với SQLite
- **Email**: PHPMailer cho gửi email
- **Frontend**: Tailwind CSS với shadcn theme
- **Build Tool**: Tailwind CLI cho CSS compilation
- **Quản lý Dependencies**: Composer (PHP) + npm (Node.js)
