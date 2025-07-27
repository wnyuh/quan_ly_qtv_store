<?php
// app/Views/layouts/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current = $_GET['url'] ?? 'trangchu/index';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cửa hàng iPhone</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
<?php
// app/Views/admin/menu_chung.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current = $_GET['url'] ?? 'admin/dashboard';

// Định nghĩa hàm chỉ một lần
if (!function_exists('isActive')) {
    /**
     * Trả về 'active' nếu $route bằng $current
     */
    function isActive(string $route, string $current): string {
        return $route === $current ? 'active' : '';
    }
}

$menuItems = [
    'admin/dashboard'       => 'Dashboard',
    'qly_sanpham/index'     => 'Quản lý Sản phẩm',
    'qly_danhmuc/index'     => 'Quản lý Danh mục',
    'qly_donhang/index'     => 'Quản lý Đơn hàng',
//    'giohang/index'         => 'Quản lý Giỏ hàng',
    'qly_khachhang/index'       => 'Quản lý Khách hàng',
    'thongkedoanhthu/index' => 'Thống kê Doanh thu',
    'danhgia/index'         => 'Quản lý Đánh giá',
    'admin/them_anh'        => 'Quản lý Ảnh',
    'mail/form'             => 'Gửi Mail',
    'admin/logout'          => 'Đăng xuất',
];
?>

<nav class="flex h-14 items-center px-4 border-b border-gray-200 bg-white/95 backdrop-blur">
    <div class="flex items-center space-x-4 lg:space-x-6">
        <a href="index.php?url=admin/dashboard" class="flex items-center space-x-2">
            <span class="font-bold text-xl text-gray-900">Admin Panel</span>
        </a>

        <div class="flex items-center space-x-4 lg:space-x-6">
            <?php foreach($menuItems as $route => $label): ?>
                <?php if($route !== 'admin/logout'): ?>
                    <a href="index.php?url=<?= $route ?>"
                       class="text-sm font-medium transition-colors hover:text-blue-600 <?= isActive($route, $current) ? 'text-blue-600' : 'text-gray-600' ?>">
                        <?= $label ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="ml-auto flex items-center space-x-4">
        <a href="index.php?url=admin/logout"
           class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 border border-gray-300 bg-white hover:bg-gray-50 hover:text-gray-900 h-9 px-3">
            Đăng xuất
        </a>
    </div>
</nav>

