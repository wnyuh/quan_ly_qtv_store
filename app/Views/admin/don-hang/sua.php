<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-foreground">Sửa đơn hàng</h1>
        <p class="text-muted-foreground">Cập nhật đơn hàng <?= htmlspecialchars($donHang->getMaDonHang()) ?></p>
    </div>

    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-4">
                <div class="card">
                    <header>
                        <h3>Cập nhật trạng thái</h3>
                    </header>
                    <section class="form grid gap-6 p-4">
                        <div class="grid gap-2">
                            <label for="trang_thai">Trạng thái xử lý</label>
                            <select id="trang_thai" name="trang_thai" required>
                                <?php foreach ([
                                                   'cho_xu_ly' => 'Chờ xử lý',
                                                   'dang_xu_ly' => 'Đang xử lý',
                                                   'da_hoan_thanh' => 'Đã hoàn thành',
                                                   'da_huy' => 'Đã hủy'
                                               ] as $value => $label): ?>
                                    <option value="<?= $value ?>" <?= ($donHang->getTrangThai() === $value) ? 'selected' : '' ?>>
                                        <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="grid gap-2">
                            <label for="trang_thai_thanh_toan">Trạng thái thanh toán</label>
                            <select id="trang_thai_thanh_toan" name="trang_thai_thanh_toan" required>
                                <?php foreach ([
                                                   'cho_thanh_toan' => 'Chờ thanh toán',
                                                   'da_thanh_toan' => 'Đã thanh toán',
                                                   'hoan_tien' => 'Hoàn tiền'
                                               ] as $value => $label): ?>
                                    <option value="<?= $value ?>" <?= ($donHang->getTrangThaiThanhToan() === $value) ? 'selected' : '' ?>>
                                        <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="grid gap-2">
                                <label for="ngay_giao">Ngày giao</label>
                                <input type="date" id="ngay_giao" name="ngay_giao"
                                       value="<?= $donHang->getNgayGiao() ? $donHang->getNgayGiao()->format('Y-m-d') : '' ?>">
                            </div>
                            <div class="grid gap-2">
                                <label for="ngay_nhan">Ngày nhận</label>
                                <input type="date" id="ngay_nhan" name="ngay_nhan"
                                       value="<?= $donHang->getNgayNhan() ? $donHang->getNgayNhan()->format('Y-m-d') : '' ?>">
                            </div>
                        </div>

                        <div class="grid gap-2">
                            <label for="ghi_chu">Ghi chú</label>
                            <textarea id="ghi_chu" name="ghi_chu" rows="4" placeholder="Ghi chú thêm về đơn hàng"><?= htmlspecialchars($_POST['ghi_chu'] ?? $donHang->getGhiChu()) ?></textarea>
                        </div>
                    </section>
                </div>
            </div>

            <!-- Actions -->
            <div class="space-y-6">
                <div class="card">
                    <footer class="flex flex-col gap-3 p-4">
                        <button type="submit" class="btn w-full">Lưu thay đổi</button>
                        <a href="/admin/don-hang/chi-tiet/<?= $donHang->getId() ?>" class="btn-outline w-full text-center">Hủy</a>
                    </footer>
                </div>
            </div>
        </div>
    </form>
</div>
