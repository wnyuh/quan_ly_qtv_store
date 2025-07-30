<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-foreground">Giỏ hàng</h1>
            <p class="text-muted-foreground mt-1">Quản lý sản phẩm trong giỏ hàng của bạn</p>
        </div>
        <div class="text-right">
            <div class="text-sm text-muted-foreground">Tổng số sản phẩm</div>
            <div class="text-2xl font-bold text-primary"><?= count($cartItems) ?></div>
        </div>
    </div>

    <?php if (empty($cartItems)): ?>
        <!-- Empty Cart State -->
        <div class="card text-center py-12">
            <div class="mx-auto w-24 h-24 bg-muted rounded-full flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground">
                    <circle cx="8" cy="21" r="1"/>
                    <circle cx="19" cy="21" r="1"/>
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57L20.3 9H5.12"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-foreground mb-2">Giỏ hàng trống</h3>
            <p class="text-muted-foreground mb-6">Chưa có sản phẩm nào trong giỏ hàng của bạn</p>
            <a href="/" class="btn inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 12h18m-9-9l9 9-9 9"/>
                </svg>
                Tiếp tục mua sắm
            </a>
        </div>
    <?php else: ?>
        <form method="post" action="/gio-hang/cap-nhat" class="space-y-6">
            <!-- Cart Items -->
            <div class="card">
                <header>
                    <h2>Sản phẩm trong giỏ</h2>
                    <p><?= count($cartItems) ?> sản phẩm</p>
                </header>
                <section class="p-0">
                    <div class="divide-y divide-border">
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
                            <div class="flex items-start gap-4 p-6 hover:bg-muted/30 transition-colors">
                                <!-- Product Image -->
                                <div class="flex-shrink-0">
                                    <div class="aspect-square flex items-center justify-center">
                                        <?php
                                        $hinhAnhChinh = $sanPham->getHinhAnhChinh();
                                        if ($hinhAnhChinh):
                                            ?>
                                            <img src="<?= htmlspecialchars($hinhAnhChinh->getFullUrl()) ?>"
                                                 alt="<?= htmlspecialchars($sanPham->getTen()) ?>"
                                                 class="max-w-full max-h-full object-contain scale-90 hover:scale-100 transition-all">
                                        <?php else: ?>
                                            <div class="text-6xl">📱</div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Product Details -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-foreground line-clamp-2">
                                                <?= htmlspecialchars($bienThe->getTenDayDu()) ?>
                                            </h3>
                                            <p class="text-sm text-muted-foreground mt-1">
                                                <?= htmlspecialchars($sanPham->getThuongHieu()->getTen() ?? '') ?>
                                            </p>
                                            <div class="flex items-center gap-4 mt-3">
                                                <div class="text-lg font-bold text-primary">
                                                    <?= number_format($bienThe->getGia(), 0, ',', '.') ?> ₫
                                                </div>
                                                <?php if ($bienThe->getGiaSoSanh() && $bienThe->getGiaSoSanh() > $bienThe->getGia()): ?>
                                                    <div class="text-sm text-muted-foreground line-through">
                                                        <?= number_format($bienThe->getGiaSoSanh(), 0, ',', '.') ?> ₫
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- Quantity Controls -->
                                        <div class="flex items-center gap-3">
                                            <div class="flex items-center border border-border rounded-lg">
                                                <input type="number" 
                                                       name="quantities[<?= $index ?>]" 
                                                       value="<?= $qty ?>" 
                                                       min="0"
                                                       class="w-16 text-center border-0 bg-transparent focus:outline-none py-2" 
                                                       onchange="handleQuantityChange(this, <?= $index ?>)" />
                                                <input type="hidden" name="ids[<?= $index ?>]" value="<?= htmlspecialchars($bienThe->getId()) ?>">
                                            </div>

                                            <!-- Remove Button -->
                                            <button formaction="/gio-hang/xoa?id=<?= htmlspecialchars($bienThe->getId()) ?>" 
                                                    formmethod="post" 
                                                    type="submit"
                                                    class="p-2 text-muted-foreground hover:text-destructive hover:bg-destructive/10 rounded-lg transition-colors"
                                                    title="Xóa khỏi giỏ hàng">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M3 6h18"/>
                                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Subtotal -->
                                    <div class="flex justify-between items-center mt-4 pt-4 border-t border-border">
                                        <span class="text-sm text-muted-foreground">Thành tiền:</span>
                                        <div class="text-xl font-bold text-foreground">
                                            <?= number_format($thanhTien, 0, ',', '.') ?> ₫
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            </div>

            <!-- Order Summary -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1">
                    <div class="card sticky top-6">
                        <header>
                            <h3>Tóm tắt đơn hàng</h3>
                            <p>Chi tiết thanh toán</p>
                        </header>
                        <section>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-muted-foreground">Tạm tính:</span>
                                    <span class="font-semibold"><?= number_format($tong, 0, ',', '.') ?> ₫</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-muted-foreground">Phí vận chuyển:</span>
                                    <span class="text-sm text-muted-foreground">Miễn phí</span>
                                </div>
                                <div class="border-t border-border pt-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-semibold text-foreground">Tổng cộng:</span>
                                        <span class="text-2xl font-bold text-primary"><?= number_format($tong, 0, ',', '.') ?> ₫</span>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <footer class="space-y-3">
                            <a href="/gio-hang/checkout" class="btn w-full">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 12l2 2 4-4"/>
                                    <path d="M21 12c-1 0-3-1-3-3s2-3 3-3 3 1 3 3-2 3-3 3"/>
                                    <path d="M3 12c1 0 3-1 3-3s-2-3-3-3-3 1-3 3 2 3 3 3"/>
                                </svg>
                                Tiến hành thanh toán
                            </a>
                        </footer>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="card">
                        <header>
                            <h3>Gợi ý cho bạn</h3>
                            <p>Sản phẩm có thể bạn quan tâm</p>
                        </header>
                        <section>
                            <div class="text-center py-8 text-muted-foreground">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4">
                                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                                    <line x1="3" x2="21" y1="6" y2="6"/>
                                    <path d="M16 10a4 4 0 0 1-8 0"/>
                                </svg>
                                Chức năng gợi ý sản phẩm đang được phát triển
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </form>

        <!-- Confirmation Dialog -->
        <dialog id="remove-dialog" class="dialog" aria-labelledby="remove-dialog-title" aria-describedby="remove-dialog-description">
            <article>
                <header>
                    <h2 id="remove-dialog-title">Xác nhận xóa sản phẩm</h2>
                    <p id="remove-dialog-description">Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng không? Hành động này không thể hoàn tác.</p>
                </header>

                <footer>
                    <button class="btn-outline" onclick="cancelRemove()">Hủy bỏ</button>
                    <button class="btn" onclick="confirmRemove()">Xác nhận xóa</button>
                </footer>
            </article>
        </dialog>

        <script>
        let pendingRemovalInput = null;
        let originalValue = null;

        function handleQuantityChange(input, index) {
            const newValue = parseInt(input.value);
            const oldValue = parseInt(input.getAttribute('data-original-value') || input.defaultValue);
            
            // Store original value if not set
            if (!input.hasAttribute('data-original-value')) {
                input.setAttribute('data-original-value', oldValue);
            }
            
            // If changing from 1 to 0, show confirmation dialog
            if (oldValue === 1 && newValue === 0) {
                pendingRemovalInput = input;
                originalValue = oldValue;
                document.getElementById('remove-dialog').showModal();
            } else {
                // Submit form for other changes
                input.form.submit();
            }
        }

        function cancelRemove() {
            if (pendingRemovalInput && originalValue !== null) {
                pendingRemovalInput.value = originalValue;
            }
            document.getElementById('remove-dialog').close();
            pendingRemovalInput = null;
            originalValue = null;
        }

        function confirmRemove() {
            if (pendingRemovalInput) {
                pendingRemovalInput.form.submit();
            }
            document.getElementById('remove-dialog').close();
            pendingRemovalInput = null;
            originalValue = null;
        }
        </script>

    <?php endif; ?>
</div>