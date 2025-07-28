<!-- Breadcrumb -->
<div class="flex items-center space-x-2 text-sm text-muted-foreground mb-6">
    <a href="/" class="hover:text-foreground transition-colors">Trang chủ</a>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
    </svg>
    <a href="/tim-kiem-san-pham" class="hover:text-foreground transition-colors">Sản phẩm</a>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
    </svg>
    <span class="text-foreground"><?= htmlspecialchars($sanPham->getTen()) ?></span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
    <!-- Product Images -->
    <div class="space-y-4">
        <!-- Main Image -->
        <div class="aspect-square bg-card border border-border rounded-lg overflow-hidden">
            <div class="w-full h-full flex items-center justify-center p-8">
                <?php 
                $hinhAnhChinh = $sanPham->getHinhAnhChinh();
                if ($hinhAnhChinh): 
                ?>
                    <img id="mainImage" 
                         src="<?= htmlspecialchars($hinhAnhChinh->getFullUrl()) ?>" 
                         alt="<?= htmlspecialchars($sanPham->getTen()) ?>"
                         class="max-w-full max-h-full object-contain">
                <?php else: ?>
                    <div class="text-6xl text-muted-foreground">📱</div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Thumbnail Images -->
        <?php if ($sanPham->getHinhAnhs()->count() > 1): ?>
            <div class="flex gap-2 overflow-x-auto pb-2">
                <?php foreach ($sanPham->getHinhAnhs() as $index => $hinhAnh): ?>
                    <button onclick="changeMainImage('<?= htmlspecialchars($hinhAnh->getFullUrl()) ?>', this)"
                            class="thumbnail flex-shrink-0 w-16 h-16 border-2 border-transparent hover:border-primary rounded-lg overflow-hidden transition-colors <?= $index === 0 ? 'border-primary' : '' ?>">
                        <img src="<?= htmlspecialchars($hinhAnh->getFullUrl()) ?>" 
                             alt="<?= htmlspecialchars($sanPham->getTen()) ?> - Ảnh <?= $index + 1 ?>"
                             class="w-full h-full object-contain">
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Product Information -->
    <div class="space-y-6">
        <!-- Product Title and Brand -->
        <div class="space-y-2">
            <h1 class="text-2xl lg:text-3xl font-bold text-foreground">
                <?= htmlspecialchars($sanPham->getTen()) ?>
            </h1>
            <div class="flex items-center gap-4 text-sm text-muted-foreground">
                <span>Thương hiệu: <span class="font-medium text-foreground"><?= htmlspecialchars($sanPham->getThuongHieu()->getTen()) ?></span></span>
                <span>|</span>
                <span>Mã: <?= htmlspecialchars($sanPham->getMaSanPham()) ?></span>
            </div>
        </div>

        <!-- Price -->
        <div class="space-y-3">
            <div class="flex items-end gap-3">
                <span class="text-2xl lg:text-3xl font-bold text-primary">
                    <?= $sanPham->getGiaFormatted() ?>
                </span>
                <?php if ($sanPham->getGiaSoSanh() && $sanPham->getGiaSoSanh() > $sanPham->getGia()): ?>
                    <span class="text-lg text-muted-foreground line-through">
                        <?= number_format($sanPham->getGiaSoSanh(), 0, ',', '.') ?> ₫
                    </span>
                <?php endif; ?>
            </div>
            
            <?php if ($sanPham->getGiaSoSanh() && $sanPham->getGiaSoSanh() > $sanPham->getGia()): ?>
                <?php $discount = round((($sanPham->getGiaSoSanh() - $sanPham->getGia()) / $sanPham->getGiaSoSanh()) * 100); ?>
                <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-destructive/10 text-destructive">
                    Tiết kiệm <?= $discount ?>% (<?= number_format($sanPham->getGiaSoSanh() - $sanPham->getGia(), 0, ',', '.') ?> ₫)
                </div>
            <?php endif; ?>
        </div>

        <!-- Short Description -->
        <?php if ($sanPham->getMoTaNgan()): ?>
            <div class="p-4 bg-muted/50 rounded-lg border border-border">
                <h3 class="font-semibold text-foreground mb-2">Đặc điểm nổi bật</h3>
                <p class="text-sm text-muted-foreground leading-relaxed">
                    <?= nl2br(htmlspecialchars($sanPham->getMoTaNgan())) ?>
                </p>
            </div>
        <?php endif; ?>

        <!-- Variant Selection -->
        <?php if ($bienTheList && $bienTheList->count() > 1): ?>
            <div class="space-y-3">
                <h3 class="font-semibold text-foreground">Chọn phiên bản:</h3>
                <div class="grid grid-cols-1 gap-2">
                    <?php foreach ($bienTheList as $index => $bienThe): ?>
                        <label class="variant-option relative flex items-center justify-between p-3 border-2 border-border rounded-lg cursor-pointer hover:bg-muted/50 transition-all <?= $index === 0 ? 'border-primary bg-primary/5' : '' ?>">
                            <input type="radio" name="selected_variant" value="<?= $bienThe->getId() ?>" 
                                   <?= $index === 0 ? 'checked' : '' ?>
                                   data-variant-image="<?= $bienThe->getHinhAnhs()->first() ? htmlspecialchars($bienThe->getHinhAnhs()->first()->getFullUrl()) : '' ?>"
                                   class="sr-only variant-radio">
                            
                            <!-- Check Icon -->
                            <div class="check-icon absolute top-2 right-2 w-5 h-5 rounded-full bg-primary text-primary-foreground items-center justify-center <?= $index === 0 ? 'flex' : 'hidden' ?>">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            
                            <div class="flex-1 pr-2">
                                <span class="font-medium text-foreground"><?= htmlspecialchars($bienThe->getTenDayDu()) ?></span>
                                <?php if ($bienThe->isTonThap()): ?>
                                    <span class="ml-2 text-xs text-orange-600 dark:text-orange-400">(Sắp hết hàng)</span>
                                <?php endif; ?>
                            </div>
                            <span class="font-bold text-primary"><?= $bienThe->getGiaFormatted() ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Add to Cart Form -->
        <div class="space-y-4 p-6 bg-card border border-border rounded-lg">
            <form id="addToCartForm" class="space-y-4">
                <?php if ($bienTheSelected): ?>
                    <input type="hidden" name="bien_the_id" id="selectedVariantId" value="<?= $bienTheSelected->getId() ?>">
                <?php else: ?>
                    <div class="p-4 bg-destructive/10 text-destructive rounded-lg text-center">
                        <p>Sản phẩm hiện tại không có phiên bản nào khả dụng.</p>
                    </div>
                <?php endif; ?>
                
                <!-- Quantity Selection -->
                <div class="flex items-center justify-between">
                    <label class="text-sm font-medium text-foreground">Số lượng:</label>
                    <div class="flex items-center border border-input rounded-md">
                        <button type="button" onclick="giamSoLuong()" 
                                class="px-3 py-2 hover:bg-accent hover:text-accent-foreground rounded-l-md transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </button>
                        <input type="number" id="quantity" name="so_luong" value="1" min="1" max="10" 
                               class="w-16 px-3 py-2 text-center border-0 focus:outline-none bg-transparent">
                        <button type="button" onclick="tangSoLuong()" 
                                class="px-3 py-2 hover:bg-accent hover:text-accent-foreground rounded-r-md transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Add to Cart Button -->
                <button type="submit" 
                        <?= !$bienTheSelected ? 'disabled' : '' ?>
                        class="w-full bg-primary hover:bg-primary/90 text-primary-foreground font-medium py-3 px-4 rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <span id="cartButtonText" class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 9M7 13l-1.5 9m4.5-9v9m4-9v9"></path>
                        </svg>
                        Thêm vào giỏ hàng
                    </span>
                    <span id="cartButtonLoading" class="hidden flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Đang thêm...
                    </span>
                </button>
            </form>
        </div>

        <!-- Product Badges -->
        <div class="flex flex-wrap gap-2">
            <?php if ($sanPham->isNoiBat()): ?>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    Sản phẩm nổi bật
                </span>
            <?php endif; ?>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300">
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                Còn hàng
            </span>
        </div>
    </div>
