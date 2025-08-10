<div class="max-w-6xl mx-auto p-6">
    <h2 class="text-3xl font-bold text-foreground mb-8">Thanh toán đơn hàng</h2>

    <!-- Display errors if any -->
    <?php if (!empty($error)): ?>
        <div class="mb-6 rounded-md border border-destructive/50 bg-destructive/10 px-4 py-3 text-sm text-destructive">
            <div class="flex items-center space-x-2">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="15" x2="9" y1="9" y2="15"/>
                    <line x1="9" x2="15" y1="9" y2="15"/>
                </svg>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Display field errors if any -->
    <?php if (!empty($errors)): ?>
        <div class="mb-6 rounded-md border border-destructive/50 bg-destructive/10 px-4 py-3 text-sm text-destructive">
            <ul class="space-y-1">
                <?php foreach ($errors as $field => $errorMsg): ?>
                    <li class="flex items-center space-x-2">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="15" x2="9" y1="9" y2="15"/>
                            <line x1="9" x2="15" y1="9" y2="15"/>
                        </svg>
                        <span><?= htmlspecialchars($errorMsg) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2">
            <div class="bg-card border border-border rounded-lg p-6">
                <h3 class="text-xl font-semibold text-foreground mb-4">Sản phẩm trong giỏ hàng</h3>
                <div class="space-y-4">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="flex items-center space-x-4 py-4 border-b border-border last:border-b-0">
                            <?php 
                            $hinhAnhChinh = $item['bienThe']->getSanPham()->getHinhAnhChinh();
                            $hinhAnhUrl = $hinhAnhChinh ? $hinhAnhChinh->getFullUrl() : '/assets/images/placeholder.jpg';
                            ?>
                            <img src="<?= htmlspecialchars($hinhAnhUrl) ?>" 
                                 alt="<?= htmlspecialchars($item['bienThe']->getSanPham()->getTen()) ?>" 
                                 class="w-16 h-16 object-cover rounded-md">
                            <div class="flex-1">
                                <h4 class="font-medium text-foreground"><?= htmlspecialchars($item['bienThe']->getSanPham()->getTen()) ?></h4>
                                <p class="text-sm text-muted-foreground">
                                    <?php if ($item['bienThe']->getBoNho()): ?>
                                        <?= htmlspecialchars($item['bienThe']->getBoNho()) ?>
                                    <?php endif; ?>
                                    <?php if ($item['bienThe']->getMauSac()): ?>
                                        <?= $item['bienThe']->getBoNho() ? ' - ' : '' ?><?= htmlspecialchars($item['bienThe']->getMauSac()) ?>
                                    <?php endif; ?>
                                </p>
                                <p class="text-sm text-muted-foreground">Số lượng: <?= $item['qty'] ?></p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-foreground"><?= number_format($item['bienThe']->getGia(), 0, ',', '.') ?> ₫</p>
                                <p class="text-sm text-muted-foreground">Tổng: <?= number_format($item['bienThe']->getGia() * $item['qty'], 0, ',', '.') ?> ₫</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Checkout Form -->
        <div class="lg:col-span-1">
            <div class="bg-card border border-border rounded-lg p-6">
                <form method="post" action="/gio-hang/process-checkout" class="space-y-6">
                    <!-- Guest Information (only if not logged in) -->
                    <?php if (!$user): ?>
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-foreground">Thông tin khách hàng</h3>
                            
                            <div>
                                <label for="guest_email" class="block text-sm font-medium text-foreground mb-2">Email *</label>
                                <input 
                                    id="guest_email" 
                                    name="guest_email" 
                                    type="email" 
                                    required
                                    value="<?= htmlspecialchars($formData['guest_email'] ?? '') ?>"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 <?= isset($errors['guest_email']) ? 'border-destructive' : '' ?>"
                                    placeholder="email@example.com">
                                <?php if (isset($errors['guest_email'])): ?>
                                    <p class="text-sm text-destructive mt-1"><?= htmlspecialchars($errors['guest_email']) ?></p>
                                <?php endif; ?>
                            </div>

