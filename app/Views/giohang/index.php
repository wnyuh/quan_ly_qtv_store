<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-foreground">Giỏ hàng</h1>
            <p class="text-muted-foreground mt-1">Quản lý sản phẩm trong giỏ hàng của bạn</p>
        </div>
        <div class="text-right">
            <div class="text-sm text-muted-foreground">Tổng số sản phẩm</div>
            <div class="text-2xl font-bold text-primary">
                <?php
                $totalQuantity = 0;
                foreach ($cartItems as $item) {
                    $totalQuantity += $item['qty'];
                }
                echo $totalQuantity;
                ?>
            </div>
        </div>
    </div>

    <?php if (empty($cartItems)): ?>
        <!-- Empty Cart State -->
        <div class="card text-center py-12">
            <div class="mx-auto w-24 h-24 bg-muted rounded-full flex items-center justify-center mb-4">
                <!-- Icon giỏ trống -->
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground">
                    <circle cx="8" cy="21" r="1" />
                    <circle cx="19" cy="21" r="1" />
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57L20.3 9H5.12" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-foreground mb-2">Giỏ hàng trống</h3>
            <p class="text-muted-foreground mb-6">Chưa có sản phẩm nào trong giỏ hàng của bạn</p>
            <a href="/" class="btn inline-flex items-center gap-2">
                <!-- Icon tiếp tục mua sắm -->
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 12h18m-9-9l9 9-9 9" />
                </svg>
                Tiếp tục mua sắm
            </a>
        </div>
    <?php else: ?>
        <form method="post" action="/gio-hang/cap-nhat" class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Danh sách sản phẩm -->
                <div class="lg:col-span-2">
                    <div class="card p-6 space-y-6">
                        <?php
                        $tong = 0;
                        foreach ($cartItems as $index => $item):
                            $bienThe = $item['bienThe'];
                            $qty = $item['qty'];
                            $sanPham = $bienThe->getSanPham();
                            $thanhTien = $bienThe->getGia() * $qty;
                            $tong += $thanhTien;
                            // Lấy ảnh đại diện biến thể (nếu có)
                            $hinhAnhs = $bienThe->getHinhAnhs();
                            $hinhAnhObj = is_array($hinhAnhs) ? ($hinhAnhs[0] ?? null) : ($hinhAnhs->first() ?: null);
                            $image = $hinhAnhObj && method_exists($hinhAnhObj, 'getDuongDan')
                                ? '/images/' . ltrim($hinhAnhObj->getDuongDan(), '/')
                                : null;
                        ?>
                            <div class="flex items-start gap-4 hover:bg-muted/30 transition-colors p-4 rounded-md">
                                <div class="flex-shrink-0 w-24 h-24 bg-gray-100 rounded-md overflow-hidden border border-border">
                                    <?php if ($image): ?>
                                        <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($bienThe->getTenDayDu()) ?>" class="w-full h-full object-cover" />
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center text-muted-foreground">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                <rect width="18" height="18" x="3" y="3" rx="2" />
                                                <path d="m9 15 6-6m0 6-6-6" />
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-foreground line-clamp-2"><?= htmlspecialchars($bienThe->getTenDayDu()) ?></h3>
                                    <p class="text-sm text-muted-foreground mt-1"><?= htmlspecialchars($sanPham->getThuongHieu()->getTen() ?? '') ?></p>

                                    <div class="mt-3 flex items-center gap-6">
                                        <div class="text-lg font-bold text-primary"><?= number_format($bienThe->getGia(), 0, ',', '.') ?> ₫</div>
                                        <?php if ($bienThe->getGiaSoSanh() && $bienThe->getGiaSoSanh() > $bienThe->getGia()): ?>
                                            <div class="text-sm text-muted-foreground line-through"><?= number_format($bienThe->getGiaSoSanh(), 0, ',', '.') ?> ₫</div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mt-4 flex items-center gap-3">
                                        <input type="number" name="quantities[<?= $index ?>]" value="<?= $qty ?>" min="0" class="w-16 text-center border rounded-md py-1" />
                                        <input type="hidden" name="ids[<?= $index ?>]" value="<?= htmlspecialchars($bienThe->getId()) ?>" />
                                    </div>

                                    <div class="mt-4 flex justify-between items-center border-t border-border pt-3">
                                        <span class="text-sm text-muted-foreground">Thành tiền:</span>
                                        <span class="text-xl font-semibold"><?= number_format($thanhTien, 0, ',', '.') ?> ₫</span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="pt-6 lg:col-span-2">
                        <div class="card">
                            <header>
                                <h3>Gợi ý cho bạn</h3>
                                <p>Sản phẩm có thể bạn quan tâm</p>
                            </header>
                            <section>
                                <div class="text-center py-8 text-muted-foreground">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4">
                                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                                        <line x1="3" x2="21" y1="6" y2="6" />
                                        <path d="M16 10a4 4 0 0 1-8 0" />
                                    </svg>
                                    Chức năng gợi ý sản phẩm đang được phát triển
                                </div>
                            </section>
                        </div>
                    </div>

                </div>

                <!-- Tóm tắt đơn hàng + Form thanh toán -->
                <div class="lg:col-span-1">
                    <div class="card sticky top-6 p-6 space-y-6">
                        <header>
                            <h3>Tóm tắt đơn hàng</h3>
                            <p>Chi tiết thanh toán</p>
                        </header>
                        <section class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-muted-foreground">Tạm tính:</span>
                                <span class="font-semibold"><?= number_format($tong, 0, ',', '.') ?> ₫</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-muted-foreground">Phí vận chuyển:</span>
                                <span class="text-sm text-muted-foreground">Miễn phí</span>
                            </div>
                            <?php if ($giamGiaTien > 0): ?>
                                <div class="flex justify-between items-center text-green-700">
                                    <span>Giảm giá:</span>
                                    <span>-<?= number_format($giamGiaTien, 0, ',', '.') ?> ₫</span>
                                </div>
                            <?php endif; ?>
                            <div class="border-t border-border pt-4 flex justify-between items-center font-semibold text-lg">
                                <span>Tổng cộng:</span>
                                <span class="text-2xl font-bold text-primary">
                                    <?= number_format($tongCong - $giamGiaTien, 0, ',', '.') ?> ₫
                                </span>
                            </div>
                        </section>

                        <form action="/gio-hang/dat-hang" method="POST" class="space-y-4 mt-6">
                            <?php if ($nguoiDung): ?>
                                <div>
                                    <label class="font-medium">Tên khách hàng</label>
                                    <input type="text" value="<?= htmlspecialchars($nguoiDung->getHoTen()) ?>" readonly
                                        class="w-full border px-3 py-2 rounded-md  cursor-not-allowed" />
                                </div>
                                <div>
                                    <label class="font-medium">Số điện thoại</label>
                                    <input type="text" value="<?= htmlspecialchars($nguoiDung->getSoDienThoai() ?? '') ?>" readonly
                                        class="w-full border px-3 py-2 rounded-md  cursor-not-allowed" />
                                </div>
                            <?php else: ?>
                                <div>
                                    <label class="font-medium">Tên khách hàng</label>
                                    <input type="text" name="ho_ten" required class="w-full border px-3 py-2 rounded-md" />
                                </div>
                                <div>
                                    <label class="font-medium">Số điện thoại</label>
                                    <input type="text" name="so_dien_thoai" required class="w-full border px-3 py-2 rounded-md" />
                                </div>
                            <?php endif; ?>
                            <div>
                                <label class="font-medium">Địa chỉ giao hàng <span class="text-red-600">*</span></label>
                                <textarea name="dia_chi_giao" required class="w-full border px-3 py-2 rounded-md"></textarea>
                            </div>
                            <div>
                                <label class="font-medium">Phương thức thanh toán <span class="text-red-600">*</span></label>
                                <select name="hinh_thuc_thanh_toan" required class="w-full border px-3 py-2 rounded-md">
                                    <option value="cod" selected>Thanh toán khi nhận hàng (COD)</option>
                                    <option value="chuyen_khoan" disabled class="opacity-50 cursor-not-allowed">Chuyển khoản (Chưa khả dụng)</option>
                                </select>
                            </div>
                            <div>
                                <label class="font-medium">Mã giảm giá <span class="text-red-600">*</span></label>
                                <input type="text" name="ma_giam_gia" value="<?= htmlspecialchars($maGiamGiaApDung ?? '') ?>" placeholder="Nhập mã giảm giá" class="w-full border px-3 py-2 rounded-md" />
                                <?php if (!empty($_GET['error_ma_giam_gia'])): ?>
                                    <p class="text-red-600 mt-1">Mã giảm giá không hợp lệ hoặc đã hết hạn.</p>
                                <?php endif; ?>
                            </div>
                            <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2 rounded-md">
                                Đặt hàng
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>

<script>
    document.querySelectorAll('input[name^="quantities"]').forEach(input => {
        input.addEventListener('change', () => {
            input.closest('form').submit();
        });
    });
</script>
