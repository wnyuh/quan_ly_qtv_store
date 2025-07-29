<!-- Views/tai-khoan/index.php -->

<div class="container mx-auto max-w-xl p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Thông Tin Tài Khoản</h2>

    <div class="rounded-xl shadow border p-6 space-y-3">
        <p><span class="font-medium text-gray-600">Họ và tên:</span> <?= htmlspecialchars($nguoi_dung->getHoTen()) ?></p>
        <p><span class="font-medium text-gray-600">Email:</span> <?= htmlspecialchars($nguoi_dung->getEmail()) ?></p>
        <p><span class="font-medium text-gray-600">Số điện thoại:</span> <?= htmlspecialchars($nguoi_dung->getSoDienThoai() ?? 'Chưa cập nhật') ?></p>
        <p><span class="font-medium text-gray-600">Ngày sinh:</span> <?= $nguoi_dung->getNgaySinh() ? $nguoi_dung->getNgaySinh()->format('d/m/Y') : 'Chưa cập nhật' ?></p>
        <p><span class="font-medium text-gray-600">Ngày tạo:</span> <?= $nguoi_dung->getNgayTao()->format('d/m/Y H:i:s') ?></p>

        <form action="/dang-xuat" method="POST" class="pt-4">
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md transition duration-200">
                Đăng xuất
            </button>
        </form>
    </div>
</div>