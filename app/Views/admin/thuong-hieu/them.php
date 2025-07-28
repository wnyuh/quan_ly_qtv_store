<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-foreground">Thêm thương hiệu mới</h1>
        <p class="text-muted-foreground">Tạo thương hiệu mới trong hệ thống</p>
    </div>

    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Thông tin cơ bản -->
            <div class="lg:col-span-2 space-y-4">
                <div class="card">
                    <header>
                        <h3>Thông tin cơ bản</h3>
                        <p>Thông tin chính của thương hiệu</p>
                    </header>
                    <section>
                        <div class="form grid gap-6">
                            <div class="grid gap-2">
                                <label for="ten">Tên thương hiệu *</label>
                                <input type="text" id="ten" name="ten" required
                                       placeholder="Nhập tên thương hiệu"
                                       value="<?= htmlspecialchars($_POST['ten'] ?? '') ?>">
                            </div>

                            <div class="grid gap-2">
                                <label for="duong_dan">Đường dẫn (URL) *</label>
                                <input type="text" id="duong_dan" name="duong_dan" required
                                       placeholder="duong-dan-thuong-hieu"
                                       value="<?= htmlspecialchars($_POST['duong_dan'] ?? '') ?>">
                            </div>

                            <div class="grid gap-2">
                                <label for="logo">Logo URL</label>
                                <input type="text" id="logo" name="logo"
                                       placeholder="https://..."
                                       value="<?= htmlspecialchars($_POST['logo'] ?? '') ?>">
                            </div>

                            <div class="grid gap-2">
                                <label for="mo_ta">Mô tả</label>
                                <textarea id="mo_ta" name="mo_ta" rows="4"
                                          placeholder="Mô tả về thương hiệu"><?= htmlspecialchars($_POST['mo_ta'] ?? '') ?></textarea>
                            </div>

                            <div class="grid gap-2">
                                <label for="website">Website</label>
                                <input type="text" id="website" name="website"
                                       placeholder="https://..."
                                       value="<?= htmlspecialchars($_POST['website'] ?? '') ?>">
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <!-- Cài đặt -->
            <div class="space-y-6">
                <div class="card">
                    <header>
                        <h3>Trạng thái</h3>
                        <p>Hiển thị thương hiệu</p>
                    </header>
                    <section>
                        <div class="space-y-4">
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" name="kich_hoat" value="1"
                                    <?= isset($_POST['kich_hoat']) ? 'checked' : '' ?>>
                                <div>
                                    <div class="font-medium text-foreground">Kích hoạt</div>
                                    <div class="text-sm text-muted-foreground">Hiển thị trên website</div>
                                </div>
                            </label>
                        </div>
                    </section>
                </div>

                <!-- Hành động -->
                <div class="card">
                    <footer class="flex flex-col gap-3">
                        <button type="submit" class="btn w-full">Lưu thương hiệu</button>
                        <a href="/admin/thuong-hieu" class="btn-outline w-full text-center">Hủy bỏ</a>
                    </footer>
                </div>
            </div>
        </div>
    </form>
</div>