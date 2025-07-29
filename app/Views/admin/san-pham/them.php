<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-foreground">Thêm sản phẩm mới</h1>
        <p class="text-muted-foreground">Tạo sản phẩm mới trong hệ thống</p>
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
                                <label for="ma_san_pham">Mã sản phẩm *</label>
                                <input type="text" id="ma_san_pham" name="ma_san_pham" required
                                       placeholder="Nhập mã sản phẩm"
                                       value="<?= htmlspecialchars($_POST['ma_san_pham'] ?? '') ?>">
                            </div>

                            <div class="grid gap-2">
                                <label for="ten">Tên sản phẩm *</label>
                                <input type="text" id="ten" name="ten" required 
                                       placeholder="Nhập tên sản phẩm" 
                                       value="<?= htmlspecialchars($_POST['ten'] ?? '') ?>">
                            </div>

                            <div class="grid gap-2">
                                <label for="duong_dan">Đường dẫn (URL)</label>
                                <input type="text" id="duong_dan" name="duong_dan" 
                                       placeholder="duong-dan-san-pham (tự động tạo nếu để trống)"
                                       value="<?= htmlspecialchars($_POST['duong_dan'] ?? '') ?>">
                            </div>

                            <div class="grid gap-2">
                                <label for="mo_ta_ngan">Mô tả ngắn</label>
                                <textarea id="mo_ta_ngan" name="mo_ta_ngan" rows="3" 
                                          placeholder="Mô tả ngắn gọn về sản phẩm"><?= htmlspecialchars($_POST['mo_ta_ngan'] ?? '') ?></textarea>
                            </div>

                            <div class="grid gap-2">
                                <label for="mo_ta">Mô tả chi tiết</label>
                                <textarea id="mo_ta" name="mo_ta" rows="6" 
                                          placeholder="Mô tả chi tiết về sản phẩm"><?= htmlspecialchars($_POST['mo_ta'] ?? '') ?></textarea>
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
                                           value="<?= htmlspecialchars($_POST['gia'] ?? '') ?>">
                                </div>
                                
                                <div class="grid gap-2">
                                    <label for="gia_so_sanh">Giá so sánh</label>
                                    <input type="number" id="gia_so_sanh" name="gia_so_sanh" min="0" step="1000"
                                           placeholder="0 (để trống nếu không có)"
                                           value="<?= htmlspecialchars($_POST['gia_so_sanh'] ?? '') ?>">
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="card">
                    <header>
                        <h3>Thông số kỹ thuật</h3>
                        <p>Nhập các thông số chi tiết</p>
                    </header>
                    <section>
                        <div class="form grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-4">
                            <?php $tsPost = $_POST['thong_so'] ?? []; ?>
                            <div class="grid gap-2">
                                <label for="ts_kich_thuoc_man_hinh">Kích thước màn hình</label>
                                <input type="text" id="ts_kich_thuoc_man_hinh" name="thong_so[kich_thuoc_man_hinh]"
                                       value="<?= htmlspecialchars($tsPost['kich_thuoc_man_hinh'] ?? '') ?>">
                            </div>

                            <div class="grid gap-2">
                                <label for="ts_do_phan_giai">Độ phân giải</label>
                                <input type="text" id="ts_do_phan_giai" name="thong_so[do_phan_giai]"
                                       value="<?= htmlspecialchars($tsPost['do_phan_giai'] ?? '') ?>">
                            </div>

                            <div class="grid gap-2">
                                <label for="ts_loai_man_hinh">Loại màn hình</label>
                                <input type="text" id="ts_loai_man_hinh" name="thong_so[loai_man_hinh]"
                                       value="<?= htmlspecialchars($tsPost['loai_man_hinh'] ?? '') ?>">
                            </div>

                            <div class="grid gap-2">
                                <label for="ts_he_dieu_hanh">Hệ điều hành</label>
                                <input type="text" id="ts_he_dieu_hanh" name="thong_so[he_dieu_hanh]"
                                       value="<?= htmlspecialchars($tsPost['he_dieu_hanh'] ?? '') ?>">
                            </div>

                            <div class="grid gap-2">
                                <label for="ts_bo_xu_ly">Bộ xử lý</label>
                                <input type="text" id="ts_bo_xu_ly" name="thong_so[bo_xu_ly]"
                                       value="<?= htmlspecialchars($tsPost['bo_xu_ly'] ?? '') ?>">
                            </div>

                            <div class="grid gap-2">
                                <label for="ts_ram">RAM</label>
                                <input type="text" id="ts_ram" name="thong_so[ram]"
                                       value="<?= htmlspecialchars($tsPost['ram'] ?? '') ?>">
                            </div>

                            <div class="grid gap-2">
                                <label for="ts_bo_nho">Bộ nhớ trong</label>
                                <input type="text" id="ts_bo_nho" name="thong_so[bo_nho]"
                                       value="<?= htmlspecialchars($tsPost['bo_nho'] ?? '') ?>">
                            </div>

                            <div class="grid gap-2">
                                <label for="ts_mo_rong_bo_nho" class="inline-flex items-center">
                                    <input type="checkbox" id="ts_mo_rong_bo_nho" name="thong_so[mo_rong_bo_nho]" value="1"
                                        <?= isset($tsPost['mo_rong_bo_nho']) ? 'checked' : '' ?>>
                                    <span class="ml-2">Hỗ trợ mở rộng bộ nhớ</span>
                                </label>
                            </div>

                            <div class="grid gap-2">
                                <label for="ts_camera_sau">Camera sau</label>
                                <input type="text" id="ts_camera_sau" name="thong_so[camera_sau]"
                                       value="<?= htmlspecialchars($tsPost['camera_sau'] ?? '') ?>">
                            </div>

                            <div class="grid gap-2">
                                <label for="ts_camera_truoc">Camera trước</label>
                                <input type="text" id="ts_camera_truoc" name="thong_so[camera_truoc]"
                                       value="<?= htmlspecialchars($tsPost['camera_truoc'] ?? '') ?>">
                            </div>

                            <div class="grid gap-2">
                                <label for="ts_dung_luong_pin">Dung lượng pin</label>
                                <input type="text" id="ts_dung_luong_pin" name="thong_so[dung_luong_pin]"
                                       value="<?= htmlspecialchars($tsPost['dung_luong_pin'] ?? '') ?>">
                            </div>

                            <div class="grid gap-2">
                                <label for="ts_loai_sac">Loại sạc</label>
                                <input type="text" id="ts_loai_sac" name="thong_so[loai_sac]"
                                       value="<?= htmlspecialchars($tsPost['loai_sac'] ?? '') ?>">
                            </div>

                            <div class="grid gap-2">
                                <label for="ts_thoi_gian_bao_hanh">Thời gian bảo hành</label>
                                <input type="text" id="ts_thoi_gian_bao_hanh" name="thong_so[thoi_gian_bao_hanh]"
                                       value="<?= htmlspecialchars($tsPost['thoi_gian_bao_hanh'] ?? '') ?>">
                            </div>

                            <div class="grid gap-2">
                                <label for="ts_chong_nuoc">Chống nước</label>
                                <input type="text" id="ts_chong_nuoc" name="thong_so[chong_nuoc]"
                                       value="<?= htmlspecialchars($tsPost['chong_nuoc'] ?? '') ?>">
                            </div>

                            <div class="grid gap-2">
                                <label for="ts_ket_noi">Kết nối</label>
                                <input type="text" id="ts_ket_noi" name="thong_so[ket_noi]"
                                       placeholder="nhập các kết nối, cách nhau dấu phẩy"
                                       value="<?= htmlspecialchars(implode(',', (array)($tsPost['ket_noi'] ?? []))) ?>">
                            </div>

                            <div class="grid gap-2">
                                <label for="ts_mau_sac_co_san">Màu sắc</label>
                                <input type="text" id="ts_mau_sac_co_san" name="thong_so[mau_sac_co_san]"
                                       placeholder="nhập màu sắc, cách nhau dấu phẩy"
                                       value="<?= htmlspecialchars(implode(',', (array)($tsPost['mau_sac_co_san'] ?? []))) ?>">
                            </div>

                            <div class="grid gap-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="thong_so[cam_bien_van_tay]" value="1"
                                        <?= isset($tsPost['cam_bien_van_tay']) ? 'checked' : '' ?>>
                                    <span class="ml-2">Cảm biến vân tay</span>
                                </label>
                            </div>

                            <div class="grid gap-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="thong_so[mo_khoa_khuon_mat]" value="1"
                                        <?= isset($tsPost['mo_khoa_khuon_mat']) ? 'checked' : '' ?>>
                                    <span class="ml-2">Mở khóa khuôn mặt</span>
                                </label>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Variants Section -->
                <div class="card">
                    <header>
                        <div class="flex items-center justify-between">
                            <div>
                                <h3>Biến thể sản phẩm</h3>
                                <p>Quản lý các biến thể của sản phẩm</p>
                            </div>
                            <button type="button" class="btn-outline text-sm" onclick="addVariantRow()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14"/>
                                    <path d="M12 5v14"/>
                                </svg>
                                Thêm biến thể
                            </button>
                        </div>
                    </header>
                    <section>
                        <div id="variants-container" class="space-y-4">
                            <!-- Variants will be dynamically added here -->
                        </div>
                        
                        <div id="no-variants" class="text-center py-8 text-muted-foreground">
                            <p>Chưa có biến thể nào. Nhấn "Thêm biến thể" để bắt đầu.</p>
                        </div>
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
                                                <?= (($_POST['danh_muc_id'] ?? '') == $danhMuc->getId()) ? 'selected' : '' ?>>
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
                                                <?= (($_POST['thuong_hieu_id'] ?? '') == $thuongHieu->getId()) ? 'selected' : '' ?>>
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
                                       <?= isset($_POST['kich_hoat']) ? 'checked' : '' ?>>
                                <div>
                                    <div class="font-medium text-foreground">Kích hoạt</div>
                                    <div class="text-sm text-muted-foreground">Sản phẩm sẽ hiển thị trên website</div>
                                </div>
                            </label>

                            <label class="flex items-center space-x-3">
                                <input type="checkbox" name="noi_bat" value="1" 
                                       <?= isset($_POST['noi_bat']) ? 'checked' : '' ?>>
                                <div>
                                    <div class="font-medium text-foreground">Nổi bật</div>
                                    <div class="text-sm text-muted-foreground">Hiển thị trong danh sách sản phẩm nổi bật</div>
                                </div>
                            </label>

                            <label class="flex items-center space-x-3">
                                <input type="checkbox" name="sp_moi" value="1"
                                        <?= isset($_POST['sp_moi']) ? 'checked' : '' ?>>
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
                            Lưu sản phẩm
                        </button>
                        <a href="/admin/san-pham" class="btn-outline w-full text-center">
                            Hủy bỏ
                        </a>
                    </footer>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Add Image URL Dialog -->
