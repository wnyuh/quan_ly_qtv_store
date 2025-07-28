<h2 class="text-2xl font-bold mb-6 text-foreground">Giỏ hàng của bạn</h2>

<?php if (empty($cartItems)): ?>
    <div class="rounded-2xl border border-border bg-muted/50 p-6 text-center text-muted-foreground shadow-sm">
        Chưa có sản phẩm nào trong giỏ hàng.
    </div>
<?php else: ?>
    <form method="post" action="/gio-hang/cap-nhat">
        <div class="w-full overflow-x-auto rounded-2xl shadow-lg bg-card border border-yellow-300">
            <table class="w-full divide-y divide-border">
                <thead class="bg-yellow-50 border-b-2 border-yellow-200">
                    <tr>
                        <th class="px-4 py-4 text-left">Ảnh</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-muted-foreground uppercase">Tên sản phẩm</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-muted-foreground uppercase">Số lượng</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-muted-foreground uppercase">Đơn giá</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-muted-foreground uppercase">Thành tiền</th>
                        <th class="px-4 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border bg-background">
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
                        <tr class="hover:bg-yellow-50 transition-colors">
                            <td class="px-4 py-4">
                                <?php if (!empty($image)): ?>
                                    <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($bienThe->getTenDayDu()) ?>"
                                        class="w-12 h-12 object-cover rounded-xl bg-slate-200 border border-border shadow" />
                                <?php else: ?>
                                    <span class="inline-flex items-center justify-center bg-muted rounded-full w-12 h-12">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <rect width="18" height="18" x="3" y="3" rx="2" />
                                            <path d="m9 9 6 6M15 9l-6 6" />
                                        </svg>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-base font-medium text-foreground">
                                <?= htmlspecialchars($bienThe->getTenDayDu()) ?>
                                <div class="text-xs text-muted-foreground"><?= htmlspecialchars($sanPham->getThuongHieu()->getTen() ?? '') ?></div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <input type="number" name="quantities[<?= $index ?>]" value="<?= $qty ?>" min="1"
                                    class="w-16 text-center border rounded-lg py-1 bg-background focus:outline-primary focus:ring-2 focus:ring-primary/40 shadow transition" />
                                <input type="hidden" name="ids[<?= $index ?>]" value="<?= htmlspecialchars($bienThe->getId()) ?>">
                            </td>
                            <td class="px-6 py-4 text-right"><?= number_format($bienThe->getGia(), 0, ',', '.') ?> ₫</td>
                            <td class="px-6 py-4 text-right font-semibold"><?= number_format($thanhTien, 0, ',', '.') ?> ₫</td>
                            <td class="px-4 py-4 text-center">
                                <button formaction="/gio-hang/xoa?id=<?= htmlspecialchars($bienThe->getId()) ?>" formmethod="post" type="submit"
                                    class="rounded-lg p-2 bg-destructive hover:bg-destructive/80 text-destructive-foreground transition-colors shadow-sm"
                                    title="Xóa khỏi giỏ hàng">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 6h18M9 6v12a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2V6" />
                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-right font-bold text-base bg-yellow-50 border-t-2 border-yellow-200">Tổng cộng:</td>
                        <td class="px-6 py-4 text-right font-extrabold text-yellow-600 text-lg bg-yellow-50 border-t-2 border-yellow-200" colspan="2">
                            <?= number_format($tong, 0, ',', '.') ?> ₫
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex flex-col sm:flex-row justify-end gap-4 mt-8 w-full">
            <button type="submit"
                class="rounded-2xl px-8 py-3 text-base font-bold bg-yellow-400 text-black shadow hover:bg-yellow-500 transition flex items-center gap-2 sm:w-auto w-full border-0 outline-none">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" class="inline"
                    stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="6" width="18" height="12" rx="2" />
                    <path d="M16 10l-4 4-4-4" />
                </svg>
                Cập nhật giỏ hàng
            </button>
            <a href="/gio-hang/checkout"
                class="rounded-2xl px-8 py-3 text-base font-bold bg-gradient-to-r from-emerald-400 to-green-600 text-white shadow hover:brightness-110 transition flex items-center gap-2 sm:w-auto w-full border-0 outline-none">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" class="inline"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 5v14M5 12l7-7 7 7" />
                </svg>
                Thanh toán
            </a>
        </div>
    </form>
<?php endif; ?>