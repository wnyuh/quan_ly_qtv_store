<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-foreground"><?= htmlspecialchars($sanPham->getTen()) ?></h1>
            <p class="text-muted-foreground">Chi tiết sản phẩm • Mã: <?= htmlspecialchars($sanPham->getMaSanPham()) ?></p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="/admin/san-pham/sua/<?= $sanPham->getId() ?>" class="btn-outline">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                </svg>
                Chỉnh sửa
            </a>
            <a href="/admin/san-pham" class="btn-outline">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 12l2 2 4-4"/>
                    <path d="M21 12c-1 0-3-1-3-3s2-3 3-3 3 1 3 3-2 3-3 3"/>
                    <path d="M3 12c1 0 3-1 3-3s-2-3-3-3-3 1-3 3 2 3 3 3"/>
                    <path d="M3 21h18"/>
                </svg>
                Quay lại
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="card">
                <header>
                    <h3>Thông tin cơ bản</h3>
                    <p>Chi tiết thông tin sản phẩm</p>
                </header>
                <section>
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-muted-foreground">Tên sản phẩm</label>
                                <p class="text-foreground"><?= htmlspecialchars($sanPham->getTen()) ?></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-muted-foreground">Đường dẫn</label>
                                <p class="text-foreground font-mono text-sm"><?= htmlspecialchars($sanPham->getDuongDan()) ?></p>
                            </div>
                        </div>

                        <?php if ($sanPham->getMoTaNgan()): ?>
                            <div>
                                <label class="text-sm font-medium text-muted-foreground">Mô tả ngắn</label>
                                <p class="text-foreground"><?= nl2br(htmlspecialchars($sanPham->getMoTaNgan())) ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if ($sanPham->getMoTa()): ?>
                            <div>
                                <label class="text-sm font-medium text-muted-foreground">Mô tả chi tiết</label>
                                <div class="text-foreground whitespace-pre-line bg-muted/50 p-4 rounded-lg">
                                    <?= nl2br(htmlspecialchars($sanPham->getMoTa())) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            </div>

            <!-- Product Variants -->
            <div class="card">
                <header>
                    <h3>Biến thể sản phẩm</h3>
                    <p>Danh sách các biến thể của sản phẩm (<?= $sanPham->getBienThes()->count() ?> biến thể)</p>
                </header>
                <section>
                    <?php if ($sanPham->getBienThes()->count() > 0): ?>
                        <div class="space-y-3">
                            <?php foreach ($sanPham->getBienThes() as $bienThe): ?>
                                <div class="flex items-center justify-between p-4 border border-border rounded-lg">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 bg-muted rounded-lg flex items-center justify-center">
                                            <?php if ($bienThe->getHinhAnhs()->first()): ?>
                                                <img src="<?= htmlspecialchars($bienThe->getHinhAnhs()->first()->getFullUrl()) ?>" 
                                                     alt="<?= htmlspecialchars($bienThe->getTenDayDu()) ?>"
                                                     class="w-full h-full object-cover rounded-lg">
                                            <?php else: ?>
                                                <span class="text-xl">📱</span>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <div class="font-medium text-foreground"><?= htmlspecialchars($bienThe->getTenDayDu()) ?></div>
                                            <div class="text-sm text-muted-foreground">
                                                Mã: <?= htmlspecialchars($bienThe->getMaSanPham()) ?>
                                            </div>
                                            <div class="flex items-center space-x-4 text-sm text-muted-foreground mt-1">
                                                <span>Giá: <span class="font-medium text-foreground"><?= $bienThe->getGiaFormatted() ?></span></span>
                                                <span>Tồn kho: <span class="font-medium <?= $bienThe->isHetHang() ? 'text-red-600' : 'text-green-600' ?>"><?= $bienThe->getSoLuongTon() ?></span></span>
                                                <?php if ($bienThe->getTrongLuong()): ?>
                                                    <span>Trọng lượng: <?= $bienThe->getTrongLuong() ?>g</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <?php if ($bienThe->isKichHoat()): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Hoạt động
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Tạm dừng
                                            </span>
                                        <?php endif; ?>
                                        
                                        <?php if ($bienThe->isTonThap()): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                Sắp hết hàng
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <div class="text-4xl mb-4">📦</div>
                            <p class="text-muted-foreground">Chưa có biến thể nào</p>
                        </div>
                    <?php endif; ?>
                </section>
            </div>

            <!-- Technical Specifications -->
            <?php if ($sanPham->getThongSo()): ?>
                <div class="card">
                    <header>
                        <h3>Thông số kỹ thuật</h3>
                        <p>Chi tiết thông số kỹ thuật của sản phẩm</p>
                    </header>
                    <section>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <?php 
                                $thongSo = $sanPham->getThongSo();
                                $specs = [
                                    'Kích thước màn hình' => $thongSo->getKichThuocManHinh(),
                                    'Độ phân giải' => $thongSo->getDoPhanGiai(),
                                    'Loại màn hình' => $thongSo->getLoaiManHinh(),
                                    'Hệ điều hành' => $thongSo->getHeDieuHanh(),
                                    'Bộ xử lý' => $thongSo->getBoXuLy(),
                                    'RAM' => $thongSo->getRam(),
                                    'Bộ nhớ trong' => $thongSo->getBoNho(),
                                    'Camera sau' => $thongSo->getCameraSau(),
                                    'Camera trước' => $thongSo->getCameraTruoc(),
                                    'Dung lượng pin' => $thongSo->getDungLuongPin(),
                                    'Loại sạc' => $thongSo->getLoaiSac(),
                                    'Chống nước' => $thongSo->getChongNuoc(),
                                    'Thời gian bảo hành' => $thongSo->getThoiGianBaoHanh(),
                                ];
                                
                                foreach ($specs as $label => $value):
                                    if ($value):
                                ?>
                                    <tr class="border-b border-border last:border-b-0">
                                        <td class="px-4 py-3 bg-muted/50 font-medium text-foreground w-1/3">
                                            <?= htmlspecialchars($label) ?>
                                        </td>
                                        <td class="px-4 py-3 text-muted-foreground">
                                            <?= htmlspecialchars($value) ?>
                                        </td>
                                    </tr>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </table>
                        </div>
                    </section>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status & Info -->
            <div class="card">
                <header>
                    <h3>Trạng thái</h3>
                    <p>Thông tin trạng thái sản phẩm</p>
                </header>
                <section>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-muted-foreground">Trạng thái</span>
                            <?php if ($sanPham->isKichHoat()): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Hoạt động
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Tạm dừng
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php if ($sanPham->isNoiBat()): ?>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-muted-foreground">Nổi bật</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    ⭐ Sản phẩm nổi bật
                                </span>
                            </div>
                        <?php endif; ?>

                        <?php if ($sanPham->isSpMoi()): ?>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-muted-foreground">Sản phẩm mới</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    ⭐ Sản phẩm mới
                                </span>
                            </div>
                        <?php endif; ?>

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-muted-foreground">Ngày tạo</span>
                            <span class="text-sm text-foreground"><?= $sanPham->getNgayTao()->format('d/m/Y H:i') ?></span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-muted-foreground">Cập nhật lần cuối</span>
                            <span class="text-sm text-foreground"><?= $sanPham->getNgayCapNhat()->format('d/m/Y H:i') ?></span>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Pricing -->
            <div class="card">
                <header>
                    <h3>Thông tin giá</h3>
                    <p>Giá bán và giá so sánh</p>
                </header>
                <section>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">Giá bán</label>
                            <p class="text-2xl font-bold text-primary"><?= $sanPham->getGiaFormatted() ?></p>
                        </div>

                        <?php if ($sanPham->getGiaSoSanh() && $sanPham->getGiaSoSanh() > $sanPham->getGia()): ?>
                            <div>
                                <label class="text-sm font-medium text-muted-foreground">Giá so sánh</label>
                                <p class="text-lg text-muted-foreground line-through">
                                    <?= number_format($sanPham->getGiaSoSanh(), 0, ',', '.') ?> ₫
                                </p>
                                <?php $discount = round((($sanPham->getGiaSoSanh() - $sanPham->getGia()) / $sanPham->getGiaSoSanh()) * 100); ?>
                                <p class="text-sm text-green-600 font-medium">Tiết kiệm <?= $discount ?>%</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            </div>

            <!-- Category & Brand -->
            <div class="card">
                <header>
                    <h3>Phân loại</h3>
                    <p>Danh mục và thương hiệu</p>
                </header>
                <section>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">Danh mục</label>
                            <p class="text-foreground"><?= htmlspecialchars($sanPham->getDanhMuc()->getTen()) ?></p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-muted-foreground">Thương hiệu</label>
                            <p class="text-foreground"><?= htmlspecialchars($sanPham->getThuongHieu()->getTen()) ?></p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>