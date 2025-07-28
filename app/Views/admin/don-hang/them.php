<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-foreground">Thêm đơn hàng mới</h1>
        <p class="text-muted-foreground">Tạo đơn hàng mới trong hệ thống</p>
    </div>

    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Thông tin khách hàng & trạng thái -->
            <div class="lg:col-span-2 space-y-4">
                <div class="card">
                    <header>
                        <h3>Thông tin</h3>
                        <p>Nhập thông tin khách hàng và trạng thái</p>
                    </header>
                    <section class="form grid gap-6 p-4">
                        <div class="grid gap-2">
                            <label for="email_khach">Email khách</label>
                            <input type="email" id="email_khach" name="email_khach"
                                   placeholder="nhập email khách"
                                   value="<?= htmlspecialchars($_POST['email_khach'] ?? '') ?>">
                        </div>
                        <div class="grid gap-2">
                            <label for="trang_thai">Trạng thái xử lý *</label>
                            <select id="trang_thai" name="trang_thai" required>
                                <?php foreach ([
                                                   'cho_xu_ly'       => 'Chờ xử lý',
                                                   'dang_xu_ly'      => 'Đang xử lý',
                                                   'da_hoan_thanh'   => 'Đã hoàn thành',
                                                   'da_huy'          => 'Đã hủy'
                                               ] as $value => $label): ?>
                                    <option value="<?= $value ?>"
                                        <?= (($_POST['trang_thai'] ?? '') === $value) ? 'selected' : '' ?>>
                                        <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="grid gap-2">
                            <label for="trang_thai_thanh_toan">Trạng thái thanh toán *</label>
                            <select id="trang_thai_thanh_toan" name="trang_thai_thanh_toan" required>
                                <?php foreach ([
                                                   'cho_thanh_toan' => 'Chờ thanh toán',
                                                   'da_thanh_toan'  => 'Đã thanh toán',
                                                   'hoan_tien'      => 'Hoàn tiền'
                                               ] as $value => $label): ?>
                                    <option value="<?= $value ?>"
                                        <?= (($_POST['trang_thai_thanh_toan'] ?? '') === $value) ? 'selected' : '' ?>>
                                        <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </section>
                </div>

                <!-- Thông tin giá -->
                <div class="card">
                    <header>
                        <h3>Chi phí</h3>
                        <p>Nhập giá trị tiền tệ</p>
                    </header>
                    <section class="form grid grid-cols-1 md:grid-cols-2 gap-6 p-4">
                        <div class="grid gap-2">
                            <label for="tong_phu">Tổng phụ *</label>
                            <input type="number" id="tong_phu" name="tong_phu" required min="0" step="0.01"
                                   value="<?= htmlspecialchars($_POST['tong_phu'] ?? '') ?>">
                        </div>
                        <div class="grid gap-2">
                            <label for="tien_thue">Thuế</label>
                            <input type="number" id="tien_thue" name="tien_thue" min="0" step="0.01"
                                   value="<?= htmlspecialchars($_POST['tien_thue'] ?? '') ?>">
                        </div>
                        <div class="grid gap-2">
                            <label for="phi_van_chuyen">Phí vận chuyển</label>
                            <input type="number" id="phi_van_chuyen" name="phi_van_chuyen" min="0" step="0.01"
                                   value="<?= htmlspecialchars($_POST['phi_van_chuyen'] ?? '') ?>">
                        </div>
                        <div class="grid gap-2">
                            <label for="tien_giam_gia">Giảm giá</label>
                            <input type="number" id="tien_giam_gia" name="tien_giam_gia" min="0" step="0.01"
                                   value="<?= htmlspecialchars($_POST['tien_giam_gia'] ?? '') ?>">
                        </div>
                    </section>
                </div>

                <!-- Thời gian & Ghi chú -->
                <div class="card">
                    <header>
                        <h3>Thời gian & Ghi chú</h3>
                    </header>
                    <section class="form grid gap-6 p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="grid gap-2">
                                <label for="ngay_giao">Ngày giao</label>
                                <input type="date" id="ngay_giao" name="ngay_giao"
                                       value="<?= htmlspecialchars($_POST['ngay_giao'] ?? '') ?>">
                            </div>
                            <div class="grid gap-2">
                                <label for="ngay_nhan">Ngày nhận</label>
                                <input type="date" id="ngay_nhan" name="ngay_nhan"
                                       value="<?= htmlspecialchars($_POST['ngay_nhan'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="grid gap-2">
                            <label for="ghi_chu">Ghi chú</label>
                            <textarea id="ghi_chu" name="ghi_chu" rows="4" placeholder="Ghi chú thêm"><?= htmlspecialchars($_POST['ghi_chu'] ?? '') ?></textarea>
                        </div>
                    </section>
                </div>
            </div>

            <!-- Actions -->
            <div class="space-y-6">
                <div class="card">
                    <footer class="flex flex-col gap-3 p-4">
                        <button type="submit" class="btn w-full">Tạo đơn hàng</button>
                        <a href="/admin/don-hang" class="btn-outline w-full text-center">Hủy</a>
                    </footer>
                </div>
            </div>
        </div>
    </form>
</div>
