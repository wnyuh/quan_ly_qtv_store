<?php
/**
 * @var \App\Models\DanhMuc $danhMuc          // Danh mục hiện tại
 * @var \App\Models\SanPham[] $sanPhams       // Danh sách sản phẩm thuộc danh mục
 * @var \App\Models\DanhMuc[] $danhMucs       // Danh sách tất cả danh mục (hoặc danh mục con tuỳ controller)
 * @var \App\Models\DanhMuc $currentDanhMuc   // Danh mục đang được chọn
 */
?>
<div class="container mx-auto px-4 py-8">
    <!-- Tiêu đề danh mục -->
    <h1 class="text-3xl font-bold mb-6 text-center">
        <?= htmlspecialchars($danhMuc->getTen()) ?>
    </h1>
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

    <!-- Danh sách sản phẩm -->
    <?php if (empty($sanPhams)): ?>
        <p class="text-gray-500 text-center">Chưa có sản phẩm trong danh mục này.</p>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($sanPhams as $sp): ?>
                <?php $hinhAnhChinh = $sp->getHinhAnhChinh(); ?>
                <div class="bg-card border border-border rounded-xl overflow-hidden shadow hover:shadow-lg transition duration-300">
                    <a href="/san-pham/<?= htmlspecialchars($sp->getDuongDan()) ?>" class="block group">

                        <!-- Ảnh sản phẩm -->
                        <div class="aspect-square bg-white flex items-center justify-center p-4">
                            <img src="<?= $hinhAnhChinh ? htmlspecialchars($hinhAnhChinh->getFullUrl()) : '/images/no-image.png' ?>"
                                 alt="<?= htmlspecialchars($sp->getTen()) ?>"
                                 class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
                        </div>

                        <!-- Thông tin sản phẩm -->
                        <div class="p-4 space-y-2">
                            <!-- Tên sản phẩm -->
                            <h2 class="text-base font-semibold text-foreground truncate group-hover:text-primary transition">
                                <?= htmlspecialchars($sp->getTen()) ?>
                            </h2>

                            <!-- Thương hiệu -->
                            <p class="text-sm text-muted-foreground">
                                <?= htmlspecialchars($sp->getThuongHieu()->getTen()) ?>
                            </p>

                            <!-- Giá -->
                            <div class="flex items-end gap-2">
                    <span class="text-lg font-bold text-primary">
                        <?= $sp->getGiaFormatted() ?>
                    </span>
                                <?php if ($sp->getGiaSoSanh() && $sp->getGiaSoSanh() > $sp->getGia()): ?>
                                    <span class="text-sm text-muted-foreground line-through">
                            <?= number_format($sp->getGiaSoSanh(), 0, ',', '.') ?> ₫
                        </span>
                                <?php endif; ?>
                            </div>

                            <!-- Mô tả ngắn -->
                            <?php if ($sp->getMoTaNgan()): ?>
                                <p class="text-xs text-muted-foreground leading-snug line-clamp-2">
                                    <?= htmlspecialchars($sp->getMoTaNgan()) ?>
                                </p>
                            <?php endif; ?>

                            <!-- Badge -->
                            <div class="pt-2">
                                <?php if ($sp->isNoiBat()): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300">
                            Nổi bật
                        </span>
                                <?php else: ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>

        </div>
    <?php endif; ?>
</div>
