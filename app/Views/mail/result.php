<?php include '../layouts/header.php'; ?>
    <h2>Kết quả gửi mail</h2>
    <p><?php echo isset($_GET['status']) && $_GET['status'] == 'success' ? 'Gửi mail thành công!' : 'Gửi mail thất bại!'; ?></p>
<?php include '../layouts/footer.php'; ?>