</div>

<!-- Product Details Tabs -->
<div class="tabs w-full mt-12" id="product-details-tabs">
    <nav role="tablist" aria-orientation="horizontal" class="w-full border-b border-border">
        <button type="button" role="tab" 
                id="product-details-tabs-tab-1" 
                aria-controls="product-details-tabs-panel-1" 
                aria-selected="true" 
                tabindex="0"
                class="py-4 px-1 border-b-2 border-primary text-primary font-medium text-sm transition-colors">
            Mô tả sản phẩm
        </button>
        
        <?php if ($sanPham->getThongSo()): ?>
            <button type="button" role="tab" 
                    id="product-details-tabs-tab-2" 
                    aria-controls="product-details-tabs-panel-2" 
                    aria-selected="false" 
                    tabindex="0"
                    class="py-4 px-1 ml-8 border-b-2 border-transparent text-muted-foreground hover:text-foreground hover:border-muted-foreground font-medium text-sm transition-colors">
                Thông số kỹ thuật
            </button>
        <?php endif; ?>
    </nav>

    <!-- Description Panel -->
    <div role="tabpanel" 
         id="product-details-tabs-panel-1" 
         aria-labelledby="product-details-tabs-tab-1" 
         tabindex="-1" 
         aria-selected="true"
         class="py-8">
        <div class="card">
            <section>
                <div class="prose prose-sm max-w-none dark:prose-invert">
                    <?php if ($sanPham->getMoTa()): ?>
                        <div class="text-foreground leading-relaxed whitespace-pre-line">
                            <?= nl2br(htmlspecialchars($sanPham->getMoTa())) ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted-foreground">Chưa có mô tả chi tiết cho sản phẩm này.</p>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>

    <!-- Specifications Panel -->
    <?php if ($sanPham->getThongSo()): ?>
        <div role="tabpanel" 
             id="product-details-tabs-panel-2" 
             aria-labelledby="product-details-tabs-tab-2" 
             tabindex="-1" 
             aria-selected="false" 
             hidden
             class="py-8">
            <div class="card">
                <header class="mb-6">
                    <h2 class="text-xl font-semibold text-foreground">Thông số kỹ thuật</h2>
                    <p class="text-sm text-muted-foreground">Chi tiết thông số kỹ thuật của sản phẩm</p>
                </header>
                <section>
                    <div class="bg-card border border-border rounded-lg overflow-hidden">
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
                                    <td class="px-6 py-4 bg-muted/50 font-medium text-foreground w-1/3">
                                        <?= htmlspecialchars($label) ?>
                                    </td>
                                    <td class="px-6 py-4 text-muted-foreground">
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
        </div>
    <?php endif; ?>
