<?php include '../layouts/header.php'; ?>
    <h2>Kết quả thanh toán</h2>
    <p><?php echo isset($_GET['status']) && $_GET['status'] == 'success' ? 'Thanh toán thành công!' : 'Thanh toán thất bại!'; ?></p>
<?php include '../layouts/footer.php'; ?>