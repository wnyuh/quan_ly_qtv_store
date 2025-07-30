<?php
?>
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


