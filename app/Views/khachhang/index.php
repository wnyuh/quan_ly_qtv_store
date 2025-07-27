<?php include '../layouts/header.php'; ?>
    <h2>Danh sách khách hàng</h2>
<?php
$khach_hang = new KhachHang($conn);
$danh_sach = $khach_hang->LayTatCa();
?>
    <table border="1">
        <tr><th>Mã khách hàng</th><th>Tên đăng nhập</th><th>Email</th></tr>
        <?php foreach ($danh_sach as $kh): ?>
            <tr>
                <td><?php echo $kh['ma_nguoi_dung']; ?></td>
                <td><?php echo $kh['ten_dang_nhap']; ?></td>
                <td><?php echo $kh['email']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="form.php">Thêm khách hàng mới</a>
<?php include '../layouts/footer.php'; ?>