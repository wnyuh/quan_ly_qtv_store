<?php include '../layouts/header.php'; ?>
    <h2>Danh sách địa chỉ</h2>
<?php
$dia_chi = new DiaChi($conn);
$danh_sach = $dia_chi->LayTheoNguoiDung($_SESSION['ma_nguoi_dung']);
?>
    <table border="1">
        <tr><th>Địa chỉ</th><th>Số điện thoại</th></tr>
        <?php foreach ($danh_sach as $dc): ?>
            <tr><td><?php echo $dc['dia_chi']; ?></td><td><?php echo $dc['so_dien_thoai']; ?></td></tr>
        <?php endforeach; ?>
    </table>
<?php include '../layouts/footer.php'; ?>