<dialog id="add-image-dialog" class="dialog" aria-labelledby="add-image-title">
  <article>
    <header>
      <h2 id="add-image-title">Thêm hình ảnh biến thể</h2>
      <p>Nhập URL của hình ảnh để thêm vào biến thể</p>
    </header>
    
    <section>
      <div class="grid gap-4">
        <div class="grid gap-2">
          <label for="image-url-input" class="text-sm font-medium text-foreground">URL hình ảnh</label>
          <input type="url" id="image-url-input" 
                 placeholder="https://example.com/image.jpg" 
                 class="w-full px-3 py-2 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
        </div>
        <div id="image-preview" class="hidden">
          <img id="preview-img" src="" alt="Preview" class="w-full h-32 object-cover rounded-lg border border-border">
        </div>
      </div>
    </section>

    <footer>
      <button class="btn-outline" onclick="cancelAddImage()">Hủy bỏ</button>
      <button class="btn" onclick="confirmAddImage()">Thêm ảnh</button>
    </footer>
  </article>
</dialog>

<script>
let variantIndex = 0;
let currentVariantIndex = null;

// Hàm thêm biến thể mới
function addVariantRow() {
    const container = document.getElementById('variants-container');
    const noVariants = document.getElementById('no-variants');
    
    // Ẩn thông báo "chưa có biến thể"
    if (noVariants) {
        noVariants.style.display = 'none';
    }
    
    const variantHtml = `
        <div class="variant-row border border-border rounded-lg p-4" data-index="${variantIndex}" data-existing="false">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                    <h4 class="font-medium text-foreground">Biến thể #${variantIndex + 1}</h4>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Mới
                    </span>
                </div>
                <button type="button" class="btn-icon-outline size-8 text-red-600 hover:bg-red-50" onclick="removeVariantRow(this)" title="Xóa biến thể">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 6h18"/>
                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                    </svg>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="grid gap-2">
                    <label class="text-sm font-medium text-foreground">Mã sản phẩm *</label>
                    <input type="text" name="bien_thes[${variantIndex}][ma_san_pham]" required
                           placeholder="VD: IP15-128GB-BLK"
                           class="form-input">
                </div>
                
                <div class="grid gap-2">
                    <label class="text-sm font-medium text-foreground">Màu sắc</label>
                    <input type="text" name="bien_thes[${variantIndex}][mau_sac]"
                           placeholder="VD: Đen, Trắng, Xanh"
                           class="form-input">
                </div>
                
                <div class="grid gap-2">
                    <label class="text-sm font-medium text-foreground">Bộ nhớ</label>
                    <input type="text" name="bien_thes[${variantIndex}][bo_nho]"
                           placeholder="VD: 128GB, 256GB, 512GB"
                           class="form-input">
                </div>
                
                <div class="grid gap-2">
                    <label class="text-sm font-medium text-foreground">Giá bán *</label>
                    <input type="number" name="bien_thes[${variantIndex}][gia]" required min="0" step="1000"
                           placeholder="0"
                           class="form-input">
                </div>
                
                <div class="grid gap-2">
                    <label class="text-sm font-medium text-foreground">Giá so sánh</label>
                    <input type="number" name="bien_thes[${variantIndex}][gia_so_sanh]" min="0" step="1000"
                           placeholder="0"
                           class="form-input">
                </div>
                
                <div class="grid gap-2">
                    <label class="text-sm font-medium text-foreground">Số lượng tồn kho</label>
                    <input type="number" name="bien_thes[${variantIndex}][so_luong_ton]" min="0"
                           placeholder="0"
                           class="form-input">
                </div>
                
                <div class="grid gap-2">
                    <label class="text-sm font-medium text-foreground">Ngưỡng tồn thấp</label>
                    <input type="number" name="bien_thes[${variantIndex}][nguong_ton_thap]" min="0"
                           placeholder="5"
                           value="5"
                           class="form-input">
                </div>
                
                <div class="grid gap-2">
                    <label class="text-sm font-medium text-foreground">Trọng lượng (g)</label>
                    <input type="number" name="bien_thes[${variantIndex}][trong_luong]" min="0" step="0.1"
                           placeholder="0"
                           class="form-input">
                </div>
                
                <div class="grid gap-2">
                    <label class="text-sm font-medium text-foreground">Trạng thái</label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="bien_thes[${variantIndex}][kich_hoat]" value="1" checked
                               class="w-4 h-4 text-primary bg-background border-border rounded focus:ring-2 focus:ring-primary/20">
                        <span class="text-sm text-foreground">Kích hoạt</span>
                    </label>
                </div>
            </div>
            
            <!-- Image Management Section -->
            <div class="mt-4 pt-4 border-t border-border">
                <div class="flex items-center justify-between mb-3">
                    <h5 class="font-medium text-foreground">Hình ảnh biến thể</h5>
                    <button type="button" class="btn-outline text-xs" onclick="addVariantImage(${variantIndex})">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"/>
                            <path d="M12 5v14"/>
                        </svg>
                        Thêm ảnh
                    </button>
                </div>
                <div id="variant-images-${variantIndex}" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    <!-- Images will be added here -->
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', variantHtml);
    variantIndex++;
    updateVariantNumbers();
}

// Hàm xóa biến thể
function removeVariantRow(button) {
    const row = button.closest('.variant-row');
    row.remove();
    
    // Cập nhật số thứ tự các biến thể còn lại
    updateVariantNumbers();
    
    // Hiển thị thông báo nếu không còn biến thể nào
    const container = document.getElementById('variants-container');
    const noVariants = document.getElementById('no-variants');
    if (container.children.length === 0 && noVariants) {
        noVariants.style.display = 'block';
    }
}

// Cập nhật số thứ tự biến thể
function updateVariantNumbers() {
    const rows = document.querySelectorAll('.variant-row');
    rows.forEach((row, index) => {
        const title = row.querySelector('h4');
        if (title) {
            title.textContent = `Biến thể #${index + 1}`;
        }
        row.setAttribute('data-index', index.toString());
    });
}

