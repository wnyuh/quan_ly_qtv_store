<?php include '../layouts/header.php'; ?>
    <h2>Đăng ký khách hàng</h2>
    <form method="POST" action="../Controllers/KhachHangController.php">
        <input type="text" name="ten_dang_nhap" placeholder="Tên đăng nhập" required><br>
        <input type="password" name="mat_khau" placeholder="Mật khẩu" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <button type="submit" name="dang_ky">Đăng ký</button>
    </form>
<?php include '../layouts/footer.php'; ?>