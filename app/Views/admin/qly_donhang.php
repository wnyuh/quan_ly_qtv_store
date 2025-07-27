<?php include '../layouts/header.php'; ?>
    <h2>Quản lý đơn hàng</h2>
    <table border="1">
        <tr><th>Mã đơn hàng</th><th>Trạng thái</th><th>Hành động</th></tr>
        <?php foreach ($don_hangs as $dh): ?>
            <tr>
                <td><?php echo $dh['ma_don_hang']; ?></td>
                <td><?php echo $dh['trang_thai']; ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="ma_don_hang" value="<?php echo $dh['ma_don_hang']; ?>">
                        <select name="trang_thai">
                            <option value="cho_xu_ly">Chờ xử lý</option>
                            <option value="dang_giao">Đang giao</option>
                            <option value="hoan_thanh">Hoàn thành</option>
                        </select>
                        <button type="submit" name="cap_nhat_trang_thai">Cập nhật</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php include '../layouts/footer.php'; ?>