// Thêm ảnh cho biến thể
function addVariantImage(variantIndex) {
    currentVariantIndex = variantIndex;
    const dialog = document.getElementById('add-image-dialog');
    const input = document.getElementById('image-url-input');
    const preview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    
    // Reset form
    input.value = '';
    preview.classList.add('hidden');
    previewImg.src = '';
    
    dialog.showModal();
}

// Xử lý URL ảnh
function handleImageUrl(imageUrl, variantIndex) {
    // Kiểm tra URL có hợp lệ không
    if (!isValidImageUrl(imageUrl)) {
        alert('Vui lòng nhập URL hình ảnh hợp lệ!');
        return;
    }

    const imageContainer = document.getElementById(`variant-images-${variantIndex}`);
    const imageIndex = imageContainer.children.length;
    
    const imageHtml = `
        <div class="variant-image-item relative group border border-border rounded-lg overflow-hidden">
            <img src="${imageUrl}" alt="Hình ảnh biến thể" class="w-full h-20 object-cover" 
                 onerror="this.src='/images/placeholder.png'">
            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-200 flex items-center justify-center">
                <button type="button" 
                        class="opacity-0 group-hover:opacity-100 btn-icon-outline size-6 text-red-600 bg-white hover:bg-red-50" 
                        onclick="removeVariantImageElement(this)"
                        title="Xóa ảnh">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"/>
                        <path d="m6 6 12 12"/>
                    </svg>
                </button>
            </div>
            <input type="hidden" name="bien_thes[${variantIndex}][hinh_anhs_moi][${imageIndex}][url]" value="${imageUrl}">
        </div>
    `;
    
    imageContainer.insertAdjacentHTML('beforeend', imageHtml);
}

