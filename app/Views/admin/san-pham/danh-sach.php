<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-foreground">Quản lý Sản phẩm</h1>
            <p class="text-muted-foreground">Danh sách tất cả sản phẩm (<?= $total ?> sản phẩm)</p>
        </div>
        <a href="/admin/san-pham/them" class="btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"/>
                <path d="M12 5v14"/>
            </svg>
            Thêm sản phẩm
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <header>
            <h3>Danh sách sản phẩm</h3>
            <p>Quản lý tất cả sản phẩm trong hệ thống</p>
        </header>
        <section>
            <?php if (empty($sanPhams)): ?>
                <div class="text-center py-8">
                    <div class="text-4xl mb-4">📦</div>
                    <p class="text-muted-foreground">Chưa có sản phẩm nào</p>
                    <a href="/admin/san-pham/them" class="btn mt-4">Thêm sản phẩm đầu tiên</a>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-border">
                                <th class="text-left py-3 px-4 font-medium text-foreground">Sản phẩm</th>
                                <th class="text-left py-3 px-4 font-medium text-foreground">Danh mục</th>
                                <th class="text-left py-3 px-4 font-medium text-foreground">Giá</th>
                                <th class="text-left py-3 px-4 font-medium text-foreground">Trạng thái</th>
                                <th class="text-left py-3 px-4 font-medium text-foreground">Biến thể</th>
                                <th class="text-right py-3 px-4 font-medium text-foreground">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sanPhams as $sanPham): ?>
                                <tr class="border-b border-border hover:bg-muted/50">
                                    <td class="py-3 px-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-12 h-12 bg-muted rounded-lg flex items-center justify-center">
                                                <?php if ($sanPham->getHinhAnhChinh()): ?>
                                                    <img src="<?= htmlspecialchars($sanPham->getHinhAnhChinh()->getFullUrl()) ?>" 
                                                         alt="<?= htmlspecialchars($sanPham->getTen()) ?>"
                                                         class="w-full h-full object-cover rounded-lg">
                                                <?php else: ?>
                                                    <span class="text-xl">📱</span>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <div class="font-medium text-foreground"><?= htmlspecialchars($sanPham->getTen()) ?></div>
                                                <div class="text-sm text-muted-foreground"><?= htmlspecialchars($sanPham->getMaSanPham()) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="text-sm text-muted-foreground">
                                            <?= htmlspecialchars($sanPham->getDanhMuc()->getTen()) ?>
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="font-medium text-foreground"><?= $sanPham->getGiaFormatted() ?></div>
                                        <?php if ($sanPham->getGiaSoSanh() && $sanPham->getGiaSoSanh() > $sanPham->getGia()): ?>
                                            <div class="text-sm text-muted-foreground line-through">
                                                <?= number_format($sanPham->getGiaSoSanh(), 0, ',', '.') ?> ₫
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 px-4">
                                        <?php if ($sanPham->isKichHoat()): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Hoạt động
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Tạm dừng
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($sanPham->isNoiBat()): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Nổi bật
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($sanPham->isSpMoi()): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Sản phẩm mới
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="text-sm text-muted-foreground">
                                            <?= $sanPham->getBienThes()->count() ?> biến thể
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="/admin/san-pham/chi-tiet/<?= $sanPham->getId() ?>" 
                                               class="btn-icon-outline size-8" title="Xem chi tiết">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                    <circle cx="12" cy="12" r="3"/>
                                                </svg>
                                            </a>
                                            <a href="/admin/san-pham/sua/<?= $sanPham->getId() ?>" 
                                               class="btn-icon-outline size-8" title="Chỉnh sửa">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                                                </svg>
                                            </a>
                                            <form method="POST" action="/admin/san-pham/xoa/<?= $sanPham->getId() ?>" class="inline-block" 
                                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                                <button type="submit" class="btn-icon-outline size-8 text-red-600 hover:bg-red-50" title="Xóa">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M3 6h18"/>
                                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($totalPages > 1): ?>
                    <div class="flex items-center justify-between mt-6 pt-6 border-t border-border">
                        <div class="text-sm text-muted-foreground">
                            Trang <?= $currentPage ?> / <?= $totalPages ?>
                        </div>
                        <div class="flex items-center space-x-2">
                            <?php if ($currentPage > 1): ?>
                                <a href="?page=<?= $currentPage - 1 ?>" class="btn-outline text-sm">Trước</a>
                            <?php endif; ?>
                            <?php if ($currentPage < $totalPages): ?>
                                <a href="?page=<?= $currentPage + 1 ?>" class="btn-outline text-sm">Sau</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </section>
    </div>
</div>