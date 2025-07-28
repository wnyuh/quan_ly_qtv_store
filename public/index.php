<?php

// 1. Include Composer's Autoloader
require '../vendor/autoload.php';

// 2. Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Initialize logger
use App\Services\Logger;
$logger = Logger::getInstance();
$logger->info('Application started');

// 2. Define a simple view rendering function

// 3. Create the dispatcher using FastRoute
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    // Define your routes
    $r->addRoute('GET', '/', ['App\Controllers\HomeController', 'index']);
    $r->addRoute('GET', '/tim-kiem-san-pham', ['App\Controllers\SanPhamController', 'timKiem']);
    $r->addRoute('GET', '/san-pham/{duongDan}', ['App\Controllers\SanPhamController', 'chiTiet']);
    $r->addRoute('POST', '/cart/add', ['App\Controllers\GioHangController', 'themVaoGio']);
    // {id:\d+} means the 'id' parameter must be a digit
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
    session_start();
    
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
