<?php include '../layouts/header.php'; ?>
    <h2>Gửi mail xác nhận</h2>
    <form method="POST" action="../Controllers/MailController.php">
        <input type="email" name="email" placeholder="Email nhận" required><br>
        <button type="submit" name="gui_mail">Gửi</button>
    </form>
<?php include '../layouts/footer.php'; ?>