<?php include '../layouts/header.php'; ?>
    <h2>Tạo đơn hàng</h2>
    <form method="POST" action="../Controllers/donhang_controler.php">
        <select name="ma_dia_chi" required>
            <?php
            $dia_chi = new DiaChi($conn);
            $danh_sach = $dia_chi->LayTheoNguoiDung($_SESSION['ma_nguoi_dung']);
            foreach ($danh_sach as $dc) {
                echo "<option value='{$dc['ma_dia_chi']}'>{$dc['dia_chi']}</option>";
            }
            ?>
        </select><br>
        <button type="submit" name="tao_don_hang">Tạo đơn hàng</button>
    </form>
<?php include '../layouts/footer.php'; ?>