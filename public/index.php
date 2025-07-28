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
use App\Services\Logger;
$logger = Logger::getInstance();
$logger->info('Application started');

// Setup default admin user
setupDefaultAdmin();

// 2. Define a simple view rendering function

// 3. Create the dispatcher using FastRoute
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    // Define your routes
    $r->addRoute('GET', '/', ['App\Controllers\HomeController', 'index']);
    $r->addRoute('GET', '/tim-kiem-san-pham', ['App\Controllers\SanPhamController', 'timKiem']);
    $r->addRoute('GET', '/san-pham/{duongDan}', ['App\Controllers\SanPhamController', 'chiTiet']);
    $r->addRoute('POST', '/cart/add', ['App\Controllers\GioHangController', 'themVaoGio']);
    
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
    $r->addRoute(['GET','POST'], '/admin/danh-muc/them',   ['App\Controllers\Admin\DanhMucController', 'them']);
    $r->addRoute(['GET','POST'], '/admin/danh-muc/sua/{id:\d+}',      ['App\Controllers\Admin\DanhMucController', 'sua']);
    $r->addRoute('GET',  '/admin/danh-muc/chi-tiet/{id:\d+}', ['App\Controllers\Admin\DanhMucController', 'chiTiet']);
    $r->addRoute('POST', '/admin/danh-muc/xoa/{id:\d+}',      ['App\Controllers\Admin\DanhMucController', 'xoa']);
    // {id:\d+} means the 'id' parameter must be a digit
    // Định tuyến cho các thao tác giỏ hàng
    $r->addRoute('GET', '/gio-hang', ['App\Controllers\GioHangController', 'index']);
    $r->addRoute('POST', '/gio-hang/cap-nhat', ['App\Controllers\GioHangController', 'capNhat']);
    $r->addRoute(['GET', 'POST'], '/gio-hang/xoa', ['App\Controllers\GioHangController', 'xoa']);
    $r->addRoute('GET', '/gio-hang/checkout', ['App\Controllers\GioHangController', 'checkout']);

    ///
    $r->addRoute('GET', '/dang-nhap', ['App\Controllers\AuthController', 'dangNhap']);
    $r->addRoute('POST', '/dang-nhap', ['App\Controllers\AuthController', 'dangNhap']);
    $r->addRoute('GET', '/dang-xuat', ['App\Controllers\AuthController', 'dangXuat']);
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
