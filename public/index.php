<?php
// public/index.php
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();

// 1. Load PDO (trả về biến $pdo)
$pdo = require __DIR__ . '/../config/db.php';

// 2. Đọc URL: ?url=controller/action
$url    = $_GET['url'] ?? 'trangchu/index';
$parts  = explode('/', trim($url, '/'));
$ctrl   = $parts[0] . '_controller';        // ví dụ "admin_controller"
$action = $parts[1] ?? 'index';             // ví dụ "login", "dashboard"

// 3. Nạp file controller
$ctrlFile = __DIR__ . "/../app/Controllers/{$ctrl}.php";
if (!file_exists($ctrlFile)) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    die("Controller file \"{$ctrl}.php\" không tồn tại.");
}
require_once $ctrlFile;

// 4. Kiểm tra class tồn tại
if (!class_exists($ctrl)) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    die("Class controller \"{$ctrl}\" không tồn tại.");
}

// 5. Khởi tạo và gọi phương thức
$controller = new $ctrl($pdo);
if (!method_exists($controller, $action)) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    die("Action \"{$action}\" không tồn tại trong {$ctrl}.");
}
$controller->{$action}();
