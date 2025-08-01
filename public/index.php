<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1. Include Composer's Autoloader
require '../vendor/autoload.php';

// 2. Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Initialize logger
use App\Controllers\ThuongHieuController;
use App\Services\Logger;

$logger = Logger::getInstance();
$logger->info('Application started');

// Setup default admin user
setupDefaultAdmin();

// 2. Define a simple view rendering function

// 3. Create the dispatcher using FastRoute
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    // Define your routes
    $r->addRoute('GET', '/', ['App\Controllers\HomeController', 'index']);
    $r->addRoute('GET', '/tim-kiem-san-pham', ['App\Controllers\SanPhamController', 'timKiem']);
    $r->addRoute('GET', '/san-pham/{duongDan}', ['App\Controllers\SanPhamController', 'chiTiet']);
    $r->addRoute('POST', '/cart/add', ['App\Controllers\GioHangController', 'themVaoGio']);

    $r->addRoute('GET', '/huong-dan-mua-hang', ['App\Controllers\ThongTinFooterController', 'huongDanMuaHang']);
    $r->addRoute('GET', '/bao-hanh', ['App\Controllers\ThongTinFooterController', 'baoHanh']);
    $r->addRoute('GET', '/doi-tra', ['App\Controllers\ThongTinFooterController', 'doiTraSanPham']);
    $r->addRoute('GET', '/hinh-thuc-thanh-toan', ['App\Controllers\ThongTinFooterController', 'hinhThucThanhToan']);
    $r->addRoute('GET', '/lien-he', ['App\Controllers\ThongTinFooterController', 'lienHe']);



    // Admin routes
    $r->addRoute(['GET', 'POST'], '/admin/login', ['App\Controllers\Admin\AdminController', 'login']);
    $r->addRoute('GET', '/admin/logout', ['App\Controllers\Admin\AdminController', 'logout']);
    $r->addRoute('GET', '/admin/dashboard', ['App\Controllers\Admin\AdminController', 'dashboard']);
    $r->addRoute('GET', '/admin', ['App\Controllers\Admin\AdminController', 'dashboard']);

    // Admin product routes
    $r->addRoute('GET', '/admin/san-pham', ['App\Controllers\Admin\SanPhamController', 'danhSach']);
    $r->addRoute(['GET', 'POST'], '/admin/san-pham/them', ['App\Controllers\Admin\SanPhamController', 'them']);
    $r->addRoute(['GET', 'POST'], '/admin/san-pham/sua/{id:\d+}', ['App\Controllers\Admin\SanPhamController', 'sua']);
    $r->addRoute('GET', '/admin/san-pham/chi-tiet/{id:\d+}', ['App\Controllers\Admin\SanPhamController', 'chiTiet']);
    $r->addRoute('POST', '/admin/san-pham/xoa/{id:\d+}', ['App\Controllers\Admin\SanPhamController', 'xoa']);
    // Admin category routes
    $r->addRoute('GET',  '/admin/danh-muc',                ['App\Controllers\Admin\DanhMucController', 'danhSach']);
    $r->addRoute(['GET', 'POST'], '/admin/danh-muc/them',   ['App\Controllers\Admin\DanhMucController', 'them']);
    $r->addRoute(['GET', 'POST'], '/admin/danh-muc/sua/{id:\d+}',      ['App\Controllers\Admin\DanhMucController', 'sua']);
    $r->addRoute('GET',  '/admin/danh-muc/chi-tiet/{id:\d+}', ['App\Controllers\Admin\DanhMucController', 'chiTiet']);
    $r->addRoute('POST', '/admin/danh-muc/xoa/{id:\d+}',      ['App\Controllers\Admin\DanhMucController', 'xoa']);

    // Admin brand routes
    $r->addRoute('GET',    '/admin/thuong-hieu',                  ['App\Controllers\Admin\ThuongHieuController', 'danhSach']);
    $r->addRoute(['GET', 'POST'], '/admin/thuong-hieu/them',       ['App\Controllers\Admin\ThuongHieuController', 'them']);
    $r->addRoute(['GET', 'POST'], '/admin/thuong-hieu/sua/{id:\d+}',   ['App\Controllers\Admin\ThuongHieuController', 'sua']);
    $r->addRoute('GET',    '/admin/thuong-hieu/chi-tiet/{id:\d+}', ['App\Controllers\Admin\ThuongHieuController', 'chiTiet']);
    $r->addRoute('POST',   '/admin/thuong-hieu/xoa/{id:\d+}',      ['App\Controllers\Admin\ThuongHieuController', 'xoa']);

    // Admin order routes
    $r->addRoute('GET',              '/admin/don-hang',                ['App\Controllers\Admin\DonHangController', 'danhSach']);
    $r->addRoute(['GET', 'POST'],     '/admin/don-hang/them',           ['App\Controllers\Admin\DonHangController', 'them']);
    $r->addRoute('GET',              '/admin/don-hang/chi-tiet/{id:\d+}', ['App\Controllers\Admin\DonHangController', 'chiTiet']);
    $r->addRoute(['GET', 'POST'],     '/admin/don-hang/sua/{id:\d+}',      ['App\Controllers\Admin\DonHangController', 'sua']);
    $r->addRoute('POST',             '/admin/don-hang/xoa/{id:\d+}',      ['App\Controllers\Admin\DonHangController', 'xoa']);

    // Admin user routes
    $r->addRoute('GET',            '/admin/nguoi-dung',                  ['App\Controllers\Admin\NguoiDungController', 'danhSach']);
    $r->addRoute(['GET', 'POST'],   '/admin/nguoi-dung/them',             ['App\Controllers\Admin\NguoiDungController', 'them']);
    $r->addRoute('GET',            '/admin/nguoi-dung/chi-tiet/{id:\d+}', ['App\Controllers\Admin\NguoiDungController', 'chiTiet']);
    $r->addRoute(['GET', 'POST'],   '/admin/nguoi-dung/sua/{id:\d+}',      ['App\Controllers\Admin\NguoiDungController', 'sua']);
    $r->addRoute('POST',           '/admin/nguoi-dung/xoa/{id:\d+}',      ['App\Controllers\Admin\NguoiDungController', 'xoa']);

    // Admin discount code routes
    $r->addRoute('GET',              '/admin/ma-giam-gia',                  ['App\Controllers\Admin\MaGiamGiaController', 'danhSach']);
    $r->addRoute(['GET','POST'],     '/admin/ma-giam-gia/them',             ['App\Controllers\Admin\MaGiamGiaController', 'them']);
    $r->addRoute('GET',              '/admin/ma-giam-gia/chi-tiet/{id:\d+}', ['App\Controllers\Admin\MaGiamGiaController', 'chiTiet']);
    $r->addRoute(['GET','POST'],     '/admin/ma-giam-gia/sua/{id:\d+}',      ['App\Controllers\Admin\MaGiamGiaController', 'sua']);
    $r->addRoute('POST',             '/admin/ma-giam-gia/xoa/{id:\d+}',      ['App\Controllers\Admin\MaGiamGiaController', 'xoa']);


    // Admin revenue report routes
    $r->addRoute('GET',  '/admin/bao-cao/doanh-thu',                  ['App\Controllers\Admin\BaoCaoController', 'doanhThu']);
    $r->addRoute('POST','/admin/bao-cao/doanh-thu',                  ['App\Controllers\Admin\BaoCaoController', 'doanhThu']);

    // {id:\d+} means the 'id' parameter must be a digit
    // Định tuyến cho các thao tác giỏ hàng
    $r->addRoute('GET', '/gio-hang', ['App\Controllers\GioHangController', 'index']);
    $r->addRoute('POST', '/gio-hang/cap-nhat', ['App\Controllers\GioHangController', 'capNhat']);
    $r->addRoute(['GET', 'POST'], '/gio-hang/xoa', ['App\Controllers\GioHangController', 'xoa']);
    $r->addRoute('GET', '/gio-hang/checkout', ['App\Controllers\GioHangController', 'checkout']);
    $r->addRoute('POST', '/gio-hang/process-checkout', ['App\Controllers\GioHangController', 'processCheckout']);
    $r->addRoute('POST', '/api/validate-discount', ['App\Controllers\GioHangController', 'validateDiscount']);
    $r->addRoute('GET', '/don-hang/thanh-cong/{orderCode}', ['App\Controllers\DonHangController', 'thanhCong']);

    ///
    $r->addRoute('GET', '/dang-nhap', ['App\Controllers\AuthController', 'dangNhap']);
    $r->addRoute('POST', '/dang-nhap', ['App\Controllers\AuthController', 'dangNhap']);
    // $r->addRoute('GET', '/dang-xuat', ['App\Controllers\AuthController', 'dangXuat']);
    $r->addRoute(['GET', 'POST'], '/dang-ky', ['App\Controllers\AuthController', 'dangKy']);
    $r->addRoute('GET', '/xac-nhan', ['App\Controllers\AuthController', 'kichHoatTaiKhoan']);

    // Route để hiển thị trang tài khoản người dùng sau khi đăng nhập
    $r->addRoute('GET', '/tai-khoan', ['App\Controllers\NguoiDungController', 'taiKhoan']);
    $r->addRoute('POST', '/dang-xuat', ['App\Controllers\NguoiDungController', 'dangXuat']);
    $r->addRoute('POST', '/tai-khoan/cap-nhat', ['App\Controllers\NguoiDungController', 'capNhat']);
    $r->addRoute('POST', '/tai-khoan/doi-mat-khau', ['App\Controllers\NguoiDungController', 'doiMatKhau']);
    $r->addRoute('GET', '/dang-xuat', ['App\Controllers\AuthController', 'dangXuat']);

    //
    $r->addRoute('GET', '/danh-muc', ['App\Controllers\DanhMucController', 'danhSach']);
    $r->addRoute('GET', '/danh-muc/{slug}', ['App\Controllers\DanhMucController', 'chiTiet']);
    //
    $r->addRoute('GET', '/thuong-hieu/{slug}', [ThuongHieuController::class, 'chiTiet']);

    // Route cho trang "Đơn hàng của tôi"
    $r->addRoute('GET', '/don-hang', ['App\Controllers\NguoiDungController', 'donHang']);
});

