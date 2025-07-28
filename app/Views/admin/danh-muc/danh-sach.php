<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-foreground">Quản lý Danh mục</h1>
            <p class="text-muted-foreground">
                Danh sách tất cả danh mục (<?= $total ?> danh mục)
            </p>
        </div>
        <a href="/admin/danh-muc/them" class="btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"/>
                <path d="M12 5v14"/>
            </svg>
            Thêm danh mục
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
            <h3>Danh sách danh mục</h3>
            <p>Quản lý tất cả danh mục trong hệ thống</p>
        </header>
        <section>
            <?php if (empty($danhMucs)): ?>
                <div class="text-center py-8">
                    <div class="text-4xl mb-4">📂</div>
                    <p class="text-muted-foreground">Chưa có danh mục nào</p>
                    <a href="/admin/danh-muc/them" class="btn mt-4">Thêm danh mục đầu tiên</a>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                        <tr class="border-b border-border">
                            <th class="text-left py-3 px-4 font-medium text-foreground">Tên danh mục</th>
                            <th class="text-left py-3 px-4 font-medium text-foreground">Đường dẫn</th>
                            <th class="text-left py-3 px-4 font-medium text-foreground">Thứ tự</th>
                            <th class="text-left py-3 px-4 font-medium text-foreground">Trạng thái</th>
                            <th class="text-right py-3 px-4 font-medium text-foreground">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($danhMucs as $dm): ?>
                            <tr class="border-b border-border hover:bg-muted/50">
                                <td class="py-3 px-4">
                                    <div class="font-medium text-foreground"><?= htmlspecialchars($dm->getTen()) ?></div>
                                </td>
                                <td class="py-3 px-4">
                                        <span class="text-sm text-muted-foreground">
                                            <?= htmlspecialchars($dm->getDuongDan()) ?>
                                        </span>
                                </td>
                                <td class="py-3 px-4">
                                    <?= htmlspecialchars($dm->getThuTu()) ?>
                                </td>
                                <td class="py-3 px-4">
                                    <?php if ($dm->isKichHoat()): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Kích hoạt
                                            </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Tạm dừng
                                            </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="/admin/danh-muc/chi-tiet/<?= $dm->getId() ?>"
                                           class="btn-icon-outline size-8" title="Xem chi tiết">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        </a>
                                        <a href="/admin/danh-muc/sua/<?= $dm->getId() ?>"
                                           class="btn-icon-outline size-8" title="Chỉnh sửa">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                                            </svg>
                                        </a>
                                        <form method="POST" action="/admin/danh-muc/xoa/<?= $dm->getId() ?>"
                                              class="inline-block"
                                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')">
                                            <button type="submit"
                                                    class="btn-icon-outline size-8 text-red-600 hover:bg-red-50"
                                                    title="Xóa">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