<!--                            <div>-->
<!--                                <label for="guest_phone" class="block text-sm font-medium text-foreground mb-2">Số điện thoại *</label>-->
<!--                                <input -->
<!--                                    id="guest_phone" -->
<!--                                    name="guest_phone" -->
<!--                                    type="tel" -->
<!--                                    required-->
<!--                                    value="--><?php //= htmlspecialchars($formData['guest_phone'] ?? '') ?><!--"-->
<!--                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 --><?php //= isset($errors['guest_phone']) ? 'border-destructive' : '' ?><!--"-->
<!--                                    placeholder="0123456789">-->
<!--                                --><?php //if (isset($errors['guest_phone'])): ?>
<!--                                    <p class="text-sm text-destructive mt-1">--><?php //= htmlspecialchars($errors['guest_phone']) ?><!--</p>-->
<!--                                --><?php //endif; ?>
<!--                            </div>-->
                        </div>
                        <hr class="border-border">
                    <?php endif; ?>

                    <!-- Shipping Address -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-foreground">Địa chỉ giao hàng</h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="ho" class="block text-sm font-medium text-foreground mb-2">Họ *</label>
                                <input 
                                    id="ho" 
                                    name="ho" 
                                    type="text" 
                                    required
                                    value="<?= htmlspecialchars($formData['ho'] ?? $user?->getHo() ?? '') ?>"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 <?= isset($errors['ho']) ? 'border-destructive' : '' ?>">
                                <?php if (isset($errors['ho'])): ?>
                                    <p class="text-sm text-destructive mt-1"><?= htmlspecialchars($errors['ho']) ?></p>
                                <?php endif; ?>
                            </div>

                            <div>
                                <label for="ten" class="block text-sm font-medium text-foreground mb-2">Tên *</label>
                                <input 
                                    id="ten" 
                                    name="ten" 
                                    type="text" 
                                    required
                                    value="<?= htmlspecialchars($formData['ten'] ?? $user?->getTen() ?? '') ?>"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 <?= isset($errors['ten']) ? 'border-destructive' : '' ?>">
                                <?php if (isset($errors['ten'])): ?>
                                    <p class="text-sm text-destructive mt-1"><?= htmlspecialchars($errors['ten']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div>
                            <label for="cong_ty" class="block text-sm font-medium text-foreground mb-2">Công ty (tùy chọn)</label>
                            <input 
                                id="cong_ty" 
                                name="cong_ty" 
                                type="text"
                                value="<?= htmlspecialchars($formData['cong_ty'] ?? '') ?>"
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                        </div>

                        <div>
                            <label for="dia_chi_1" class="block text-sm font-medium text-foreground mb-2">Địa chỉ *</label>
                            <input 
                                id="dia_chi_1" 
                                name="dia_chi_1" 
                                type="text" 
                                required
                                value="<?= htmlspecialchars($formData['dia_chi_1'] ?? '') ?>"
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 <?= isset($errors['dia_chi_1']) ? 'border-destructive' : '' ?>"
                                placeholder="Số nhà, tên đường">
                            <?php if (isset($errors['dia_chi_1'])): ?>
                                <p class="text-sm text-destructive mt-1"><?= htmlspecialchars($errors['dia_chi_1']) ?></p>
                            <?php endif; ?>
                        </div>

<!--                        <div>-->
<!--                            <label for="dia_chi_2" class="block text-sm font-medium text-foreground mb-2">Địa chỉ 2 (tùy chọn)</label>-->
<!--                            <input -->
<!--                                id="dia_chi_2" -->
<!--                                name="dia_chi_2" -->
<!--                                type="text"-->
<!--                                value="--><?php //= htmlspecialchars($formData['dia_chi_2'] ?? '') ?><!--"-->
<!--                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"-->
<!--                                placeholder="Căn hộ, tầng, khu vực...">-->
<!--                        </div>-->

                        <div class="grid grid-cols-1 gap-4">
                            <!-- Province/City Selection -->
                            <div>
                                <label for="tinh_thanh" class="block text-sm font-medium text-foreground mb-2">Tỉnh/Thành phố *</label>
                                <div id="select-province" class="select">
                                    <button type="button" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 <?= isset($errors['tinh_thanh']) ? 'border-destructive' : '' ?>" id="select-province-trigger" aria-haspopup="listbox" aria-expanded="false" aria-controls="select-province-listbox">
                                        <span class="truncate" id="province-selected">Chọn tỉnh/thành phố</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevrons-up-down text-muted-foreground opacity-50 shrink-0 h-4 w-4">
                                            <path d="m7 15 5 5 5-5" />
                                            <path d="m7 9 5-5 5 5" />
                                        </svg>
                                    </button>
                                    <div id="select-province-popover" class="hidden absolute z-50 w-full mt-1 bg-background border border-border rounded-md shadow-lg max-h-60 overflow-auto" data-popover aria-hidden="true">
                                        <header class="p-2 border-b border-border">
                                            <div class="flex items-center space-x-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search h-4 w-4 text-muted-foreground">
                                                    <circle cx="11" cy="11" r="8" />
                                                    <path d="m21 21-4.3-4.3" />
                                                </svg>
                                                <input type="text" id="province-search" placeholder="Tìm kiếm tỉnh/thành phố..." class="flex-1 bg-transparent outline-none text-sm" autocomplete="off" />
                                            </div>
                                        </header>
                                        <div role="listbox" id="select-province-listbox" aria-orientation="vertical" aria-labelledby="select-province-trigger" class="p-1">
                                            <!-- Options will be populated by JavaScript -->
                                        </div>
                                    </div>
                                    <input type="hidden" name="tinh_thanh" id="province-value" value="<?= htmlspecialchars($formData['tinh_thanh'] ?? '') ?>" />
                                </div>
                                <?php if (isset($errors['tinh_thanh'])): ?>
                                    <p class="text-sm text-destructive mt-1"><?= htmlspecialchars($errors['tinh_thanh']) ?></p>
                                <?php endif; ?>
                            </div>

                            <!-- District Selection -->
                            <div>
                                <label for="huyen_quan" class="block text-sm font-medium text-foreground mb-2">Quận/Huyện *</label>
                                <div id="select-district" class="select">
                                    <button type="button" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" id="select-district-trigger" aria-haspopup="listbox" aria-expanded="false" aria-controls="select-district-listbox" disabled>
                                        <span class="truncate" id="district-selected">Chọn quận/huyện</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevrons-up-down text-muted-foreground opacity-50 shrink-0 h-4 w-4">
                                            <path d="m7 15 5 5 5-5" />
                                            <path d="m7 9 5-5 5 5" />
                                        </svg>
                                    </button>
                                    <div id="select-district-popover" class="hidden absolute z-50 w-full mt-1 bg-background border border-border rounded-md shadow-lg max-h-60 overflow-auto" data-popover aria-hidden="true">
                                        <header class="p-2 border-b border-border">
                                            <div class="flex items-center space-x-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search h-4 w-4 text-muted-foreground">
                                                    <circle cx="11" cy="11" r="8" />
                                                    <path d="m21 21-4.3-4.3" />
                                                </svg>
                                                <input type="text" id="district-search" placeholder="Tìm kiếm quận/huyện..." class="flex-1 bg-transparent outline-none text-sm" autocomplete="off" />
                                            </div>
                                        </header>
                                        <div role="listbox" id="select-district-listbox" aria-orientation="vertical" aria-labelledby="select-district-trigger" class="p-1">
                                            <!-- Options will be populated by JavaScript -->
                                        </div>
                                    </div>
                                    <input type="hidden" name="huyen_quan" id="district-value" value="" />
                                </div>
                            </div>

                            <!-- Ward Selection -->
                            <div>
                                <label for="xa_phuong" class="block text-sm font-medium text-foreground mb-2">Phường/Xã *</label>
                                <div id="select-ward" class="select">
                                    <button type="button" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" id="select-ward-trigger" aria-haspopup="listbox" aria-expanded="false" aria-controls="select-ward-listbox" disabled>
                                        <span class="truncate" id="ward-selected">Chọn phường/xã</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevrons-up-down text-muted-foreground opacity-50 shrink-0 h-4 w-4">
                                            <path d="m7 15 5 5 5-5" />
                                            <path d="m7 9 5-5 5 5" />
                                        </svg>
                                    </button>
                                    <div id="select-ward-popover" class="hidden absolute z-50 w-full mt-1 bg-background border border-border rounded-md shadow-lg max-h-60 overflow-auto" data-popover aria-hidden="true">
                                        <header class="p-2 border-b border-border">
                                            <div class="flex items-center space-x-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search h-4 w-4 text-muted-foreground">
                                                    <circle cx="11" cy="11" r="8" />
                                                    <path d="m21 21-4.3-4.3" />
                                                </svg>
                                                <input type="text" id="ward-search" placeholder="Tìm kiếm phường/xã..." class="flex-1 bg-transparent outline-none text-sm" autocomplete="off" />
                                            </div>
                                        </header>
                                        <div role="listbox" id="select-ward-listbox" aria-orientation="vertical" aria-labelledby="select-ward-trigger" class="p-1">
                                            <!-- Options will be populated by JavaScript -->
                                        </div>
                                    </div>
                                    <input type="hidden" name="xa_phuong" id="ward-value" value="" />
                                </div>
                            </div>

                            <!-- City field for backward compatibility -->
                            <div class="hidden">
                                <input type="hidden" name="thanh_pho" id="thanh_pho" value="" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
<!--                            <div>-->
<!--                                <label for="ma_buu_dien" class="block text-sm font-medium text-foreground mb-2">Mã bưu điện *</label>-->
<!--                                <input -->
<!--                                    id="ma_buu_dien" -->
<!--                                    name="ma_buu_dien" -->
<!--                                    type="text" -->
<!--                                    required-->
<!--                                    value="--><?php //= htmlspecialchars($formData['ma_buu_dien'] ?? '') ?><!--"-->
<!--                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 --><?php //= isset($errors['ma_buu_dien']) ? 'border-destructive' : '' ?><!--"-->
<!--                                    placeholder="70000">-->
<!--                                --><?php //if (isset($errors['ma_buu_dien'])): ?>
<!--                                    <p class="text-sm text-destructive mt-1">--><?php //= htmlspecialchars($errors['ma_buu_dien']) ?><!--</p>-->
<!--                                --><?php //endif; ?>
<!--                            </div>-->

                            <div>
                                <label for="so_dien_thoai" class="block text-sm font-medium text-foreground mb-2">Số điện thoại *</label>
                                <input 
                                    id="so_dien_thoai" 
                                    name="so_dien_thoai" 
                                    type="tel" 
                                    required
                                    value="<?= htmlspecialchars($formData['so_dien_thoai'] ?? $user?->getSoDienThoai() ?? '') ?>"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 <?= isset($errors['so_dien_thoai']) ? 'border-destructive' : '' ?>"
                                    placeholder="0123456789">
                                <?php if (isset($errors['so_dien_thoai'])): ?>
                                    <p class="text-sm text-destructive mt-1"><?= htmlspecialchars($errors['so_dien_thoai']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div>
                            <label for="ghi_chu" class="block text-sm font-medium text-foreground mb-2">Ghi chú (tùy chọn)</label>
                            <textarea 
                                id="ghi_chu" 
                                name="ghi_chu" 
                                rows="3"
                                class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                                placeholder="Ghi chú cho đơn hàng..."><?= htmlspecialchars($formData['ghi_chu'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- Payment Method Selection -->
                    <div class="border-t border-border pt-6">
                        <h3 class="text-lg font-semibold text-foreground mb-4">Phương thức thanh toán</h3>
                        <div class="space-y-3">
                            <div class="grid grid-cols-1 gap-3">
                                <!-- Cash on Delivery -->
                                <label class="flex items-center space-x-3 p-4 border border-border rounded-lg cursor-pointer hover:bg-accent transition-colors">
                                    <input 
                                        type="radio" 
                                        name="payment_method" 
                                        value="cod" 
                                        class="h-4 w-4 text-primary focus:ring-primary"
                                        <?= (!isset($formData['payment_method']) || $formData['payment_method'] === 'cod') ? 'checked' : '' ?>>
                                    <div class="flex items-center space-x-3 flex-1">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium text-foreground">Thanh toán khi nhận hàng (COD)</div>
                                            <div class="text-sm text-muted-foreground">Thanh toán bằng tiền mặt khi nhận hàng</div>
                                        </div>
                                    </div>
                                </label>

                                <!-- Banking -->
                                <label class="flex items-center space-x-3 p-4 border border-border rounded-lg cursor-pointer hover:bg-accent transition-colors">
                                    <input 
                                        type="radio" 
                                        name="payment_method" 
                                        value="banking" 
                                        class="h-4 w-4 text-primary focus:ring-primary"
                                        <?= (isset($formData['payment_method']) && $formData['payment_method'] === 'banking') ? 'checked' : '' ?>>
                                    <div class="flex items-center space-x-3 flex-1">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium text-foreground">Chuyển khoản ngân hàng</div>
                                            <div class="text-sm text-muted-foreground">Chuyển khoản qua các ngân hàng trong nước</div>
                                        </div>
                                    </div>
                                </label>

                                <!-- MoMo -->
                                <label class="flex items-center space-x-3 p-4 border border-border rounded-lg cursor-pointer hover:bg-accent transition-colors">
                                    <input 
                                        type="radio" 
                                        name="payment_method" 
                                        value="momo" 
                                        class="h-4 w-4 text-primary focus:ring-primary"
                                        <?= (isset($formData['payment_method']) && $formData['payment_method'] === 'momo') ? 'checked' : '' ?>>
                                    <div class="flex items-center space-x-3 flex-1">
                                        <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-pink-600" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium text-foreground">Ví điện tử MoMo</div>
                                            <div class="text-sm text-muted-foreground">Thanh toán qua ứng dụng MoMo</div>
                                        </div>
                                    </div>
                                </label>

                                <!-- ZaloPay -->
                                <label class="flex items-center space-x-3 p-4 border border-border rounded-lg cursor-pointer hover:bg-accent transition-colors">
                                    <input 
                                        type="radio" 
                                        name="payment_method" 
                                        value="zalopay" 
                                        class="h-4 w-4 text-primary focus:ring-primary"
                                        <?= (isset($formData['payment_method']) && $formData['payment_method'] === 'zalopay') ? 'checked' : '' ?>>
                                    <div class="flex items-center space-x-3 flex-1">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium text-foreground">Ví điện tử ZaloPay</div>
                                            <div class="text-sm text-muted-foreground">Thanh toán qua ứng dụng ZaloPay</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            
                            <?php if (isset($errors['payment_method'])): ?>
                                <p class="text-sm text-destructive"><?= htmlspecialchars($errors['payment_method']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Discount Code Section -->
                    <div class="border-t border-border pt-6">
                        <h3 class="text-lg font-semibold text-foreground mb-4">Mã giảm giá</h3>
                        <div class="space-y-3">
                            <div class="flex space-x-2">
                                <input 
                                    type="text" 
                                    id="discount_code" 
                                    name="discount_code"
                                    value="<?= htmlspecialchars($formData['discount_code'] ?? '') ?>"
                                    placeholder="Nhập mã giảm giá"
                                    class="flex-1 h-10 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                <button 
                                    type="button" 
                                    id="apply-discount-btn"
                                    class="h-10 px-4 bg-secondary text-secondary-foreground hover:bg-secondary/80 rounded-md text-sm font-medium transition-colors">
                                    Áp dụng
                                </button>
                            </div>
                            <div id="discount-message" class="text-sm hidden"></div>
                            <?php if (isset($errors['discount_code'])): ?>
                                <p class="text-sm text-destructive"><?= htmlspecialchars($errors['discount_code']) ?></p>
                            <?php endif; ?>
                            <div id="discount-info" class="hidden bg-green-50 border border-green-200 rounded-md p-3">
                                <div class="flex items-center space-x-2 text-green-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span id="discount-description" class="font-medium"></span>
                                </div>
                                <p id="discount-details" class="text-sm text-green-600 mt-1"></p>
                                <button type="button" id="remove-discount-btn" class="text-sm text-red-600 hover:text-red-800 mt-2">
                                    Bỏ mã giảm giá
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="border-t border-border pt-6">
                        <h3 class="text-lg font-semibold text-foreground mb-4">Tóm tắt đơn hàng</h3>
                        <?php 
                        $tongPhu = 0;
                        foreach ($cartItems as $item) {
                            $tongPhu += $item['bienThe']->getGia() * $item['qty'];
                        }
                        $phiVanChuyen = 0;
                        $tongTien = $tongPhu + $phiVanChuyen;
                        ?>
                        <div class="space-y-2" id="order-summary">
                            <div class="flex justify-between text-sm">
                                <span class="text-muted-foreground">Tạm tính:</span>
                                <span class="text-foreground" id="subtotal-amount"><?= number_format($tongPhu, 0, ',', '.') ?> ₫</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-muted-foreground">Phí vận chuyển:</span>
                                <span class="text-foreground"><?= number_format($phiVanChuyen, 0, ',', '.') ?> ₫</span>
                            </div>
                            <div id="discount-row" class="hidden flex justify-between text-sm">
                                <span class="text-muted-foreground">Giảm giá:</span>
                                <span class="text-green-600" id="discount-amount">-0 ₫</span>
                            </div>
                            <hr class="border-border">
                            <div class="flex justify-between font-semibold text-lg">
                                <span class="text-foreground">Tổng cộng:</span>
                                <span class="text-foreground" id="total-amount"><?= number_format($tongTien, 0, ',', '.') ?> ₫</span>
                            </div>
                        </div>
                        
                        <!-- Hidden fields for discount calculation -->
                        <input type="hidden" id="subtotal-value" value="<?= $tongPhu ?>">
                        <input type="hidden" id="shipping-value" value="<?= $phiVanChuyen ?>">
                        <input type="hidden" id="discount-value" name="discount_amount" value="0">
                        <input type="hidden" id="applied-discount-code" name="applied_discount_code" value="">
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        class="w-full inline-flex h-12 items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground ring-offset-background transition-colors hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50">
                        Xác nhận đặt hàng
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load address data
    let addressData = [];
    
    fetch('/json/diachi.json')
        .then(response => response.json())
        .then(data => {
            addressData = data;
            initializeProvinceSelect();
        })
        .catch(error => {
            console.error('Error loading address data:', error);
        });

    function initializeProvinceSelect() {
        const provinceListbox = document.getElementById('select-province-listbox');
        const provinceSearch = document.getElementById('province-search');
        
        // Populate provinces
        populateProvinces(addressData);
        
        // Add search functionality
        provinceSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            populateProvinces(addressData.filter(province => 
                province[1].toLowerCase().includes(searchTerm)
            ));
        });
    }

    function populateProvinces(provinces) {
        const listbox = document.getElementById('select-province-listbox');
        listbox.innerHTML = '';
        
        provinces.forEach(province => {
            const option = document.createElement('div');
            option.className = 'px-3 py-2 text-sm cursor-pointer hover:bg-accent rounded-sm';
            option.setAttribute('role', 'option');
            option.setAttribute('data-value', province[1]);
            option.setAttribute('data-id', province[0]);
            option.textContent = province[1];
            
            option.addEventListener('click', function() {
                selectProvince(province);
            });
            
            listbox.appendChild(option);
        });
    }

    function selectProvince(province) {
        document.getElementById('province-selected').textContent = province[1];
        document.getElementById('province-value').value = province[1];
        document.getElementById('thanh_pho').value = province[1]; // Backward compatibility
        
        // Hide province dropdown
        toggleDropdown('select-province', false);
        
        // Enable and populate district dropdown
        enableDistrictSelect(province[4]);
        
        // Reset ward selection
        resetWardSelect();
    }

    function enableDistrictSelect(districts) {
        const districtTrigger = document.getElementById('select-district-trigger');
        const districtSearch = document.getElementById('district-search');
        
        districtTrigger.disabled = false;
        districtTrigger.classList.remove('opacity-50');
        
        // Populate districts
        populateDistricts(districts);
        
        // Add search functionality
        districtSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            populateDistricts(districts.filter(district => 
                district[1].toLowerCase().includes(searchTerm)
            ));
        });
    }

    function populateDistricts(districts) {
        const listbox = document.getElementById('select-district-listbox');
        listbox.innerHTML = '';
        
        districts.forEach(district => {
            const option = document.createElement('div');
            option.className = 'px-3 py-2 text-sm cursor-pointer hover:bg-accent rounded-sm';
            option.setAttribute('role', 'option');
            option.setAttribute('data-value', district[1]);
            option.setAttribute('data-id', district[0]);
            option.textContent = district[1];
            
            option.addEventListener('click', function() {
                selectDistrict(district);
            });
            
            listbox.appendChild(option);
        });
    }

    function selectDistrict(district) {
        document.getElementById('district-selected').textContent = district[1];
        document.getElementById('district-value').value = district[1];
        
        // Hide district dropdown
        toggleDropdown('select-district', false);
        
        // Enable and populate ward dropdown
        enableWardSelect(district[4]);
    }

    function enableWardSelect(wards) {
        const wardTrigger = document.getElementById('select-ward-trigger');
        const wardSearch = document.getElementById('ward-search');
        
        wardTrigger.disabled = false;
        wardTrigger.classList.remove('opacity-50');
        
        // Populate wards
        populateWards(wards);
        
        // Add search functionality
        wardSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            populateWards(wards.filter(ward => 
                ward[1].toLowerCase().includes(searchTerm)
            ));
        });
    }

    function populateWards(wards) {
        const listbox = document.getElementById('select-ward-listbox');
        listbox.innerHTML = '';
        
        wards.forEach(ward => {
            const option = document.createElement('div');
            option.className = 'px-3 py-2 text-sm cursor-pointer hover:bg-accent rounded-sm';
            option.setAttribute('role', 'option');
            option.setAttribute('data-value', ward[1]);
            option.setAttribute('data-id', ward[0]);
            option.textContent = ward[1];
            
            option.addEventListener('click', function() {
                selectWard(ward);
            });
            
            listbox.appendChild(option);
        });
    }

    function selectWard(ward) {
        document.getElementById('ward-selected').textContent = ward[1];
        document.getElementById('ward-value').value = ward[1];
        
        // Hide ward dropdown
        toggleDropdown('select-ward', false);
    }

    function resetWardSelect() {
        const wardTrigger = document.getElementById('select-ward-trigger');
        wardTrigger.disabled = true;
        wardTrigger.classList.add('opacity-50');
        document.getElementById('ward-selected').textContent = 'Chọn phường/xã';
        document.getElementById('ward-value').value = '';
        
        // Reset district as well
        document.getElementById('district-selected').textContent = 'Chọn quận/huyện';
        document.getElementById('district-value').value = '';
    }

    function toggleDropdown(selectId, show) {
        const popover = document.getElementById(selectId + '-popover');
        const trigger = document.getElementById(selectId + '-trigger');
        
        if (show === undefined) {
            show = popover.classList.contains('hidden');
        }
        
        if (show) {
            popover.classList.remove('hidden');
            trigger.setAttribute('aria-expanded', 'true');
        } else {
            popover.classList.add('hidden');
            trigger.setAttribute('aria-expanded', 'false');
        }
    }

    // Add click handlers for dropdowns
    document.getElementById('select-province-trigger').addEventListener('click', function() {
        toggleDropdown('select-province');
    });

    document.getElementById('select-district-trigger').addEventListener('click', function() {
        if (!this.disabled) {
            toggleDropdown('select-district');
        }
    });

    document.getElementById('select-ward-trigger').addEventListener('click', function() {
        if (!this.disabled) {
            toggleDropdown('select-ward');
        }
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        const selectElements = ['select-province', 'select-district', 'select-ward'];
        
        selectElements.forEach(selectId => {
            const select = document.getElementById(selectId);
            if (!select.contains(event.target)) {
                toggleDropdown(selectId, false);
            }
        });
    });

    // Prevent form submission if required fields are empty
    const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        const requiredFields = ['province-value', 'district-value', 'ward-value'];
        let isValid = true;
        
        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (!field.value.trim()) {
                isValid = false;
                // Add visual feedback for empty fields
                const container = field.closest('.select');
                const trigger = container.querySelector('button');
                trigger.classList.add('border-destructive');
                
                setTimeout(() => {
                    trigger.classList.remove('border-destructive');
                }, 3000);
            }
        });
        
        if (!isValid) {
            event.preventDefault();
            alert('Vui lòng chọn đầy đủ thông tin địa chỉ (Tỉnh/Thành phố, Quận/Huyện, Phường/Xã)');
        }
    });

    // Discount code functionality
    let appliedDiscount = null;

    document.getElementById('apply-discount-btn').addEventListener('click', function() {
        const discountCode = document.getElementById('discount_code').value.trim();
        if (!discountCode) {
            showDiscountMessage('Vui lòng nhập mã giảm giá', 'error');
            return;
        }

        // Show loading state
        this.disabled = true;
        this.textContent = 'Đang xử lý...';

        // Validate discount code via AJAX
        fetch('/api/validate-discount', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                discount_code: discountCode,
                order_total: parseFloat(document.getElementById('subtotal-value').value)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                applyDiscount(data.discount);
                showDiscountInfo(data.discount);
                showDiscountMessage('Mã giảm giá đã được áp dụng thành công!', 'success');
            } else {
                showDiscountMessage(data.message || 'Mã giảm giá không hợp lệ', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showDiscountMessage('Có lỗi xảy ra khi kiểm tra mã giảm giá', 'error');
        })
        .finally(() => {
            this.disabled = false;
            this.textContent = 'Áp dụng';
        });
    });

    document.getElementById('remove-discount-btn').addEventListener('click', function() {
        removeDiscount();
    });

    function applyDiscount(discount) {
        appliedDiscount = discount;
        
        // Update hidden fields
        document.getElementById('discount-value').value = discount.discount_amount;
        document.getElementById('applied-discount-code').value = discount.code;
        
        // Update UI
        document.getElementById('discount-amount').textContent = '-' + formatCurrency(discount.discount_amount);
        document.getElementById('discount-row').classList.remove('hidden');
        
        // Update total
        const subtotal = parseFloat(document.getElementById('subtotal-value').value);
        const shipping = parseFloat(document.getElementById('shipping-value').value);
        const newTotal = subtotal + shipping - discount.discount_amount;
        document.getElementById('total-amount').textContent = formatCurrency(newTotal);
    }

    function removeDiscount() {
        appliedDiscount = null;
        
        // Clear hidden fields
        document.getElementById('discount-value').value = '0';
        document.getElementById('applied-discount-code').value = '';
        document.getElementById('discount_code').value = '';
        
        // Hide discount info
        document.getElementById('discount-info').classList.add('hidden');
        document.getElementById('discount-row').classList.add('hidden');
        document.getElementById('discount-message').classList.add('hidden');
        
        // Recalculate total
        const subtotal = parseFloat(document.getElementById('subtotal-value').value);
        const shipping = parseFloat(document.getElementById('shipping-value').value);
        document.getElementById('total-amount').textContent = formatCurrency(subtotal + shipping);
    }

    function showDiscountInfo(discount) {
        const infoDiv = document.getElementById('discount-info');
        const description = document.getElementById('discount-description');
        const details = document.getElementById('discount-details');
        
        description.textContent = discount.name;
        details.textContent = `Giảm ${discount.value_display}${discount.min_order ? ' - Đơn hàng tối thiểu: ' + formatCurrency(discount.min_order) : ''}`;
        
        infoDiv.classList.remove('hidden');
    }

    function showDiscountMessage(message, type) {
        const messageDiv = document.getElementById('discount-message');
        messageDiv.textContent = message;
        messageDiv.className = `text-sm ${type === 'error' ? 'text-red-600' : 'text-green-600'}`;
        messageDiv.classList.remove('hidden');
        
        setTimeout(() => {
            if (type === 'error') {
                messageDiv.classList.add('hidden');
            }
        }, 5000);
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount) + ' ₫';
    }

    // Allow Enter key to apply discount
    document.getElementById('discount_code').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('apply-discount-btn').click();
        }
    });
});
</script>