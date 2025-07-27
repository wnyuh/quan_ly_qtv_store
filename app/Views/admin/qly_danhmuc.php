<?php
// app/Views/admin/qly_danhmuc.php

// Kiểm tra đã login admin
if (empty($_SESSION['user']) || $_SESSION['user']['vai_tro'] !== 'admin') {
    header('Location: index.php?url=admin/login');
    exit;
}

// Nạp menu chung
//include __DIR__ . '/menu_chung.php';
//include __DIR__ . '/../layouts/header.php';
?>

<main class="container">
    <h1>Quản lý Danh mục</h1>

    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['flash_success']) ?>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <p>
        <a href="index.php?url=qly_danhmuc/them" class="btn btn-primary">Thêm danh mục mới</a>
    </p>

    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>Tên danh mục</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($dms)): ?>
            <?php foreach($dms as $dm): ?>
                <tr>
                    <td><?= $dm['ma_danh_muc'] ?></td>
                    <td><?= htmlspecialchars($dm['ten_danh_muc']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($dm['ngay_tao'])) ?></td>
                    <td>
                        <a href="index.php?url=qly_danhmuc/sua&id=<?= $dm['ma_danh_muc'] ?>" class="btn btn-sm btn-warning">Sửa</a>
                        <a href="index.php?url=qly_danhmuc/xoa&id=<?= $dm['ma_danh_muc'] ?>"
                           onclick="return confirm('Bạn có chắc muốn xóa danh mục này?')"
                           class="btn btn-sm btn-danger">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Chưa có danh mục nào.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</main>

<?php
include __DIR__ . '/../layouts/footer.php';
?>
