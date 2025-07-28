<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-foreground"><?= htmlspecialchars($thuongHieu->getTen()) ?></h1>
            <p class="text-muted-foreground">Chi tiết thương hiệu • Đường dẫn: <?= htmlspecialchars($thuongHieu->getDuongDan()) ?></p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="/admin/thuong-hieu/sua/<?= $thuongHieu->getId() ?>" class="btn-outline">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                </svg>
                Chỉnh sửa
            </a>
            <a href="/admin/thuong-hieu" class="btn-outline">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 12h18"/>
                    <path d="M9 6l-6 6 6 6"/>
                </svg>
                Quay lại
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card">
                <header>
                    <h3>Thông tin cơ bản</h3>
                    <p>Chi tiết thông tin thương hiệu</p>
                </header>
                <section>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">Tên</label>
                            <p class="text-foreground"><?= htmlspecialchars($thuongHieu->getTen()) ?></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">Đường dẫn (URL)</label>
                            <p class="text-foreground font-mono text-sm"><?= htmlspecialchars($thuongHieu->getDuongDan()) ?></p>
                        </div>
                        <?php if ($thuongHieu->getLogo()): ?>
                            <div>
                                <label class="text-sm font-medium text-muted-foreground">Logo</label>
                                <div class="mt-2">
                                    <img src="<?= htmlspecialchars($thuongHieu->getLogo()) ?>" alt="<?= htmlspecialchars($thuongHieu->getTen()) ?>" class="w-32 h-32 object-cover rounded-lg border">
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($thuongHieu->getMoTa()): ?>
                            <div>
                                <label class="text-sm font-medium text-muted-foreground">Mô tả</label>
                                <div class="text-foreground whitespace-pre-line bg-muted/50 p-4 rounded-lg">
                                    <?= nl2br(htmlspecialchars($thuongHieu->getMoTa())) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($thuongHieu->getWebsite()): ?>
                            <div>
                                <label class="text-sm font-medium text-muted-foreground">Website</label>
                                <p class="text-primary underline">
                                    <a href="<?= htmlspecialchars($thuongHieu->getWebsite()) ?>" target="_blank" rel="noopener">
                                        <?= htmlspecialchars($thuongHieu->getWebsite()) ?>
                                    </a>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            </div>

            <div class="card">
                <header>
                    <h3>Sản phẩm liên quan</h3>
                    <p>Số sản phẩm: <?= $thuongHieu->getSanPhams()->count() ?></p>
                </header>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="card">
                <header>
                    <h3>Trạng thái</h3>
                    <p>Thông tin hiển thị thương hiệu</p>
                </header>
                <section>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-muted-foreground">Kích hoạt</span>
                            <?php if ($thuongHieu->isKichHoat()): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Có</span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Không</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            </div>

            <div class="card">
                <header>
                    <h3>Thông tin thời gian</h3>
                    <p>Ngày tạo và cập nhật</p>
                </header>
                <section>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-muted-foreground">Ngày tạo</span>
                            <span class="text-sm text-foreground"><?= $thuongHieu->getNgayTao()->format('d/m/Y H:i') ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-muted-foreground">Cập nhật</span>
                            <span class="text-sm text-foreground"><?= $thuongHieu->getNgayCapNhat()->format('d/m/Y H:i') ?></span>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>