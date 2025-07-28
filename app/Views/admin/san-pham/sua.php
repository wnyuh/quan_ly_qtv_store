<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-foreground">Chỉnh sửa sản phẩm</h1>
        <p class="text-muted-foreground">Cập nhật thông tin sản phẩm: <?= htmlspecialchars($sanPham->getTen()) ?></p>
    </div>

    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Information -->
            <div class="lg:col-span-2 space-y-4">
                <div class="card">
                    <header>
                        <h3>Thông tin cơ bản</h3>
                        <p>Thông tin chính của sản phẩm</p>
                    </header>
                    <section>
                        <div class="form grid gap-6">
                            <div class="grid gap-2">
                                <label for="ten">Tên sản phẩm *</label>
                                <input type="text" id="ten" name="ten" required 
                                       placeholder="Nhập tên sản phẩm" 
                                       value="<?= htmlspecialchars($_POST['ten'] ?? $sanPham->getTen()) ?>">
                            </div>

                            <div class="grid gap-2">
                                <label for="duong_dan">Đường dẫn (URL)</label>
                                <input type="text" id="duong_dan" name="duong_dan" 
                                       placeholder="duong-dan-san-pham"
                                       value="<?= htmlspecialchars($_POST['duong_dan'] ?? $sanPham->getDuongDan()) ?>">
                            </div>

                            <div class="grid gap-2">
                                <label for="mo_ta_ngan">Mô tả ngắn</label>
                                <textarea id="mo_ta_ngan" name="mo_ta_ngan" rows="3" 
                                          placeholder="Mô tả ngắn gọn về sản phẩm"><?= htmlspecialchars($_POST['mo_ta_ngan'] ?? $sanPham->getMoTaNgan()) ?></textarea>
                            </div>

                            <div class="grid gap-2">
                                <label for="mo_ta">Mô tả chi tiết</label>
                                <textarea id="mo_ta" name="mo_ta" rows="6" 
                                          placeholder="Mô tả chi tiết về sản phẩm"><?= htmlspecialchars($_POST['mo_ta'] ?? $sanPham->getMoTa()) ?></textarea>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Pricing -->
                <div class="card">
                    <header>
                        <h3>Giá bán</h3>
                        <p>Thông tin giá cả sản phẩm</p>
                    </header>
                    <section>
                        <div class="form grid gap-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="grid gap-2">
                                    <label for="gia">Giá bán *</label>
                                    <input type="number" id="gia" name="gia" required min="0" step="1000"
                                           placeholder="0" 
                                           value="<?= htmlspecialchars($_POST['gia'] ?? $sanPham->getGia()) ?>">
                                </div>
                                
                                <div class="grid gap-2">
                                    <label for="gia_so_sanh">Giá so sánh</label>
                                    <input type="number" id="gia_so_sanh" name="gia_so_sanh" min="0" step="1000"
                                           placeholder="0 (để trống nếu không có)"
                                           value="<?= htmlspecialchars($_POST['gia_so_sanh'] ?? $sanPham->getGiaSoSanh()) ?>">
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Variants Section -->
                <div class="card">
                    <header>
                        <h3>Biến thể sản phẩm</h3>
                        <p>Quản lý các biến thể của sản phẩm (<?= $sanPham->getBienThes()->count() ?> biến thể)</p>
                    </header>
                    <section>
                        <?php if ($sanPham->getBienThes()->count() > 0): ?>
                            <div class="space-y-3">
                                <?php foreach ($sanPham->getBienThes() as $bienThe): ?>
                                    <div class="flex items-center justify-between p-3 border border-border rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-muted rounded-lg flex items-center justify-center">
                                                <span class="text-sm">📱</span>
                                            </div>
                                            <div>
                                                <div class="font-medium text-foreground"><?= htmlspecialchars($bienThe->getTenDayDu()) ?></div>
                                                <div class="text-sm text-muted-foreground">
                                                    <?= $bienThe->getGiaFormatted() ?> • 
                                                    Tồn kho: <?= $bienThe->getSoLuongTon() ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <?php if ($bienThe->isKichHoat()): ?>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Hoạt động
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Tạm dừng
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <p class="text-muted-foreground">Chưa có biến thể nào</p>
                            </div>
                        <?php endif; ?>
                    </section>
                </div>
            </div>

            <!-- Sidebar Information -->
            <div class="space-y-6">
                <!-- Category & Brand -->
                <div class="card">
                    <header>
                        <h3>Phân loại</h3>
                        <p>Danh mục và thương hiệu</p>
                    </header>
                    <section>
                        <div class="form grid gap-6">
                            <div class="grid gap-2">
                                <label for="danh_muc_id">Danh mục *</label>
                                <select id="danh_muc_id" name="danh_muc_id" required>
                                    <option value="">Chọn danh mục</option>
                                    <?php foreach ($danhMucs as $danhMuc): ?>
                                        <option value="<?= $danhMuc->getId() ?>" 
                                                <?= (($_POST['danh_muc_id'] ?? $sanPham->getDanhMuc()->getId()) == $danhMuc->getId()) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($danhMuc->getTen()) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="grid gap-2">
                                <label for="thuong_hieu_id">Thương hiệu *</label>
                                <select id="thuong_hieu_id" name="thuong_hieu_id" required>
                                    <option value="">Chọn thương hiệu</option>
                                    <?php foreach ($thuongHieus as $thuongHieu): ?>
                                        <option value="<?= $thuongHieu->getId() ?>" 
                                                <?= (($_POST['thuong_hieu_id'] ?? $sanPham->getThuongHieu()->getId()) == $thuongHieu->getId()) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($thuongHieu->getTen()) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Status -->
                <div class="card">
                    <header>
                        <h3>Trạng thái</h3>
                        <p>Cài đặt hiển thị sản phẩm</p>
                    </header>
                    <section>
                        <div class="space-y-4">
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" name="kich_hoat" value="1" 
                                       <?= (isset($_POST['kich_hoat']) ? $_POST['kich_hoat'] : $sanPham->isKichHoat()) ? 'checked' : '' ?>>
                                <div>
                                    <div class="font-medium text-foreground">Kích hoạt</div>
                                    <div class="text-sm text-muted-foreground">Sản phẩm sẽ hiển thị trên website</div>
                                </div>
                            </label>

                            <label class="flex items-center space-x-3">
                                <input type="checkbox" name="noi_bat" value="1" 
                                       <?= (isset($_POST['noi_bat']) ? $_POST['noi_bat'] : $sanPham->isNoiBat()) ? 'checked' : '' ?>>
                                <div>
                                    <div class="font-medium text-foreground">Nổi bật</div>
                                    <div class="text-sm text-muted-foreground">Hiển thị trong danh sách sản phẩm nổi bật</div>
                                </div>
                            </label>

                            <label class="flex items-center space-x-3">
                                <input type="checkbox" name="sp_moi" value="1"
                                    <?= (isset($_POST['sp_moi']) ? $_POST['sp_moi'] : $sanPham->isSpMoi()) ? 'checked' : '' ?>>
                                <div>
                                    <div class="font-medium text-foreground">Sản phẩm mới</div>
                                    <div class="text-sm text-muted-foreground">Hiển thị trong danh sách sản phẩm mới</div>
                                </div>
                            </label>
                        </div>
                    </section>
                </div>

                <!-- Actions -->
                <div class="card">
                    <footer class="flex flex-col gap-3">
                        <button type="submit" class="btn w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                <polyline points="17,21 17,13 7,13 7,21"/>
                                <polyline points="7,3 7,8 15,8"/>
                            </svg>
                            Cập nhật sản phẩm
                        </button>
                        <a href="/admin/san-pham/chi-tiet/<?=$sanPham->getId() ?>" class="btn-outline w-full text-center">
                            Hủy bỏ
                        </a>
                    </footer>
                </div>
            </div>
        </div>
    </form>
</div>