// Kiểm tra URL hình ảnh hợp lệ
function isValidImageUrl(url) {
    try {
        new URL(url);
        return /\.(jpg|jpeg|png|gif|webp|svg)(\?.*)?$/i.test(url) || 
               url.includes('data:image/') ||
               url.includes('imgur.com') ||
               url.includes('cloudinary.com') ||
               url.includes('unsplash.com');
    } catch {
        return false;
    }
}

// Xóa ảnh mới (chưa lưu)
function removeVariantImageElement(button) {
    button.closest('.variant-image-item').remove();
}

// Xác nhận thêm ảnh
function confirmAddImage() {
    const input = document.getElementById('image-url-input');
    const imageUrl = input.value.trim();
    
    if (imageUrl && currentVariantIndex !== null) {
        handleImageUrl(imageUrl, currentVariantIndex);
        document.getElementById('add-image-dialog').close();
    } else {
        alert('Vui lòng nhập URL hình ảnh!');
    }
}

// Hủy thêm ảnh
function cancelAddImage() {
    currentVariantIndex = null;
    document.getElementById('add-image-dialog').close();
}

// Preview image when URL is entered
document.getElementById('image-url-input').addEventListener('input', function(e) {
    const url = e.target.value.trim();
    const preview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    
    if (url && isValidImageUrl(url)) {
        previewImg.src = url;
        previewImg.onload = function() {
            preview.classList.remove('hidden');
        };
        previewImg.onerror = function() {
            preview.classList.add('hidden');
        };
    } else {
        preview.classList.add('hidden');
    }
});

// Handle Enter key in URL input
document.getElementById('image-url-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        confirmAddImage();
    }
});
</script>