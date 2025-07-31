<!-- Views/tai-khoan/index.php -->

<div class="container mx-auto max-w-3xl p-6">
    <h2 class="text-3xl font-semibold text-gray-800 mb-6">Tài Khoản Của Tôi</h2>

    <!-- Phần 1: Thông tin tài khoản -->
    <form action="/tai-khoan/cap-nhat" method="POST" class="rounded-xl shadow border p-6 space-y-4 mb-8">
        <h3 class="text-xl font-semibold text-gray-700">Thông tin tài khoản</h3>

        <div>
            <label class="font-medium text-gray-600">Họ và tên</label>
            <input type="text" name="ho_ten"
                class="mt-1 w-full border px-3 py-2 rounded-md"
                value="<?= htmlspecialchars($nguoi_dung->getHoTen()) ?>">
        </div>

        <div>
            <label class="font-medium text-gray-600">Email</label>
            <input type="email" name="email" readonly
                class="mt-1 w-full border px-3 py-2 rounded-md  cursor-not-allowed"
                value="<?= htmlspecialchars($nguoi_dung->getEmail()) ?>">
        </div>

        <div>
            <label class="font-medium text-gray-600">Số điện thoại</label>
            <input type="text" name="so_dien_thoai"
                class="mt-1 w-full border px-3 py-2 rounded-md"
                value="<?= htmlspecialchars($nguoi_dung->getSoDienThoai() ?? '') ?>">
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
            Lưu thay đổi
        </button>
    </form>

    <!-- Phần 2: Đổi mật khẩu -->
    <form action="/tai-khoan/doi-mat-khau" method="POST" class="rounded-xl shadow border p-6 space-y-4 mb-8">
        <h3 class="text-xl font-semibold text-gray-700">Đổi mật khẩu</h3>

        <div>
            <label class="font-medium text-gray-600">Mật khẩu cũ</label>
            <input type="password" name="mat_khau_cu"
                class="mt-1 w-full border px-3 py-2 rounded-md" required>
        </div>

        <div>
            <label class="font-medium text-gray-600">Mật khẩu mới</label>
            <input type="password" name="mat_khau_moi"
                class="mt-1 w-full border px-3 py-2 rounded-md" required>
        </div>

        <div>
            <label class="font-medium text-gray-600">Xác nhận mật khẩu mới</label>
            <input type="password" name="mat_khau_moi_confirm"
                class="mt-1 w-full border px-3 py-2 rounded-md" required>
        </div>

        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md">
            Đổi mật khẩu
        </button>
    </form>

    <!-- Phần 3: Lịch sử mua hàng -->
    <div class="rounded-xl shadow border p-6 space-y-3">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">Lịch sử mua hàng</h3>

        <?php if (count($don_hangs) > 0): ?>
            <table class="w-full text-left table-auto">
                <thead>
                    <tr class="border-b ">
                        <th class="py-2 px-4">Mã đơn hàng</th>
                        <th class="py-2 px-4">Ngày mua</th>
                        <th class="py-2 px-4">Tổng tiền</th>
                        <th class="py-2 px-4">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($don_hangs as $don_hang): ?>
                        <tr class="border-b">
                            <td class="py-2 px-4"><?= $don_hang->getId() ?></td>
                            <td class="py-2 px-4"><?= $don_hang->getNgayTao()->format('d/m/Y') ?></td>
                            <td class="py-2 px-4"><?= number_format($don_hang->getTongTien(), 0, ',', '.') ?>₫</td>
                            <td class="py-2 px-4"><?= $don_hang->getTrangThai() ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-gray-600">Bạn chưa có đơn hàng nào.</p>
        <?php endif; ?>
    </div>

    <form action="/dang-xuat" method="POST" class="pt-4">
        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md transition duration-200">
            Đăng xuất
        </button>
    </form>
</div>