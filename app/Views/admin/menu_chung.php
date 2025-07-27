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
    'giohang/index'         => 'Quản lý Giỏ hàng',
    'khachhang/index'       => 'Quản lý Khách hàng',
    'thongkedoanhthu/index' => 'Thống kê Doanh thu',
    'danhgia/index'         => 'Quản lý Đánh giá',
    'admin/them_anh'        => 'Quản lý Ảnh',
    'mail/form'             => 'Gửi Mail',
    'admin/logout'          => 'Đăng xuất',
];
?>

<nav class="admin-nav">
    <ul>
        <?php foreach($menuItems as $route => $label): ?>
            <li>
                <a href="index.php?url=<?= $route ?>"
                   class="<?= isActive($route, $current) ?>">
                    <?= $label ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

<style>
    .admin-nav {
        background-color: #2c3e50;
        padding: 10px 20px;
    }
    .admin-nav ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        flex-wrap: wrap;
    }
    .admin-nav li + li {
        margin-left: 15px;
    }
    .admin-nav a {
        color: #ecf0f1;
        text-decoration: none;
        padding: 6px 10px;
        border-radius: 4px;
        transition: background .2s;
    }
    .admin-nav a:hover {
        background-color: #34495e;
    }
    .admin-nav a.active {
        background-color: #ecf0f1;
        color: #2c3e50;
        font-weight: bold;
    }
</style>
