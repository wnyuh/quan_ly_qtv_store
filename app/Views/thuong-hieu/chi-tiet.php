<?php
/**
 * @var \App\Models\ThuongHieu $thuongHieu
 * @var \App\Models\SanPham[] $sanPhams
 * @var \App\Models\ThuongHieu[] $thuongHieus
 */
?>

<!-- Nav ngang các thương hiệu -->
<h1 class="text-2xl font-bold text-foreground mb-6">Thương Hiệu</h1>
<div class="container mx-auto mb-6 overflow-x-auto whitespace-nowrap px-6">
    <ul class="inline-flex space-x-4">
        <?php foreach ($thuongHieus as $b): ?>
            <li class="flex-shrink-0">
                <a href="/thuong-hieu/<?= htmlspecialchars($b->getDuongDan()) ?>"
                   class="flex items-center space-x-2 px-3 py-2 rounded-md transition
             <?= $b->getId() === $thuongHieu->getId()
                       ? 'bg-primary text-primary-foreground'
                       : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                    <img src="<?= htmlspecialchars($b->getLogo()) ?>"
                         alt="<?= htmlspecialchars($b->getTen()) ?>"
                         class="h-6 w-auto">
                    <span class="text-base font-medium text-black"><?= htmlspecialchars($b->getTen()) ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- Header brand -->
<section class="bg-gradient-to-r from-primary/10 to-primary/5 rounded-xl p-6 mb-8 container mx-auto px-6">
    <div class="text-center">
        <h1 class="text-3xl font-bold mb-2"><?= htmlspecialchars($thuongHieu->getTen()) ?></h1>
        <?php if ($thuongHieu->getMoTa()): ?>
            <p class="text-base text-muted-foreground"><?= htmlspecialchars($thuongHieu->getMoTa()) ?></p>
        <?php endif; ?>
    </div>
</section>

<!-- Grid sản phẩm -->
<section class="container mx-auto px-6 mb-12">
    <?php if (empty($sanPhams)): ?>
        <p class="text-center text-gray-500">Chưa có sản phẩm nào thuộc thương hiệu này.</p>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($sanPhams as $sp): ?>
                <?php component('product-card', ['sanPham' => $sp]); ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
