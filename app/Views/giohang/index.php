<h2 class="text-2xl font-bold mb-6 text-foreground">Giỏ hàng của bạn</h2>

<?php if (empty($cart)): ?>
    <div class="rounded-2xl border border-border bg-muted/50 p-6 text-center text-muted-foreground shadow-sm">
        Chưa có sản phẩm nào trong giỏ hàng.
    </div>
<?php else: ?>
    <form method="post" action="/gio-hang/cap-nhat">
    <!-- DEBUG dữ liệu cart, đặt ngoài table -->
    <pre style="background:#23272f; color:#facc15; padding:12px; border-radius:8px; font-size:13px">
<?php print_r($cart); ?>
    </pre>
    <div class="overflow-x-auto rounded-2xl shadow-md bg-card">
        <table class="min-w-full divide-y divide-border">
            <thead class="bg-muted">
                <tr>
                    <th class="px-4 py-4"></th>
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
                foreach ($cart as $index => $item): 
                    $thanhTien = $item['price'] * $item['qty'];
                    $tong += $thanhTien;

                    // Xử lý src ảnh
                    $src = null;
                    if (!empty($item['image'])) {
                        $src = (str_starts_with($item['image'], '/'))
                            ? $item['image']
                            : '/images/' . ltrim($item['image'], '/');
                    }
                ?>
                <tr class="hover:bg-muted/60 transition-colors">
                    <!-- Ảnh sản phẩm -->
                    <td class="px-4 py-4">
                        <?php if (!empty($src)): ?>
                            <img src="<?= htmlspecialchars($src) ?>"
                                 alt="<?= htmlspecialchars($item['name']) ?>"
                                 class="w-12 h-12 rounded-xl object-cover bg-slate-200 shadow border border-border" />
                        <?php else: ?>
                            <span class="inline-flex items-center justify-center bg-muted rounded-full w-9 h-9">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="m9 9 6 6M15 9l-6 6"/></svg>
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-base font-medium text-foreground"><?= htmlspecialchars($item['name']) ?></td>
                    <td class="px-6 py-4 text-center">
                        <input type="number" name="quantities[<?= $index ?>]" value="<?= $item['qty'] ?>" min="1" class="w-16 text-center border rounded-lg py-1 bg-background focus:outline-primary focus:ring-2 focus:ring-primary/40 shadow transition" />
                        <input type="hidden" name="ids[<?= $index ?>]" value="<?= htmlspecialchars($item['id']) ?>">
                    </td>
                    <td class="px-6 py-4 text-right"><?= $item['price_formatted'] ?></td>
                    <td class="px-6 py-4 text-right font-semibold"><?= number_format($thanhTien, 0, ',', '.') ?> ₫</td>
                    <td class="px-4 py-4 text-center">
                        <button formaction="/gio-hang/xoa?id=<?= htmlspecialchars($item['id']) ?>" formmethod="post" type="submit"
                            class="rounded-lg p-2 bg-destructive hover:bg-destructive/80 text-destructive-foreground transition-colors shadow-sm"
                            title="Xóa khỏi giỏ hàng">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M9 6v12a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4" class="px-6 py-4 text-right font-bold text-base">Tổng cộng:</td>
                    <td class="px-6 py-4 text-right font-bold text-primary text-lg" colspan="2"><?= number_format($tong, 0, ',', '.') ?> ₫</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="flex justify-end mt-4">
      <button
        type="submit"
        class="w-full sm:w-auto min-w-[180px] rounded-2xl px-8 py-3 text-base font-bold bg-primary text-primary-foreground shadow-md hover:bg-primary/90 transition"
        style="border-width:6px"
      >
        Cập nhật giỏ hàng
      </button>
    </div>
    </form>
<?php endif; ?>
