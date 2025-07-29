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
<?php if (!empty($danhMucs)): ?>
<section class="mb-8">
    <h2 class="text-2xl font-bold text-foreground mb-6">Danh Mục Sản Phẩm</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <?php foreach ($danhMucs as $danhMuc): ?>
        <div class="bg-card border rounded-lg p-6 text-center hover:shadow-md transition-shadow cursor-pointer">
            <div class="text-3xl mb-3">📱</div>
            <h3 class="font-semibold text-foreground"><?= htmlspecialchars($danhMuc->getTen()) ?></h3>
            <p class="text-sm text-muted-foreground mt-1"><?= htmlspecialchars($danhMuc->getMoTa() ?? '') ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

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
