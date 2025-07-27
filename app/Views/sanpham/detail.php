<?php
// app/Views/sanpham/detail.php

// Nhúng header chung của site
include __DIR__ . '/../layouts/header.php';

// Nếu không tìm thấy sản phẩm, thoát luôn
if (empty($sp)) {
    echo '<p>Không tìm thấy Sản phẩm.</p>';
    include __DIR__ . '/../layouts/footer.php';
    exit;
}
?>

<main class="product-detail">
    <h1><?= htmlspecialchars($sp['ten_san_pham']) ?></h1>

    <div class="product-detail__content">
        <!-- Ảnh chính -->
        <div class="product-detail__image">
            <img src="/phan_mem_quan_ly_ban_dien_thoai_iphone/public/images/<?= htmlspecialchars($sp['hinh_anh']) ?>"
                 alt="<?= htmlspecialchars($sp['ten_san_pham']) ?>">
        </div>

        <!-- Thông tin -->
        <div class="product-detail__info">
            <p><strong>Giá bán:</strong> <span class="price"><?= number_format($sp['gia_ban'], 0, ',', '.') ?>₫</span></p>
            <p><strong>Mô tả:</strong></p>
            <p><?= nl2br(htmlspecialchars($sp['mo_ta'])) ?></p>
            <a href="?url=giohang/add&id=<?= (int)$sp['ma_san_pham'] ?>" class="btn btn-primary">Thêm vào giỏ</a>
        </div>
    </div>

    <!-- Sản phẩm liên quan -->
    <section class="related-products">
        <h2>Sản phẩm liên quan</h2>
        <?php if (!empty($rel)): ?>
            <div class="grid-sp">
                <?php foreach ($rel as $r): ?>
                    <div class="card">
                        <a href="?url=sanpham/detail&id=<?= (int)$r['ma_san_pham'] ?>">
                            <img src="/phan_mem_quan_ly_ban_dien_thoai_iphone/public/images/<?= htmlspecialchars($r['hinh_anh']) ?>"
                                 alt="<?= htmlspecialchars($r['ten_san_pham']) ?>">
                            <h3><?= htmlspecialchars($r['ten_san_pham']) ?></h3>
                            <p class="price"><?= number_format($r['gia_ban'], 0, ',', '.') ?>₫</p>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Chưa có sản phẩm liên quan.</p>
        <?php endif; ?>
    </section>
</main>

<?php
// Nhúng footer chung của site
include __DIR__ . '/../layouts/footer.php';
?>
