<?php include '../layouts/header.php'; ?>
    <h2>Danh sách đánh giá</h2>
<?php
if (isset($_GET['ma_san_pham'])) {
    $ma_san_pham = $_GET['ma_san_pham'];
    $danh_gia = new DanhGia($conn);
    $danh_sach = $danh_gia->LayTheoSanPham($ma_san_pham);
}
?>
    <table border="1">
        <tr><th>Mã đánh giá</th><th>Số sao</th><th>Bình luận</th></tr>
        <?php foreach ($danh_sach as $dg): ?>
            <tr>
                <td><?php echo $dg['ma_danh_gia']; ?></td>
                <td><?php echo $dg['so_sao']; ?></td>
                <td><?php echo $dg['binh_luan']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="form.php?ma_san_pham=<?php echo $ma_san_pham; ?>">Thêm đánh giá mới</a>
<?php include '../layouts/footer.php'; ?>