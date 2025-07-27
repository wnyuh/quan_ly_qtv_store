<?php include '../layouts/header.php'; ?>
    <h2>Thêm địa chỉ</h2>
    <form method="POST" action="../Controllers/DiaChiController.php">
        <input type="text" name="dia_chi" placeholder="Địa chỉ" required><br>
        <input type="text" name="so_dien_thoai" placeholder="Số điện thoại" required><br>
        <button type="submit" name="them_dia_chi">Thêm</button>
    </form>
<?php include '../layouts/footer.php'; ?>