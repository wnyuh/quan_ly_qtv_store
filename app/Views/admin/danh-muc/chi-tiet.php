<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-foreground">
                <?= htmlspecialchars($danhMuc->getTen()) ?>
            </h1>
            <p class="text-muted-foreground">
                Chi tiết danh mục • Đường dẫn: <?= htmlspecialchars($danhMuc->getDuongDan()) ?>
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="/admin/danh-muc/sua/<?= $danhMuc->getId() ?>" class="btn-outline">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                </svg>
                Chỉnh sửa
            </a>
            <a href="/admin/danh-muc" class="btn-outline">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 12h18"/>
                    <path d="M9 6l6 6-6 6"/>
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
                    <p>Chi tiết thông tin danh mục</p>
                </header>
                <section>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">Tên danh mục</label>
                            <p class="text-foreground"><?= htmlspecialchars($danhMuc->getTen()) ?></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">Đường dẫn (URL)</label>
                            <p class="text-foreground font-mono text-sm"><?= htmlspecialchars($danhMuc->getDuongDan()) ?></p>
                        </div>
                        <?php if ($danhMuc->getMoTa()): ?>
                            <div>
                                <label class="text-sm font-medium text-muted-foreground">Mô tả</label>
                                <div class="text-foreground whitespace-pre-line bg-muted/50 p-4 rounded-lg">
                                    <?= nl2br(htmlspecialchars($danhMuc->getMoTa())) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($danhMuc->getHinhAnh()): ?>
                            <div>
                                <label class="text-sm font-medium text-muted-foreground">Hình ảnh</label>
                                <div class="mt-2">
                                    <img src="<?= htmlspecialchars($danhMuc->getHinhAnh()) ?>"
                                         alt="<?= htmlspecialchars($danhMuc->getTen()) ?>"
                                         class="w-32 h-32 object-cover rounded-lg border">
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            </div>

            <!-- Child Categories & Products Count -->
            <div class="card">
                <header>
                    <h3>Liên kết</h3>
                    <p>Số lượng con và sản phẩm</p>
                </header>
                <section>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-muted-foreground">Danh mục con</span>
                            <span class="text-foreground"><?= $danhMuc->getDanhMucCons()->count() ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-muted-foreground">Sản phẩm thuộc danh mục</span>
                            <span class="text-foreground"><?= $danhMuc->getSanPhams()->count() ?></span>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status & Ordering -->
            <div class="card">
                <header>
                    <h3>Trạng thái & Thứ tự</h3>
                    <p>Thông tin hiển thị và sắp xếp</p>
                </header>
                <section>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-muted-foreground">Kích hoạt</span>
                            <?php if ($danhMuc->isKichHoat()): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Có
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Không
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-muted-foreground">Thứ tự</span>
                            <span class="text-foreground"><?= htmlspecialchars($danhMuc->getThuTu()) ?></span>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Dates -->
            <div class="card">
                <header>
                    <h3>Thông tin thời gian</h3>
                    <p>Ngày tạo và cập nhật</p>
                </header>
                <section>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-muted-foreground">Ngày tạo</span>
                            <span class="text-sm text-foreground">
                                <?= $danhMuc->getNgayTao()->format('d/m/Y H:i') ?>
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-muted-foreground">Cập nhật lần cuối</span>
                            <span class="text-sm text-foreground">
                                <?= $danhMuc->getNgayCapNhat()->format('d/m/Y H:i') ?>
                            </span>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
