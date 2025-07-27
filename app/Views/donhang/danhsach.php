<?php include '../layouts/header.php'; ?>
    <h2>Danh sách đơn hàng</h2>
<?php
$don_hang = new DonHang($conn);
$danh_sach = $don_hang->LayTheoNguoiDung($_SESSION['ma_nguoi_dung']);
?>
    <table border="1">
        <tr><th>Mã đơn hàng</th><th>Trạng thái</th></tr>
        <?php foreach ($danh_sach as $dh): ?>
            <tr><td><?php echo $dh['ma_don_hang']; ?></td><td><?php echo $dh['trang_thai']; ?></td></tr>
        <?php endforeach; ?>
    </table>
<?php include '../layouts/footer.php'; ?>