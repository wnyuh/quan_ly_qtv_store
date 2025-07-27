<?php include '../layouts/header.php'; ?>
    <h2>Thêm sản phẩm</h2>
    <form method="POST" action="../Controllers/QuanLySpAdminController.php">
        <input type="text" name="ten_san_pham" placeholder="Tên sản phẩm" required><br>
        <input type="number" name="gia_ban" step="0.01" placeholder="Giá" required><br>
        <input type="text" name="hinh_anh" placeholder="Đường dẫn hình ảnh" required><br>
        <textarea name="mo_ta" placeholder="Mô tả"></textarea><br>
        <input type="number" name="ma_danh_muc" placeholder="Mã danh mục" required><br>
        <button type="submit" name="them">Thêm</button>
    </form>
<?php include '../layouts/footer.php'; ?>