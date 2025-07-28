<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Sửa Người dùng</h1>
        <p class="text-muted-foreground">ID: <?= $user->getId() ?></p>
    </div>

    <?php if(isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="grid gap-2">
                <label for="ho">Họ *</label>
                <input type="text" id="ho" name="ho" required
                       value="<?= htmlspecialchars($_POST['ho'] ?? $user->getHo()) ?>"
                       class="input" />
            </div>
            <div class="grid gap-2">
                <label for="ten">Tên *</label>
                <input type="text" id="ten" name="ten" required
                       value="<?= htmlspecialchars($_POST['ten'] ?? $user->getTen()) ?>"
                       class="input" />
            </div>
            <div class="grid gap-2">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required
                       value="<?= htmlspecialchars($_POST['email'] ?? $user->getEmail()) ?>"
                       class="input" />
            </div>
            <div class="grid gap-2">
                <label for="mat_khau">Mật khẩu mới</label>
                <input type="password" id="mat_khau" name="mat_khau"
                       placeholder="Để trống nếu không đổi"
                       class="input" />
            </div>
            <div class="grid gap-2">
                <label for="so_dien_thoai">Số điện thoại</label>
                <input type="text" id="so_dien_thoai" name="so_dien_thoai"
                       value="<?= htmlspecialchars($_POST['so_dien_thoai'] ?? $user->getSoDienThoai() ?? '') ?>"
                       class="input" />
            </div>
            <div class="grid gap-2">
                <label for="ngay_sinh">Ngày sinh</label>
                <input type="date" id="ngay_sinh" name="ngay_sinh"
                       value="<?= htmlspecialchars($_POST['ngay_sinh'] ?? $user->getNgaySinh()?->format('Y-m-d') ?? '') ?>"
                       class="input" />
            </div>
        </div>

        <button type="submit" class="btn">Lưu thay đổi</button>
        <a href="/admin/nguoi-dung" class="btn-outline">Hủy bỏ</a>
    </form>
</div>