// 4. Fetch the request method and URI
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

// 5. Dispatch the route
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // 404 Not Found
        http_response_code(404);
        view('errors/404');
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        // 405 Method Not Allowed
        http_response_code(405);
        view('errors/405');
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1]; // The controller/method array
        $vars = $routeInfo[2];    // The route parameters (e.g., ['id' => '123'])

        $logger->info('Route found', ['handler' => $handler, 'method' => $httpMethod, 'uri' => $uri]);

        // Call the controller method
        $controller = new $handler[0]();
        $method = $handler[1];
        $controller->$method(...array_values($vars));
        break;
}


function getCartItemCount(): int
{
    try {
        $em = require __DIR__ . '/../config/doctrine.php';

        if (isset($_SESSION['user_id'])) {
            // Logged in user - get from database
            $qb = $em->createQueryBuilder();
            $qb->select('sum(gh.soLuong)')
                ->from('App\Models\GioHang', 'gh')
                ->where('gh.nguoiDung = :userId')
                ->setParameter('userId', $_SESSION['user_id']);

            return (int) $qb->getQuery()->getSingleScalarResult();
        } else {
            // Guest user - get from session
            $maPhien = $_SESSION['ma_phien'] ?? null;
            if ($maPhien) {
                $phienKhach = $em->getRepository('App\Models\PhienKhach')->findOneBy(['maPhien' => $maPhien]);
                if ($phienKhach && !$phienKhach->isExpired()) {
                    return $phienKhach->getCartItemCount();
                }
            }
        }
    } catch (\Exception $e) {
        // Nếu có lỗi, trả về 0
        return 0;
    }

    return 0;
}

