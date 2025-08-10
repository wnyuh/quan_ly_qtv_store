<!-- Hero Section -->
<!-- Link Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<section class="swiper bg-gradient-to-r from-primary/10 to-primary/5 rounded-xl p-6 mb-6">
    <div class="swiper-wrapper">
        <!-- Slide 1 -->
        <div class="swiper-slide">
            <div class="text-center">
                <img src="/images/quangcao9.jpg" alt="Khuyến mãi " class="mx-auto rounded-lg mb-4" />
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="swiper-slide">
            <div class="text-center">
                <img src="/images/quangcao4.png" alt="Khuyến mãi" class="mx-auto rounded-lg mb-4" />
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="swiper-slide">
            <div class="text-center">
                <img src="/images/quangcao2.png" alt="Mua trả góp iPhone" class="mx-auto rounded-lg mb-4" />
            </div>
        </div>
    </div>

    <!-- Nút điều khiển -->
    <div class="swiper-pagination"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</section>

<!-- Link Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    new Swiper('.swiper', {
        loop: true,
        autoplay: {
            delay: 3000, // 3 giây tự chuyển
            disableOnInteraction: false
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev'
        }
    });
</script>


<!-- Categories Section -->
<div class="container mx-auto mb-12" x-data="{ scrollX: 0 }">
    <h1 class="text-2xl font-bold text-foreground mb-6">Danh Mục Sản Phẩm</h1>

    <?php if (empty($danhMucs)): ?>
        <p class="text-gray-500 text-center">Chưa có danh mục nào.</p>
    <?php else: ?>
        <div class="relative">
            <div x-ref="scrollContainer" class="flex overflow-x-hidden gap-6 px-8">
                <?php foreach ($danhMucs as $dm): ?>
                    <a href="/danh-muc/<?= htmlspecialchars($dm->getDuongDan()) ?>"
                       class="flex-shrink-0 flex flex-col items-center w-34 px-4 py-3 rounded-sm
                              text-foreground font-medium bg-gray shadow-sm
                              hover:text-yellow-900 hover:bg-yellow-500 hover:shadow-md
                              transition duration-300 group">
                        <?php if ($dm->getHinhAnh()): ?>
                            <img src="<?= htmlspecialchars($dm->getHinhAnh()) ?>"
                                 alt="<?= htmlspecialchars($dm->getTen()) ?>"
                                 class="w-14 h-14 object-cover
                                       group-hover:border-yellow-500 group-hover:scale-105 transition duration-300">
                        <?php else: ?>
                            <div class="w-14 h-14 flex items-center justify-center
                                        bg-gray-200 text-gray-500 rounded-full border-2 border-gray-300">
                                ?
                            </div>
                        <?php endif; ?>
                        <span class="mt-3 text-sm font-semibold text-center leading-snug">
                            <?= htmlspecialchars($dm->getTen()) ?>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Brands Section -->
<div class="container mx-auto mb-12" x-data="{ scrollX: 0 }">
    <h1 class="text-2xl font-bold text-foreground mb-6">Thương Hiệu</h1>
        <?php if (!empty($thuongHieus)): ?>
            <div class="container mx-auto mb-12 overflow-x-auto whitespace-nowrap ">
                <ul class="inline-flex space-x-4 px-8">
                    <?php foreach ($thuongHieus as $thuongHieu): ?>
                        <li class="flex-shrink-0">
                            <a href="/thuong-hieu/<?= htmlspecialchars($thuongHieu->getDuongDan()) ?>"
                               class="flex items-center space-x-2 px-3 py-2 bg-gray-100 shadow-sm rounded-md hover:bg-yellow-500 transition">
                                <img src="<?= htmlspecialchars($thuongHieu->getLogo()) ?>"
                                     alt="<?= htmlspecialchars($thuongHieu->getTen()) ?>"
                                     class="h-6 w-auto">
                                <span class="text-base font-medium text-black">
                    <?= htmlspecialchars($thuongHieu->getTen()) ?>
                  </span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
</div>

<!-- Featured Products -->
<?php if (!empty($sanPhamNoiBat)): ?>
<section class="mb-12">
    <h2 class="text-2xl font-bold text-foreground mb-6">Sản Phẩm Nổi Bật</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach ($sanPhamNoiBat as $sanPham): ?>
            <?php component('product-card', ['sanPham' => $sanPham]); ?>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Latest Products -->
<?php if (!empty($sanPhamMoi)): ?>
<section class="mb-12">
    <h2 class="text-2xl font-bold text-foreground mb-6">Sản Phẩm Mới Nhất</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach ($sanPhamMoi as $sanPham): ?>
            <?php component('product-card', ['sanPham' => $sanPham, 'showDescription' => false]); ?>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Newsletter Section -->
<section class="bg-card border rounded-xl p-8 text-center">
    <h2 class="text-2xl font-bold text-foreground mb-4">Đăng Ký Nhận Tin</h2>
    <p class="text-muted-foreground mb-6">Nhận thông tin về sản phẩm mới và ưu đãi đặc biệt</p>
    <div class="max-w-md mx-auto flex gap-4">
        <input type="email" placeholder="Nhập email của bạn" 
               class="flex-1 px-4 py-2 border border-input rounded-md focus:outline-none focus:ring-2 focus:ring-ring">
        <button class="px-6 py-2 bg-primary text-primary-foreground rounded-md hover:bg-primary/90 transition-colors">
            Đăng Ký
        </button>
    </div>
</section>
