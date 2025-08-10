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
                                <?php foreach (
                                    [
                                        'cho_xu_ly' => 'Chờ xử lý',
                                        'dang_xu_ly' => 'Đang xử lý',
                                        'da_hoan_thanh' => 'Đã hoàn thành',
                                        'da_huy' => 'Đã hủy'
                                    ] as $value => $label
                                ): ?>
                                    <option value="<?= $value ?>" <?= ($donHang->getTrangThai() === $value) ? 'selected' : '' ?>>
                                        <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="grid gap-2">
                            <label for="trang_thai_thanh_toan">Trạng thái thanh toán</label>
                            <select id="trang_thai_thanh_toan" name="trang_thai_thanh_toan" required>
                                <?php foreach (
                                    [
                                        'cho_thanh_toan' => 'Chờ thanh toán',
                                        'da_thanh_toan' => 'Đã thanh toán',
                                        'hoan_tien' => 'Hoàn tiền'
                                    ] as $value => $label
                                ): ?>
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

                    <header class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold">📍 Cập nhật địa chỉ giao hàng</h3>
                    </header>

                    <div class="p-4 border-b border-gray-200">
                        <div>
                            <label class="block text-sm font-medium mb-1">Địa chỉ 1 *</label>
                            <input type="text" name="dia_chi_1" class="w-full border rounded px-3 py-2" value="<?= htmlspecialchars($donHang->getDiaChiGiaoHang()?->getDiaChi1() ?? '') ?>">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <?php $selectedTinh = $donHang->getDiaChiGiaoHang()?->getTinhThanh() ?? ''; ?>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Tỉnh/Thành phố *</label>
                            <div class="relative">
                                <button type="button" id="province-button" class="flex h-10 w-full items-center justify-between rounded-md border px-3 py-2 text-sm" aria-haspopup="listbox">
                                    <span id="province-selected"><?= htmlspecialchars($selectedTinh ?: 'Chọn tỉnh/thành phố') ?></span>
                                    <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div id="province-options" class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-60 overflow-y-auto">
                                    <?php
                                    $provinces = json_decode(file_get_contents('https://provinces.open-api.vn/api/?depth=1'), true);
                                    foreach ($provinces as $province):
                                        $selected = $province['name'] === $selectedTinh ? 'bg-gray-100 font-semibold' : '';
                                    ?>
                                        <div class="px-3 py-2 hover:bg-gray-100 cursor-pointer <?= $selected ?>" data-name="<?= htmlspecialchars($province['name']) ?>">
                                            <?= htmlspecialchars($province['name']) ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <input type="hidden" name="tinh_thanh" id="province-value" value="<?= htmlspecialchars($selectedTinh) ?>">
                            </div>
                        </div>


                        <?php $selectedDistrict = $donHang->getDiaChiGiaoHang()?->getHuyenQuan() ?? ''; ?>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Quận/Huyện *</label>
                            <div class="relative">
                                <button type="button" id="district-button" class="flex h-10 w-full items-center justify-between rounded-md border px-3 py-2 text-sm bg-white disabled:opacity-50" disabled>
                                    <span id="district-selected"><?= htmlspecialchars($selectedDistrict ?: 'Chọn quận/huyện') ?></span>
                                    <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div id="district-options" class="absolute z-10 mt-1 w-full bg-white border rounded-md shadow-lg hidden max-h-60 overflow-y-auto">
                                    <!-- District options will be appended here -->
                                </div>
                                <input type="hidden" name="huyen_quan" id="district-value" value="<?= htmlspecialchars($selectedDistrict) ?>">
                            </div>
                        </div>

                        <?php $selectedWard = $donHang->getDiaChiGiaoHang()?->getXaPhuong() ?? ''; ?>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Phường/Xã *</label>
                            <div class="relative">
                                <button type="button" id="ward-button" class="flex h-10 w-full items-center justify-between rounded-md border px-3 py-2 text-sm bg-white disabled:opacity-50" disabled>
                                    <span id="ward-selected"><?= htmlspecialchars($selectedWard ?: 'Chọn phường/xã') ?></span>
                                    <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div id="ward-options" class="absolute z-10 mt-1 w-full bg-white border rounded-md shadow-lg hidden max-h-60 overflow-y-auto">
                                    <!-- Ward options will be populated here -->
                                </div>
                                <input type="hidden" name="xa_phuong" id="ward-value" value="<?= htmlspecialchars($selectedWard) ?>">
                            </div>
                        </div>

                    </div>

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