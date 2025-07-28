<div class="search-page">
    <div class="container mx-auto px-4 py-8">
        <!-- Search Form -->
        <div class="search-form bg-card p-6 rounded-lg shadow-sm border mb-8">
            <form method="GET" action="/tim-kiem-san-pham" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Search Input -->
                    <div>
                        <label for="q" class="block text-sm font-medium text-foreground mb-2">Từ khóa tìm kiếm</label>
                        <input type="text" 
                               id="q" 
                               name="q" 
                               value="<?= htmlspecialchars($tuKhoaTimKiem) ?>"
                               placeholder="Nhập tên sản phẩm, mã sản phẩm..."
                               class="w-full px-3 py-2 border border-border rounded-md bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label for="danh_muc" class="block text-sm font-medium text-foreground mb-2">Danh mục</label>
                        <select id="danh_muc" 
                                name="danh_muc" 
                                onchange="this.form.submit()"
                                class="w-full px-3 py-2 border border-border rounded-md bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="">Tất cả danh mục</option>
                            <?php foreach ($danhMucs as $danhMuc): ?>
                                <option value="<?= $danhMuc->getId() ?>" 
                                        <?= $danhMucId == $danhMuc->getId() ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($danhMuc->getTen()) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Brand Filter -->
                    <div>
                        <label for="thuong_hieu" class="block text-sm font-medium text-foreground mb-2">Thương hiệu</label>
                        <select id="thuong_hieu" 
                                name="thuong_hieu" 
                                onchange="this.form.submit()"
                                class="w-full px-3 py-2 border border-border rounded-md bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="">Tất cả thương hiệu</option>
                            <?php foreach ($thuongHieus as $thuongHieu): ?>
                                <option value="<?= $thuongHieu->getId() ?>" 
                                        <?= $thuongHieuId == $thuongHieu->getId() ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($thuongHieu->getTen()) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div>
                        <label for="gia_thap_nhat" class="block text-sm font-medium text-foreground mb-2">Giá từ</label>
                        <input type="number" 
                               id="gia_thap_nhat" 
                               name="gia_thap_nhat" 
                               value="<?= htmlspecialchars($giaThapNhat) ?>"
                               placeholder="0"
                               min="0"
                               class="w-full px-3 py-2 border border-border rounded-md bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>

                    <div>
                        <label for="gia_cao_nhat" class="block text-sm font-medium text-foreground mb-2">Giá đến</label>
                        <input type="number" 
                               id="gia_cao_nhat" 
                               name="gia_cao_nhat" 
                               value="<?= htmlspecialchars($giaCaoNhat) ?>"
                               placeholder="999999999"
                               min="0"
                               class="w-full px-3 py-2 border border-border rounded-md bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>

                    <!-- Sort By -->
                    <div>
                        <label for="sap_xep" class="block text-sm font-medium text-foreground mb-2">Sắp xếp</label>
                        <select id="sap_xep" 
                                name="sap_xep" 
                                onchange="this.form.submit()"
                                class="w-full px-3 py-2 border border-border rounded-md bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="moi_nhat" <?= $sapXep === 'moi_nhat' ? 'selected' : '' ?>>Mới nhất</option>
                            <option value="gia_tang" <?= $sapXep === 'gia_tang' ? 'selected' : '' ?>>Giá tăng dần</option>
                            <option value="gia_giam" <?= $sapXep === 'gia_giam' ? 'selected' : '' ?>>Giá giảm dần</option>
                            <option value="ten_tang" <?= $sapXep === 'ten_tang' ? 'selected' : '' ?>>Tên A-Z</option>
                            <option value="ten_giam" <?= $sapXep === 'ten_giam' ? 'selected' : '' ?>>Tên Z-A</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <button type="submit" 
                            class="bg-primary text-primary-foreground px-6 py-2 rounded-md hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary">
                        Tìm kiếm
                    </button>
                    <a href="/tim-kiem-san-pham" 
                       class="text-muted-foreground hover:text-foreground">
                        Xóa bộ lọc
                    </a>
                </div>
            </form>
        </div>

        <!-- Search Results -->
        <div class="search-results">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-foreground">
                    Kết quả tìm kiếm
                    <?php if (!empty($tuKhoaTimKiem)): ?>
                        cho "<?= htmlspecialchars($tuKhoaTimKiem) ?>"
                    <?php endif; ?>
                </h2>
                <span class="text-muted-foreground">
                    Tìm thấy <?= $tongSanPham ?> sản phẩm
                </span>
            </div>

            <?php if (empty($sanPhams)): ?>
                <div class="text-center py-12">
                    <div class="text-muted-foreground text-6xl mb-4">
                        🔍
                    </div>
                    <h3 class="text-xl font-semibold text-foreground mb-2">Không tìm thấy sản phẩm</h3>
                    <p class="text-muted-foreground">Hãy thử tìm kiếm với từ khóa khác hoặc điều chỉnh bộ lọc</p>
                </div>
            <?php else: ?>
                <!-- Products Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
                    <?php foreach ($sanPhams as $sanPham): ?>
                        <?php component('product-card', ['sanPham' => $sanPham]); ?>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($tongTrang > 1): ?>
                    <div class="pagination flex justify-center items-center space-x-2">
                        <?php
                        $currentParams = $_GET;
                        ?>
                        
                        <!-- Previous Page -->
                        <?php if ($trangHienTai > 1): ?>
                            <?php
                            $currentParams['trang'] = $trangHienTai - 1;
                            $prevUrl = '?' . http_build_query($currentParams);
                            ?>
                            <a href="<?= $prevUrl ?>" 
                               class="px-3 py-2 text-muted-foreground hover:text-foreground hover:bg-card rounded border">
                                &laquo; Trước
                            </a>
                        <?php endif; ?>

                        <!-- Page Numbers -->
                        <?php
                        $startPage = max(1, $trangHienTai - 2);
                        $endPage = min($tongTrang, $trangHienTai + 2);
                        ?>
                        
                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <?php
                            $currentParams['trang'] = $i;
                            $pageUrl = '?' . http_build_query($currentParams);
                            ?>
                            <a href="<?= $pageUrl ?>" 
                               class="px-3 py-2 <?= $i === $trangHienTai ? 'bg-primary text-primary-foreground' : 'text-muted-foreground hover:text-foreground hover:bg-card' ?> rounded border">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <!-- Next Page -->
                        <?php if ($trangHienTai < $tongTrang): ?>
                            <?php
                            $currentParams['trang'] = $trangHienTai + 1;
                            $nextUrl = '?' . http_build_query($currentParams);
                            ?>
                            <a href="<?= $nextUrl ?>" 
                               class="px-3 py-2 text-muted-foreground hover:text-foreground hover:bg-card rounded border">
                                Sau &raquo;
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>