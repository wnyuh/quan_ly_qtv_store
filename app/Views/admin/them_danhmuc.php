<?php include '../layouts/header.php'; ?>
    <h2>Thêm danh mục</h2>
    <form method="POST" action="../Controllers/QuanLyDmAdminController.php">
        <input type="text" name="ten_danh_muc" placeholder="Tên danh mục" required><br>
        <button type="submit" name="them">Thêm</button>
    </form>
<?php include '../layouts/footer.php'; ?>