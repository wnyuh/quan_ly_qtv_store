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
    <link rel="stylesheet" href="/phan_mem_quan_ly_ban_dien_thoai_iphone/public/css/style.css">
</head>
<body>

<header class="site-header">
    <!-- Logo -->
    <div class="logo">
        <a href="index.php?url=trangchu/index">
            <img src="/phan_mem_quan_ly_ban_dien_thoai_iphone/public/images/logo.svg" alt="Logo">
        </a>
    </div>

    <!-- Tìm kiếm -->
    <div class="search-wrap">
        <form action="index.php" method="get">
            <input type="hidden" name="url" value="sanpham/index">
            <input type="text" name="q" placeholder="Bạn tìm gì..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
            <button type="submit">🔍</button>
        </form>
    </div>

    <!-- Đăng nhập / Giỏ hàng -->
    <div class="actions">
        <?php if (!isset($_SESSION['user'])): ?>
            <a href="index.php?url=admin/login">
                <span>👤</span> Đăng nhập
            </a>
        <?php else: ?>
            <a href="index.php?url=nguoidung/profile">
                <span>👤</span> <?= htmlspecialchars($_SESSION['user']['ten_dang_nhap']) ?>
            </a>
            <a href="index.php?url=admin/logout">
                <span>🚪</span> Đăng xuất
            </a>
        <?php endif; ?>

        <a href="index.php?url=giohang/index" class="cart" data-count="<?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>">
            <span>🛒</span> Giỏ hàng
        </a>
    </div>
</header>


<main>
    <!-- Nội dung trang được include tiếp ở đây -->
