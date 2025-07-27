<?php include '../layouts/header.php'; ?>
    <h2>Sửa sản phẩm</h2>
<?php
if (isset($_GET['ma_san_pham'])) {
    $ma_san_pham = $_GET['ma_san_pham'];
    $san_pham = new SanPham($conn);
    $sp = $san_pham->LayTheoMa($ma_san_pham);
}
?>
    <form method="POST">
        <input type="hidden" name="ma_san_pham" value="<?php echo $sp['ma_san_pham']; ?>">
        <input type="text" name="ten_san_pham" value="<?php echo $sp['ten_san_pham']; ?>" required><br>
        <input type="number" name="gia_ban" step="0.01" value="<?php echo $sp['gia_ban']; ?>" required><br>
        <input type="text" name="hinh_anh" value="<?php echo $sp['hinh_anh']; ?>" required><br>
        <textarea name="mo_ta"><?php echo $sp['mo_ta']; ?></textarea><br>
        <input type="number" name="ma_danh_muc" value="<?php echo $sp['ma_danh_muc']; ?>" required><br>
        <button type="submit" name="cap_nhat">Cập nhật</button>
    </form>
<?php include '../layouts/footer.php'; ?>