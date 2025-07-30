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

    <!-- Thanh danh mục -->
    <?php if (!empty($danhMucs)): ?>
        <div class="border-b border-gray-300 mb-6">
            <div class="flex overflow-x-auto no-scrollbar space-x-6 justify-center">
                <?php foreach ($danhMucs as $dm): ?>
                    <a href="/danh-muc/<?= htmlspecialchars($dm->getDuongDan()) ?>"
                       class="py-3 px-5 font-medium border-b-2 border-transparent transition
                              hover:border-red-500 hover:text-red-600
                              <?= ($currentDanhMuc && $currentDanhMuc->getId() === $dm->getId())
                           ? 'border-red-600 text-red-600'
                           : 'text-gray-700' ?>">
                        <?= htmlspecialchars($dm->getTen()) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Danh sách sản phẩm -->
    <?php if (empty($sanPhams)): ?>
        <p class="text-gray-500 text-center">Chưa có sản phẩm trong danh mục này.</p>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($sanPhams as $sp): ?>
                <div class="border rounded-xl overflow-hidden hover:shadow-xl transition bg-white">
                    <a href="/san-pham/<?= htmlspecialchars($sp->getDuongDan()) ?>">
                        <img src="<?= htmlspecialchars($sp->getHinhChinhUrl()) ?>"
                             alt="<?= htmlspecialchars($sp->getTen()) ?>"
                             class="w-full h-56 object-cover hover:scale-105 transition">
                        <div class="p-4">
                            <h2 class="font-semibold text-lg mb-2 text-gray-800 truncate">
                                <?= htmlspecialchars($sp->getTen()) ?>
                            </h2>
                            <p class="text-lg font-bold text-red-600">
                                <?= number_format($sp->getGia(), 0, ',', '.') ?> ₫
                            </p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
