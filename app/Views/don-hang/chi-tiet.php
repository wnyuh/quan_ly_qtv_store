<div class="bg-card border border-border rounded-lg p-6 mb-6">
    <h2 class="text-xl font-semibold text-foreground mb-4">Thông tin đơn hàng</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Order Details -->
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-sm font-medium text-muted-foreground">Mã đơn hàng:</span>
                <span class="text-sm font-semibold text-foreground"><?= htmlspecialchars($donHang->getMaDonHang()) ?></span>
            </div>
            
            <div class="flex justify-between">
                <span class="text-sm font-medium text-muted-foreground">Ngày đặt hàng:</span>
                <span class="text-sm text-foreground"><?= $donHang->getNgayTao()->format('d/m/Y H:i') ?></span>
            </div>
            
            <div class="flex justify-between">
                <span class="text-sm font-medium text-muted-foreground">Trạng thái:</span>
                <span class="inline-flex px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                    <?= ucfirst(str_replace('_', ' ', $donHang->getTrangThai())) ?>
                </span>
            </div>
            
            <div class="flex justify-between">
                <span class="text-sm font-medium text-muted-foreground">Phương thức thanh toán:</span>
                <span class="text-sm text-foreground">
                    <?php 
                    $thanhToan = $donHang->getThanhToans()->first();
                    echo $thanhToan ? htmlspecialchars($thanhToan->getPhuongThucThanhToan()) : 'N/A';
                    ?>
                </span>
            </div>
            
            <div class="flex justify-between">
                <span class="text-sm font-medium text-muted-foreground">Tổng thanh toán:</span>
                <span class="text-lg font-bold text-primary"><?= $donHang->getTongTienFormatted() ?></span>
            </div>
        </div>

        <!-- Shipping Address -->
        <div class="space-y-3">
            <h3 class="font-semibold text-foreground">Địa chỉ giao hàng</h3>
            <?php $diaChiGiaoHang = $donHang->getDiaChiGiaoHang(); ?>
            <?php if ($diaChiGiaoHang): ?>
                <div class="text-sm text-muted-foreground space-y-1">
                    <p class="font-medium text-foreground"><?= htmlspecialchars($diaChiGiaoHang->getHoTen()) ?></p>
                    <?php if ($diaChiGiaoHang->getCongTy()): ?>
                        <p><?= htmlspecialchars($diaChiGiaoHang->getCongTy()) ?></p>
                    <?php endif; ?>
                    <p><?= htmlspecialchars($diaChiGiaoHang->getDiaChiDayDu()) ?></p>
                    <p><?= htmlspecialchars($diaChiGiaoHang->getSoDienThoai()) ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
