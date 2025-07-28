<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-foreground">Đơn hàng <?= htmlspecialchars($donHang->getMaDonHang()) ?></h1>
            <p class="text-muted-foreground">Người tạo: <?= $donHang->isGuestOrder() ? 'Khách vãng lai' : htmlspecialchars($donHang->getNguoiDung()->getEmail()) ?></p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="/admin/don-hang/sua/<?= $donHang->getId() ?>" class="btn-outline">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                </svg>
                Chỉnh sửa
            </a>
            <a href="/admin/don-hang" class="btn-outline">
                Quay lại
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Thông tin cơ bản -->
            <div class="card">
                <header>
                    <h3>Thông tin Đơn hàng</h3>
                </header>
                <section class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">Mã đơn hàng</label>
                            <p class="text-foreground"><?= htmlspecialchars($donHang->getMaDonHang()) ?></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">Trạng thái xử lý</label>
                            <p class="text-foreground"><?= htmlspecialchars($donHang->getTrangThai()) ?></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">Trạng thái thanh toán</label>
                            <p class="text-foreground"><?= htmlspecialchars($donHang->getTrangThaiThanhToan()) ?></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">Ngày tạo</label>
                            <p class="text-foreground"><?= $donHang->getNgayTao()->format('d/m/Y H:i') ?></p>
                        </div>
                        <?php if ($donHang->getNgayGiao()): ?>
                            <div>
                                <label class="text-sm font-medium text-muted-foreground">Ngày giao</label>
                                <p class="text-foreground"><?= $donHang->getNgayGiao()->format('d/m/Y') ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if ($donHang->getNgayNhan()): ?>
                            <div>
                                <label class="text-sm font-medium text-muted-foreground">Ngày nhận</label>
                                <p class="text-foreground"><?= $donHang->getNgayNhan()->format('d/m/Y') ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if ($donHang->getGhiChu()): ?>
                            <div class="md:col-span-2">
                                <label class="text-sm font-medium text-muted-foreground">Ghi chú</label>
                                <div class="bg-muted/50 p-4 rounded-lg text-foreground whitespace-pre-line">
                                    <?= nl2br(htmlspecialchars($donHang->getGhiChu())) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            </div>

            <!-- Chi tiết sản phẩm -->
            <div class="card">
                <header>
                    <h3>Chi tiết sản phẩm</h3>
                    <p>Tổng số <?= $donHang->getChiTiets()->count() ?> mục</p>
                </header>
                <section class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                        <tr class="border-b border-border">
                            <th class="py-2 px-4 text-left text-sm font-medium text-foreground">Sản phẩm</th>
                            <th class="py-2 px-4 text-right text-sm font-medium text-foreground">Đơn giá</th>
                            <th class="py-2 px-4 text-center text-sm font-medium text-foreground">Số lượng</th>
                            <th class="py-2 px-4 text-right text-sm font-medium text-foreground">Thành tiền</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($donHang->getChiTiets() as $item): ?>
                            <tr class="border-b border-border">
                                <td class="py-2 px-4 text-foreground"><?= htmlspecialchars($item->getSanPham()->getTen()) ?></td>
                                <td class="py-2 px-4 text-right text-foreground"><?= number_format($item->getDonGia(), 0, ',', '.') ?> ₫</td>
                                <td class="py-2 px-4 text-center text-foreground"><?= $item->getSoLuong() ?></td>
                                <td class="py-2 px-4 text-right text-foreground"><?= number_format($item->getDonGia() * $item->getSoLuong(), 0, ',', '.') ?> ₫</td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Thông tin giá -->
            <div class="card">
                <header>
                    <h3>Tóm tắt giá</h3>
                </header>
                <section class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-muted-foreground">Tổng phụ</span>
                        <span class="font-medium text-foreground"><?= number_format($donHang->getTongPhu(), 0, ',', '.') ?> ₫</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-muted-foreground">Thuế</span>
                        <span class="font-medium text-foreground"><?= number_format($donHang->getTienThue(), 0, ',', '.') ?> ₫</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-muted-foreground">Phí vận chuyển</span>
                        <span class="font-medium text-foreground"><?= number_format($donHang->getPhiVanChuyen(), 0, ',', '.') ?> ₫</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-muted-foreground">Giảm giá</span>
                        <span class="font-medium text-foreground">-<?= number_format($donHang->getTienGiamGia(), 0, ',', '.') ?> ₫</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-border">
                        <span class="text-sm font-medium text-foreground">Tổng cộng</span>
                        <span class="text-lg font-bold text-primary"><?= $donHang->getTongTienFormatted() ?></span>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
