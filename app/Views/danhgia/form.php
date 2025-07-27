<?php include '../layouts/header.php'; ?>
    <h2>Thêm đánh giá</h2>
    <form method="POST" action="../Controllers/DanhGiaController.php">
        <input type="hidden" name="ma_san_pham" value="<?php echo isset($_GET['ma_san_pham']) ? $_GET['ma_san_pham'] : ''; ?>">
        <input type="number" name="so_sao" min="1" max="5" placeholder="Số sao" required><br>
        <textarea name="binh_luan" placeholder="Bình luận" required></textarea><br>
        <button type="submit" name="them_danh_gia">Gửi đánh giá</button>
    </form>
<?php include '../layouts/footer.php'; ?>