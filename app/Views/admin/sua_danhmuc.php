<?php include '../layouts/header.php'; ?>
    <h2>Sửa danh mục</h2>
<?php
if (isset($_GET['ma_danh_muc'])) {
    $ma_danh_muc = $_GET['ma_danh_muc'];
    $danh_muc = new DanhMuc($conn);
    $dm = $danh_muc->LayTheoMa($ma_danh_muc);
}
?>
    <form method="POST">
        <input type="hidden" name="ma_danh_muc" value="<?php echo $dm['ma_danh_muc']; ?>">
        <input type="text" name="ten_danh_muc" value="<?php echo $dm['ten_danh_muc']; ?>" required><br>
        <button type="submit" name="cap_nhat">Cập nhật</button>
    </form>
<?php include '../layouts/footer.php'; ?>