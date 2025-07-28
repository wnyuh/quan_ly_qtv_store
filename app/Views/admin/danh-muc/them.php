<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-foreground">Thêm danh mục mới</h1>
        <p class="text-muted-foreground">Tạo danh mục mới trong hệ thống</p>
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
                        <p>Thông tin chính của danh mục</p>
                    </header>
                    <section>
                        <div class="form grid gap-6">
                            <div class="grid gap-2">
                                <label for="ten">Tên danh mục *</label>
                                <input type="text" id="ten" name="ten" required
                                       placeholder="Nhập tên danh mục"
                                       value="<?= htmlspecialchars($_POST['ten'] ?? '') ?>">
                            </div>

                            <div class="grid gap-2">
                                <label for="duong_dan">Đường dẫn (URL) *</label>
                                <input type="text" id="duong_dan" name="duong_dan" required
                                       placeholder="duong-dan-danh-muc"
                                       value="<?= htmlspecialchars($_POST['duong_dan'] ?? '') ?>">
                            </div>

                            <div class="grid gap-2">
                                <label for="mo_ta">Mô tả</label>
                                <textarea id="mo_ta" name="mo_ta" rows="4"
                                          placeholder="Mô tả về danh mục"><?= htmlspecialchars($_POST['mo_ta'] ?? '') ?></textarea>
                            </div>

                            <div class="grid gap-2">
                                <label for="hinh_anh">Hình ảnh URL</label>
                                <input type="text" id="hinh_anh" name="hinh_anh"
                                       placeholder="https://..."
                                       value="<?= htmlspecialchars($_POST['hinh_anh'] ?? '') ?>">
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <!-- Sidebar Information -->
            <div class="space-y-6">
                <!-- Parent Category -->
                <div class="card">
                    <header>
                        <h3>Danh mục cha</h3>
                        <p>Chọn danh mục cha (nếu có)</p>
                    </header>
                    <section>
                        <div class="form grid gap-6">
                            <div class="grid gap-2">
                                <label for="danh_muc_cha_id">Danh mục cha</label>
                                <select id="danh_muc_cha_id" name="danh_muc_cha_id">
                                    <option value="">— Không —</option>
                                    <?php foreach ($parents as $parent): ?>
                                        <option value="<?= $parent->getId() ?>"
                                            <?= (($_POST['danh_muc_cha_id'] ?? '') == $parent->getId()) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($parent->getTen()) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Ordering & Status -->
                <div class="card">
                    <header>
                        <h3>Thứ tự & Trạng thái</h3>
                        <p>Sắp xếp và kích hoạt danh mục</p>
                    </header>
                    <section>
                        <div class="form grid gap-6">
                            <div class="grid gap-2">
                                <label for="thu_tu">Thứ tự</label>
                                <input type="number" id="thu_tu" name="thu_tu" min="0" step="1"
                                       value="<?= htmlspecialchars($_POST['thu_tu'] ?? 0) ?>">
                            </div>

                            <label class="flex items-center space-x-3">
                                <input type="checkbox" name="kich_hoat" value="1"
                                    <?= isset($_POST['kich_hoat']) ? 'checked' : '' ?>>
                                <div>
                                    <div class="font-medium text-foreground">Kích hoạt</div>
                                    <div class="text-sm text-muted-foreground">
                                        Hiển thị danh mục trên website
                                    </div>
                                </div>
                            </label>
                        </div>
                    </section>
                </div>

                <!-- Actions -->
                <div class="card">
                    <footer class="flex flex-col gap-3">
                        <button type="submit" class="btn w-full">
                            Lưu danh mục
                        </button>
                        <a href="/admin/danh-muc" class="btn-outline w-full text-center">
                            Hủy bỏ
                        </a>
                    </footer>
                </div>
            </div>
        </div>
    </form>
</div>