function view(string $path, array $data = []): void
{
    // Thêm cart count vào data để layout có thể sử dụng
    $data['cartItemCount'] = getCartItemCount();

    // Make variables available to both the view and the layout.
    extract($data);

    // Start output buffering.
    ob_start();

    // Include the specific view file. Its output is captured by the buffer.
    require "../app/Views/{$path}.php";

    // Get the captured content from the buffer and clean the buffer.
    $content = ob_get_clean();

    // Now, include the main layout file.
    // The $content variable is now available to it.
    require '../app/Views/layouts/main.php';
}

function admin_view(string $path, array $data = []): void
{
    // Make variables available to both the view and the layout.
    extract($data);

    // Start output buffering.
    ob_start();

    // Include the specific view file. Its output is captured by the buffer.
    require "../app/Views/{$path}.php";

    // Get the captured content from the buffer and clean the buffer.
    $content = ob_get_clean();

    // Now, include the main layout file.
    // The $content variable is now available to it.
    require '../app/Views/layouts/admin.php';
}

function component(string $name, array $data = []): void
{
    // Make variables available to the component.
    extract($data);

    // Include the component file.
    require "../app/Views/components/{$name}.php";
}

function setupDefaultAdmin(): void
{
    try {
        $em = require __DIR__ . '/../config/doctrine.php';

        // Kiểm tra xem đã có admin nào chưa
        $adminCount = $em->getRepository('App\Models\Admin')->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        if ($adminCount == 0) {
            // Tạo admin mặc định
            $admin = new App\Models\Admin();
            $admin->setUsername('admin');
            $admin->setPassword($_ENV['ADMIN_PASSWORD'] ?? '123456');

            $em->persist($admin);
            $em->flush();

            error_log('Default admin created: username=admin');
        }
    } catch (\Exception $e) {
        // Nếu có lỗi (ví dụ: bảng chưa tồn tại), bỏ qua im lặng
        error_log('Could not setup default admin: ' . $e->getMessage());
    }
}
