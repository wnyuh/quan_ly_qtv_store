<?php
// app/Views/admin/login.php
include __DIR__ . '/../layouts/header.php';
?>

<main class="container" style="max-width:400px;margin:50px auto;">
    <h2>Đăng nhập Admin</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="index.php?url=admin/login" method="post">
        <div class="form-group">
            <label for="ten_dn">Tên đăng nhập</label>
            <input
                    type="text"
                    name="ten_dn"
                    id="ten_dn"
                    class="form-control"
                    required
                    autofocus
            >
        </div>

        <div class="form-group">
            <label for="mat_khau">Mật khẩu</label>
            <input
                    type="password"
                    name="mat_khau"
                    id="mat_khau"
                    class="form-control"
                    required
            >
        </div>

        <button type="submit" class="btn btn-primary">Đăng nhập</button>
    </form>
</main>

<?php
// Nạp footer chung (nếu có)
include __DIR__ . '/../layouts/footer.php';
?>```
