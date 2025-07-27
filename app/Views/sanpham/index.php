<?php
// app/Views/sanpham/index.php
?>

<main class="container sanpham-page">

    <h1 class="page-title">Danh sách sản phẩm</h1>

    <!-- Tabs danh mục -->
    <nav class="tabs">
        <a href="index.php?url=sanpham/index"
           class="<?= empty($_GET['dm']) ? 'active' : '' ?>">
            Tất cả
        </a>
        <?php foreach($dms as $dm): ?>
            <a href="index.php?url=sanpham/index&dm=<?= $dm['ma_danh_muc'] ?>"
               class="<?= (isset($_GET['dm']) && $_GET['dm']==$dm['ma_danh_muc'])?'active':'' ?>">
                <?= htmlspecialchars($dm['ten_danh_muc']) ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <!-- Lưới sản phẩm -->
    <section class="grid-sp">
        <?php if (empty($sps)): ?>
            <p class="no-products">Chưa có sản phẩm nào.</p>
        <?php else: ?>
            <?php foreach($sps as $sp): ?>
                <div class="card">
                    <div class="card-img-wrap">
                        <?php if (!empty($sp['hinh_anh'])): ?>
                            <img src="images/<?= htmlspecialchars($sp['hinh_anh']) ?>"
                                 alt="<?= htmlspecialchars($sp['ten_san_pham']) ?>" />
                        <?php else: ?>
                            <img src="images/no-image.png" alt="No image" />
                        <?php endif; ?>
                    </div>
                    <h3><?= htmlspecialchars($sp['ten_san_pham']) ?></h3>
                    <p class="price"><?= number_format($sp['gia_ban'],0,',','.') ?>₫</p>
                    <a class="btn"
                       href="index.php?url=sanpham/detail&id=<?= $sp['ma_san_pham'] ?>">
                        Xem chi tiết
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

</main>

<?php
// Bao footer (nếu bạn có file footer.php)
include __DIR__ . '/../layouts/footer.php';
?>
