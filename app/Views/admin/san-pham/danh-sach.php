<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-foreground">Quản lý Sản phẩm</h1>
            <p class="text-muted-foreground">Danh sách tất cả sản phẩm (<?= $total ?> sản phẩm)</p>
        </div>
        <a href="/admin/san-pham/them" class="btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"/>
                <path d="M12 5v14"/>
            </svg>
            Thêm sản phẩm
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Search and Filter Form -->
    <div class="card">
        <header>
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>
                <h3>Tìm kiếm & Lọc</h3>
            </div>
            <p>Tìm kiếm và lọc sản phẩm theo các tiêu chí</p>
        </header>
        <section>
            <form method="GET" class="space-y-6">
                <input type="hidden" name="page" value="1">
                
                <!-- Main Search Input -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.35-4.35"/>
                        </svg>
                    </div>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           placeholder="Tìm kiếm theo tên sản phẩm, mã sản phẩm hoặc mô tả..."
                           value="<?= htmlspecialchars($search) ?>"
                           class="w-full pl-10 pr-4 py-3 border border-border rounded-lg bg-background focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                </div>

                <!-- Advanced Filters -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Category Filter -->
                    <div class="space-y-2">
                        <label for="danh_muc" class="text-sm font-medium text-foreground flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground">
                                <path d="M3 3h18v18H3z"/>
                                <path d="M9 9h6v6H9z"/>
                            </svg>
                            Danh mục
                        </label>
                        <div class="relative">
                            <select id="danh_muc" name="danh_muc" class="w-full appearance-none bg-background border border-border rounded-lg px-3 py-2.5 pr-8 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                                <option value="">Tất cả danh mục</option>
                                <?php foreach ($danhMucs as $danhMuc): ?>
                                    <option value="<?= $danhMuc->getId() ?>" 
                                            <?= $selectedDanhMuc == $danhMuc->getId() ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($danhMuc->getTen()) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground">
                                    <path d="m6 9 6 6 6-6"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Brand Filter -->
                    <div class="space-y-2">
                        <label for="thuong_hieu" class="text-sm font-medium text-foreground flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                                <line x1="3" x2="21" y1="6" y2="6"/>
                                <path d="M16 10a4 4 0 0 1-8 0"/>
                            </svg>
                            Thương hiệu
                        </label>
                        <div class="relative">
                            <select id="thuong_hieu" name="thuong_hieu" class="w-full appearance-none bg-background border border-border rounded-lg px-3 py-2.5 pr-8 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                                <option value="">Tất cả thương hiệu</option>
                                <?php foreach ($thuongHieus as $thuongHieu): ?>
                                    <option value="<?= $thuongHieu->getId() ?>" 
                                            <?= $selectedThuongHieu == $thuongHieu->getId() ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($thuongHieu->getTen()) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground">
                                    <path d="m6 9 6 6 6-6"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="space-y-2">
                        <label for="trang_thai" class="text-sm font-medium text-foreground flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground">
                                <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/>
                                <path d="M19 10v2a7 7 0 0 1-14 0v-2"/>
                                <line x1="12" x2="12" y1="19" y2="22"/>
                            </svg>
                            Trạng thái
                        </label>
                        <div class="relative">
                            <select id="trang_thai" name="trang_thai" class="w-full appearance-none bg-background border border-border rounded-lg px-3 py-2.5 pr-8 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                                <option value="">Tất cả trạng thái</option>
                                <option value="1" <?= $selectedTrangThai === '1' ? 'selected' : '' ?>>
                                    Hoạt động
                                </option>
                                <option value="0" <?= $selectedTrangThai === '0' ? 'selected' : '' ?>>
                                    Tạm dừng
                                </option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground">
                                    <path d="m6 9 6 6 6-6"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Checkbox Filters -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Featured Products Filter -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-foreground flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground">
                                <polygon points="12 2 15.09 8.26 22 9 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9 8.91 8.26 12 2"/>
                            </svg>
                            Sản phẩm nổi bật
                        </label>
                        <div class="flex items-center space-x-3">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="noi_bat" 
                                       value="1" 
                                       <?= isset($_GET['noi_bat']) && $_GET['noi_bat'] == '1' ? 'checked' : '' ?>
                                       class="w-4 h-4 text-primary bg-background border-border rounded focus:ring-2 focus:ring-primary/20">
                                <span class="ml-2 text-sm text-foreground">Chỉ hiển thị sản phẩm nổi bật</span>
                            </label>
                        </div>
                    </div>

                    <!-- New Products Filter -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-foreground flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground">
                                <path d="M8 2v4"/>
                                <path d="M16 2v4"/>
                                <rect width="18" height="18" x="3" y="4" rx="2"/>
                                <path d="M3 10h18"/>
                                <path d="M8 14h.01"/>
                                <path d="M12 14h.01"/>
                                <path d="M16 14h.01"/>
                                <path d="M8 18h.01"/>
                                <path d="M12 18h.01"/>
                                <path d="M16 18h.01"/>
                            </svg>
                            Sản phẩm mới
                        </label>
                        <div class="flex items-center space-x-3">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="sp_moi" 
                                       value="1" 
                                       <?= isset($_GET['sp_moi']) && $_GET['sp_moi'] == '1' ? 'checked' : '' ?>
                                       class="w-4 h-4 text-primary bg-background border-border rounded focus:ring-2 focus:ring-primary/20">
                                <span class="ml-2 text-sm text-foreground">Chỉ hiển thị sản phẩm mới</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-border">
                    <button type="submit" class="btn inline-flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.35-4.35"/>
                        </svg>
                        Tìm kiếm
                    </button>
                    
                    <a href="/admin/san-pham" class="btn-outline inline-flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 6h18"/>
                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                        </svg>
                        Xóa bộ lọc
                    </a>

                    <!-- Show active filters count -->
                    <?php 
                    $activeFilters = 0;
                    if (!empty($search)) $activeFilters++;
                    if ($selectedDanhMuc) $activeFilters++;
                    if ($selectedThuongHieu) $activeFilters++;
                    if ($selectedTrangThai !== '') $activeFilters++;
                    if ($selectedNoiBat === '1') $activeFilters++;
                    if ($selectedSpMoi === '1') $activeFilters++;
                    ?>
                    <?php if ($activeFilters > 0): ?>
                        <div class="flex items-center gap-2 text-sm text-muted-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                            </svg>
                            <span><?= $activeFilters ?> bộ lọc đang áp dụng</span>
                        </div>
                    <?php endif; ?>
                </div>
            </form>
        </section>
    </div>

    <div class="card">
        <header>
            <h3>Danh sách sản phẩm</h3>
            <p>
                <?php if (!empty($search) || $selectedDanhMuc || $selectedThuongHieu || $selectedTrangThai !== '' || $selectedNoiBat === '1' || $selectedSpMoi === '1'): ?>
                    Kết quả tìm kiếm: <?= $total ?> sản phẩm
                <?php else: ?>
                    Quản lý tất cả sản phẩm trong hệ thống (<?= $total ?> sản phẩm)
                <?php endif; ?>
            </p>
        </header>
        <section>
            <?php if (empty($sanPhams)): ?>
                <div class="text-center py-8">
                    <div class="text-4xl mb-4">📦</div>
                    <p class="text-muted-foreground">Chưa có sản phẩm nào</p>
                    <a href="/admin/san-pham/them" class="btn mt-4">Thêm sản phẩm đầu tiên</a>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-border">
                                <th class="text-left py-3 px-4 font-medium text-foreground">Sản phẩm</th>
                                <th class="text-left py-3 px-4 font-medium text-foreground">Danh mục</th>
                                <th class="text-left py-3 px-4 font-medium text-foreground">Giá</th>
                                <th class="text-left py-3 px-4 font-medium text-foreground">Trạng thái</th>
                                <th class="text-left py-3 px-4 font-medium text-foreground">Biến thể</th>
                                <th class="text-right py-3 px-4 font-medium text-foreground">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sanPhams as $sanPham): ?>
                                <tr class="border-b border-border hover:bg-muted/50">
                                    <td class="py-3 px-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-12 h-12 bg-muted rounded-lg flex items-center justify-center">
                                                <?php if ($sanPham->getHinhAnhChinh()): ?>
                                                    <img src="<?= htmlspecialchars($sanPham->getHinhAnhChinh()->getFullUrl()) ?>" 
                                                         alt="<?= htmlspecialchars($sanPham->getTen()) ?>"
                                                         class="w-full h-full object-cover rounded-lg">
                                                <?php else: ?>
                                                    <span class="text-xl">📱</span>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <div class="font-medium text-foreground"><?= htmlspecialchars($sanPham->getTen()) ?></div>
                                                <div class="text-sm text-muted-foreground"><?= htmlspecialchars($sanPham->getMaSanPham()) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="text-sm text-muted-foreground">
                                            <?= htmlspecialchars($sanPham->getDanhMuc()->getTen()) ?>
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="font-medium text-foreground"><?= $sanPham->getGiaFormatted() ?></div>
                                        <?php if ($sanPham->getGiaSoSanh() && $sanPham->getGiaSoSanh() > $sanPham->getGia()): ?>
                                            <div class="text-sm text-muted-foreground line-through">
                                                <?= number_format($sanPham->getGiaSoSanh(), 0, ',', '.') ?> ₫
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 px-4">
                                        <?php if ($sanPham->isKichHoat()): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Hoạt động
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Tạm dừng
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($sanPham->isNoiBat()): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Nổi bật
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($sanPham->isSpMoi()): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Sản phẩm mới
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="text-sm text-muted-foreground">
                                            <?= $sanPham->getBienThes()->count() ?> biến thể
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="/admin/san-pham/chi-tiet/<?= $sanPham->getId() ?>" 
                                               class="btn-icon-outline size-8" title="Xem chi tiết">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                    <circle cx="12" cy="12" r="3"/>
                                                </svg>
                                            </a>
                                            <a href="/admin/san-pham/sua/<?= $sanPham->getId() ?>" 
                                               class="btn-icon-outline size-8" title="Chỉnh sửa">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                                                </svg>
                                            </a>
                                            <form method="POST" action="/admin/san-pham/xoa/<?= $sanPham->getId() ?>" class="inline-block" 
                                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                                <button type="submit" class="btn-icon-outline size-8 text-red-600 hover:bg-red-50" title="Xóa">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M3 6h18"/>
                                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($totalPages > 1): ?>
                    <div class="flex items-center justify-between mt-6 pt-6 border-t border-border">
                        <div class="text-sm text-muted-foreground">
                            Trang <?= $currentPage ?> / <?= $totalPages ?>
                        </div>
                        <div class="flex items-center space-x-2">
                            <?php
                            // Build query parameters for pagination
                            $queryParams = [];
                            if (!empty($search)) $queryParams['search'] = $search;
                            if ($selectedDanhMuc) $queryParams['danh_muc'] = $selectedDanhMuc;
                            if ($selectedThuongHieu) $queryParams['thuong_hieu'] = $selectedThuongHieu;
                            if ($selectedTrangThai !== '') $queryParams['trang_thai'] = $selectedTrangThai;
                            if ($selectedNoiBat === '1') $queryParams['noi_bat'] = $selectedNoiBat;
                            if ($selectedSpMoi === '1') $queryParams['sp_moi'] = $selectedSpMoi;
                            
                            $baseQuery = !empty($queryParams) ? '&' . http_build_query($queryParams) : '';
                            ?>
                            
                            <?php if ($currentPage > 1): ?>
                                <a href="?page=<?= $currentPage - 1 ?><?= $baseQuery ?>" class="btn-outline text-sm">Trước</a>
                            <?php endif; ?>
                            
                            <?php if ($currentPage < $totalPages): ?>
                                <a href="?page=<?= $currentPage + 1 ?><?= $baseQuery ?>" class="btn-outline text-sm">Sau</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </section>
    </div>
</div>