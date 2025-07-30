<!-- Hero Section -->
<section class="bg-gradient-to-r from-primary/10 to-primary/5 rounded-xl p-6 mb-6">
    <div class="text-center">
        <h1 class="text-xl font-bold text-foreground mb-2 md:mb-8">Cửa Hàng Điện Thoại Hàng Đầu</h1>
        <p class="text-lg text-muted-foreground mb-6 hidden md:block ">Khám phá các mẫu iPhone mới nhất với giá tốt nhất</p>
        <a href="/tim-kiem-san-pham" class="px-8 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors font-medium">
            Xem Sản Phẩm
        </a>
    </div>
</section>

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
                              hover:text-yellow-600 hover:bg-yellow-100 hover:shadow-md
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

<!-- Brands Section -->
<?php if (!empty($thuongHieus)): ?>
<section class="mb-12">
    <h2 class="text-2xl font-bold text-foreground mb-6">Thương Hiệu</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        <?php foreach ($thuongHieus as $thuongHieu): ?>
        <div class="bg-card border rounded-lg p-4 text-center hover:shadow-md transition-shadow cursor-pointer">
            <div class="font-semibold text-foreground"><?= htmlspecialchars($thuongHieu->getTen()) ?></div>
        </div>
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
