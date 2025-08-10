<?php
/** @var \App\Models\DonHang $donHang */
$thanhToan = $donHang->getThanhToans() ? $donHang->getThanhToans()->first() : null;
$paymentMethod = $thanhToan ? $thanhToan->getPhuongThucThanhToan() : '';
$diaChiGiaoHang = $donHang->getDiaChiGiaoHang();
?>
<div class="max-w-4xl mx-auto p-6">
    <div class="bg-card border border-border rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-foreground mb-4">Thông tin đơn hàng</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Order Details -->
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-muted-foreground">Mã đơn hàng:</span>
                    <span class="text-sm font-semibold text-foreground">
                        <?= htmlspecialchars($donHang->getMaDonHang()) ?>
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-sm font-medium text-muted-foreground">Ngày đặt hàng:</span>
                    <span class="text-sm text-foreground">
                        <?= $donHang->getNgayTao()->format('d/m/Y H:i') ?>
                    </span>
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
                        <?= $paymentMethod ? htmlspecialchars($paymentMethod) : 'N/A' ?>
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
                <?php if ($diaChiGiaoHang): ?>
                    <div class="text-lg text-muted-foreground space-y-1">
                        <p class="font-medium text-foreground">
                            <?= htmlspecialchars($diaChiGiaoHang->getHoTen()) ?>
                        </p>
                        <?php if ($diaChiGiaoHang->getCongTy()): ?>
                            <p><?= htmlspecialchars($diaChiGiaoHang->getCongTy()) ?></p>
                        <?php endif; ?>
                        <p><?= htmlspecialchars($diaChiGiaoHang->getDiaChiDayDu()) ?></p>
                        <p><?= htmlspecialchars($diaChiGiaoHang->getSoDienThoai()) ?></p>
                    </div>
                <?php else: ?>
                    <p class="text-sm text-muted-foreground">Chưa có địa chỉ giao hàng.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="bg-card border border-border rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-foreground mb-4">Sản phẩm đã đặt</h2>

        <div class="space-y-4">
            <?php if ($donHang->getChiTiets() && count($donHang->getChiTiets()) > 0): ?>
                <?php foreach ($donHang->getChiTiets() as $chiTiet): ?>
                    <div class="flex items-center space-x-4 py-4 border-b border-border last:border-b-0">
                        <?php
                        $sanPham = $chiTiet->getBienTheSanPham() ? $chiTiet->getBienTheSanPham()->getSanPham() : null;
                        $hinhAnhChinh = $sanPham ? $sanPham->getHinhAnhChinh() : null;
                        $hinhAnhUrl = $hinhAnhChinh ? $hinhAnhChinh->getFullUrl() : '/assets/images/placeholder.jpg';
                        ?>
                        <img src="<?= htmlspecialchars($hinhAnhUrl) ?>"
                             alt="<?= htmlspecialchars($chiTiet->getTenSanPham()) ?>"
                             class="w-16 h-16 object-cover rounded-md">

                        <div class="flex-1">
                            <h4 class="font-medium text-foreground">
                                <?= htmlspecialchars($chiTiet->getTenSanPham()) ?>
                            </h4>
                            <p class="text-sm text-muted-foreground">
                                <?php
                                $chiTietBienThe = $chiTiet->getChiTietBienThe();
                                if ($chiTietBienThe) {
                                    $details = [];
                                    if (!empty($chiTietBienThe['bo_nho']))  $details[] = $chiTietBienThe['bo_nho'];
                                    if (!empty($chiTietBienThe['mau_sac'])) $details[] = $chiTietBienThe['mau_sac'];
                                    echo htmlspecialchars(implode(' - ', $details));
                                }
                                ?>
                            </p>
                            <p class="text-sm text-muted-foreground">
                                Số lượng: <?= (int) $chiTiet->getSoLuong() ?>
                            </p>
                        </div>

                        <div class="text-right">
                            <p class="font-medium text-foreground">
                                <?= number_format($chiTiet->getGiaDonVi(), 0, ',', '.') ?> ₫
                            </p>
                            <p class="text-sm text-muted-foreground">
                                Tổng: <?= $chiTiet->getTongGiaFormatted() ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-sm text-muted-foreground">Đơn hàng chưa có sản phẩm.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="bg-card border border-border rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold text-foreground mb-4">Tóm tắt đơn hàng</h2>

        <div class="space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-muted-foreground">Tạm tính:</span>
                <span class="text-foreground"><?= number_format($donHang->getTongPhu(), 0, ',', '.') ?> ₫</span>
            </div>

            <div class="flex justify-between text-sm">
                <span class="text-muted-foreground">Phí vận chuyển:</span>
                <span class="text-foreground"><?= number_format($donHang->getPhiVanChuyen(), 0, ',', '.') ?> ₫</span>
            </div>

            <?php if ($donHang->getTienThue() > 0): ?>
                <div class="flex justify-between text-sm">
                    <span class="text-muted-foreground">Thuế:</span>
                    <span class="text-foreground"><?= number_format($donHang->getTienThue(), 0, ',', '.') ?> ₫</span>
                </div>
            <?php endif; ?>

            <?php if ($donHang->getTienGiamGia() > 0): ?>
                <div class="flex justify-between text-sm">
                    <span class="text-muted-foreground">Giảm giá:</span>
                    <span class="text-red-600">-<?= number_format($donHang->getTienGiamGia(), 0, ',', '.') ?> ₫</span>
                </div>
            <?php endif; ?>

            <hr class="border-border">

            <div class="flex justify-between font-semibold text-lg">
                <span class="text-foreground">Tổng cộng:</span>
                <span class="text-primary"><?= $donHang->getTongTienFormatted() ?></span>
            </div>
        </div>
    </div>

    <!-- Action Buttons / Notes by payment method -->
    <div class="text-center space-y-4">
        <?php if ($paymentMethod === 'cash_on_delivery'): ?>
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-center space-x-2 text-orange-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2"></path>
                    </svg>
                    <p class="text-sm font-medium">
                        Bạn sẽ thanh toán bằng tiền mặt khi nhận hàng.
                        Vui lòng chuẩn bị: <span class="font-bold"><?= $donHang->getTongTienFormatted() ?></span>
                    </p>
                </div>
            </div>
        <?php elseif (in_array($paymentMethod, ['momo_wallet', 'zalopay_wallet'])): ?>
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-center space-x-2 text-purple-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-sm font-medium">
                        Đơn hàng đang chờ thanh toán qua ví điện tử.
                        Chúng tôi sẽ liên hệ để hướng dẫn thanh toán.
                    </p>
                </div>
            </div>
        <?php elseif ($paymentMethod === 'bank_transfer'): ?>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-center space-x-2 text-green-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <p class="text-sm font-medium">
                        Vui lòng chuyển khoản theo hướng dẫn được gửi qua email.
                        Đơn sẽ xử lý sau khi nhận được thanh toán.
                    </p>
                </div>
            </div>
        <?php endif; ?>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-center space-x-2 text-blue-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm font-medium">
                    Chúng tôi sẽ gửi email xác nhận và theo dõi đến
                    <span class="font-semibold">
                        <?= htmlspecialchars($donHang->getNguoiDung() ? $donHang->getNguoiDung()->getEmail() : $donHang->getEmailKhach()) ?>
                    </span>
                </p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/"
               class="inline-flex h-10 items-center justify-center rounded-md border border-input bg-background px-6 py-2 text-sm font-medium ring-offset-background transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                Tiếp tục mua sắm
            </a>

            <a href="/don-hang"
               class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-6 py-2 text-sm font-medium text-primary-foreground ring-offset-background transition-colors hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                Quay lại đơn hàng
            </a>
        </div>
    </div>
</div>
