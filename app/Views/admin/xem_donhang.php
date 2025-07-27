<?php include '../layouts/header.php'; ?>
    <h2>Xem chi tiết đơn hàng</h2>
<?php
if (isset($_GET['ma_don_hang'])) {
    $ma_don_hang = $_GET['ma_don_hang'];
    $don_hang = new DonHang($conn);
    $dh = $don_hang->LayTheoMa($ma_don_hang);
    $chi_tiet = new chitietdonhang($conn);
    $ctdh = $chi_tiet->LayTheoDonHang($ma_don_hang);
}
?>
    <p>Mã đơn hàng: <?php echo $dh['ma_don_hang']; ?></p>
    <table border="1">
        <tr><th>Sản phẩm</th><th>Số lượng</th></tr>
        <?php foreach ($ctdh as $item): ?>
            <tr><td><?php echo $item['ma_san_pham']; ?></td><td><?php echo $item['so_luong']; ?></td></tr>
        <?php endforeach; ?>
    </table>
<?php include '../layouts/footer.php'; ?>