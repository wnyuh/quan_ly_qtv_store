<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-foreground">Quản lý Đơn hàng</h1>
            <p class="text-muted-foreground">Danh sách tất cả đơn hàng (<?= $total ?> đơn hàng)</p>
        </div>
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
            <h3>Danh sách Đơn hàng</h3>
            <p>Quản lý các đơn hàng trong hệ thống</p>
        </header>
        <section>
            <?php if (empty($donHangs)): ?>
                <div class="text-center py-8">
                    <div class="text-4xl mb-4">🛒</div>
                    <p class="text-muted-foreground">Chưa có đơn hàng nào</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                        <tr class="border-b border-border">
                            <th class="text-left py-3 px-4 font-medium text-foreground">Mã đơn</th>
                            <th class="text-left py-3 px-4 font-medium text-foreground">Khách hàng</th>
                            <th class="text-left py-3 px-4 font-medium text-foreground">Trạng thái</th>
                            <th class="text-left py-3 px-4 font-medium text-foreground">Thanh toán</th>
                            <th class="text-left py-3 px-4 font-medium text-foreground">Tổng tiền</th>
                            <th class="text-left py-3 px-4 font-medium text-foreground">Ngày tạo</th>
                            <th class="text-right py-3 px-4 font-medium text-foreground">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($donHangs as $dh): ?>
                            <tr class="border-b border-border hover:bg-muted/50">
                                <td class="py-3 px-4"><?= htmlspecialchars($dh->getMaDonHang()) ?></td>
                                <td class="py-3 px-4">
                                    <?php if ($dh->getNguoiDung()): ?>
                                        <?= htmlspecialchars($dh->getNguoiDung()->getEmail()) ?>
                                    <?php else: ?>
                                        <?= htmlspecialchars($dh->getEmailKhach() ?? 'Khách vãng lai') ?>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-sm text-foreground"><?= htmlspecialchars($dh->getTrangThai()) ?></span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-sm text-foreground"><?= htmlspecialchars($dh->getTrangThaiThanhToan()) ?></span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="font-medium text-foreground"><?= $dh->getTongTienFormatted() ?></span>
                                </td>
                                <td class="py-3 px-4"><?= $dh->getNgayTao()->format('d/m/Y H:i') ?></td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="/admin/don-hang/chi-tiet/<?= $dh->getId() ?>" class="btn-icon-outline size-8" title="Xem chi tiết">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        </a>
                                        <a href="/admin/don-hang/sua/<?= $dh->getId() ?>" class="btn-icon-outline size-8" title="Sửa">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                                            </svg>
                                        </a>
                                        <form method="POST" action="/admin/don-hang/xoa/<?= $dh->getId() ?>" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?')">
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
                        <div class="text-sm text-muted-foreground">Trang <?= $currentPage ?> / <?= $totalPages ?></div>
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
