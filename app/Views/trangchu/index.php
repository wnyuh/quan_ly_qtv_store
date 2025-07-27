<!-- app/Views/trangchu/index.php -->


<link
        rel="stylesheet"
        href="https://unpkg.com/swiper/swiper-bundle.min.css"
/>

<section class="bannerSwiper swiper">
    <div class="swiper-wrapper">
        <?php foreach($banners as $b): ?>
            <div class="swiper-slide">
                <img src="images/<?= htmlspecialchars($b['hinh_anh']) ?>"
                     alt=""
                     class="banner-img" />
            </div>
        <?php endforeach; ?>
    </div>
    <div class="swiper-pagination"></div>
</section>

<nav class="tabs">
    <?php foreach($dms as $dm): ?>
        <a href="index.php?url=sanpham/index&dm=<?= $dm['ma_danh_muc'] ?>">
            <?= htmlspecialchars($dm['ten_danh_muc']) ?>
        </a>
    <?php endforeach; ?>
</nav>

<section class="grid-sp">
    <?php foreach($sps as $sp): ?>
        <div class="card">
            <div class="card-img-wrap">
                <img src="images/<?= htmlspecialchars($sp['hinh_anh']) ?>"
                     alt="<?= htmlspecialchars($sp['ten_san_pham']) ?>" />
            </div>
            <h3><?= htmlspecialchars($sp['ten_san_pham']) ?></h3>
            <p class="price"><?= number_format($sp['gia_ban'],0,',','.') ?>₫</p>
            <a class="btn" href="index.php?url=sanpham/detail&id=<?= $sp['ma_san_pham'] ?>">
                Xem chi tiết
            </a>
        </div>
    <?php endforeach; ?>
</section>

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    new Swiper('.bannerSwiper', {
        loop: true,
        pagination: { el: '.swiper-pagination' },
        autoplay: { delay: 3000 }
    });
</script>
