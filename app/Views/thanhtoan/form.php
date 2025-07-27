<?php include '../layouts/header.php'; ?>
    <h2>Thanh toán</h2>
    <form method="POST" action="../Controllers/ThanhToanController.php">
        <input type="hidden" name="ma_don_hang" value="<?php echo $ma_don_hang; ?>">
        <input type="number" name="so_tien" step="0.01" placeholder="Số tiền" required><br>
        <select name="phuong_thuc" required>
            <option value="chuyen_khoan">Chuyển khoản</option>
            <option value="tien_mat">Tiền mặt</option>
        </select><br>
        <button type="submit" name="thanh_toan">Thanh toán</button>
    </form>
<?php include '../layouts/footer.php'; ?>