</div>

<script>
function doiHinhChinh(src, element) {
    document.getElementById('mainImage').src = src;
    
    // Xóa class active khỏi tất cả thumbnail
    document.querySelectorAll('.thumbnail').forEach(thumb => {
        thumb.classList.remove('border-primary');
        thumb.classList.add('border-transparent');
    });
    
    // Thêm class active cho thumbnail được click
    element.classList.remove('border-transparent');
    element.classList.add('border-primary');
}

// Xử lý chọn phiên bản sản phẩm
document.addEventListener('DOMContentLoaded', function() {
    const variantRadios = document.querySelectorAll('.variant-radio');
    const selectedVariantInput = document.getElementById('selectedVariantId');
    
    variantRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked && selectedVariantInput) {
                selectedVariantInput.value = this.value;
                
                // Cập nhật UI khi chọn phiên bản
                capNhatPhienBanDuocChon(this);
                
                // Đổi hình ảnh chính nếu phiên bản có hình riêng
                const variantImage = this.dataset.variantImage;
                if (variantImage) {
                    document.getElementById('mainImage').src = variantImage;
                }
            }
        });
    });
});

function capNhatPhienBanDuocChon(selectedRadio) {
    // Xóa trạng thái active khỏi tất cả variant options
    document.querySelectorAll('.variant-option').forEach(option => {
        option.classList.remove('border-primary', 'bg-primary/5');
        option.classList.add('border-border');
        option.querySelector('.check-icon').classList.add('hidden');
        option.querySelector('.check-icon').classList.remove('flex');
    });
    
    // Thêm trạng thái active cho variant được chọn
    const selectedOption = selectedRadio.closest('.variant-option');
    selectedOption.classList.remove('border-border');
    selectedOption.classList.add('border-primary', 'bg-primary/5');
    selectedOption.querySelector('.check-icon').classList.remove('hidden');
    selectedOption.querySelector('.check-icon').classList.add('flex');
}

function tangSoLuong() {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    if (currentValue < parseInt(input.max)) {
        input.value = currentValue + 1;
    }
}

function giamSoLuong() {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    if (currentValue > parseInt(input.min)) {
        input.value = currentValue - 1;
    }
}

// Chức năng thêm vào giỏ hàng
document.getElementById('addToCartForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const button = e.target.querySelector('button[type="submit"]');
    const buttonText = document.getElementById('cartButtonText');
    const buttonLoading = document.getElementById('cartButtonLoading');
    
    // Hiển thị trạng thái đang tải
    button.disabled = true;
    buttonText.classList.add('hidden');
    buttonLoading.classList.remove('hidden');
    
    // Lấy dữ liệu form
    const formData = new FormData(e.target);
    
    // Gửi yêu cầu AJAX
    fetch('/cart/add', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hiển thị thông báo thành công
            buttonText.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Đã thêm vào giỏ hàng
            `;
            buttonText.classList.remove('hidden');
            buttonLoading.classList.add('hidden');
            
            // Cập nhật số lượng giỏ hàng nếu có
            const cartCountElement = document.getElementById('cart-item-count');
            if (cartCountElement && typeof data.cartItemCount !== 'undefined') {
                cartCountElement.textContent = data.cartItemCount;
            }
            
            // Đặt lại nút sau 2 giây
            setTimeout(() => {
                buttonText.innerHTML = `
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 9M7 13l-1.5 9m4.5-9v9m4-9v9"></path>
                    </svg>
                    Thêm vào giỏ hàng
                `;
                button.disabled = false;
            }, 2000);
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        // Hiển thị thông báo lỗi
        buttonText.innerHTML = `
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            Có lỗi xảy ra
        `;
        buttonText.classList.remove('hidden');
        buttonLoading.classList.add('hidden');
        
        // Đặt lại nút sau 2 giây
        setTimeout(() => {
            buttonText.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 9M7 13l-1.5 9m4.5-9v9m4-9v9"></path>
                </svg>
                Thêm vào giỏ hàng
            `;
            button.disabled = false;
        }, 2000);
        
        console.error('Lỗi:', error);
    });
});
</script>